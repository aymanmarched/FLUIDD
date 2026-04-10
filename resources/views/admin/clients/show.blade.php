{{-- resources/views/admin/clients/show.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Client')

@section('content')
    <div class="max-w-5xl mx-auto">

        {{-- Back --}}
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.clients') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                     stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Retour
            </a>
        </div>

        {{-- Client card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-extrabold text-slate-900">Informations Client</h3>
                <p class="text-sm text-slate-500 mt-0.5">Détails du compte et localisation.</p>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-slate-700">
                <div>
                    <div class="text-xs uppercase font-semibold text-slate-500">Nom</div>
                    <div class="font-extrabold text-slate-900">{{ $client->nom }} {{ $client->prenom }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase font-semibold text-slate-500">Créé le</div>
                    <div class="font-semibold text-slate-800">{{ $client->created_at->format('d/m/Y - H:i') }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase font-semibold text-slate-500">Téléphone</div>
                    <div class="mt-1 inline-flex px-2.5 py-1 rounded-xl text-sm font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $client->telephone }}
                    </div>
                </div>

                <div>
                    <div class="text-xs uppercase font-semibold text-slate-500">Email</div>
                    <div class="font-semibold text-slate-800 break-words">{{ $client->email ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs uppercase font-semibold text-slate-500">Ville</div>
                    <div class="font-semibold text-slate-800">{{ $client->ville->name ?? '-' }}</div>
                </div>

                <div class="sm:col-span-2">
                    <div class="text-xs uppercase font-semibold text-slate-500">Adresse</div>
                    <div class="font-semibold text-slate-800">{{ $client->adresse }}</div>
                </div>

                {{-- Location --}}
                <div class="sm:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-base font-extrabold text-slate-900">Localisation du client</h3>
                        </div>

                        @if ($client->location)
                            <div class="mt-3 bg-white border border-slate-200 rounded-xl p-4">
                                <p class="text-slate-700 font-semibold mb-3 break-words">
                                    "{{ $client->location }}"
                                </p>

                                <a href="https://www.google.com/maps?q={{ urlencode($client->location) }}" target="_blank"
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                         stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    Voir sur Google Maps
                                </a>
                            </div>
                        @else
                            <p class="mt-3 text-slate-500 italic">Aucune donnée de localisation disponible.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="mb-4">
            <div class="inline-flex items-center gap-2 bg-slate-100 p-2 rounded-2xl">
                <button id="btn-entretien"
                        onclick="showTable('entretien')"
                        class="px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition bg-indigo-600 text-white shadow-sm">
                    Commandes Entretien
                </button>

                <button id="btn-remplacer"
                        onclick="showTable('remplacer')"
                        class="px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition text-slate-600 hover:bg-slate-200">
                    Commandes Remplacer
                </button>
            </div>
        </div>

        {{-- Entretien --}}
        <div id="table-entretien" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Référence</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Total</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($entretiens as $cmd)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $cmd->reference }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ optional($cmd->date)->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-6 py-4 font-extrabold text-indigo-700">{{ number_format($cmd->total, 2) }} MAD</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.clients.entretien', $cmd->reference) }}"
                                           class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition"
                                           title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd"
                                                      d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden p-4 space-y-3">
                @foreach($entretiens as $cmd)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs text-slate-500">Référence</div>
                                <div class="text-base font-extrabold text-slate-900">{{ $cmd->reference }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Total</div>
                                <div class="text-sm font-extrabold text-indigo-700">{{ number_format($cmd->total, 2) }} MAD</div>
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-slate-600">
                            <span class="text-slate-500">Date: </span>{{ optional($cmd->date)->format('d/m/Y') ?? '-' }}
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.clients.entretien', $cmd->reference) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition">
                                Voir
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Remplacer --}}
        <div id="table-remplacer" class="hidden bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mt-4">
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Référence</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Total</th>
                            <th class="px-6 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($remplacers as $cmd)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ $cmd->reference }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ optional($cmd->date)->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-6 py-4 font-extrabold text-emerald-700">{{ number_format($cmd->total, 2) }} MAD</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.clients.remplacer', $cmd->reference) }}"
                                           class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition"
                                           title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd"
                                                      d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden p-4 space-y-3">
                @foreach($remplacers as $cmd)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs text-slate-500">Référence</div>
                                <div class="text-base font-extrabold text-slate-900">{{ $cmd->reference }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Total</div>
                                <div class="text-sm font-extrabold text-emerald-700">{{ number_format($cmd->total, 2) }} MAD</div>
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-slate-600">
                            <span class="text-slate-500">Date: </span>{{ optional($cmd->date)->format('d/m/Y') ?? '-' }}
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.clients.remplacer', $cmd->reference) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm transition">
                                Voir
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            function showTable(type) {
                const entretien = document.getElementById('table-entretien');
                const remplacer = document.getElementById('table-remplacer');

                const btnEntretien = document.getElementById('btn-entretien');
                const btnRemplacer = document.getElementById('btn-remplacer');

                entretien.classList.add('hidden');
                remplacer.classList.add('hidden');

                btnEntretien.className = 'px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition text-slate-600 hover:bg-slate-200';
                btnRemplacer.className = 'px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition text-slate-600 hover:bg-slate-200';

                if (type === 'entretien') {
                    entretien.classList.remove('hidden');
                    btnEntretien.className = 'px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition bg-indigo-600 text-white shadow-sm';
                } else {
                    remplacer.classList.remove('hidden');
                    btnRemplacer.className = 'px-4 sm:px-6 py-2 text-sm font-extrabold rounded-xl transition bg-indigo-600 text-white shadow-sm';
                }
            }
        </script>
    </div>
@endsection
