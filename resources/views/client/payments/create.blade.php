@extends('client.menu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Paiement</div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Paiement par carte</h1>
                <p class="text-slate-600 mt-1">Finalisez votre paiement en toute simplicité.</p>
            </div>

            <div class="rounded-2xl bg-sky-50 border border-sky-100 px-4 py-2 text-sm text-sky-900 font-semibold w-full sm:w-auto">
                Réf: <span class="font-extrabold">{{ $reference }}</span>
            </div>
        </div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-5 text-sm">
            <div class="font-extrabold mb-2">Veuillez corriger :</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Order summary --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="font-extrabold">Résumé</div>
                <div class="text-sm text-slate-500">Détails de la commande</div>
            </div>

            <div class="p-6 space-y-4">
                <div class="text-sm text-slate-600">
                    Commande:
                    <span class="font-extrabold uppercase text-slate-900">{{ $type }}</span>
                    <span class="text-slate-400">•</span>
                    Référence:
                    <span class="font-extrabold text-slate-900">{{ $reference }}</span>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-slate-700">
                        <span>Total</span>
                        <span class="font-semibold">{{ number_format($total, 2) }} MAD</span>
                    </div>

                    <div class="flex justify-between text-sm text-slate-700">
                        <span>Réduction</span>
                        <span class="font-semibold">{{ $discountPercent }}%</span>
                    </div>

                    <div class="pt-3 border-t border-gray-200 flex justify-between items-baseline">
                        <span class="text-slate-800 font-semibold">À payer</span>
                        <span class="text-2xl font-extrabold text-emerald-700">
                            {{ number_format($amountToPay, 2) }} MAD
                        </span>
                    </div>
                </div>

                @if($discountPercent > 0)
                    <div class="text-sm text-emerald-800 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl">
                        <span class="font-extrabold">Réduction appliquée :</span>
                        la mission n’a pas encore démarré.
                    </div>
                @else
                    <div class="text-sm text-slate-700 bg-gray-50 border border-gray-200 p-4 rounded-2xl">
                        <span class="font-extrabold">Pas de réduction :</span>
                        mission déjà démarrée ou terminée.
                    </div>
                @endif

                <div class="text-xs text-slate-500 bg-gray-50 border border-gray-200 p-4 rounded-2xl">
                    Mode démo: aucune transaction réelle n’est effectuée (pas de CMI).
                    Le paiement sera enregistré à la validation.
                </div>
            </div>
        </div>

        {{-- Card form --}}
        <form method="POST"
              action="{{ route('client.payments.store', ['type' => $type, 'reference' => $reference]) }}"
              class="lg:col-span-3 bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
            @csrf

            <div class="px-6 py-4 border-b border-gray-200">
                <div class="font-extrabold">Informations de carte</div>
                <div class="text-sm text-slate-500">Saisie sécurisée (interface démo)</div>
            </div>

            <div class="p-6 space-y-6">

                <!-- Brand -->
                <div>
                    <div class="text-sm font-extrabold text-slate-900 mb-3">Moyen de paiement</div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="border border-gray-200 rounded-2xl p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="card_brand" value="visa" class="accent-sky-600" checked>
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900">VISA</div>
                                <div class="text-xs text-slate-500">Carte Visa</div>
                            </div>
                        </label>

                        <label class="border border-gray-200 rounded-2xl p-4 flex items-center gap-3 cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="card_brand" value="mastercard" class="accent-sky-600">
                            <div class="min-w-0">
                                <div class="font-extrabold text-slate-900">Mastercard</div>
                                <div class="text-xs text-slate-500">Carte Mastercard</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Fields -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-sm font-extrabold text-slate-700">Nom sur la carte</label>
                        <input
                            name="card_name"
                            type="text"
                            autocomplete="cc-name"
                            placeholder="Ex: Ahmed El Amrani"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3
                                   focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                            required
                        >
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-slate-700">Numéro de carte</label>
                        <input
                            id="cc_number"
                            name="card_number"
                            type="text"
                            inputmode="numeric"
                            autocomplete="cc-number"
                            placeholder="1234 5678 9012 3456"
                            maxlength="19"
                            class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3
                                   focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                            required
                        >
                        <div class="text-xs text-slate-500 mt-2">16 chiffres (formatage automatique).</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-extrabold text-slate-700">Expiration</label>
                            <input
                                id="cc_exp"
                                name="card_exp"
                                type="text"
                                inputmode="numeric"
                                autocomplete="cc-exp"
                                placeholder="MM/YY"
                                maxlength="5"
                                class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3
                                       focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                required
                            >
                        </div>

                        <div>
                            <label class="text-sm font-extrabold text-slate-700">CVV</label>
                            <input
                                name="card_cvv"
                                type="password"
                                inputmode="numeric"
                                autocomplete="cc-csc"
                                placeholder="123"
                                maxlength="4"
                                class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3
                                       focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Consent -->
                <label class="flex items-start gap-3 text-sm">
                    <input type="checkbox" name="agree" class="mt-1 accent-sky-600" required>
                    <span class="text-slate-700">
                        Je confirme le paiement (démo) et j’accepte que le statut devienne <strong>Payée</strong>.
                    </span>
                </label>

                <!-- CTA -->
                <button type="submit"
                        class="w-full px-5 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition">
                    Payer {{ number_format($amountToPay, 2) }} MAD
                </button>

                <div class="text-xs text-slate-500 bg-gray-50 border border-gray-200 p-4 rounded-2xl">
                    Important: ces champs sont uniquement pour l’interface. Aucune vérification bancaire n’est faite.
                </div>

            </div>
        </form>

    </div>
</div>

<script>
    (function () {
        const number = document.getElementById('cc_number');
        const exp = document.getElementById('cc_exp');

        if (number) {
            number.addEventListener('input', () => {
                let v = number.value.replace(/\D/g, '').slice(0, 16);
                number.value = v.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
            });
        }

        if (exp) {
            exp.addEventListener('input', () => {
                let v = exp.value.replace(/\D/g, '').slice(0, 4);
                if (v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
                exp.value = v;
            });
        }
    })();
</script>
@endsection
