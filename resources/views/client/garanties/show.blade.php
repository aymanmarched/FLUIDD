@extends('client.menu')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Garantie</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Détails garantie : <span class="text-sky-700">{{ $garantie->machine->name }}</span>
                </h2>
                <p class="text-slate-600 mt-1">
                    Créé le : <span class="font-semibold">{{ $garantie->created_at->format('d/m/Y') }}</span>
                </p>
            </div>

            <a href="{{ route('client.garanties') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition w-full md:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    @php
        if (now()->greaterThan($garantie->date_garante)) {
            $years = $months = $days = 0;
        } else {
            $totalDays = now()->diffInDays($garantie->date_garante);
            $years = intdiv($totalDays, 365);
            $remainingAfterYears = $totalDays % 365;
            $months = intdiv($remainingAfterYears, 30);
            $days = $remainingAfterYears % 30;
        }

        $parts = [];
        if ($years > 0) $parts[] = $years . ' ' . ($years === 1 ? 'an' : 'ans');
        if ($months > 0) $parts[] = $months . ' ' . 'mois';
        if ($days > 0 || empty($parts)) $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');
        $remainingText = implode(' ', $parts);

        $isExpired = ($years === 0 && $months === 0 && $days === 0);
    @endphp

    <!-- Details -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="font-extrabold">Informations de garantie</div>
            <div class="text-sm text-slate-500">Validité et informations machine</div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Date de garantie -->
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                <div class="text-sm text-slate-500 font-semibold">Date de garantie</div>
                <div class="text-lg font-extrabold text-slate-900 mt-1">
                    {{ \Carbon\Carbon::parse($garantie->date_garante)->format('d/m/Y') }}
                </div>
                <div class="text-sm text-slate-600 mt-2">
                    @if($garantie->date_garante->isPast())
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-rose-50 text-rose-700 border-rose-100 font-semibold">
                            Expirée
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                            Active
                        </span>
                    @endif
                </div>
            </div>

            <!-- Temps restant -->
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                <div class="text-sm text-slate-500 font-semibold">Temps restant</div>
                <div class="mt-2">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-extrabold text-white
                        {{ $isExpired ? 'bg-rose-600' : 'bg-emerald-600' }}">
                        {{ $remainingText }}
                    </span>
                </div>
                <p class="text-sm text-slate-600 mt-3">
                    @if($isExpired)
                        Votre garantie est terminée.
                    @else
                        Votre garantie est encore valable.
                    @endif
                </p>
            </div>

            <!-- Machine info (responsive) -->
            <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-white p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                        <div class="text-xs text-slate-500 font-semibold">Machine</div>
                        <div class="font-extrabold text-slate-900 mt-1">{{ $garantie->machine->name }}</div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                        <div class="text-xs text-slate-500 font-semibold">Marque</div>
                        <div class="font-extrabold text-slate-900 mt-1">{{ $garantie->marque->nom }}</div>
                    </div>

                    <div class="rounded-2xl bg-gray-50 border border-gray-200 p-4">
                        <div class="text-xs text-slate-500 font-semibold">Série</div>
                        <div class="mt-1">
                            <span class="font-mono text-sm text-slate-700 bg-white border border-gray-200 px-3 py-1 rounded-full">
                                {{ $garantie->machine_series }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
