@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-xl">
        <div class="rounded-2xl border border-white/60 bg-white/85 p-5 shadow-xl backdrop-blur md:p-6">

            <div class="mb-6 text-center">
                <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    Étape 4
                </span>

                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                    Vérification SMS
                </h2>

                <p class="mt-3 text-sm leading-6 text-slate-700">
                    Un code a été envoyé au numéro {{ $client->telephone }}.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-center">
                <h3 class="text-lg font-extrabold text-slate-900">
                    Entrez le code reçu par SMS
                </h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Saisissez le code pour continuer vers l’étape suivante.
                </p>
            </div>

            <form method="POST" action="{{ route('service.entretien.entretenir.step4.verify') }}" class="mt-5">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="reference" value="{{ $reference }}">

                <label for="verification_code" class="mb-2 block text-sm font-extrabold text-slate-700">
                    Code de vérification
                </label>

                <input
                    type="text"
                    name="verification_code"
                    id="verification_code"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-center text-lg font-bold tracking-[0.4em] text-slate-900 outline-none focus:ring-4 focus:ring-blue-100"
                    placeholder="000000"
                    value="{{ old('verification_code') }}"
                    required
                >

                <button class="mt-5 w-full rounded-xl bg-primary px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700" type="submit">
                    Continuer vers l’étape 5
                </button>
            </form>

            <form method="POST" action="{{ route('service.entretien.entretenir.resendSms') }}" class="mt-3">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <input type="hidden" name="reference" value="{{ $reference }}">
                <button class="w-full rounded-xl border border-slate-200 bg-slate-50 px-6 py-3 text-sm font-extrabold text-slate-800 transition hover:bg-slate-100" type="submit">
                    Renvoyer le code
                </button>
            </form>

            <!-- <a class="mt-3 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-extrabold text-slate-800 transition hover:bg-slate-50"
               href="{{ route('service.entretien.entretenir.step3', ['selection_ids' => request('selection_ids'), 'reference' => $reference]) }}">
                Retour
            </a> -->
        </div>
    </div>
</section>
@endsection