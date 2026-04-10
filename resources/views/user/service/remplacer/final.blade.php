@extends('user.header')

@section('content')
<div class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-4xl space-y-6">

        <div class="rounded-2xl border border-white/60 bg-white/85 p-5 shadow-xl backdrop-blur md:p-6">
            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">
                        Bonjour {{ $client->nom }} {{ $client->prenom }}
                    </h2>
                    <p class="mt-2 text-sm text-slate-500">
                        Référence de commande : <br class="sm:hidden">
                        <span class="font-bold text-primary">{{ $reference }}</span>
                    </p>
                    <p class="mt-3 text-sm leading-6 text-slate-600 md:text-base">
                        Votre commande a été effectuée avec succès. Nous vous contacterons bientôt.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="mb-3 text-lg font-extrabold text-slate-900">Vos informations</h3>
                    <div class="space-y-2 text-sm text-slate-700">
                        <p><span class="font-bold text-slate-900">Téléphone :</span> {{ $client->telephone }}</p>
                        <p><span class="font-bold text-slate-900">Email :</span> {{ $client->email }}</p>
                        <p><span class="font-bold text-slate-900">Ville :</span> {{ $client->ville->name ?? '-' }}</p>
                        <p><span class="font-bold text-slate-900">Adresse :</span> {{ $client->adresse }}</p>
                    </div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <h3 class="mb-3 text-lg font-extrabold text-slate-900">Résumé de la commande</h3>

                    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Machine</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Marque</th>
                                    <th class="px-4 py-3 text-left font-bold text-slate-600">Prix</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach($selections as $s)
                                    <tr>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->machine->name }}</td>
                                        <td class="px-4 py-3 text-slate-700">{{ $s->marque->nom }}</td>
                                        <td class="px-4 py-3 font-semibold text-slate-900">{{ number_format($s->marque->prix ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-slate-50 font-bold">
                                    <td class="px-4 py-3">Total</td>
                                    <td></td>
                                    <td class="px-4 py-3 text-slate-900">{{ number_format($total, 2) }} MAD</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @php
                $connectedClient =
                    auth()->check()
                    && auth()->user()?->client
                    && auth()->user()->client->id === $client->id;
            @endphp

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('service.remplacer.devis.pdf', ['client_id' => $client->id, 'token' => $client->sms_token, 'reference' => $reference]) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-primary px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Télécharger le devis (PDF)
                </a>

                @if($connectedClient)
                    <a href="{{ route('client.remplacers.show', $reference) }}"
                       class="inline-flex items-center justify-center text-center rounded-xl bg-green-600 px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-green-700">
                        Voir ma commande <br class="sm:hidden"> ({{ $reference }})
                    </a>
                @elseif($client->password_token)
                    <a href="{{ route('client.setPassword', $client) }}"
                       class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Aller à mon compte
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection