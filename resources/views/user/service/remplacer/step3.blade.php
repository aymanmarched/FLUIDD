@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-4xl">

        <div class="mx-auto mb-6 max-w-3xl text-center">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                Étape 3
            </span>

            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                Informations client
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-700 md:text-base">
                Renseignez vos coordonnées pour finaliser votre demande.
            </p>
        </div>

        <div class="rounded-2xl border border-white/60 bg-white/85 p-4 shadow-xl backdrop-blur md:p-6">
            <form method="POST" action="{{ route('service.remplacer.step3.store') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="selection_ids" value="{{ request('selection_ids') }}">
                <input type="hidden" name="reference" value="{{ $reference }}">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Nom</label>
                        <input type="text" name="nom"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100"
                            required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Prénom</label>
                        <input type="text" name="prenom"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100"
                            required>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Téléphone (+212)</label>
                        <input type="text" name="telephone" maxlength="9" pattern="^(6|7)[0-9]{8}$" placeholder="6XXXXXXXX"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"
                            required>
                        @error('telephone')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                        <small class="mt-2 block text-xs text-slate-500">Format : 6XXXXXXXX ou 7XXXXXXXX</small>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Email (optionnel)</label>
                        <input type="email" name="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Ville</label>
                        <select name="ville_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
                            <option value="">-- Choisir --</option>
                            @foreach($villes as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Adresse précise</label>
                        <input type="text" name="adresse" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-extrabold text-slate-700">Localisation GPS (optionnel)</label>

                        <input type="text" id="locationField" name="location"
                            class="w-full rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm cursor-not-allowed"
                            readonly
                            placeholder="Cliquez pour partager votre position">

                        <button type="button" onclick="getLocation()"
                            class="mt-3 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                            Partager ma localisation
                        </button>

                        <div id="map" class="mt-3 rounded-xl border" style="height: 320px;"></div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Continuer
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    let map, marker;

    function initMap(lat = 48.8566, lng = 2.3522) {
        map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function () {
            const pos = marker.getLatLng();
            document.getElementById('locationField').value = pos.lat + ',' + pos.lng;
        });

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            document.getElementById('locationField').value = e.latlng.lat + ',' + e.latlng.lng;
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    document.getElementById('locationField').value = lat + ',' + lng;

                    if (!map) {
                        initMap(lat, lng);
                    } else {
                        map.setView([lat, lng], 13);
                        marker.setLatLng([lat, lng]);
                    }
                },
                function () {
                    alert('Impossible de récupérer votre localisation.');
                }
            );
        } else {
            alert('Votre navigateur ne supporte pas la géolocalisation.');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initMap();
    });
</script>
@endsection