@extends('client.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Mes garanties</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Garanties</h2>
                <p class="text-slate-600 mt-1">Consultez l’état et la date d’expiration de vos garanties.</p>
            </div>

            <a href="{{ url('/service/entretien/activer-ma-garantie') }}" target="_blank"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-soft transition w-full md:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                </svg>
                Nouvelle garantie
            </a>
        </div>
    </div>

    @if($garanties->isEmpty())
        <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-10 text-center">
            <div class="mx-auto h-12 w-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M12 3.75c2.485 0 4.5 4.03 4.5 9S14.485 21.75 12 21.75s-4.5-4.03-4.5-9 2.015-9 4.5-9Z"/>
                </svg>
            </div>
            <div class="font-extrabold text-lg">Aucune garantie trouvée</div>
            <p class="text-slate-500 mt-1">Activez une garantie pour commencer.</p>
        </div>
    @else

        {{-- =========================
            DESKTOP TABLE (unchanged)
           ========================= --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-[800px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-slate-700">
                            <th class="px-6 py-4">Machine</th>
                            <th class="px-6 py-4">Marque</th>
                            <th class="px-6 py-4">Série</th>
                            <th class="px-6 py-4">Expiration</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($garanties as $g)
                            <tr class="hover:bg-gray-50/60">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $g->machine->name }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $g->marque->nom }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-slate-600 bg-gray-50 border border-gray-200 px-3 py-1 rounded-full">
                                        {{ $g->machine_series }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($g->date_garante->isPast())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-rose-50 text-rose-700 border-rose-100 font-semibold">
                                            Expirée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                                            {{ $g->date_garante->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <a href="{{ route('client.garanties.show', $g) }}"
                                           class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white shadow-soft transition"
                                           title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- =========================
            MOBILE CARDS (new)
           ========================= --}}
        <div class="md:hidden space-y-3">
            @foreach($garanties as $g)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs text-slate-500 font-semibold">Machine</div>
                                <div class="font-extrabold text-slate-900 truncate">
                                    {{ $g->machine->name }}
                                </div>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs text-slate-500 font-semibold">Marque</div>
                                        <div class="text-sm font-semibold text-slate-800 truncate">
                                            {{ $g->marque->nom }}
                                        </div>
                                    </div>

                                    <div class="min-w-0 text-right">
                                        <div class="text-xs text-slate-500 font-semibold">Série</div>
                                        <div class="inline-flex justify-end">
                                            <span class="font-mono text-xs text-slate-600 bg-gray-50 border border-gray-200 px-3 py-1 rounded-full truncate max-w-[12rem]">
                                                {{ $g->machine_series }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 flex flex-col items-end gap-2">
                                <div class="text-xs text-slate-500 font-semibold">Expiration</div>
                                @if($g->date_garante->isPast())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs border bg-rose-50 text-rose-700 border-rose-100 font-semibold">
                                        Expirée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs border bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                                        {{ $g->date_garante->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('client.garanties.show', $g) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-semibold shadow-soft transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                                Voir la garantie
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
