{{-- resources/views/technicien/missions/detailsmission.blade.php --}}
@extends('technicien.menu')

@section('content')
@php
    $kind = strtoupper($mission->kind ?? '');
    $step = (int)($mission->current_step ?? 1);
    $inProgress = ($mission->status === 'in_progress');
    $completed  = ($mission->status === 'completed');

    $statusBadge = match($mission->status) {
        'in_progress' => 'bg-blue-50 text-blue-800 border-blue-200',
        'completed'   => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'cancelled'   => 'bg-red-50 text-red-800 border-red-200',
        default       => 'bg-zinc-50 text-zinc-800 border-zinc-200',
    };

    $kindBadge = ($mission->kind === 'entretien')
        ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
        : 'bg-indigo-50 text-indigo-800 border-indigo-200';

    $progressPct = match(true) {
        $completed => 100,
        $step <= 1 => 50,
        default => 90,
    };

    $step1 = $steps->get(1);
    $step2 = $steps->get(2);

    $step1Url = $step1?->media_path ? asset('storage/'.$step1->media_path) : null;
    $step2Url = $step2?->media_path ? asset('storage/'.$step2->media_path) : null;

    $clientName = trim(($client?->prenom ?? '').' '.($client?->nom ?? '')) ?: '-';
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- TOP BAR --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('technicien.commandes') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition w-full sm:w-auto">
            ← Retour planning
        </a>

        <div class="flex flex-wrap gap-2 justify-end items-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusBadge }}">
                {{ strtoupper($mission->status) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $kindBadge }}">
                {{ $kind }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-zinc-200 bg-zinc-50 text-zinc-800">
                Step {{ $step }}/2
            </span>

            <!-- <a href="{{ route('technicien.missions.show', $mission) }}"
               class="inline-flex items-center px-3 py-2 rounded-xl border border-orange-200 bg-orange-50 text-orange-900 hover:bg-orange-100 font-extrabold text-xs">
                Ouvrir la mission
            </a>

            <a href="{{ route('technicien.missions.steps.edit', [$mission, 1]) }}"
               class="inline-flex items-center px-3 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-800 font-extrabold text-xs">
                Edit Step 1
            </a>

            <a href="{{ route('technicien.missions.steps.edit', [$mission, 2]) }}"
               class="inline-flex items-center px-3 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-800 font-extrabold text-xs">
                Edit Step 2
            </a> -->
        </div>
    </div>

    {{-- HEADER --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-zinc-500 font-semibold">Mission</div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900">
                    <span class="text-orange-600">{{ $mission->reference }}</span>
                </h1>

                <div class="mt-2 text-sm text-zinc-600 font-semibold">
                    Client : <span class="text-zinc-900 font-extrabold">{{ $clientName }}</span>
                </div>

                <div class="mt-4">
                    <div class="h-2 rounded-full bg-zinc-100 overflow-hidden">
                        <div class="h-full bg-orange-600" style="width: {{ $progressPct }}%"></div>
                    </div>
                    <div class="mt-2 text-sm text-zinc-600 font-semibold">
                        Progression: {{ $progressPct }}%
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4">
                <div class="text-xs uppercase tracking-wide text-orange-800 font-extrabold">Rendez-vous</div>

                @if($reservation)
                    <div class="mt-1 font-extrabold text-orange-900">
                        {{ $reservation->date_souhaite }} — {{ $reservation->hour }}
                    </div>
                @else
                    <div class="mt-1 font-semibold text-orange-900">Aucun RDV</div>
                @endif

                <div class="mt-2 text-sm text-orange-800">
                    Paiement:
                    @if(($isPaid ?? false))
                        <span class="font-extrabold text-emerald-800">PAYÉ</span>
                    @else
                        <span class="font-extrabold text-red-800">NON PAYÉ</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- CLIENT DETAILS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
            <div class="text-sm text-zinc-500 font-semibold">Détails client</div>
            <div class="mt-2 font-extrabold text-zinc-900 text-lg">{{ $clientName }}</div>

            <div class="mt-3 text-sm text-zinc-700 space-y-1">
                <div><span class="font-semibold">Téléphone:</span> {{ $client?->telephone ?? '-' }}</div>
                <div><span class="font-semibold">Email:</span> {{ $client?->email ?? '-' }}</div>
                <div><span class="font-semibold">Adresse:</span> {{ $client?->adresse ?? '-' }}</div>
                <div><span class="font-semibold">Ville:</span> {{ $client?->ville?->name ?? '-' }}</div>
                <div><span class="font-semibold">Localisation:</span> {{ $client?->location ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
            <div class="text-sm text-zinc-500 font-semibold">Infos mission</div>

            <div class="mt-3 text-sm text-zinc-700 space-y-1">
                <div><span class="font-semibold">Référence:</span> {{ $mission->reference }}</div>
                <div><span class="font-semibold">Type:</span> {{ strtoupper($mission->kind) }}</div>
                <div><span class="font-semibold">Status:</span> {{ strtoupper($mission->status) }}</div>
                <div><span class="font-semibold">Step actuel:</span> {{ $step }}/2</div>

                @if($mission->kind === 'entretien')
                    <div class="mt-2">
                        <span class="font-semibold">Réparable ?</span>
                        <span class="font-extrabold">
                            @if(is_null($mission->will_fix)) -
                            @else {{ $mission->will_fix ? 'OUI' : 'NON' }} @endif
                        </span>
                    </div>
                    @if($mission->will_fix === false)
                        <div><span class="font-semibold">Raison:</span> {{ $mission->cannot_fix_reason ?? '-' }}</div>
                        <div><span class="font-semibold">Remplacement proposé:</span> {{ $mission->propose_remplacer ? 'OUI' : 'NON' }}</div>
                        <div><span class="font-semibold">Proposal status:</span> {{ strtoupper($mission->proposal_status ?? 'none') }}</div>
                    @endif
                @else
                    <div class="mt-2">
                        <span class="font-semibold">Installation ?</span>
                        <span class="font-extrabold">
                            @if(is_null($mission->will_install)) -
                            @else {{ $mission->will_install ? 'OUI' : 'NON' }} @endif
                        </span>
                    </div>
                    @if($mission->will_install === false)
                        <div><span class="font-semibold">Raison:</span> {{ $mission->cannot_install_reason ?? '-' }}</div>
                    @endif
                @endif

                <div class="mt-2">
                    <span class="font-semibold">Paiement:</span>
                    <span class="font-extrabold">{{ ($isPaid ?? false) ? 'PAYÉ' : 'NON PAYÉ' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- COMMANDE DETAILS --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="text-sm text-zinc-500 font-semibold">Détails commande</div>

        <div class="mt-4 space-y-3">
            @forelse($selections as $sel)
                <div class="border border-zinc-200 rounded-2xl p-4">
                    <div class="font-extrabold text-zinc-900">
                        @if($mission->kind === 'entretien')
                            {{ $sel->machine?->name ?? 'Machine' }}
                            <span class="text-zinc-400">—</span>
                            <span class="text-zinc-800">{{ $sel->type?->name ?? '-' }}</span>
                        @else
                            {{ $sel->machine?->name ?? 'Machine' }}
                            <span class="text-zinc-400">—</span>
                            <span class="text-zinc-800">{{ $sel->marque?->name ?? '-' }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-sm text-zinc-600">Aucune sélection.</div>
            @endforelse
        </div>
    </div>

    {{-- STEP DETAILS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Step 1 --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div class="font-extrabold text-zinc-900">Step 1 — Diagnostic</div>
                <a href="{{ route('technicien.missions.steps.edit', [$mission, 1]) }}"
                   class="text-xs font-extrabold px-3 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50">
                    Modifier
                </a>
            </div>

            <div class="mt-3 text-sm text-zinc-700">
                <div class="font-semibold text-zinc-900">Commentaire</div>
                <div class="mt-1 bg-zinc-50 border border-zinc-200 rounded-xl p-3">
                    {{ $step1?->comment ?? '-' }}
                </div>
            </div>

            <div class="mt-4">
                <div class="font-semibold text-zinc-900 text-sm">Média</div>
                @if($step1Url)
                    @if(($step1->media_type ?? '') === 'video')
                        <video class="w-full mt-2 rounded-xl border border-zinc-200" controls playsinline>
                            <source src="{{ $step1Url }}">
                        </video>
                    @else
                        <img class="w-full mt-2 rounded-xl border border-zinc-200" src="{{ $step1Url }}" alt="step1 media">
                    @endif

                    <a class="inline-flex mt-3 items-center justify-center w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold"
                       href="{{ $step1Url }}" target="_blank">
                        Ouvrir média
                    </a>
                @else
                    <div class="mt-2 text-sm text-zinc-600">Aucun média.</div>
                @endif
            </div>
        </div>

        {{-- Step 2 --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
            <div class="flex items-center justify-between">
                <div class="font-extrabold text-zinc-900">Step 2 — Final</div>
                <a href="{{ route('technicien.missions.steps.edit', [$mission, 2]) }}"
                   class="text-xs font-extrabold px-3 py-2 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50">
                    Modifier
                </a>
            </div>

            <div class="mt-3 text-sm text-zinc-700">
                <div class="font-semibold text-zinc-900">Commentaire</div>
                <div class="mt-1 bg-zinc-50 border border-zinc-200 rounded-xl p-3">
                    {{ $step2?->comment ?? '-' }}
                </div>
            </div>

            <div class="mt-4">
                <div class="font-semibold text-zinc-900 text-sm">Média</div>
                @if($step2Url)
                    @if(($step2->media_type ?? '') === 'video')
                        <video class="w-full mt-2 rounded-xl border border-zinc-200" controls playsinline>
                            <source src="{{ $step2Url }}">
                        </video>
                    @else
                        <img class="w-full mt-2 rounded-xl border border-zinc-200" src="{{ $step2Url }}" alt="step2 media">
                    @endif

                    <a class="inline-flex mt-3 items-center justify-center w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold"
                       href="{{ $step2Url }}" target="_blank">
                        Ouvrir média
                    </a>
                @else
                    <div class="mt-2 text-sm text-zinc-600">Aucun média.</div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection