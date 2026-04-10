@extends('user.header')

@section('content')
    <section class="flex min-h-[calc(100vh-120px)] items-center justify-center bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:py-8">
        <div class="w-full max-w-xl">
            <div class="overflow-hidden rounded-2xl border border-white/60 bg-white/85 shadow-xl backdrop-blur">

                <!-- top -->
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 px-5 py-6 text-white md:px-6 md:py-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wide">
                                Confirmation
                            </div>

                            <h1 class="text-2xl font-extrabold md:text-3xl">
                                Merci !
                            </h1>

                            <p class="mt-2 text-sm text-white/90">
                                Votre demande a été envoyée avec succès.
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/15 border border-white/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.53a.75.75 0 0 0-1.22-.872l-3.236 4.53L9.53 11.5a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- body -->
                <div class="p-5 md:p-6">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h2 class="text-base font-extrabold text-slate-900 md:text-lg">
                            Demande enregistrée
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            Notre équipe prendra connaissance de votre message et vous recontactera dans les meilleurs délais.
                        </p>
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a href="/"
                            class="inline-flex w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                            Retour à l'accueil
                        </a>

                        <a href="/Contactez_Nous"
                            class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-800 transition hover:bg-slate-50">
                            Nouvelle demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection