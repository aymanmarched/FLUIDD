{{-- resources/views/client/home.blade.php --}}
@extends('client.menu')

@section('content')
@php
    $client = auth()->user()->client;
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <!-- <div class="text-sm text-zinc-500">Aujourd’hui</div> -->
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Bonjour, {{ $client->nom }} {{ $client->prenom }}
                </h1>
                <p class="text-zinc-600 mt-1">Votre espace Client Portal pour gérer vos demandes.</p>
            </div>

            <!-- <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <a href="{{ route('client.profile.edit') }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl border border-zinc-200 bg-white hover:bg-zinc-50 font-extrabold transition w-full sm:w-auto">
                    Modifier profil
                </a>

                <a href="{{ route('client.entretiens') }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
                    Nouvelle demande
                </a>
            </div> -->
        </div>
    </div>

    <!-- Profile -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <div>
                    <div class="font-extrabold text-zinc-900">Mon profil</div>
                    <div class="text-sm text-zinc-500">Informations client</div>
                </div>
            </div>

            <a href="{{ route('client.profile.edit') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold transition">
                Modifier
            </a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Nom</div>
                <div class="mt-1 font-extrabold text-zinc-900">{{ $client->nom }} {{ $client->prenom }}</div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Téléphone</div>
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm border border-sky-200 bg-sky-50 text-sky-800 font-extrabold">
                    {{ $client->telephone }}
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Email</div>
                <div class="mt-1 font-semibold text-zinc-900 break-all">{{ $client->email ?? auth()->user()->email }}</div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Ville</div>
                <div class="mt-1 font-extrabold text-zinc-900">{{ $client->ville?->name ?? '-' }}</div>
            </div>

            <div class="md:col-span-2 rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Adresse</div>
                <div class="mt-1 font-semibold text-zinc-900">{{ $client->adresse ?? '-' }}</div>
            </div>

            <!-- Location -->
            <div class="md:col-span-2 rounded-2xl border border-zinc-200 p-5 bg-zinc-50">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-wide text-zinc-500">Localisation</div>
                        @if($client->location)
                            <div class="mt-1 font-semibold text-zinc-900 break-words">{{ $client->location }}</div>
                        @else
                            <div class="mt-1 text-zinc-500 italic">Aucune localisation disponible.</div>
                        @endif
                    </div>

                    @if($client->location)
                        <a href="https://www.google.com/maps?q={{ urlencode($client->location) }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold transition w-full md:w-auto">
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

</div>
@endsection
