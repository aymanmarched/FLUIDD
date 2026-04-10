{{-- resources/views/admin/garanties/show.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Détails Garantie')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6">

    {{-- BACK --}}
    <a href="{{ route('admin.garanties') }}"
       class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        Retour
    </a>

    {{-- TITLE --}}
    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6 tracking-tight">
        Détails Garantie :
        <span class="text-indigo-600">{{ $garantie->nom }} {{ $garantie->prenom }}</span>
    </h2>

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
        if ($months > 0) $parts[] = $months . ' mois';
        if ($days > 0 || empty($parts)) $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');
        $remainingText = implode(' ', $parts);

        $expired = ($years === 0 && $months === 0 && $days === 0);
    @endphp

    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">

        <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h3 class="text-base sm:text-lg font-bold text-slate-800">Informations Client</h3>
        </div>

        {{-- =========================
            MOBILE VERSION (Cards)
        ========================= --}}
        <div class="sm:hidden p-4 space-y-3 text-slate-700">

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-semibold text-slate-500">Nom complet</div>
                <div class="text-base font-extrabold text-slate-900 mt-1">
                    {{ $garantie->nom }} {{ $garantie->prenom }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Téléphone</div>
                    <div class="mt-1">
                        <span class="inline-flex px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold">
                            {{ $garantie->telephone }}
                        </span>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Email</div>
                    <div class="mt-1 font-semibold text-slate-800 break-words">
                        {{ $garantie->email ?? '-' }}
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Ville</div>
                    <div class="mt-1 font-semibold text-slate-800">
                        {{ $garantie->ville->name ?? '-' }}
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Adresse</div>
                    <div class="mt-1 font-semibold text-slate-800 break-words">
                        {{ $garantie->adresse ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Créé le</div>
                    <div class="mt-1 font-semibold text-slate-800">
                        {{ $garantie->created_at->format('d/m/Y') }}
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-semibold text-slate-500">Fin Garantie</div>
                    <div class="mt-1 font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($garantie->date_garante)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-semibold text-slate-500">Temps restant</div>
                <div class="mt-1">
                    <span class="inline-flex px-3 py-1 rounded-full text-white text-sm font-extrabold {{ $expired ? 'bg-rose-600' : 'bg-emerald-600' }}">
                        {{ $remainingText }}
                    </span>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
                    <div class="text-sm font-bold text-slate-800">Machine</div>
                </div>

                <div class="p-4 space-y-3">
                    <div>
                        <div class="text-xs font-semibold text-slate-500">Machine</div>
                        <div class="mt-1 font-extrabold text-indigo-700">
                            {{ $garantie->machine->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-slate-500">Marque</div>
                        <div class="mt-1 font-extrabold text-emerald-700">
                            {{ $garantie->marque->nom }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-slate-500">Série</div>
                        <div class="mt-1 font-mono font-semibold text-slate-800 break-words">
                            {{ $garantie->machine_series }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- =========================
            DESKTOP VERSION (Grid + Table)
        ========================= --}}
        <div class="hidden sm:block p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-700">

                <div class="space-y-2">
                    <div><span class="font-semibold">Nom :</span> {{ $garantie->nom }} {{ $garantie->prenom }}</div>
                    <div>
                        <span class="font-semibold">Téléphone :</span>
                        <span class="ml-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-semibold">
                            {{ $garantie->telephone }}
                        </span>
                    </div>
                    <div><span class="font-semibold">Email :</span> {{ $garantie->email ?? '-' }}</div>
                    <div><span class="font-semibold">Ville :</span> {{ $garantie->ville->name ?? '-' }}</div>
                    <div><span class="font-semibold">Adresse :</span> {{ $garantie->adresse ?? '-' }}</div>
                </div>

                <div class="space-y-2">
                    <div><span class="font-semibold">Créé le :</span> {{ $garantie->created_at->format('d/m/Y') }}</div>
                    <div>
                        <span class="font-semibold">Fin Garantie :</span>
                        {{ \Carbon\Carbon::parse($garantie->date_garante)->format('d/m/Y') }}
                    </div>
                    <div>
                        <span class="font-semibold">Temps restant :</span>
                        <span class="ml-2 px-3 py-1 rounded-full text-white text-sm font-extrabold {{ $expired ? 'bg-rose-600' : 'bg-emerald-600' }}">
                            {{ $remainingText }}
                        </span>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="overflow-x-auto mt-2">
                        <table class="min-w-full border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 text-sm text-slate-600">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold">Machine</th>
                                    <th class="px-4 py-2 text-left font-semibold">Marque</th>
                                    <th class="px-4 py-2 text-left font-semibold">Série</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-white">
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $garantie->machine->name }}</td>
                                    <td class="px-4 py-3 font-semibold text-emerald-700">{{ $garantie->marque->nom }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-700">{{ $garantie->machine_series }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
