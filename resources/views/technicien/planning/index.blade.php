{{-- resources/views/technicien/commandes/index.blade.php --}}
@extends('technicien.menu')

@section('content')
@php
    // Field Ops palette
    $typeBadge = fn($t) => match ($t) {
        'ENTRETIEN' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'REMPLACER' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
        default     => 'bg-zinc-50 text-zinc-800 border-zinc-200',
    };

    $payBadge = fn($paid) => $paid
        ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
        : 'bg-red-50 text-red-800 border-red-200';

    // Mission status helpers
    $missionStatusBadge = fn($st) => match ($st) {
        'not_started' => 'bg-zinc-50 text-zinc-800 border-zinc-200',
        'in_progress' => 'bg-orange-50 text-orange-900 border-orange-200',
        'completed'   => 'bg-emerald-50 text-emerald-900 border-emerald-200',
        default       => 'bg-zinc-50 text-zinc-800 border-zinc-200',
    };

    $missionStatusLabel = fn($st) => match ($st) {
        'not_started' => 'Not started',
        'in_progress' => 'In progress',
        'completed'   => 'Complete',
        default       => '—',
    };

    // today highlight
    $isToday = ($selectedDate ?? '') === now()->format('Y-m-d');

    // ✅ FILTER (query param)
    $filter = request('filter', 'all'); // all | entretien | remplacer | not_started | in_progress | completed

    // ✅ Build mission map ONCE
    $missionByKey = [];      // key => Mission|null
    $missionStateByKey = []; // key => not_started|in_progress|completed

    foreach ($commandes as $c) {
        if ($c->type === 'UNKNOWN') {
            $key = 'unknown|' . $c->reference;
            $missionByKey[$key] = null;
            $missionStateByKey[$key] = 'not_started';
            continue;
        }

        $kind = strtolower($c->type); // entretien|remplacer
        $key  = $kind . '|' . $c->reference;

        $m = \App\Models\Mission::where('reference', $c->reference)
            ->where('kind', $kind)
            ->first();

        $missionByKey[$key] = $m;

        if (!$m) {
            $missionStateByKey[$key] = 'not_started';
        } elseif ($m->status === 'completed') {
            $missionStateByKey[$key] = 'completed';
        } else {
            $missionStateByKey[$key] = 'in_progress';
        }
    }

    // ✅ QUICK STATS COUNTS
    $countEntretien = $commandes->where('type', 'ENTRETIEN')->count();
    $countRemplacer = $commandes->where('type', 'REMPLACER')->count();

    $statusCounts = ['not_started' => 0, 'in_progress' => 0, 'completed' => 0];
    foreach ($commandes as $c) {
        if ($c->type === 'UNKNOWN') continue;
        $key = strtolower($c->type) . '|' . $c->reference;
        $st  = $missionStateByKey[$key] ?? 'not_started';
        $statusCounts[$st] = ($statusCounts[$st] ?? 0) + 1;
    }

    // ✅ Apply filtering to commandes
    $filteredCommandes = $commandes->filter(function ($c) use ($filter, $missionStateByKey) {
        if ($filter === 'all') return true;

        if ($filter === 'entretien') return $c->type === 'ENTRETIEN';
        if ($filter === 'remplacer') return $c->type === 'REMPLACER';

        if (in_array($filter, ['not_started','in_progress','completed'], true)) {
            if ($c->type === 'UNKNOWN') return false;
            $key = strtolower($c->type) . '|' . $c->reference;
            return ($missionStateByKey[$key] ?? 'not_started') === $filter;
        }

        return true;
    });

    // helper: active style for filter buttons
    $filterBtn = function(string $value) use ($filter) {
        $base = "inline-flex items-center justify-center px-4 py-2 rounded-2xl border font-extrabold transition text-sm";
        if ($filter === $value) {
            return $base . " border-orange-200 bg-orange-50 text-orange-900";
        }
        return $base . " border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-900";
    };
@endphp

