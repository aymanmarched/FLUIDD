@extends('technicien.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header / Today -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-zinc-500">Aujourd’hui</div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Bonjour, {{ auth()->user()->name }}
                </h1>
                <p class="text-zinc-600 mt-1">Votre espace Field Ops pour gérer les missions.</p>
            </div>

            <a href="{{ route('technicien.commandes') }}"
               class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold shadow-soft transition">
                Voir les commandes
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M3 12h18"/>
                </svg>
            </a>
        </div>
    </div>

    @php $technician = auth()->user()->technician; @endphp

    <!-- Profile summary -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-200 bg-zinc-50">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold">Mon profil</div>
                        <div class="text-sm text-zinc-500">Infos de contact technicien</div>
                    </div>
                </div>

                <a href="{{ route('technicien.profile.edit') }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition">
                    Modifier
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Nom</div>
                <div class="mt-1 font-extrabold text-zinc-900">{{ $technician->name }} {{ $technician->prenom }}</div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Téléphone</div>
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm border border-orange-200 bg-orange-50 text-orange-800 font-extrabold">
                    {{ $technician->phone }}
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                <div class="text-xs uppercase tracking-wide text-zinc-500 font-bold">Email</div>
                <div class="mt-1 font-semibold text-zinc-900 break-all">{{ $technician->email ?? auth()->user()->email }}</div>
            </div>
        </div>
    </div>

</div>
@endsection
