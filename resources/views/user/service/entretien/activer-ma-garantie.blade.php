@extends('user.header')

@section('content')
    <section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
        <div class="mx-auto max-w-5xl">

            <!-- heading -->
            <div class="mx-auto mb-6 max-w-2xl text-center">
                <span
                    class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-secondary"></span>
                    Service garantie
                </span>

                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                    Activer ma garantie
                </h1>

                <p class="mt-3 text-sm leading-6 text-slate-700 md:text-base">
                    Enregistrez votre machine rapidement avec un formulaire simple et clair.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">

                <!-- left -->
                <div class="lg:col-span-4">
                    <div class="overflow-hidden rounded-2xl border border-white/50 bg-white/70 shadow-xl backdrop-blur">
                        <div class="bg-gradient-to-r from-secondary to-yellow-400 px-5 py-5 text-slate-900">
                            <h2 class="text-xl font-extrabold">Activation simple</h2>
                            <p class="mt-2 text-sm text-slate-800/80">
                                Renseignez vos informations puis ajoutez le numéro de série de votre machine.
                            </p>
                        </div>

                        <div class="space-y-3 p-5">
                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">1. Informations client</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Nom, téléphone, ville et adresse.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">2. Choix machine / marque</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Sélectionnez la machine concernée.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-white p-4">
                                <h3 class="text-sm font-extrabold text-slate-900">3. Numéro de série</h3>
                                <p class="mt-1 text-sm leading-6 text-slate-600">
                                    Saisie manuelle ou scan.
                                </p>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <p class="text-sm leading-6 text-slate-700">
                                    Vérifiez bien les informations avant l’envoi pour éviter les erreurs.
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
                                Formulaire d’activation
                            </h2>
                            <p class="mt-2 text-sm text-slate-600">
                                Complétez les champs ci-dessous.
                            </p>
                        </div>

                        <form action="{{ route('garantie.store') }}" method="POST" x-data="garantieForm()"
                            class="space-y-5">
                            @csrf

                            <!-- SCANNER MODAL -->


                            {{-- CLIENT NOT LOGGED --}}
                            @guest

                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    <div>
                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Nom *</label>
                                        <input type="text" name="nom" value="{{ old('nom') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800"
                                            required>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Prénom *</label>
                                        <input type="text" name="prenom" value="{{ old('prenom') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800"
                                            required>
                                    </div>

                                </div>


                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    <div>
                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Téléphone (+212)
                                            *</label>

                                        <input type="text" name="telephone" maxlength="9" pattern="^(6|7)[0-9]{8}$"
                                            placeholder="6XXXXXXXX"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800"
                                            required>

                                        <p class="mt-2 text-xs text-slate-500">Format : 6XXXXXXXX</p>

                                    </div>

                                    <div>
                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Email</label>

                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">

                                    </div>

                                </div>


                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                    <div>

                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Ville *</label>

                                        <select name="ville_id"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800"
                                            required>

                                            @foreach($villes as $ville)

                                                <option value="{{ $ville->id }}">

                                                    {{ $ville->name }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div>

                                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Adresse</label>

                                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800">

                                    </div>

                                </div>

                            @endguest



                            {{-- CLIENT LOGGED --}}
                            @auth

                                <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

                                    Vos informations client seront utilisées automatiquement.

                                </div>

                                <input type="hidden" name="nom" value="{{ $client->nom }}">
                                <input type="hidden" name="prenom" value="{{ $client->prenom }}">
                                <input type="hidden" name="telephone" value="{{ substr($client->telephone, 4) }}">
                                <input type="hidden" name="email" value="{{ $client->email }}">
                                <input type="hidden" name="ville_id" value="{{ $client->ville_id }}">
                                <input type="hidden" name="adresse" value="{{ $client->adresse }}">

                            @endauth



                            {{-- MACHINE --}}
                            <div>

                                <label class="mb-3 block text-sm font-extrabold text-slate-700">

                                    Machine

                                </label>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                                    @foreach($machines as $machine)

                                        <label
                                            class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 transition hover:border-primary hover:bg-blue-50/40">

                                            <input type="radio" name="machine_id" value="{{ $machine->id }}"
                                                @change="loadMarques({{ $machine->id }})" class="h-4 w-4 accent-[#1E90FF]"
                                                required>

                                            <span class="text-sm font-bold text-slate-800">

                                                {{ $machine->name }}

                                            </span>

                                        </label>

                                    @endforeach

                                </div>

                            </div>



                            {{-- MARQUE --}}
                            <div>

                                <label class="mb-2 block text-sm font-extrabold text-slate-700">

                                    Marque

                                </label>

                                <select name="marque_id"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800"
                                    required>

                                    <option value="">

                                        -- Choisir une marque --

                                    </option>

                                    <template x-for="marque in marques" :key="marque.id">

                                        <option :value="marque.id" x-text="marque.nom"></option>

                                    </template>

                                </select>

                            </div>



                            {{-- SERIAL --}}
                            <div>
                                <label class="mb-2 block text-sm font-extrabold text-slate-700">Numéro de série</label>

                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <input type="text" name="machine_series" x-model="series"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100"
                                        placeholder="Scanner ou saisir le numéro de série" required>

                                    <button type="button" @click="openScanner"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-5 py-3 text-sm font-extrabold text-white transition hover:brightness-95 sm:min-w-[130px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7V6a2 2 0 012-2h2" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 17v1a2 2 0 002 2h2" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 4h2a2 2 0 012 2v1" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 20h2a2 2 0 002-2v-1" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                        </svg>
                                        Scan
                                    </button>
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <p class="text-xs leading-5 text-slate-600">
                                    Vérifiez que la machine, la marque et le numéro de série correspondent exactement à
                                    votre équipement.
                                </p>
                            </div>

                            <button
                                class="w-full rounded-xl bg-primary px-6 py-3.5 text-sm font-extrabold text-white hover:bg-blue-700">

                                Activer ma garantie

                            </button>
                            <!-- modal scanner -->

                            <div x-show="scannerOpen" x-transition
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4">

                                <div class="w-full max-w-md rounded-2xl bg-white p-4 shadow-2xl">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h3 class="text-base font-extrabold text-slate-900">Scanner le numéro de série</h3>
                                        <button @click="closeScanner" type="button"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100">
                                            ✕
                                        </button>
                                    </div>

                                    <div id="qr-reader" class="w-full overflow-hidden rounded-xl border border-slate-200">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>



    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function garantieForm() {
            return {
                marques: [],
                series: '',
                scannerOpen: false,
                qrScanner: null,

                loadMarques(machineId) {
                    fetch(`/get-marques/${machineId}`)
                        .then(res => res.json())
                        .then(data => this.marques = data);
                },

              openScanner() {
            this.scannerOpen = true;

            this.$nextTick(() => {
                this.qrScanner = new Html5Qrcode("qr-reader");

                this.qrScanner.start(
                    { facingMode: "environment" }, // back camera
                      { fps: 10,
                        qrbox: { width: 300, height: 200 }, // 👈 PERFECT for 1D barcode
                       experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true // 🔥 VERY IMPORTANT
                        }
                    },
                    (decodedText) => {
                        this.series = decodedText; // ✅ fill input
                        this.closeScanner();
                    },
                    (error) => {
                        // silent errors (normal while scanning)
                    }
                );
            });
        },
                    closeScanner() {
                    this.scannerOpen = false;
                    if (this.qrScanner) {
                        this.qrScanner.stop().then(() => {
                            this.qrScanner.clear();
                        });
                    }
                }
            }
        }
    </script>
@endsection