<div class="max-w-6xl mx-auto p-6 space-y-6">

    {{-- HEADER --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-zinc-500 font-semibold">Planning</div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-zinc-900">
                    Planning des commandes
                    @if($isToday)
                        <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-orange-200 bg-orange-50 text-orange-900">
                            Aujourd’hui
                        </span>
                    @endif
                </h1>
                <div class="mt-2 text-sm text-zinc-600 font-semibold">
                    Date:
                    <span class="text-orange-700 font-extrabold">
                        {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            {{-- DATE PICKER --}}
            <form method="GET" action="{{ route('technicien.commandes') }}" class="flex items-end gap-3">
                <div class="flex flex-col">
                    <label class="text-sm font-extrabold text-zinc-900">Choisir une date</label>
                    <input type="date"
                           name="date"
                           value="{{ $selectedDate }}"
                           class="mt-2 border border-zinc-200 rounded-2xl px-4 py-3 font-semibold focus:ring-2 focus:ring-orange-200 focus:border-orange-400"
                           onchange="this.form.submit()">
                </div>

                <a href="{{ route('technicien.commandes', ['date' => now()->format('Y-m-d'), 'filter' => $filter]) }}"
                   class="hidden sm:inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition">
                    Today
                </a>
            </form>
        </div>

        {{-- QUICK STATS (type + status) --}}
        {{-- ✅ QUICK STATS (clickable filter cards) --}}
<div class="mt-6 grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-3">

    @php
        $cardBase = "group block rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-sm";
        $cardActive = "border-orange-200 bg-orange-50";
        $cardIdle   = "border-zinc-200 bg-zinc-50 hover:bg-white";

        $isActive = fn($v) => ($filter ?? 'all') === $v;
    @endphp

    {{-- TOTAL = ALL --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'all']) }}"
       class="{{ $cardBase }} {{ $isActive('all') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('all') ? 'text-orange-900' : 'text-zinc-500' }}">
            Total
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('all') ? 'text-orange-900' : 'text-zinc-900' }}">
            {{ $commandes->count() }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('all') ? 'bg-orange-600' : 'bg-zinc-200 group-hover:bg-orange-200' }}"></div>
    </a>

    {{-- ENTRETIEN --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'entretien']) }}"
       class="{{ $cardBase }} {{ $isActive('entretien') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('entretien') ? 'text-orange-900' : 'text-zinc-500' }}">
            Entretien
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('entretien') ? 'text-orange-900' : 'text-emerald-700' }}">
            {{ $countEntretien }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('entretien') ? 'bg-orange-600' : 'bg-emerald-200 group-hover:bg-orange-200' }}"></div>
    </a>

    {{-- REMPLACER --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'remplacer']) }}"
       class="{{ $cardBase }} {{ $isActive('remplacer') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('remplacer') ? 'text-orange-900' : 'text-zinc-500' }}">
            Remplacer
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('remplacer') ? 'text-orange-900' : 'text-indigo-700' }}">
            {{ $countRemplacer }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('remplacer') ? 'bg-orange-600' : 'bg-indigo-200 group-hover:bg-orange-200' }}"></div>
    </a>

    {{-- NOT STARTED --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'not_started']) }}"
       class="{{ $cardBase }} {{ $isActive('not_started') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('not_started') ? 'text-orange-900' : 'text-zinc-500' }}">
            Not started
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('not_started') ? 'text-orange-900' : 'text-zinc-900' }}">
            {{ $statusCounts['not_started'] }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('not_started') ? 'bg-orange-600' : 'bg-zinc-200 group-hover:bg-orange-200' }}"></div>
    </a>

    {{-- IN PROGRESS --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'in_progress']) }}"
       class="{{ $cardBase }} {{ $isActive('in_progress') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('in_progress') ? 'text-orange-900' : 'text-zinc-500' }}">
            In progress
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('in_progress') ? 'text-orange-900' : 'text-orange-700' }}">
            {{ $statusCounts['in_progress'] }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('in_progress') ? 'bg-orange-600' : 'bg-orange-200 group-hover:bg-orange-300' }}"></div>
    </a>

    {{-- COMPLETED --}}
    <a href="{{ route('technicien.commandes', ['date' => $selectedDate, 'filter' => 'completed']) }}"
       class="{{ $cardBase }} {{ $isActive('completed') ? $cardActive : $cardIdle }}">
        <div class="text-xs uppercase tracking-wide font-extrabold {{ $isActive('completed') ? 'text-orange-900' : 'text-zinc-500' }}">
            Complete
        </div>
        <div class="text-2xl font-extrabold {{ $isActive('completed') ? 'text-orange-900' : 'text-emerald-700' }}">
            {{ $statusCounts['completed'] }}
        </div>
        <div class="mt-2 h-1 w-10 rounded-full {{ $isActive('completed') ? 'bg-orange-600' : 'bg-emerald-200 group-hover:bg-orange-200' }}"></div>
    </a>

