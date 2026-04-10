<?php
// app/Http/Controllers/TechnicianMissionController.php

namespace App\Http\Controllers;

use App\Models\ClientMachineMarqueSelection;
use App\Models\ClientServiceSelection;
use App\Models\ConversionProposal;
use App\Models\Machine;
use App\Models\Mission;
use App\Models\MissionRecommendation;
use App\Models\MissionStep;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\EntretienToRemplacerProposalNotification;
use App\Notifications\MissionStartedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

// ...

class TechnicianMissionController extends Controller
{
    public function start(string $type, string $reference)
    {
        $type = strtolower($type);
        abort_unless(in_array($type, ['entretien', 'remplacer']), 404);

        if ($type === 'entretien') {
            $row = ClientServiceSelection::where('reference', $reference)->firstOrFail();
            $clientId = $row->client_id;
        } else {
            $row = ClientMachineMarqueSelection::where('reference', $reference)->firstOrFail();
            $clientId = $row->client_id;
        }

        $mission = Mission::firstOrCreate(
            ['reference' => $reference, 'kind' => $type],
            [
                'client_id' => $clientId,
                'technicien_user_id' => auth()->id(),
                'status' => 'in_progress',
                'current_step' => 1, // ✅ now Step 1 = Diagnostic / Decision
            ]
        );

        if ($mission->wasRecentlyCreated) {
            $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
            Notification::send(
                $admins,
                new MissionStartedNotification(
                    $mission,
                    auth()->id(),
                    auth()->user()->name ?? 'Technicien'
                )
            );
        }

        abort_if($mission->technicien_user_id !== auth()->id(), 403);

        return redirect()->route('technicien.missions.show', $mission->id);
    }

    public function show(Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);

        $reference = $mission->reference;

        $payment = Payment::where('reference', $reference)
            ->where('kind', $mission->kind)
            ->first();

        $isPaid = (bool) ($payment && $payment->status === 'paid');

        $replacementMachines = collect();

        if ($mission->kind === 'entretien') {
            $selections = ClientServiceSelection::with(['machine', 'type'])
                ->where('reference', $reference)
                ->get();

            $remIds = $selections
                ->pluck('machine.remplacer_machine_id')
                ->filter()
                ->unique()
                ->values();

            $replacementMachines = Machine::with('marques')
                ->whereIn('id', $remIds)
                ->get()
                ->keyBy('id');
        } else {
            $selections = ClientMachineMarqueSelection::with(['machine', 'marque'])
                ->where('reference', $reference)
                ->get();
        }

        $reservation = Reservation::where('reference', $reference)->first();
        $steps = $mission->steps()->get()->keyBy('step_no');

