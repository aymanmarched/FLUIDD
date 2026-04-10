@extends('user.header')

@section('content')
<style>
    .file-input {
        position: relative;
        padding: 14px;
        min-height: 58px;
        border-radius: 14px;
        border: 2px dashed #cdd4df;
        background: #f9fbff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-align: center;
        transition: 0.25s;
        font-weight: 700;
        gap: 8px;
        font-size: 14px;
    }

    .file-input:hover {
        border-color: #1E90FF;
        background: #f0f6ff;
    }

    .file-input input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .file-input svg {
        width: 20px;
        height: 20px;
        opacity: .85;
    }

    .upload-success {
        margin-top: 8px;
        font-weight: 700;
        color: #16a34a;
        display: none;
        font-size: 14px;
    }

    .upload-error {
        margin-top: 8px;
        font-weight: 700;
        color: #dc2626;
        display: none;
        font-size: 14px;
    }

    .btn-disabled {
        opacity: .6;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }
</style>

@php
    $isConnectedClient = isset($client);
@endphp

<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-5xl">

        <div class="mx-auto mb-6 max-w-3xl text-center">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                Étape 3
            </span>

            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                Informations client
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-700 md:text-base">
                Ajoutez vos coordonnées et, si nécessaire, des photos ou vidéos pour mieux expliquer le problème.
            </p>
        </div>

        @error('upload')
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 font-semibold text-red-700">
                {{ $message }}
            </div>
        @enderror

        <div class="rounded-2xl border border-white/60 bg-white/85 p-4 shadow-xl backdrop-blur md:p-6">
            <form id="step3Form"
                  method="POST"
                  action="{{ route('service.entretien.entretenir.step3.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="reference" value="{{ $reference }}">
                <input type="hidden" name="selection_ids" value="{{ request('selection_ids') }}">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                   {{-- CLIENT NOT LOGGED --}}
@guest

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Nom</label>
<input type="text"
name="nom"
value="{{ old('nom') }}"
class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100"
required>
</div>

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Prénom</label>
<input type="text"
name="prenom"
value="{{ old('prenom') }}"
class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100"
required>
</div>

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Téléphone</label>
<input type="text"
name="telephone"
maxlength="9"
pattern="^(6|7)[0-9]{8}$"
placeholder="6XXXXXXXX"
class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"
required>

<small class="mt-2 block text-xs text-slate-500">Format : 6XXXXXXXX ou 7XXXXXXXX</small>
</div>

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Email</label>
<input type="email"
name="email"
value="{{ old('email') }}"
class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
</div>

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Ville</label>
<select name="ville_id"
class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
<option value="">-- Choisir --</option>

@foreach($villes as $v)
<option value="{{ $v->id }}">
{{ $v->name }}
</option>
@endforeach

</select>
</div>

<div>
<label class="mb-2 block text-sm font-extrabold text-slate-700">Adresse</label>
<input type="text"
name="adresse"
value="{{ old('adresse') }}"
class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
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
<input type="hidden" name="telephone" value="{{ $client->telephone }}">
<input type="hidden" name="email" value="{{ $client->email }}">
<input type="hidden" name="ville_id" value="{{ $client->ville_id }}">
<input type="hidden" name="adresse" value="{{ $client->adresse }}">
<input type="hidden" name="location" value="{{ $client->location }}">

