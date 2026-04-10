@extends('client.menu')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Compte</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Modifier mon profil</h2>
                <p class="text-slate-600 mt-1">Mettez à jour vos informations pour faciliter l’intervention.</p>
            </div>

            <div class="rounded-2xl bg-sky-50 border border-sky-100 px-4 py-2 text-sm text-sky-900 font-semibold">
                Profil client
            </div>
        </div>
    </div>

    <!-- Form card -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="font-extrabold">Informations personnelles</div>
            <div class="text-sm text-slate-500">Champs obligatoires : Nom, Prénom, Téléphone.</div>
        </div>

        <form action="{{ route('client.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- NOM --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom', $client->nom) }}"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           required>
                </div>

                {{-- PRENOM --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           required>
                </div>

                {{-- TELEPHONE --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Téléphone (+212)</label>

                    <div class="flex rounded-2xl border border-gray-200 overflow-hidden focus-within:ring-4 focus-within:ring-sky-100 focus-within:border-sky-300">
                        <span class="px-4 py-3 bg-gray-50 text-slate-600 font-semibold border-r border-gray-200">+212</span>
                        <input type="text" name="telephone" maxlength="9" pattern="^(6|7)[0-9]{8}$"
                               value="{{ old('telephone', ltrim($client->telephone, '+212')) }}"
                               placeholder="6XXXXXXXX"
                               class="w-full px-4 py-3 outline-none"
                               required>
                    </div>

                    <div class="text-xs text-slate-500 mt-2">Format : 6XXXXXXXX ou 7XXXXXXXX</div>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Email (optionnel)</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                </div>

                {{-- VILLE --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Ville</label>
                    <select name="ville_id"
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white
                                   focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                        <option value="">-- Choisir --</option>
                        @foreach($villes as $v)
                            <option value="{{ $v->id }}" {{ $client->ville_id == $v->id ? 'selected' : '' }}>
                                {{ $v->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ADRESSE --}}
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Adresse précise</label>
                    <input type="text" name="adresse" value="{{ old('adresse', $client->adresse) }}"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                </div>

                {{-- GPS --}}
                <div class="md:col-span-2">
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <div class="text-sm font-extrabold text-slate-900">Localisation GPS (optionnel)</div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Cliquez sur la carte ou déplacez le marqueur pour ajuster la position.
                                </div>
                            </div>

                            <button type="button" onclick="getLocation()"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition w-full md:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 21s7-4.438 7-11a7 7 0 1 0-14 0c0 6.562 7 11 7 11Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 10.5a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5Z" />
                                </svg>
                                Partager ma localisation
                            </button>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Coordonnées</label>
                            <input type="text" id="locationField" name="location"
                                   value="{{ old('location', $client->location) }}"
                                   class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white"
                                   readonly
                                   placeholder="Latitude,Longitude">
                            <div class="text-xs text-slate-500 mt-2">
                                Exemple : 33.589886,-7.603869
                            </div>
                        </div>

                        <div id="map" class="mt-4 w-full rounded-2xl border border-gray-200 overflow-hidden"
                             style="height: 360px;"></div>
                    </div>
                </div>

            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Leaflet Map Scripts --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map, marker;

    function initMap(lat, lng) {
        map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            document.getElementById('locationField').value = pos.lat + ',' + pos.lng;
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('locationField').value = e.latlng.lat + ',' + e.latlng.lng;
        });
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('locationField').value = lat + ',' + lng;

                if (!map) {
                    initMap(lat, lng);
                } else {
                    map.setView([lat, lng], 13);
                    marker.setLatLng([lat, lng]);
                }
            }, function() {
                alert('Impossible de récupérer votre localisation.');
            });
        } else {
            alert('Votre navigateur ne supporte pas la géolocalisation.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        let loc = "{{ $client->location }}";
        let lat = 33.5731, lng = -7.5898; // Default Casablanca

        if (loc) {
            const parts = loc.split(',');
            if (parts.length === 2) {
                lat = parseFloat(parts[0]);
                lng = parseFloat(parts[1]);
            }
        }

        initMap(lat, lng);
    });
</script>
@endsection