        return view('technicien.missions.show', compact(
            'mission',
            'selections',
            'reservation',
            'steps',
            'replacementMachines',
            'isPaid'
        ));
    }

    private function assertHasMedia(Request $request): void
    {
        if (
            !$request->hasFile('media_photo')
            && !$request->hasFile('media_video')
            && !$request->hasFile('media_file')
        ) {
            throw ValidationException::withMessages([
                'media' => 'Veuillez joindre au moins un média (photo / vidéo / fichier) avant de continuer.',
            ]);
        }
    }

    // =========================
    // ✅ NEW STEP 1 (was old step2)
    // =========================

    public function saveStep1(Request $request, Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_if($mission->status !== 'in_progress' || $mission->current_step !== 1, 403);

        if ($mission->kind === 'entretien') {

            $data = $request->validate([
                'comment' => ['nullable', 'string', 'max:2000'],
                'media_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
                'media_video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
                'media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],

                'will_fix' => ['required', 'in:yes,no'],
                'cannot_fix_reason' => ['required_if:will_fix,no', 'nullable', 'string', 'max:3000'],
                'propose_remplacer' => ['required_if:will_fix,no', 'in:yes,no', 'nullable'],
            ]);

            $this->assertHasMedia($request);
            $this->storeStepMedia($request, $mission, 1, $data); // ✅ step_no = 1

            // ✅ YES => go to Step 2 (final)
            if ($data['will_fix'] === 'yes') {
                $mission->update([
                    'will_fix' => true,
                    'cannot_fix_reason' => null,
                    'propose_remplacer' => null,
                    'proposal_status' => 'none',
                    'current_step' => 2,
                ]);

                return back()->with('success', 'Étape 1 enregistrée. Aller à l’étape 2.');
            }

            // ✅ NO
            $mission->update([
                'will_fix' => false,
                'cannot_fix_reason' => $data['cannot_fix_reason'] ?? null,
                'propose_remplacer' => ($data['propose_remplacer'] ?? 'no') === 'yes',
            ]);

            // If propose remplacer YES: stay on step 1 to choose marques + send proposal
            if (($data['propose_remplacer'] ?? 'no') === 'yes') {
                $mission->update([
                    'proposal_status' => 'none',
                    'current_step' => 1,
                ]);

                return redirect()
                    ->route('technicien.missions.entretien.remplacer.marques', $mission)
                    ->with('success', 'Choisissez les marques puis envoyez la proposition au client.');
            }

            // propose remplacer NO => mission finished now
            $mission->update([
                'proposal_status' => 'none',
                'status' => 'completed',
            ]);

            return redirect()->route('technicien.commandes')
                ->with('success', 'Mission terminée (non réparable).');
        }

        // ======================
        // REMPLACER
        // ======================
        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:2000'],
            'media_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'media_video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
            'media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],

            'will_install' => ['required', 'in:yes,no'],
            'cannot_install_reason' => ['required_if:will_install,no', 'nullable', 'string', 'max:3000'],
        ]);

        $this->assertHasMedia($request);
        $this->storeStepMedia($request, $mission, 1, $data); // ✅ step_no = 1

        // ✅ YES => go to Step 2 (final)
        if ($data['will_install'] === 'yes') {
            $mission->update([
                'will_install' => true,
                'cannot_install_reason' => null,
                'current_step' => 2,
            ]);

            return back()->with('success', 'Étape 1 enregistrée. Aller à l’étape 2.');
        }

        // ✅ NO => finish immediately
        $mission->update([
            'will_install' => false,
            'cannot_install_reason' => $data['cannot_install_reason'] ?? null,
            'status' => 'completed',
        ]);

        return redirect()->route('technicien.commandes')
            ->with('success', 'Mission terminée (installation impossible).');
    }

    // Proposal sending (unchanged)
    public function sendRemplacerProposal(Request $request, Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_if($mission->kind !== 'entretien', 403);
        abort_if($mission->will_fix !== false || $mission->propose_remplacer !== true, 403);

        $data = $request->validate([
            'marques' => ['required', 'array', 'min:1'],
            'marques.*' => ['nullable', 'integer', 'exists:marques,id'],
        ]);

        DB::transaction(function () use ($mission, $data) {

            MissionRecommendation::where('mission_id', $mission->id)->delete();

            foreach ($data['marques'] as $machineId => $marqueId) {
                $machineId = (int) $machineId;
                $marqueId = (int) $marqueId;

                if (!$machineId || !$marqueId) {
                    continue;
                }

                $ok = Machine::whereKey($machineId)
                    ->whereHas('marques', fn($q) => $q->whereKey($marqueId))
                    ->exists();

                if (!$ok) {
                    abort(422, "Marque ($marqueId) n'appartient pas à la machine ($machineId).");
                }

                MissionRecommendation::create([
                    'mission_id' => $mission->id,
                    'machine_id' => $machineId,
                    'marque_id' => $marqueId,
                ]);
            }

            $hasAny = MissionRecommendation::where('mission_id', $mission->id)->exists();
            if (!$hasAny) {
                abort(422, "Veuillez choisir au moins une marque.");
            }

            $proposal = ConversionProposal::firstOrCreate(
                ['mission_id' => $mission->id],
                [
                    'client_id' => $mission->client_id,
                    'old_reference' => $mission->reference,
                    'status' => 'pending',
                    'token' => Str::random(64),
                ]
            );

            $mission->update(['proposal_status' => 'pending']);

            $clientUser = User::whereHas('client', fn($q) => $q->where('id', $mission->client_id))->first();
            if ($clientUser) {
                $clientUser->notify(new EntretienToRemplacerProposalNotification($proposal->id));
            }
        });

        return redirect()->route('technicien.commandes')->with('success', 'Proposition envoyée au client (notification + email).');
    }

    // =========================
    // ✅ NEW STEP 2 (was old step3)
    // =========================

    public function saveStep2(Request $request, Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_if($mission->status !== 'in_progress' || $mission->current_step !== 2, 403);

        $alreadyPaid = Payment::where('reference', $mission->reference)
            ->where('kind', $mission->kind)
            ->where('status', 'paid')
            ->exists();

        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:2000'],
            'media_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'media_video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
            'media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],
            'paid' => $alreadyPaid ? ['nullable', 'in:yes,no'] : ['required', 'in:yes,no'],
        ]);

        $this->assertHasMedia($request);

        if ($alreadyPaid && (($data['paid'] ?? 'yes') !== 'yes')) {
            throw ValidationException::withMessages([
                'paid' => 'Commande déjà payée: impossible de changer le paiement.',
            ]);
        }

        $paidYes = $alreadyPaid ? true : (($data['paid'] ?? 'no') === 'yes');

        $this->storeStepMedia($request, $mission, 2, $data); // ✅ step_no = 2

        if ($paidYes) {
            $reference = $mission->reference;
            $type = $mission->kind;
            $clientId = $mission->client_id;

            if ($type === 'entretien') {
                $total = (float) ClientServiceSelection::where('client_id', $clientId)
                    ->where('reference', $reference)
                    ->with('type')
                    ->get()
                    ->sum(fn($s) => $s->type->prix ?? 0);
            } else {
                $total = (float) ClientMachineMarqueSelection::where('client_id', $clientId)
                    ->where('reference', $reference)
                    ->with('marque')
                    ->get()
                    ->sum(fn($s) => $s->marque->prix ?? 0);
            }

            DB::transaction(function () use ($clientId, $reference, $type, $total) {

                $payment = Payment::where('reference', $reference)
                    ->where('kind', $type)
                    ->lockForUpdate()
                    ->first();

                if ($payment && $payment->status === 'paid') {
                    return;
                }

                if (!$payment) {
                    Payment::create([
                        'client_id' => $clientId,
                        'reference' => $reference,
                        'kind' => $type,
                        'amount_original' => $total,
                        'discount_percent' => 0,
                        'amount_paid' => $total,
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                } else {
                    $payment->update([
                        'amount_original' => $payment->amount_original ?? $total,
                        'discount_percent' => $payment->discount_percent ?? 0,
                        'amount_paid' => $payment->amount_paid ?? $total,
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }
            });
        }

        $mission->update([
            'paid' => $paidYes,
            'status' => 'completed',
        ]);

        return redirect()->route('technicien.missions.show', $mission->id)
            ->with('success', 'Mission terminée.');
    }

    private function storeStepMedia(Request $request, Mission $mission, int $stepNo, array $data): void
    {
        $step = MissionStep::updateOrCreate(
            ['mission_id' => $mission->id, 'step_no' => $stepNo],
            ['comment' => $data['comment'] ?? null]
        );

        $file = null;
        $type = null;

        if ($request->hasFile('media_photo')) {
            $file = $request->file('media_photo');
            $type = 'photo';
        } elseif ($request->hasFile('media_video')) {
            $file = $request->file('media_video');
            $type = 'video';
        } elseif ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $mime = (string) $file->getMimeType();
            $type = str_starts_with($mime, 'video/') ? 'video' : 'photo';
        }

        if (!$file) {
            return;
        }

        $path = $file->store('missions', 'public');

        $step->update([
            'media_path' => $path,
            'media_type' => $type,
        ]);
    }

    // Marque selection screen (unchanged, still under current_step=1)
    public function entretienRemplacerMarques(Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_if($mission->kind !== 'entretien', 403);
        abort_if($mission->status !== 'in_progress' || $mission->current_step !== 1, 403);
        abort_if($mission->will_fix !== false || $mission->propose_remplacer !== true, 403);

        $reference = $mission->reference;

        $selections = ClientServiceSelection::with(['machine', 'type'])
            ->where('reference', $reference)
            ->get();

        $remIds = $selections->pluck('machine.remplacer_machine_id')->filter()->unique()->values();

        $replacementMachines = Machine::with('marques')
            ->whereIn('id', $remIds)
            ->get()
            ->keyBy('id');

        return view('technicien.missions.entretien_remplacer_marques', compact(
            'mission',
            'reference',
            'selections',
            'replacementMachines'
        ));
    }

    public function sendEntretienRemplacerProposal(Request $request, Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_if($mission->kind !== 'entretien', 403);
        abort_if($mission->status !== 'in_progress' || $mission->current_step !== 1, 403);
        abort_if($mission->will_fix !== false || $mission->propose_remplacer !== true, 403);

        $data = $request->validate([
            'marques' => ['required', 'array', 'min:1'],
            'marques.*' => ['required', 'integer', 'exists:marques,id'],
        ]);

        $reference = $mission->reference;

        $selections = ClientServiceSelection::with('machine')
            ->where('reference', $reference)
            ->get()
            ->keyBy('id');

        DB::transaction(function () use ($mission, $data, $selections) {

            MissionRecommendation::where('mission_id', $mission->id)->delete();

            $chosenByReplacementMachine = [];

            foreach ($data['marques'] as $selectionId => $marqueId) {
                $selectionId = (int) $selectionId;
                $marqueId = (int) $marqueId;

                $sel = $selections->get($selectionId);
                if (!$sel)
                    continue;

                $remId = (int) ($sel->machine->remplacer_machine_id ?? 0);
                if (!$remId)
                    continue;

                $ok = Machine::whereKey($remId)
                    ->whereHas('marques', fn($q) => $q->whereKey($marqueId))
                    ->exists();

                abort_if(!$ok, 422, "Marque ($marqueId) n'appartient pas à la machine ($remId).");

                $chosenByReplacementMachine[$remId] = $marqueId;
            }

            abort_if(empty($chosenByReplacementMachine), 422, "Veuillez choisir au moins une marque.");

            foreach ($chosenByReplacementMachine as $remMachineId => $marqueId) {
                MissionRecommendation::create([
                    'mission_id' => $mission->id,
                    'machine_id' => (int) $remMachineId,
                    'marque_id' => (int) $marqueId,
                ]);
            }

            $proposal = ConversionProposal::firstOrCreate(
                ['mission_id' => $mission->id],
                [
                    'client_id' => $mission->client_id,
                    'old_reference' => $mission->reference,
                    'status' => 'pending',
                    'token' => Str::random(64),
                ]
            );

            $mission->update([
                'proposal_status' => 'pending',
                'status' => 'completed',
            ]);

            $clientUser = User::whereHas('client', fn($q) => $q->where('id', $mission->client_id))->first();
            if ($clientUser) {
                $clientUser->notify(new EntretienToRemplacerProposalNotification($proposal->id));
            }
        });

        return redirect()
            ->route('technicien.commandes')
            ->with('success', 'Proposition envoyée au client. Retour au planning.');
    }


    private function canEditStep(Mission $mission, int $stepNo): void
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);
        abort_unless(in_array($stepNo, [1, 2], true), 404);

        // ✅ Allow edit ONLY if mission is completed or step already exists OR current_step >= stepNo
        // you can tighten/relax this depending on your business logic
        abort_if(!in_array($mission->status, ['in_progress', 'completed'], true), 403);
    }

    public function editStep(Mission $mission, int $stepNo)
    {
        $this->canEditStep($mission, $stepNo);

        $step = MissionStep::where('mission_id', $mission->id)
            ->where('step_no', $stepNo)
            ->first();

        return view('technicien.missions.edit_step', compact('mission', 'stepNo', 'step'));
    }

    public function updateStep(Request $request, Mission $mission, int $stepNo)
    {
        $this->canEditStep($mission, $stepNo);

        $data = $request->validate([
            'comment' => ['nullable', 'string', 'max:2000'],

            // optional new media
            'media_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:20480'],
            'media_video' => ['nullable', 'file', 'mimes:mp4,mov,webm', 'max:51200'],
            'media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],
        ]);

        $step = MissionStep::firstOrCreate(
            ['mission_id' => $mission->id, 'step_no' => $stepNo],
            ['comment' => null]
        );

        // ✅ Update comment
        $step->comment = $data['comment'] ?? $step->comment;
        $step->save();

        // ✅ If a new media is uploaded, replace old one
        $newFile = null;
        $newType = null;

        if ($request->hasFile('media_photo')) {
            $newFile = $request->file('media_photo');
            $newType = 'photo';
        } elseif ($request->hasFile('media_video')) {
            $newFile = $request->file('media_video');
            $newType = 'video';
        } elseif ($request->hasFile('media_file')) {
            $newFile = $request->file('media_file');
            $mime = (string) $newFile->getMimeType();
            $newType = str_starts_with($mime, 'video/') ? 'video' : 'photo';
        }

        if ($newFile) {
            // delete old file
            if ($step->media_path && Storage::disk('public')->exists($step->media_path)) {
                Storage::disk('public')->delete($step->media_path);
            }

            $path = $newFile->store('missions', 'public');
            $step->update([
                'media_path' => $path,
                'media_type' => $newType,
            ]);
        }

        return redirect()
    ->route('technicien.missions.details', $mission->id)
    ->with('success', "Step {$stepNo} mis à jour.");
    }

    public function deleteStepMedia(Mission $mission, int $stepNo)
    {
        $this->canEditStep($mission, $stepNo);

        $step = MissionStep::where('mission_id', $mission->id)
            ->where('step_no', $stepNo)
            ->firstOrFail();

        if ($step->media_path && Storage::disk('public')->exists($step->media_path)) {
            Storage::disk('public')->delete($step->media_path);
        }

        $step->update([
            'media_path' => null,
            'media_type' => null,
        ]);

        return back()->with('success', "Média Step {$stepNo} supprimé.");
    }
    public function details(Mission $mission)
    {
        abort_if($mission->technicien_user_id !== auth()->id(), 403);

        $reference = $mission->reference;

        $payment = Payment::where('reference', $reference)
            ->where('kind', $mission->kind)
            ->first();

        $isPaid = (bool) ($payment && $payment->status === 'paid');

        $replacementMachines = collect();

        if ($mission->kind === 'entretien') {
            $selections = ClientServiceSelection::with(['machine', 'type'])
                ->where('reference', $reference)
                ->get();

            $remIds = $selections
                ->pluck('machine.remplacer_machine_id')
                ->filter()
                ->unique()
                ->values();

            $replacementMachines = Machine::with('marques')
                ->whereIn('id', $remIds)
                ->get()
                ->keyBy('id');
        } else {
            $selections = ClientMachineMarqueSelection::with(['machine', 'marque'])
                ->where('reference', $reference)
                ->get();
        }

        $reservation = Reservation::where('reference', $reference)->first();
        $steps = $mission->steps()->get()->keyBy('step_no');
        $client = $mission->client;

        return view('technicien.missions.detailsmission', compact(
            'mission',
            'selections',
            'reservation',
            'steps',
            'replacementMachines',
            'isPaid',
            'client'
        ));
    }

}