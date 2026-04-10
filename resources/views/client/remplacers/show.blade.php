@extends('client.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('client.remplacers') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Retour
        </a>

        <a href="{{ route('client.remplacers.edit', $reference) }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
            Modifier
        </a>
    </div>

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="text-sm text-slate-500">Commande de remplacement</div>
        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
            Référence : <span class="text-sky-700">{{ $reference }}</span>
        </h2>
    </div>

    <!-- Selections -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="font-extrabold">Détails</div>
            <div class="text-sm text-slate-500">Machines, marques, caractéristiques et prix.</div>
        </div>

        <div class="p-6 space-y-4">
            @foreach($selections as $s)
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="text-sm text-slate-500">Machine</div>
                            <div class="text-lg font-extrabold text-slate-900">{{ $s->machine->name }}</div>

                            <div class="mt-2">
                                <span class="text-sm text-slate-500">Marque :</span>
                                <span class="font-extrabold text-slate-900">{{ $s->marque->nom }}</span>
                            </div>

                            @if(is_array($s->marque->caractere))
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach($s->marque->caractere as $c)
                                        <div class="inline-flex items-start gap-2 text-sm text-slate-700">
                                            <span class="text-sky-600">•</span>
                                            <span>{{ $c }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="shrink-0">
                            <div class="text-sm text-slate-500">Prix</div>
                            <div class="mt-1 inline-flex items-center px-4 py-2 rounded-full text-sm border
                                        bg-emerald-50 text-emerald-700 border-emerald-100 font-extrabold">
                                {{ number_format($s->marque->prix, 2) }} MAD
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="rounded-2xl border border-gray-200 bg-white p-5 flex items-center justify-between">
                <div class="font-extrabold text-slate-900">Total</div>
                <div class="text-2xl font-extrabold text-emerald-700">
                    {{ number_format($total, 2) }} MAD
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation -->
    @if(!$reservation)
        <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-10 text-center">
            <div class="font-extrabold text-lg text-slate-900">Aucune réservation trouvée</div>
            <p class="text-slate-500 mt-1">Vous pouvez la définir en modifiant la commande.</p>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="font-extrabold">Réservation</div>
                <div class="text-sm text-slate-500">Date et créneau souhaités.</div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="flex items-center gap-4 p-5 rounded-2xl border border-gray-200 bg-gray-50">
                        <div class="w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center text-sky-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-slate-500 font-semibold">Date souhaitée</div>
                            <div class="text-lg font-extrabold text-slate-900">
                                {{ \Carbon\Carbon::parse($reservation->date_souhaite)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-5 rounded-2xl border border-gray-200 bg-gray-50">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-slate-500 font-semibold">Créneau</div>
                            <div class="text-lg font-extrabold text-slate-900">
                                {{ substr($reservation->hour, 0, 5) }}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-5 rounded-2xl bg-sky-50 border border-sky-100 p-4">
                    <p class="text-sm text-sky-900">
                        <span class="font-extrabold">Info :</span>
                        Un technicien vous contactera avant l’intervention pour confirmation.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- PDF -->
    <div class="text-center">
        <a href="{{ route('service.remplacer.devis.pdf', ['client_id' => $client->id, 'token' => $client->sms_token,'reference' => $reference]) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </svg>
            Télécharger le devis (PDF)
        </a>
    </div>

</div>
@endsection