@endauth

                </div>

                <div class="my-8 h-px bg-slate-200"></div>

                <h3 class="mb-5 text-xl font-extrabold text-slate-900">
                    Découvrons votre problème
                </h3>

                @foreach($selections as $selection)
                    <div class="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 md:p-5">
                        <h4 class="mb-4 text-lg font-extrabold text-slate-900">
                            {{ $selection->machine->name }}
                        </h4>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-extrabold text-slate-700">Photo (optionnel)</label>
                                <p class="mt-1 text-xs text-slate-500">
                                    Taille max : <b>2MB</b> — JPG, PNG, WEBP
                                </p>

                                <div class="file-input mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23L4.052 7.405C2.999 7.58 2.25 8.507 2.25 9.574V18A2.25 2.25 0 0 0 4.5 20.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169l-1.134-.175A2.31 2.31 0 0 1 17.174 6.175l-.822-1.316A2.192 2.192 0 0 0 14.616 3.82a48.774 48.774 0 0 0-5.232 0A2.192 2.192 0 0 0 7.648 4.859l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Z" />
                                    </svg>
                                    Ajouter une photo
                                    <input type="file"
                                           name="photo[{{ $selection->id }}]"
                                           accept="image/*"
                                           onchange="validateFile(this, 'photo', {{ $selection->id }})">
                                </div>

                                <div id="photoSuccess{{ $selection->id }}" class="upload-success">Image ajoutée ✓</div>
                                <div id="photoError{{ $selection->id }}" class="upload-error"></div>
                            </div>

                            <div>
                                <label class="text-sm font-extrabold text-slate-700">Vidéo (optionnel)</label>
                                <p class="mt-1 text-xs text-slate-500">
                                    Taille max : <b>30MB</b> — MP4, MOV, AVI
                                </p>

                                <div class="file-input mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9A2.25 2.25 0 0 0 15.75 16.5v-9A2.25 2.25 0 0 0 13.5 5.25h-9A2.25 2.25 0 0 0 2.25 7.5v9A2.25 2.25 0 0 0 4.5 18.75Z" />
                                    </svg>
                                    Ajouter une vidéo
                                    <input type="file"
                                           name="video[{{ $selection->id }}]"
                                           accept="video/*"
                                           onchange="validateFile(this, 'video', {{ $selection->id }})">
                                </div>

                                <div id="videoSuccess{{ $selection->id }}" class="upload-success">Vidéo ajoutée ✓</div>
                                <div id="videoError{{ $selection->id }}" class="upload-error"></div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-6 text-center">
                    <button id="continueBtn"
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                        Continuer
                    </button>

                    <p id="continueHint" class="mt-3 hidden text-sm font-semibold text-red-600">
                        Corrigez les fichiers trop volumineux pour continuer.
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
  const MAX_PHOTO_MB = 2;
  const MAX_VIDEO_MB = 30;
  const invalidFiles = new Set();

  function bytesToMB(bytes){ return bytes / (1024 * 1024); }

  function showError(id, msg){
    const el = document.getElementById(id);
    if(!el) return;
    el.textContent = msg;
    el.style.display = 'block';
  }

  function clearError(id){
    const el = document.getElementById(id);
    if(!el) return;
    el.textContent = '';
    el.style.display = 'none';
  }

  function showSuccess(id) {
    const el = document.getElementById(id);
    if(el) el.style.display = 'block';
  }

  function hideSuccess(id) {
    const el = document.getElementById(id);
    if(el) el.style.display = 'none';
  }

  function setContinueEnabled(enabled){
    const btn = document.getElementById('continueBtn');
    const hint = document.getElementById('continueHint');
    if(!btn) return;

    btn.disabled = !enabled;

    if(!enabled){
      btn.classList.add('btn-disabled');
      if(hint) hint.classList.remove('hidden');
    } else {
      btn.classList.remove('btn-disabled');
      if(hint) hint.classList.add('hidden');
    }
  }

  function updateContinueState(){
    setContinueEnabled(invalidFiles.size === 0);
  }

  function validateFile(input, kind, selectionId){
    const file = input.files && input.files[0] ? input.files[0] : null;

    const errId = (kind === 'photo') ? `photoError${selectionId}` : `videoError${selectionId}`;
    const okId  = (kind === 'photo') ? `photoSuccess${selectionId}` : `videoSuccess${selectionId}`;

    const key = `${kind}-${selectionId}`;

    clearError(errId);
    hideSuccess(okId);

    if(!file){
      invalidFiles.delete(key);
      updateContinueState();
      return true;
    }

    const maxMB = (kind === 'photo') ? MAX_PHOTO_MB : MAX_VIDEO_MB;
    const sizeMB = bytesToMB(file.size);

    if(sizeMB > maxMB){
      showError(errId, `Fichier trop grand: ${sizeMB.toFixed(1)}MB. Max autorisé: ${maxMB}MB.`);
      input.value = '';
      invalidFiles.add(key);
      updateContinueState();
      return false;
    }

    if(kind === 'photo' && !file.type.startsWith('image/')){
      showError(errId, 'Format invalide. Merci de choisir une image.');
      input.value = '';
      invalidFiles.add(key);
      updateContinueState();
      return false;
    }

    if(kind === 'video' && !file.type.startsWith('video/')){
      showError(errId, 'Format invalide. Merci de choisir une vidéo.');
      input.value = '';
      invalidFiles.add(key);
      updateContinueState();
      return false;
    }

    invalidFiles.delete(key);
    showSuccess(okId);
    updateContinueState();
    return true;
  }

  document.addEventListener('DOMContentLoaded', function(){
    updateContinueState();

    const form = document.getElementById('step3Form');
    if(!form) return;

    form.addEventListener('submit', function(e){
      if(invalidFiles.size > 0){
        e.preventDefault();
        updateContinueState();
        const firstErr = form.querySelector('.upload-error[style*="block"]');
        if(firstErr) firstErr.scrollIntoView({behavior:'smooth', block:'center'});
        return;
      }
    });
  });
</script>

<script>
    function syncLocation(value) {
        const hidden = document.getElementById('locationHidden');
        if (hidden) hidden.value = value;
    }
</script>

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
            const value = pos.lat + ',' + pos.lng;
            document.getElementById('locationField').value = value;
            syncLocation(value);
        });

        map.on('click', function (e) {
            const value = e.latlng.lat + ',' + e.latlng.lng;
            marker.setLatLng(e.latlng);
            document.getElementById('locationField').value = value;
            syncLocation(value);
        });
    }

    function getLocation() {
        if (!navigator.geolocation) {
            alert("Votre navigateur ne supporte pas la géolocalisation.");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const value = lat + ',' + lng;

                document.getElementById('locationField').value = value;
                syncLocation(value);

                if (!map) {
                    initMap(lat, lng);
                } else {
                    map.setView([lat, lng], 13);
                    marker.setLatLng([lat, lng]);
                }
            },
            function () {
                alert("Impossible de récupérer votre localisation.");
            }
        );
    }

    document.addEventListener('DOMContentLoaded', function () {
        const field = document.getElementById('locationField');
        if (field && field.value) {
            const parts = field.value.split(',');
            const lat = parts[0], lng = parts[1];
            if (lat && lng) {
                initMap(parseFloat(lat), parseFloat(lng));
                return;
            }
        }
        initMap();
    });
</script>
@endsection