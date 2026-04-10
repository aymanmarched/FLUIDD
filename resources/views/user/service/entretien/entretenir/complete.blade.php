@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-xl">
        <div class="rounded-2xl border border-white/60 bg-white/85 p-6 text-center shadow-xl backdrop-blur md:p-8">

            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                    <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                </svg>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-900">
                Réservation complétée
            </h3>

            <p class="mt-3 text-sm leading-7 text-slate-600 md:text-base">
                Merci <strong class="text-slate-900">{{ $client->nom }}</strong>.<br>
                Votre demande a bien été enregistrée.
            </p>

            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4">
                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Référence</p>
                <p class="mt-2 text-lg font-extrabold text-primary">{{ $reference }}</p>
            </div>

            <div class="mt-6">
                @if(auth()->check() && auth()->user()?->client && auth()->user()->client->id === $client->id)
                    <a href="{{ route('client.entretiens.show', $reference) }}"
                       class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Voir ma commande ({{ $reference }})
                    </a>
                @elseif($client->password_token)
                    <a href="{{ route('client.setPassword', $client) }}"
                       class="inline-flex items-center justify-center rounded-xl bg-green-600 px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-green-700">
                        Aller à mon compte
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection