{{-- resources/views/admin/clients/remplacercammnde.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Commande Remplacer')

@section('content')
    <div class="max-w-5xl mx-auto">

        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.clients.show', $client->id) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Retour
            </a>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-6">
            Commande :
            <span class="text-indigo-700">{{ $reference }}</span>
        </h2>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-extrabold text-slate-900">Packs Sélectionnés</h3>
                <p class="text-sm text-slate-500 mt-0.5">Détails des packs et total.</p>
            </div>

            {{-- Desktop table --}}
            <div class="hidden sm:block p-4 sm:p-6 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-slate-600 font-semibold">Machine</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-semibold">Marque</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-semibold">Caractères</th>
                            <th class="px-4 py-3 text-left text-slate-600 font-semibold">Prix (MAD)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($selections as $s)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-slate-900 font-semibold">{{ $s->machine->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $s->marque->nom ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-700">
                                    @if(is_array($s->marque->caractere ?? []))
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($s->marque->caractere as $c)
                                                <li>{{ $c }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-900 font-extrabold">
                                    {{ number_format($s->marque->prix ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr class="bg-slate-50 font-semibold">
                            <td class="px-4 py-3">Total :</td>
                            <td></td>
                            <td></td>
                            <td class="px-4 py-3 text-slate-900 font-extrabold">{{ number_format($total ?? 0, 2) }} MAD</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden p-4 space-y-3">
                @foreach($selections as $s)
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-xs text-slate-500">Machine</div>
                                <div class="text-base font-extrabold text-slate-900">{{ $s->machine->name ?? '-' }}</div>
                                <div class="text-xs text-slate-500 mt-2">Marque</div>
                                <div class="text-sm font-semibold text-slate-800">{{ $s->marque->nom ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Prix</div>
                                <div class="text-sm font-extrabold text-slate-900">{{ number_format($s->marque->prix ?? 0, 2) }} MAD</div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-xs text-slate-500 mb-1">Caractères</div>
                            @if(is_array($s->marque->caractere ?? []))
                                <ul class="list-disc list-inside space-y-1 text-sm text-slate-700">
                                    @foreach($s->marque->caractere as $c)
                                        <li>{{ $c }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-sm text-slate-700">-</div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-center justify-between">
                    <div class="text-sm font-semibold text-slate-700">Total</div>
                    <div class="text-sm font-extrabold text-slate-900">{{ number_format($total ?? 0, 2) }} MAD</div>
                </div>
            </div>
        </div>
    </div>
@endsection
