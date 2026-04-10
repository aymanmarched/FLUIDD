{{-- resources/views/admin/garanties/index.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Garanties Clients')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6"
     x-data="{ machine: 'all', marque: 'all' }">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl flex items-center gap-3 font-extrabold tracking-tight text-slate-900">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
            Garanties Clients
        </h1>
        <p class="text-sm text-slate-500 mt-1">Filtrez par machine et marque, puis consultez les garanties.</p>
    </div>

    {{-- Filters --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 sm:p-5 mb-6">
        {{-- Machine filter --}}
        <div class="flex flex-wrap gap-2">
            <button type="button"
                    @click="machine='all'; marque='all'"
                    :class="machine==='all' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                    class="px-4 py-2 rounded-xl font-semibold transition text-sm">
                All
            </button>

            @foreach($machines as $m)
                <button type="button"
                        @click="machine='{{ $m->id }}'; marque='all'"
                        :class="machine==='{{ $m->id }}' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl font-semibold transition text-sm">
                    {{ $m->name }}
                </button>
            @endforeach
        </div>

        {{-- Marque filter --}}
        <div class="mt-4" x-show="machine !== 'all'" x-cloak>
            <div class="flex flex-wrap gap-2">
                <button type="button"
                        @click="marque='all'"
                        :class="marque==='all' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-sm font-semibold transition">
                    All Marques
                </button>

                @foreach($machines as $m)
                    <template x-if="machine === '{{ $m->id }}'">
                        <div class="flex gap-2 flex-wrap">
                            @foreach($m->marques as $mq)
                                <button type="button"
                                        @click="marque='{{ $mq->id }}'"
                                        :class="marque==='{{ $mq->id }}' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                        class="px-3 py-1.5 rounded-lg text-sm font-semibold transition">
                                    {{ $mq->nom }}
                                </button>
                            @endforeach
                        </div>
                    </template>
                @endforeach
            </div>
        </div>
    </div>

    @php
        // Helper for mobile-only badge text without heavy PHP repetition in markup
        $formatRemaining = function($date) {
            if (now()->greaterThan($date)) return ['0 jour', true];

            $totalDays = now()->diffInDays($date);
            $years = intdiv($totalDays, 365);
            $remainingAfterYears = $totalDays % 365;
            $months = intdiv($remainingAfterYears, 30);
            $days = $remainingAfterYears % 30;

            $parts = [];
            if ($years > 0) $parts[] = $years . ' ' . ($years === 1 ? 'an' : 'ans');
            if ($months > 0) $parts[] = $months . ' mois';
            if ($days > 0 || empty($parts)) $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');

            $txt = implode(' ', $parts);
            $expired = ($years === 0 && $months === 0 && $days === 0);

            return [$txt, $expired];
        };
    @endphp

    {{-- ===== Mobile (Better UX) =====
         - sticky small header inside list
         - clear "badge" (expired/active)
         - 1-line phone + date row
         - tap target full card + arrow
    --}}
    <div class="sm:hidden space-y-3">
        <div class="px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div class="text-sm font-semibold text-slate-700">
                Résultats
            </div>
            <div class="text-xs text-slate-500">
                {{ $garanties->total() ?? $garanties->count() }} ligne(s)
            </div>
        </div>

        <div class="space-y-3">
            @foreach($garanties as $garantie)
                @php
                    [$remainingText, $expired] = $formatRemaining($garantie->date_garante);
                @endphp

                <a
                    href="{{ route('admin.garanties.show', $garantie->id) }}"
                    x-show="(machine === 'all' || machine == '{{ $garantie->machine_id }}') && (marque === 'all' || marque == '{{ $garantie->marque_id }}')"
                    class="block bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden active:scale-[0.99] transition">

                    {{-- Top --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-base font-extrabold text-slate-900 truncate">
                                    {{ $garantie->nom }} {{ $garantie->prenom }}
                                </div>

                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold">
                                        {{ $garantie->machine->name }}
                                    </span>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                                        {{ $garantie->marque->nom }}
                                    </span>

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-extrabold text-white {{ $expired ? 'bg-rose-600' : 'bg-emerald-600' }}">
                                        {{ $expired ? 'Expirée' : 'Active' }}
                                    </span>
                                </div>
                            </div>

                            <div class="shrink-0 mt-0.5">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Info row (compact, readable) --}}
                        <div class="mt-3 grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-2.5">
                                <div class="text-[11px] font-semibold text-slate-500">Téléphone</div>
                                <div class="font-extrabold text-slate-800 mt-0.5 truncate">{{ $garantie->telephone }}</div>
                            </div>

                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-2.5">
                                <div class="text-[11px] font-semibold text-slate-500">Fin garantie</div>
                                <div class="font-extrabold text-slate-800 mt-0.5">
                                    {{ \Carbon\Carbon::parse($garantie->date_garante)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        {{-- Remaining + series (stacked, no clutter) --}}
                        <div class="mt-2 bg-white border border-slate-200 rounded-xl p-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-[11px] font-semibold text-slate-500">Temps restant</div>
                                <div class="text-sm font-extrabold {{ $expired ? 'text-rose-600' : 'text-emerald-700' }}">
                                    {{ $remainingText }}
                                </div>
                            </div>

                            <div class="mt-2 flex items-center justify-between gap-2">
                                <div class="text-[11px] font-semibold text-slate-500">Série</div>
                                <div class="font-mono text-xs font-semibold text-slate-800 max-w-[60%] truncate">
                                    {{ $garantie->machine_series }}
                                </div>
                            </div>

                            <div class="mt-2 flex items-center justify-between gap-2">
                                <div class="text-[11px] font-semibold text-slate-500">Email</div>
                                <div class="text-xs font-semibold text-slate-800 max-w-[60%] truncate">
                                    {{ $garantie->email ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom bar --}}
                    <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-600">Voir détails</span>
                        <span class="text-xs font-semibold text-indigo-700">Ouvrir</span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination (Mobile) --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="text-slate-700 text-sm">Afficher</span>
                    <select onchange="window.location='?perPage='+this.value" class="border-slate-300 rounded-md text-sm p-1">
                        <option value="10" @if(request('perPage') == 10) selected @endif>10 lignes</option>
                        <option value="25" @if(request('perPage') == 25) selected @endif>25 lignes</option>
                        <option value="50" @if(request('perPage') == 50) selected @endif>50 lignes</option>
                    </select>
                </div>
            </div>
            <div class="p-4">
                {{ $garanties->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    {{-- ===== Desktop Table ===== --}}
    <div class="hidden sm:block bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Téléphone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Machine</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Marque</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Fin Garantie</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-slate-600">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @foreach($garanties as $garantie)
                        <tr
                            x-show="(machine === 'all' || machine == '{{ $garantie->machine_id }}') && (marque === 'all' || marque == '{{ $garantie->marque_id }}')"
                            class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-slate-800 font-semibold">{{ $garantie->nom }} {{ $garantie->prenom }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $garantie->telephone }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $garantie->email ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-indigo-700">{{ $garantie->machine->name }}</td>
                            <td class="px-6 py-4 font-semibold text-emerald-700">{{ $garantie->marque->nom }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ \Carbon\Carbon::parse($garantie->date_garante)->format('d/m/Y') }}</td>

                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.garanties.show', $garantie->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd"
                                              d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between p-4 bg-white border-t border-slate-200">
            <div class="flex items-center gap-2">
                <span class="text-slate-700 text-sm">Afficher</span>
                <select onchange="window.location='?perPage='+this.value" class="border-slate-300 rounded-md text-sm p-1">
                    <option value="10" @if(request('perPage') == 10) selected @endif>10 lignes</option>
                    <option value="25" @if(request('perPage') == 25) selected @endif>25 lignes</option>
                    <option value="50" @if(request('perPage') == 50) selected @endif>50 lignes</option>
                </select>
            </div>
            <div>
                {{ $garanties->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

</div>
@endsection
