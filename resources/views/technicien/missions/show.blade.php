{{-- resources/views/technicien/missions/show.blade.php --}}
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

    // ✅ 2 steps now
    $progressPct = match(true) {
        $completed => 100,
        $step <= 1 => 50,
        default => 90,
    };
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- TOP BAR --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Retour
        </a>

        <div class="flex flex-wrap gap-2 justify-end">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $statusBadge }}">
                {{ strtoupper($mission->status) }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $kindBadge }}">
                {{ $kind }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-zinc-200 bg-zinc-50 text-zinc-800">
                Step {{ $step }}/2
            </span>
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
                <div class="text-xs uppercase tracking-wide text-orange-800 font-extrabold">Focus</div>
                @if($completed)
                    <div class="mt-1 font-extrabold text-orange-900">Mission terminée</div>
                @else
                    <div class="mt-1 font-extrabold text-orange-900">Step {{ $step }}</div>
                @endif
                <div class="text-sm text-orange-800">
                    @if($step === 1) Diagnostic & décision @endif
                    @if($step === 2) Finalisation & paiement @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ERROR BOX --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-2xl p-4 text-sm">
            <div class="font-extrabold mb-2">Veuillez corriger :</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @error('media')
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl p-4">
            {{ $message }}
        </div>
    @enderror

    @if ($errors->has('media_photo') || $errors->has('media_video') || $errors->has('media_file'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl p-4 space-y-1">
            @error('media_photo') <div>{{ $message }}</div> @enderror
            @error('media_video') <div>{{ $message }}</div> @enderror
            @error('media_file') <div>{{ $message }}</div> @enderror
        </div>
    @endif

    {{-- ✅ NEW STEP 1 (old step2) --}}
    @if($step === 1 && $inProgress)
        <form method="POST"
              action="{{ route('technicien.missions.step1', $mission) }}"
              enctype="multipart/form-data"
              class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6 space-y-5">
            @csrf

            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500 font-extrabold">Step 1</div>
                    <h2 class="text-xl font-extrabold text-zinc-900">Diagnostic / Décision</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-orange-200 bg-orange-50 text-orange-900">
                    Diagnostic
                </span>
            </div>

            {{-- MEDIA PICKER --}}
            <div x-data="{
                    mode: 'photo',
                    setMode(m){
                        this.mode = m;
                        if(m !== 'photo' && this.$refs.photo) this.$refs.photo.value = '';
                        if(m !== 'video' && this.$refs.video) this.$refs.video.value = '';
                        if(m !== 'file'  && this.$refs.file)  this.$refs.file.value  = '';
                    }
                }" class="space-y-3">

                <label class="font-extrabold text-zinc-900">Média</label>

                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="setMode('photo')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='photo' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Photo
                    </button>

                    <button type="button" @click="setMode('video')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='video' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Vidéo
                    </button>

                    <button type="button" @click="setMode('file')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='file' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Fichier
                    </button>
                </div>

                <div x-show="mode==='photo'" x-cloak>
                    <input x-ref="photo" type="file" name="media_photo" accept="image/*" capture="environment"
                           :required="mode==='photo'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>

                <div x-show="mode==='video'" x-cloak>
                    <input x-ref="video" type="file" name="media_video" accept="video/*" capture="environment"
                           :required="mode==='video'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>

                <div x-show="mode==='file'" x-cloak>
                    <input x-ref="file" type="file" name="media_file" accept="image/*,video/*"
                           :required="mode==='file'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>
            </div>

            <div>
                <label class="font-extrabold text-zinc-900">Commentaire (optionnel)</label>
                <textarea name="comment"
                          class="mt-2 w-full border border-zinc-200 rounded-2xl p-3"
                          rows="3"
                          placeholder="Résultat diagnostic...">{{ old('comment') }}</textarea>
            </div>

            @if($mission->kind === 'entretien')
                <div x-data="{ willFix: '{{ old('will_fix', 'yes') }}', propose: '{{ old('propose_remplacer', 'no') }}' }"
                     class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 space-y-4">

                    <div>
                        <div class="font-extrabold text-zinc-900">Vous allez réparer ?</div>
                        <div class="mt-2 flex gap-6">
                            <label class="flex items-center gap-2 font-semibold">
                                <input type="radio" name="will_fix" value="yes" x-model="willFix" required>
                                Oui
                            </label>
                            <label class="flex items-center gap-2 font-semibold">
                                <input type="radio" name="will_fix" value="no" x-model="willFix" required>
                                Non
                            </label>
                        </div>
                    </div>

                    <div x-show="willFix==='no'" x-cloak class="space-y-3">
                        <textarea name="cannot_fix_reason"
                                  class="w-full border border-zinc-200 rounded-2xl p-3"
                                  rows="3"
                                  :disabled="willFix!=='no'"
                                  :required="willFix==='no'"
                                  placeholder="Si NON: pourquoi ?">{{ old('cannot_fix_reason') }}</textarea>

                        <div>
                            <div class="font-extrabold text-zinc-900">Proposer un remplacement ?</div>
                            <div class="mt-2 flex gap-6">
                                <label class="flex items-center gap-2 font-semibold">
                                    <input type="radio" name="propose_remplacer" value="yes" x-model="propose" :disabled="willFix!=='no'">
                                    Oui
                                </label>
                                <label class="flex items-center gap-2 font-semibold">
                                    <input type="radio" name="propose_remplacer" value="no" x-model="propose" :disabled="willFix!=='no'">
                                    Non
                                </label>
                            </div>
                        </div>

                        <div class="text-xs text-zinc-600 font-semibold">
                            Si remplacement = OUI, vous allez ensuite choisir une marque et envoyer une proposition.
                        </div>
                    </div>
                </div>
            @else
                <div x-data="{ willInstall: '{{ old('will_install', 'yes') }}' }"
                     class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 space-y-4">

                    <div>
                        <div class="font-extrabold text-zinc-900">Vous allez installer ?</div>
                        <div class="mt-2 flex gap-6">
                            <label class="flex items-center gap-2 font-semibold">
                                <input type="radio" name="will_install" value="yes" x-model="willInstall" required>
                                Oui
                            </label>
                            <label class="flex items-center gap-2 font-semibold">
                                <input type="radio" name="will_install" value="no" x-model="willInstall" required>
                                Non
                            </label>
                        </div>
                    </div>

                    <div x-show="willInstall==='no'" x-cloak class="space-y-2">
                        <textarea name="cannot_install_reason"
                                  class="w-full border border-zinc-200 rounded-2xl p-3"
                                  rows="3"
                                  :disabled="willInstall!=='no'"
                                  :required="willInstall==='no'"
                                  placeholder="Si NON: pourquoi ?">{{ old('cannot_install_reason') }}</textarea>
                    </div>
                </div>
            @endif

            <button class="w-full px-5 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-2xl font-extrabold transition">
                Enregistrer Step 1
            </button>
        </form>
    @endif

    {{-- ✅ NEW STEP 2 (old step3) --}}
    @if($step === 2 && $inProgress)
        <form method="POST"
              action="{{ route('technicien.missions.step2', $mission) }}"
              enctype="multipart/form-data"
              class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6 space-y-5">
            @csrf

            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="text-xs uppercase tracking-wide text-zinc-500 font-extrabold">Step 2</div>
                    <h2 class="text-xl font-extrabold text-zinc-900">Finalisation (preuve + paiement)</h2>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-orange-200 bg-orange-50 text-orange-900">
                    Clôture
                </span>
            </div>

            {{-- MEDIA PICKER --}}
            <div x-data="{
                    mode: 'photo',
                    setMode(m){
                        this.mode = m;
                        if(m !== 'photo' && this.$refs.photo) this.$refs.photo.value = '';
                        if(m !== 'video' && this.$refs.video) this.$refs.video.value = '';
                        if(m !== 'file'  && this.$refs.file)  this.$refs.file.value  = '';
                    }
                }" class="space-y-3">

                <label class="font-extrabold text-zinc-900">Média</label>

                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="setMode('photo')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='photo' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Photo
                    </button>

                    <button type="button" @click="setMode('video')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='video' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Vidéo
                    </button>

                    <button type="button" @click="setMode('file')"
                        class="px-4 py-3 rounded-2xl border text-sm font-extrabold transition"
                        :class="mode==='file' ? 'bg-orange-600 text-white border-orange-600' : 'bg-white hover:bg-zinc-50 border-zinc-200'">
                        Fichier
                    </button>
                </div>

                <div x-show="mode==='photo'" x-cloak>
                    <input x-ref="photo" type="file" name="media_photo" accept="image/*" capture="environment"
                           :required="mode==='photo'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>

                <div x-show="mode==='video'" x-cloak>
                    <input x-ref="video" type="file" name="media_video" accept="video/*" capture="environment"
                           :required="mode==='video'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>

                <div x-show="mode==='file'" x-cloak>
                    <input x-ref="file" type="file" name="media_file" accept="image/*,video/*"
                           :required="mode==='file'"
                           class="block w-full border border-zinc-200 rounded-2xl p-3">
                </div>
            </div>

            <div>
                <label class="font-extrabold text-zinc-900">Commentaire (optionnel)</label>
                <textarea name="comment"
                          class="mt-2 w-full border border-zinc-200 rounded-2xl p-3"
                          rows="3"
                          placeholder="Fin mission...">{{ old('comment') }}</textarea>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 space-y-3">
                <div class="font-extrabold text-zinc-900">Client a payé ?</div>

                @if(($isPaid ?? false))
                    <input type="hidden" name="paid" value="yes">
                    <div class="text-sm text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 font-semibold">
                        Paiement déjà enregistré. Vous ne pouvez pas modifier ce statut.
                    </div>
                @endif

                <div class="flex gap-6">
                    <label class="flex items-center gap-2 font-semibold">
                        <input type="radio" name="paid" value="yes"
                               {{ ($isPaid ?? false) ? 'checked' : (old('paid') === 'yes' ? 'checked' : '') }}
                               {{ ($isPaid ?? false) ? 'disabled' : '' }}>
                        Oui
                    </label>

                    <label class="flex items-center gap-2 font-semibold">
                        <input type="radio" name="paid" value="no"
                               {{ ($isPaid ?? false) ? '' : (old('paid', 'no') === 'no' ? 'checked' : '') }}
                               {{ ($isPaid ?? false) ? 'disabled' : '' }}>
                        Non
                    </label>
                </div>

                @error('paid')
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-2xl p-4">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button class="w-full px-5 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-2xl font-extrabold transition">
                Terminer mission
            </button>
        </form>
    @endif

    {{-- COMPLETED --}}
    @if($completed)
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-2xl p-6">
            <div class="font-extrabold text-lg">Mission terminée ✅</div>

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('technicien.commandes') }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold transition">
                    Retour planning
                </a>

               <a href="{{ route('technicien.missions.details', $mission) }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-white border border-zinc-200 hover:bg-zinc-50 rounded-2xl font-extrabold transition">
                    Voir mission
                </a>
            </div>
        </div>
    @endif

</div>
@endsection