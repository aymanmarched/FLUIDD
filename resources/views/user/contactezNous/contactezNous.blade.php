@extends('user.header')

@section('content')
    @php
        $specialites = [
            'Installation de climatisation',
            'Dépannage climatisation',
            'Entretien climatisation',
            'Recharge gaz',
            'Recherche de fuite',
            'Maintenance préventive',
            'Contrat annuel d’entretien',
        ];
    @endphp

    <section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
        <div class="mx-auto max-w-5xl">

            <!-- heading -->
            <div class="mx-auto mb-6 max-w-2xl text-center">
                <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    Assistance client
                </span>

                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                    Contactez-nous
                </h1>

                <p class="mt-3 text-sm leading-6 text-slate-700 md:text-base">
                    Décrivez votre besoin et notre équipe vous contactera rapidement.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">

                <!-- left -->
                <div class="lg:col-span-4">
                    <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/70 shadow-xl backdrop-blur">
                        <div class="bg-gradient-to-r from-primary to-secondary px-5 py-5 text-white">
                            <h2 class="text-xl font-extrabold">Une demande simple</h2>
                            <p class="mt-2 text-sm text-white/90">
                                Remplissez le formulaire avec vos coordonnées et le type de service souhaité.
                            </p>
                        </div>

                        <div class="space-y-3 p-5">
                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">1. Vos coordonnées</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Nom, téléphone, email et adresse.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">2. Votre besoin</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Sélectionnez le type de service correspondant à votre demande.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">3. Description du problème</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Donnez un maximum de détails pour une prise en charge plus rapide.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm leading-6 text-slate-700">
                                    Plus votre message est précis, plus notre équipe pourra vous orienter efficacement.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- right -->
                <div class="lg:col-span-8">
                    <div class="rounded-2xl border border-white/60 bg-white/80 p-5 shadow-xl backdrop-blur md:p-6">
                        <div class="mb-5">
                            <h2 class="text-xl font-extrabold text-slate-900 md:text-2xl">
                                Formulaire de contact
                            </h2>
                            <p class="mt-2 text-sm text-slate-600">
                                Complétez les champs ci-dessous.
                            </p>
                        </div>

                        <form action="/contact/send" method="POST" class="space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                        Nom complet
                                    </label>
                                    <input type="text" name="name" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                        Téléphone
                                    </label>
                                    <input type="tel" name="phone" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                        Email
                                    </label>
                                    <input type="email" name="email" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                        Adresse
                                    </label>
                                    <input type="text" name="addresse" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                    Type de service
                                </label>
                                <select name="service_type" required
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    @foreach($specialites as $specialite)
                                        <option value="{{ $specialite }}">{{ $specialite }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-extrabold text-slate-700">
                                    Décrivez votre problème
                                </label>
                                <textarea name="problem" rows="4" required
                                    placeholder="Exemple : la climatisation ne refroidit plus, fuite d’eau, bruit étrange..."
                                    class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100"></textarea>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <p class="text-xs leading-5 text-slate-600">
                                    Vérifiez vos coordonnées avant l’envoi afin que nous puissions vous recontacter rapidement.
                                </p>
                            </div>

                            <button type="submit"
                                class="w-full rounded-xl bg-primary px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                Envoyer ma demande
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection