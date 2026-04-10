{{-- resources/views/admin/home.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Overview')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
            👋 Bienvenue, Admin
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Vue d’ensemble de votre activité.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Admins (Indigo) --}}
        <a href="{{ route('admin.admins.index') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Admins</h2>
                    <p class="mt-2 text-3xl font-extrabold text-indigo-600 group-hover:text-indigo-700 transition">
                        {{ $admins }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-indigo-50 border border-indigo-200
                                                group-hover:bg-indigo-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-700" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z" />
                        <path fill-rule="evenodd"
                            d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z"
                            clip-rule="evenodd" />
                        <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                    </svg>

                </div>
            </div>
        </a>

        {{-- Clients (Sky) --}}
        <a href="{{ route('admin.clients') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Clients</h2>
                    <p class="mt-2 text-3xl font-extrabold text-sky-600 group-hover:text-sky-700 transition">
                        {{ $clients }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-sky-50 border border-sky-200
                                                group-hover:bg-sky-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-700" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                            clip-rule="evenodd" />
                        <path
                            d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>
                </div>
            </div>
        </a>
        {{-- Commandes du jour (Total + breakdown) --}}
        <a href="{{ route('admin.commandes', ['date' => \Carbon\Carbon::today()->toDateString(), 'type' => 'all']) }}"
            class="group rounded-2xl border border-slate-200 bg-white hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-sm font-semibold text-slate-600">Commandes (Aujourd’hui)</h2>

                    <p class="mt-2 text-3xl font-extrabold text-slate-900">
                        {{ $today_commandes_total }}
                    </p>

                    <p
                        class="mt-2 text-sm text-slate-500 flex flex-row items-center gap-2 sm:flex-col sm:items-start sm:gap-1">
                        <span>
                            <span class="font-semibold text-fuchsia-700">{{ $today_remplacer }}</span> REMPLACER
                        </span>

                        <span class="inline sm:hidden text-slate-300">•</span>

                        <span>
                            <span class="font-semibold text-cyan-700">{{ $today_entretien }}</span> ENTRETIEN
                        </span>
                    </p>

                </div>

                <div
                    class="h-12 w-12 rounded-2xl flex items-center justify-center bg-slate-50 border border-slate-200 group-hover:bg-slate-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700" viewBox="0 0 24 24"
                        fill="currentColor" class="size-6">
                        <path
                            d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z" />
                    </svg>

                </div>
            </div>
        </a>

        {{-- Commandes d'entretien (Cyan) --}}
        <a href="{{ route('admin.clientsentretien') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Commandes d'entretien</h2>
                    <p class="mt-2 text-3xl font-extrabold text-cyan-600 group-hover:text-cyan-700 transition">
                        {{ $clientsentretien }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-cyan-50 border border-cyan-200
                                                group-hover:bg-cyan-100 transition">


                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-700" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                </div>

            </div>
        </a>

        {{-- Commandes de remplacer (Fuchsia) --}}
        <a href="{{ route('admin.clientsremplacer') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Commandes de remplacer</h2>
                    <p class="mt-2 text-3xl font-extrabold text-fuchsia-600 group-hover:text-fuchsia-700 transition">
                        {{ $clientsremplacer }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-fuchsia-50 border border-fuchsia-200
                                                group-hover:bg-fuchsia-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-fuchsia-700" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>

                </div>
            </div>
        </a>




        {{-- Techniciens (Emerald) --}}
        <a href="{{ route('admin.technicians') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Techniciens</h2>
                    <p class="mt-2 text-3xl font-extrabold text-emerald-600 group-hover:text-emerald-700 transition">
                        {{ $technicians }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-emerald-50 border border-emerald-200
                                                group-hover:bg-emerald-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-700" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                            clip-rule="evenodd" />
                        <path
                            d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z" />
                        <path fill-rule="evenodd"
                            d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                    </svg>
                </div>
            </div>
        </a>

        {{-- Messages Clients (Rose) --}}
        <a href="{{ route('admin.clientsMessage') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Messages Clients</h2>
                    <p class="mt-2 text-3xl font-extrabold text-rose-600 group-hover:text-rose-700 transition">
                        {{ $client_messages }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-rose-50 border border-rose-200
                                                group-hover:bg-rose-100 transition">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 text-rose-700"
                        fill="currentColor" class="size-6">
                        <path
                            d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z" />
                        <path
                            d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z" />
                    </svg>


                </div>
            </div>
        </a>

        {{-- Avis Clients (Amber) --}}
        <a href="{{ route('admin.AvisClients') }}" class="group rounded-2xl border border-slate-200 bg-white
                                      hover:shadow-md transition shadow-sm">
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-slate-600">Avis Clients</h2>
                    <p class="mt-2 text-3xl font-extrabold text-amber-600 group-hover:text-amber-700 transition">
                        {{ $client_avis }}
                    </p>
                </div>

                <div class="h-12 w-12 rounded-2xl flex items-center justify-center
                                                bg-amber-50 border border-amber-200
                                                group-hover:bg-amber-100 transition">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-700" viewBox="0 0 24 24"
                        fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                            clip-rule="evenodd" />
                    </svg>

                </div>
            </div>
        </a>

    </div>
@endsection