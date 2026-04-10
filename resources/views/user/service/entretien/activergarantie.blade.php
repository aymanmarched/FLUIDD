@extends('user.header')

@section('content')
    @php
        $isAlready = $status === 'already';

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

        if ($years > 0) {
            $parts[] = $years . ' ' . ($years === 1 ? 'an' : 'ans');
        }

        if ($months > 0) {
            $parts[] = $months . ' mois';
        }

        if ($days > 0 || empty($parts)) {
            $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');
        }

        $remainingText = implode(' ', $parts);
    @endphp

    <section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
        <div class="mx-auto max-w-4xl">
            <div class="overflow-hidden rounded-2xl border border-white/60 bg-white/85 shadow-xl backdrop-blur">

                <!-- header -->
                <div class="{{ $isAlready ? 'from-amber-500 to-red-500' : 'from-emerald-500 to-green-600' }} bg-gradient-to-r px-5 py-6 text-white md:px-7 md:py-7">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wide">
                                Garantie machine
                            </div>

                            <h1 class="text-xl font-extrabold leading-tight md:text-2xl">
                                Bonjour {{ $garantie->nom }} {{ $garantie->prenom }}
                            </h1>

                            <p class="mt-2 text-sm text-white/90">
                                @if($isAlready)
                                    Votre garantie est déjà enregistrée dans notre système.
                                @else
                                    Votre garantie a été activée avec succès.
                                @endif
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/15 border border-white/20">
                            @if($isAlready)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008Zm9-3.758c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- body -->
                <div class="p-5 md:p-6">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- info -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <h2 class="mb-4 text-base font-extrabold text-slate-900 md:text-lg">
                                Informations de la garantie
                            </h2>

                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                <div class="divide-y divide-slate-200">
                                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Machine</span>
                                        <span class="text-sm font-bold text-slate-900">{{ $garantie->machine->name }}</span>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Marque</span>
                                        <span class="text-sm font-bold text-slate-900">{{ $garantie->marque->nom }}</span>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                                        <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Série</span>
                                        <span class="break-all text-sm font-mono font-bold text-slate-900">{{ $garantie->machine_series }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 rounded-xl border {{ $isAlready ? 'border-amber-200 bg-amber-50' : 'border-emerald-200 bg-emerald-50' }} p-3">
                                <p class="text-sm font-medium {{ $isAlready ? 'text-amber-800' : 'text-emerald-800' }}">
                                    @if($isAlready)
                                        Cette garantie était déjà activée.
                                    @else
                                        L’activation a bien été enregistrée.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- state -->
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <h3 class="mb-4 text-base font-extrabold text-slate-900 md:text-lg">
                                État de la couverture
                            </h3>

                            <div class="space-y-3">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                        {{ $isAlready ? 'Temps restant' : 'Durée restante' }}
                                    </div>
                                    <div class="mt-2 text-xl font-extrabold {{ $isAlready ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $remainingText }}
                                    </div>
                                </div>

                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                        Expiration
                                    </div>
                                    <div class="mt-2 text-lg font-extrabold text-slate-900">
                                        {{ $garantie->date_garante->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                <a href="/"
                                   class="inline-flex w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                    Retour à l’accueil
                                </a>

                                <a href="{{ route('garantie.create') }}"
                                   class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-800 transition hover:bg-slate-50">
                                    Nouvelle demande
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection