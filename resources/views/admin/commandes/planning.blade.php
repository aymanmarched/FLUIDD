{{-- resources/views/admin/commande/planning.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Planning des commandes')

@section('content')
@php
    $paymentFilter = $paymentFilter ?? request('payment', 'all');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">

    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Planning des Commandes
            </h1>
            <p class="text-sm text-slate-500 mt-1">Filtrer et suivre les commandes, paiements et missions.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.commandes') }}"
          class="mb-6 bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $selectedDate }}"
                       class="w-full border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                              focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Type</label>
                <select name="type"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                               focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="entretien" {{ $typeFilter === 'entretien' ? 'selected' : '' }}>Entretien</option>
                    <option value="remplacer" {{ $typeFilter === 'remplacer' ? 'selected' : '' }}>Remplacer</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Statut mission</label>
                <select name="statut"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                               focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    <option value="all" {{ $statutFilter === 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="not_started" {{ $statutFilter === 'not_started' ? 'selected' : '' }}>Non démarrée</option>
                    <option value="completed" {{ $statutFilter === 'completed' ? 'selected' : '' }}>Mission terminée</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Paiement</label>
                <select name="payment"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 bg-white
                               focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    <option value="all" {{ ($paymentFilter ?? 'all') === 'all' ? 'selected' : '' }}>Tous</option>
                    <option value="paid" {{ ($paymentFilter ?? '') === 'paid' ? 'selected' : '' }}>Payée</option>
                    <option value="unpaid" {{ ($paymentFilter ?? '') === 'unpaid' ? 'selected' : '' }}>Non payée</option>
                </select>
            </div>

            <div class="flex gap-3 items-end">
                <button class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                    Filtrer
                </button>
                <a href="{{ route('admin.commandes') }}"
                   class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm text-center">
                    Tout afficher
                </a>
            </div>
        </div>
    </form>

    @if($commandes->count() === 0)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-2xl">
            Aucun commande pour ce filtre.
        </div>
    @else

        {{-- Desktop/tablet table --}}
        <div class="hidden md:block bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-3 text-left font-semibold text-slate-600">Date</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Type</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Référence</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Client</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Paiement</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Action</th>
                        <th class="p-3 text-left font-semibold text-slate-600">Mission</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @foreach($commandes as $cmd)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-3 text-slate-800">{{ $cmd->date }}</td>

                            <td class="p-3">
                                <span class="px-2 py-1 rounded-xl text-white text-xs font-semibold
                                    {{ $cmd->type == 'ENTRETIEN' ? 'bg-emerald-600' : '' }}
                                    {{ $cmd->type == 'REMPLACER' ? 'bg-indigo-600' : '' }}
                                    {{ $cmd->type == 'UNKNOWN' ? 'bg-slate-500' : '' }}">
                                    {{ $cmd->type }}
                                </span>
                            </td>

                            <td class="p-3 font-extrabold text-slate-900">{{ $cmd->reference }}</td>

                            <td class="p-3 text-slate-800 font-semibold">
                                {{ $cmd->client ? ($cmd->client->nom . ' ' . $cmd->client->prenom) : '-' }}
                            </td>

                            <td class="p-3">
                                @if($cmd->is_paid)
                                    <span class="px-2 py-1 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Payée
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-xl text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        Non payée
                                    </span>
                                @endif
                            </td>

                            <td class="p-3">
                                @if($cmd->type === 'ENTRETIEN')
                                    <a href="{{ route('admin.clientsentretien.show', $cmd->reference) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                                        Voir
                                    </a>
                                @elseif($cmd->type === 'REMPLACER')
                                    <a href="{{ route('admin.clientsremplacer.show', $cmd->reference) }}"
                                       class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                                        Voir
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-3">
                                @if($cmd->mission)
                                    <a href="{{ route('admin.commandes.missions.show', $cmd->mission->id) }}"
                                       class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm">
                                        Voir mission
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-sm text-slate-500">Non démarrée</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden space-y-3">
            @foreach($commandes as $cmd)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs text-slate-500">Référence</div>
                            <div class="text-base font-extrabold text-slate-900 break-words">
                                {{ $cmd->reference }}
                            </div>

                            <div class="mt-2 text-xs text-slate-500">Client</div>
                            <div class="text-sm font-semibold text-slate-800 truncate">
                                {{ $cmd->client ? ($cmd->client->nom . ' ' . $cmd->client->prenom) : '-' }}
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold text-white
                                    {{ $cmd->type == 'ENTRETIEN' ? 'bg-emerald-600' : '' }}
                                    {{ $cmd->type == 'REMPLACER' ? 'bg-indigo-600' : '' }}
                                    {{ $cmd->type == 'UNKNOWN' ? 'bg-slate-500' : '' }}">
                                    {{ $cmd->type }}
                                </span>

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $cmd->date }}
                                </span>

                                @if($cmd->is_paid)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Payée
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        Non payée
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2">
                        {{-- View order --}}
                        @if($cmd->type === 'ENTRETIEN')
                            <a href="{{ route('admin.clientsentretien.show', $cmd->reference) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                                Voir commande
                            </a>
                        @elseif($cmd->type === 'REMPLACER')
                            <a href="{{ route('admin.clientsremplacer.show', $cmd->reference) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                                Voir commande
                            </a>
                        @else
                            <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-center">
                                Type inconnu
                            </div>
                        @endif

                        {{-- Mission --}}
                        @if($cmd->mission)
                            <a href="{{ route('admin.commandes.missions.show', $cmd->mission->id) }}"
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm">
                                Voir mission
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068-.807.567-1.138 1.57-.85 2.513.16.523.243 1.078.243 1.669 0 .414-.336.75-.75.75H6.41a.75.75 0 0 1-.75-.75c0-.59.083-1.146.243-1.669.288-.943-.043-1.946-.85-2.513C4.09 14.39 3.46 13.268 3.46 12c0-1.268.63-2.39 1.593-3.068.807-.567 1.138-1.57.85-2.513A5.99 5.99 0 0 1 5.66 4.75c0-.414.336-.75.75-.75h11.58c.414 0 .75.336.75.75 0 .59-.083 1.146-.243 1.669-.288.943.043 1.946.85 2.513C20.37 9.61 21 10.732 21 12Z" />
                                </svg>
                            </a>
                        @else
                            <div class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 text-center font-semibold">
                                Mission : Non démarrée
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $commandes->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