</div>

       
    </div>

    {{-- EMPTY --}}
    @if($filteredCommandes->count() === 0)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-900 rounded-2xl p-6 font-semibold">
            Aucune commande pour cette date / filtre.
        </div>
    @else

    {{-- TABLE / MOBILE CARDS --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200 flex items-center justify-between">
            <div class="font-extrabold text-zinc-900">Liste des commandes</div>
            <div class="text-sm text-zinc-600 font-semibold">
                {{ $filteredCommandes->count() }} élément(s)
            </div>
        </div>

        {{-- ✅ MOBILE: CARDS --}}
        <div class="md:hidden p-4 space-y-3">
            @foreach($filteredCommandes as $cmd)
                @php
                    $typeUrl = strtolower($cmd->type);

                    $key = ($cmd->type === 'UNKNOWN')
                        ? ('unknown|' . $cmd->reference)
                        : ($typeUrl . '|' . $cmd->reference);

                    $mission = $missionByKey[$key] ?? null;
                    $missionState = $missionStateByKey[$key] ?? 'not_started';
                @endphp

                <div class="rounded-2xl border border-zinc-200 bg-white p-4">

                    {{-- Labels (Heure + Référence same line) --}}
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-zinc-500 font-extrabold uppercase">Heure</div>
                        <div class="text-xs text-zinc-500 font-extrabold uppercase">Référence</div>
                    </div>

                    {{-- Values (time left, reference right) --}}
                    <div class="flex items-start justify-between gap-3 mt-1">
                        <div class="text-lg font-extrabold text-zinc-900 whitespace-nowrap">
                            {{ substr($cmd->hour, 0, 5) }}
                        </div>

                        <div class="font-extrabold text-zinc-900 text-right break-all">
                            {{ $cmd->reference }}
                        </div>
                    </div>

                    {{-- Type + Status same line --}}
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $typeBadge($cmd->type) }}">
                            {{ $cmd->type }}
                        </span>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $missionStatusBadge($missionState) }}">
                            {{ $missionStatusLabel($missionState) }}
                        </span>
                    </div>

                    {{-- Client --}}
                    <div class="mt-3 rounded-xl bg-zinc-50 border border-zinc-200 p-3">
                        <div class="text-xs text-zinc-500 font-extrabold uppercase">Client</div>
                        <div class="font-semibold text-zinc-900 break-words">
                            {{ $cmd->client ? ($cmd->client->nom . ' ' . $cmd->client->prenom) : '-' }}
                        </div>
                    </div>

                    {{-- Buttons each on its own line --}}
                    <div class="mt-4 space-y-3">

                        {{-- Ouvrir --}}
                        @if($cmd->type !== 'UNKNOWN')
                            <a href="{{ route('technicien.commandes.show', ['type' => $typeUrl, 'reference' => $cmd->reference]) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                                Ouvrir
                            </a>
                        @else
                            <span class="w-full inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-400 font-extrabold">
                                —
                            </span>
                        @endif

                        {{-- Mission action (each state has its own design) --}}
                        @if($cmd->type !== 'UNKNOWN')

                            @if($missionState === 'not_started')
                                <a href="{{ route('technicien.missions.start', ['type' => $typeUrl, 'reference' => $cmd->reference]) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25v13.5l13.5-6.75-13.5-6.75Z" />
                                    </svg>
                                    Start mission
                                </a>

                            @elseif($missionState === 'in_progress' && $mission)
                                <a href="{{ route('technicien.missions.show', $mission->id) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-900 font-extrabold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Complete mission
                                </a>

                            @elseif($missionState === 'completed' && $mission)
                                <a href="{{ route('technicien.missions.details', $mission->id) }}"
                                   class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-900 font-extrabold transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Voir mission details
                                </a>

                            @else
                                <span class="w-full inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-400 font-extrabold">
                                    —
                                </span>
                            @endif

                        @else
                            <span class="w-full inline-flex items-center justify-center px-4 py-3 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-400 font-extrabold">
                                —
                            </span>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

        {{-- ✅ DESKTOP/TABLET: TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border-collapse text-sm md:text-base">
                <thead class="bg-zinc-50">
                    <tr class="text-left text-zinc-600">
                        <th class="px-5 py-4 font-extrabold">Heure</th>
                        <th class="px-5 py-4 font-extrabold">Type</th>
                        <th class="px-5 py-4 font-extrabold">Référence</th>
                        <th class="px-5 py-4 font-extrabold">Client</th>
                        <th class="px-5 py-4 font-extrabold">Détails</th>
                        <th class="px-5 py-4 font-extrabold">Status</th>
                        <th class="px-5 py-4 font-extrabold">Mission</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100">
                    @foreach($filteredCommandes as $cmd)
                        @php
                            $typeUrl = strtolower($cmd->type);

                            $key = ($cmd->type === 'UNKNOWN')
                                ? ('unknown|' . $cmd->reference)
                                : ($typeUrl . '|' . $cmd->reference);

                            $mission = $missionByKey[$key] ?? null;
                            $missionState = $missionStateByKey[$key] ?? 'not_started';

                            $rowAccent = $missionState === 'completed' ? 'bg-emerald-50/40' : 'bg-white';
                        @endphp

                        <tr class="hover:bg-zinc-50 transition {{ $rowAccent }}">
                            <td class="px-5 py-4 font-extrabold text-zinc-900 whitespace-nowrap">
                                {{ substr($cmd->hour, 0, 5) }}
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $typeBadge($cmd->type) }}">
                                    {{ $cmd->type }}
                                </span>
                            </td>

                            <td class="px-5 py-4 font-extrabold text-zinc-900">
                                {{ $cmd->reference }}
                            </td>

                            <td class="px-5 py-4 text-zinc-800 font-semibold">
                                {{ $cmd->client ? ($cmd->client->nom . ' ' . $cmd->client->prenom) : '-' }}
                            </td>

                            <td class="px-5 py-4">
                                @if($cmd->type !== 'UNKNOWN')
                                    <a href="{{ route('technicien.commandes.show', ['type' => $typeUrl, 'reference' => $cmd->reference]) }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                        </svg>
                                        Ouvrir
                                    </a>
                                @else
                                    <span class="text-zinc-400 font-semibold">—</span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $missionStatusBadge($missionState) }}">
                                    {{ $missionStatusLabel($missionState) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 flex items-center justify-center">
                                @if($cmd->type !== 'UNKNOWN')
                                    @php
                                        $btnBase    = "inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl font-extrabold transition whitespace-nowrap";
                                        $btnStart   = $btnBase . " bg-orange-600 hover:bg-orange-700 text-white";
                                        $btnNeutral = $btnBase . " border border-zinc-200 bg-white hover:bg-zinc-50 text-zinc-900";
                                    @endphp

                                    @if($missionState === 'not_started')
                                        <a href="{{ route('technicien.missions.start', ['type' => $typeUrl, 'reference' => $cmd->reference]) }}"
                                           class="{{ $btnStart }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25v13.5l13.5-6.75-13.5-6.75Z" />
                                            </svg>
                                            Start mission
                                        </a>

                                    @elseif($missionState === 'in_progress' && $mission)
                                        <a href="{{ route('technicien.missions.show', $mission->id) }}"
                                           class="{{ $btnNeutral }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Complete mission
                                        </a>

                                    @elseif($missionState === 'completed' && $mission)
                                        <a href="{{ route('technicien.missions.details', $mission->id) }}"
                                           class="{{ $btnNeutral }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            Voir mission details
                                        </a>
                                    @else
                                        <span class="text-zinc-400 font-semibold">—</span>
                                    @endif
                                @else
                                    <span class="text-zinc-400 font-semibold">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
    @endif

</div>
@endsection