@extends('technicien.menu')

@section('content')

@php
    $typeLower = strtolower($type ?? '');
    $isEntretien = $typeLower === 'entretien';

    $typeBadge = $isEntretien
        ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
        : 'bg-blue-50 text-blue-800 border-blue-200';
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- TOP BAR --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        {{-- Back --}}
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Retour
        </a>

        {{-- Quick actions --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            @if(!empty($client?->telephone))
                <a href="tel:{{ preg_replace('/\s+/', '', $client->telephone) }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 0 0 2.25-2.25v-1.372a1.125 1.125 0 0 0-.772-1.063l-3.712-1.237a1.125 1.125 0 0 0-1.216.315l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.035 12.035 0 0 1-7.143-7.143 1.125 1.125 0 0 1 .38-1.21l1.293-.97a1.125 1.125 0 0 0 .315-1.216L6.435 3.522A1.125 1.125 0 0 0 5.372 2.75H4A1.75 1.75 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    Appeler
                </a>
            @endif

            @if(!empty($client?->location))
                <a href="https://www.google.com/maps?q={{ urlencode($client->location) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl border border-orange-200 bg-orange-50 hover:bg-orange-100 text-orange-900 font-extrabold transition w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    Itinéraire
                </a>
            @endif
        </div>
    </div>

    {{-- HEADER --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-zinc-500">Détails mission</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900">
                    Commande <span class="text-orange-600">{{ $reference }}</span>
                </h2>

                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border {{ $typeBadge }}">
                        {{ strtoupper($typeLower) }}
                    </span>

                    @if($reservation)
                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border border-zinc-200 bg-zinc-50 text-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($reservation->date_souhaite)->format('d/m/Y') }}
                        </span>

                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold border border-zinc-200 bg-zinc-50 text-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                            </svg>
                            {{ substr($reservation->hour, 0, 5) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-right">
                <div class="text-xs text-zinc-500 font-bold uppercase tracking-wide">Total</div>
                <div class="text-2xl font-extrabold text-zinc-900">
                    <span class="text-emerald-700">{{ number_format($total ?? 0, 2) }}</span>
                    <span class="text-zinc-500 text-base font-bold">MAD</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CLIENT CARD --}}
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <div class="font-extrabold text-zinc-900">Client</div>
                    <div class="text-sm text-zinc-500">Contact & adresse</div>
                </div>
            </div>

            @if(!empty($client?->telephone))
                <a href="tel:{{ preg_replace('/\s+/', '', $client->telephone) }}"
                   class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition">
                    Appeler
                </a>
            @endif
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-zinc-200 p-5">
                <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Nom</div>
                <div class="mt-1 font-extrabold text-zinc-900">{{ $client->nom }} {{ $client->prenom }}</div>
            </div>

            <div class="rounded-2xl border border-zinc-200 p-5">
                <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Téléphone</div>
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm border border-orange-200 bg-orange-50 text-orange-900 font-extrabold">
                    {{ $client->telephone }}
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 p-5">
                <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Email</div>
                <div class="mt-1 font-semibold text-zinc-900 break-all">{{ $client->email ?? '-' }}</div>
            </div>

            <div class="rounded-2xl border border-zinc-200 p-5">
                <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Ville</div>
                <div class="mt-1 font-extrabold text-zinc-900">{{ $client->ville->name ?? '-' }}</div>
            </div>

            <div class="md:col-span-2 rounded-2xl border border-zinc-200 p-5">
                <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Adresse</div>
                <div class="mt-1 font-semibold text-zinc-900">{{ $client->adresse ?? '-' }}</div>
            </div>

            {{-- LOCATION --}}
            <div class="md:col-span-2 rounded-2xl border border-zinc-200 p-5 bg-zinc-50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Localisation</div>
                        @if($client->location)
                            <div class="mt-1 font-semibold text-zinc-900 break-words">{{ $client->location }}</div>
                        @else
                            <div class="mt-1 text-zinc-500 italic">Aucune donnée de localisation disponible.</div>
                        @endif
                    </div>

                    @if($client->location)
                        <a href="https://www.google.com/maps?q={{ urlencode($client->location) }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition w-full md:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            Ouvrir Google Maps
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT: ENTRETIEN --}}
    @if($isEntretien)

        {{-- Packs (cards) --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold text-zinc-900">Packs sélectionnés</div>
                    <div class="text-sm text-zinc-500 font-semibold">{{ count($selections ?? []) }} élément(s)</div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($selections as $sel)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-5 hover:shadow-soft transition">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="text-lg font-extrabold text-zinc-900">
                                    {{ $sel->machine->name ?? '-' }}
                                </div>
                                <div class="text-sm text-zinc-600 mt-1">
                                    <span class="font-bold">Type :</span> {{ $sel->type->name ?? '-' }}
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <div class="text-xs text-zinc-500 font-bold uppercase">Prix</div>
                                <div class="text-lg font-extrabold text-emerald-700">
                                    {{ number_format($sel->type->prix ?? 0, 2) }} <span class="text-xs text-zinc-500">MAD</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($sel->type->caracteres ?? []))
                            <div class="mt-4 space-y-2">
                                @foreach($sel->type->caracteres as $c)
                                    <div class="flex items-start gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                                        <div class="text-sm text-zinc-800">{{ $c }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="px-6 pb-6">
                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 flex items-center justify-between">
                    <div class="font-extrabold text-zinc-900">Total</div>
                    <div class="text-2xl font-extrabold text-emerald-700">
                        {{ number_format($total ?? 0, 2) }} <span class="text-sm text-zinc-500 font-bold">MAD</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Machine media --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200">
                <div class="font-extrabold text-zinc-900">Détails des machines (médias)</div>
                <div class="text-sm text-zinc-500">Cliquez pour agrandir</div>
            </div>

            <div class="p-6 space-y-4">
                @if($client->machineDetails ?? false)
                    @php $details = $client->machineDetails->where('reference', $reference); @endphp

                    @if($details->isEmpty())
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 text-zinc-600">
                            Aucun détail machine pour cette référence.
                        </div>
                    @endif

                    @foreach($details as $detail)
                        <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div class="font-extrabold text-zinc-900">
                                    {{ $detail->machine->machine ?? '—' }}
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                {{-- PHOTO --}}
                                @if($detail->photo)
                                    <button type="button"
                                            class="text-left rounded-2xl border border-zinc-200 bg-white p-4 hover:shadow-soft transition"
                                            onclick="openMediaModal('{{ asset('storage/' . $detail->photo) }}', 'image')">
                                        <div class="flex items-center gap-2 font-extrabold text-zinc-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                                            </svg>
                                            Photo
                                        </div>
                                        <img src="{{ asset('storage/' . $detail->photo) }}"
                                             class="mt-3 w-full h-40 object-cover rounded-xl border border-zinc-200">
                                    </button>
                                @endif

                                {{-- VIDEO --}}
                                @if($detail->video)
                                    <button type="button"
                                            class="text-left rounded-2xl border border-zinc-200 bg-white p-4 hover:shadow-soft transition"
                                            onclick="openMediaModal('{{ asset('storage/' . $detail->video) }}', 'video')">
                                        <div class="flex items-center gap-2 font-extrabold text-zinc-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9A2.25 2.25 0 0 0 15.75 16.5v-9A2.25 2.25 0 0 0 13.5 5.25h-9A2.25 2.25 0 0 0 2.25 7.5v9A2.25 2.25 0 0 0 4.5 18.75Z" />
                                            </svg>
                                            Vidéo
                                        </div>
                                        <video class="mt-3 w-full h-40 object-cover rounded-xl border border-zinc-200" muted>
                                            <source src="{{ asset('storage/' . $detail->video) }}" type="video/mp4">
                                        </video>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 text-zinc-600">
                        Aucun détail machine disponible.
                    </div>
                @endif
            </div>

            {{-- Media modal (kept) --}}
            <div id="mediaModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4">
                <button class="absolute top-5 right-5 text-white text-3xl font-extrabold focus:outline-none"
                        onclick="closeMediaModal()">&times;</button>

                <div id="mediaContentWrapper" class="max-h-full max-w-full flex items-center justify-center relative">
                    <img id="modalImage" class="max-h-[90vh] max-w-[95vw] rounded-2xl hidden border border-white/10">
                    <video id="modalVideo" class="max-h-[90vh] max-w-[95vw] rounded-2xl hidden border border-white/10" controls autoplay></video>
                </div>
            </div>
        </div>

    @else
        {{-- CONTENT: REMPLACER --}}
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200">
                <div class="flex items-center justify-between gap-3">
                    <div class="font-extrabold text-zinc-900">Packs sélectionnés</div>
                    <div class="text-sm text-zinc-500 font-semibold">{{ count($selections ?? []) }} élément(s)</div>
                </div>
            </div>

            {{-- Desktop table --}}
            <div class="hidden md:block p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-extrabold text-zinc-600">Machine</th>
                            <th class="px-4 py-3 text-left font-extrabold text-zinc-600">Marque</th>
                            <th class="px-4 py-3 text-left font-extrabold text-zinc-600">Caractères</th>
                            <th class="px-4 py-3 text-left font-extrabold text-zinc-600">Prix</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 bg-white">
                        @foreach($selections as $s)
                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-4 py-3 font-extrabold text-zinc-900">{{ $s->machine->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-zinc-800 font-semibold">{{ $s->marque->nom ?? '-' }}</td>
                                <td class="px-4 py-3 text-zinc-800">
                                    @if(is_array($s->marque->caractere ?? []))
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach($s->marque->caractere as $c)
                                                <li>{{ $c }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-zinc-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-extrabold text-emerald-700">
                                    {{ number_format($s->marque->prix ?? 0, 2) }} <span class="text-xs text-zinc-500">MAD</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-5 rounded-2xl border border-zinc-200 bg-zinc-50 p-5 flex items-center justify-between">
                    <div class="font-extrabold text-zinc-900">Total</div>
                    <div class="text-2xl font-extrabold text-emerald-700">
                        {{ number_format($total ?? 0, 2) }} <span class="text-sm text-zinc-500 font-bold">MAD</span>
                    </div>
                </div>
            </div>

            {{-- Mobile cards --}}
            <div class="md:hidden p-6 space-y-4">
                @foreach($selections as $s)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-extrabold text-zinc-900">{{ $s->machine->name ?? '-' }}</div>
                                <div class="text-sm text-zinc-600 mt-1">
                                    <span class="font-bold">Marque :</span> {{ $s->marque->nom ?? '-' }}
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-xs text-zinc-500 font-bold uppercase">Prix</div>
                                <div class="font-extrabold text-emerald-700">
                                    {{ number_format($s->marque->prix ?? 0, 2) }} <span class="text-xs text-zinc-500">MAD</span>
                                </div>
                            </div>
                        </div>

                        @if(is_array($s->marque->caractere ?? []))
                            <div class="mt-4 space-y-2">
                                @foreach($s->marque->caractere as $c)
                                    <div class="flex items-start gap-2">
                                        <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                                        <div class="text-sm text-zinc-800">{{ $c }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 flex items-center justify-between">
                    <div class="font-extrabold text-zinc-900">Total</div>
                    <div class="text-xl font-extrabold text-emerald-700">
                        {{ number_format($total ?? 0, 2) }} <span class="text-sm text-zinc-500 font-bold">MAD</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- RESERVATION (full card, already shown in header badges too) --}}
    @if($reservation)
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 bg-zinc-50 border-b border-zinc-200">
                <div class="font-extrabold text-zinc-900">Réservation</div>
                <div class="text-sm text-zinc-500">Créneau planifié</div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Date souhaitée</div>
                        <div class="text-lg font-extrabold text-zinc-900">
                            {{ \Carbon\Carbon::parse($reservation->date_souhaite)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-5 flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Créneau</div>
                        <div class="text-lg font-extrabold text-zinc-900">
                            {{ substr($reservation->hour, 0, 5) }}
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 rounded-2xl border border-orange-200 bg-orange-50 p-5">
                    <div class="text-sm text-orange-900 font-semibold">
                        <span class="font-extrabold">Info :</span> contactez le client avant l’intervention si nécessaire.
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- MODAL JS (kept) --}}
<script>
    function openMediaModal(src, type) {
        const modal = document.getElementById('mediaModal');
        const img = document.getElementById('modalImage');
        const video = document.getElementById('modalVideo');

        if (!modal) return;

        if (type === 'image') {
            img.src = src;
            img.classList.remove('hidden');
            video.classList.add('hidden');
            video.pause && video.pause();
        } else {
            video.src = src;
            video.classList.remove('hidden');
            img.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    function closeMediaModal() {
        const modal = document.getElementById('mediaModal');
        const video = document.getElementById('modalVideo');

        if (!modal) return;
        modal.classList.add('hidden');

        if (video) {
            video.pause();
            video.currentTime = 0;
        }
    }

    document.addEventListener('click', function(e) {
        const modal = document.getElementById('mediaModal');
        const content = document.getElementById('mediaContentWrapper');
        if (!modal || modal.classList.contains('hidden')) return;

        if (content && !content.contains(e.target) && e.target === modal) {
            closeMediaModal();
        }
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeMediaModal();
    });
</script>

@endsection
