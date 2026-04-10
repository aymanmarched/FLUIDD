@extends('client.menu')

@section('content')
<style>
  .upload-error{
    margin-top: 10px;
    font-weight: 700;
    color: #dc2626; /* red-600 */
    display: none;
    font-size: 14px;
  }
</style>

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Commande</div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Modifier la commande : <span class="text-sky-700">{{ $reference }}</span>
                </h1>
                <p class="text-slate-600 mt-1">Mettez à jour les packs, les médias et la réservation.</p>
            </div>

            <a href="{{ route('client.entretiens') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition w-full md:w-auto">
                Retour liste
            </a>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERRORS --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editCommandeForm"
          method="POST"
          action="{{ route('client.entretiens.update', $reference) }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ================= MACHINES ================= --}}
        @foreach($selections as $sel)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
                <!-- Section header -->
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs text-slate-500">Machine</div>
                        <div class="text-lg font-extrabold truncate">{{ $sel->machine->name }}</div>
                    </div>

                    <label class="inline-flex items-center gap-2 text-rose-700 font-semibold">
                        <input type="checkbox"
                               name="remove[{{ $sel->id }}]"
                               value="1"
                               class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                        Supprimer cette machine
                    </label>
                </div>

                <div class="p-6 space-y-6">

                    {{-- TYPE --}}
                    <div>
                        <h3 class="font-extrabold text-slate-900">Type d’entretien</h3>
                        <p class="text-sm text-slate-500 mt-1">Choisissez un pack pour cette machine.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            @foreach($sel->machine->types as $type)
                                <label class="relative p-5 rounded-2xl border cursor-pointer transition bg-white
                                    {{ $sel->type_id == $type->id ? 'border-sky-300 ring-4 ring-sky-100' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50' }}">
                                    <input type="radio"
                                           name="type_id[{{ $sel->id }}]"
                                           value="{{ $type->id }}"
                                           class="sr-only peer"
                                           {{ $sel->type_id == $type->id ? 'checked' : '' }}
                                           required>

                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h4 class="font-extrabold text-base">{{ $type->name }}</h4>
                                            @if(is_array($type->caracteres))
                                                <ul class="mt-2 text-sm text-slate-600 space-y-1">
                                                    @foreach($type->caracteres as $c)
                                                        <li class="flex gap-2">
                                                            <span class="text-sky-600">•</span>
                                                            <span>{{ $c }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            <div class="text-xs text-slate-500">Prix</div>
                                            <div class="font-extrabold text-emerald-700">{{ $type->prix }} MAD</div>
                                        </div>
                                    </div>

                                    <span class="absolute top-4 right-4 hidden peer-checked:flex items-center justify-center w-8 h-8 rounded-2xl bg-sky-600 text-white font-black">
                                        ✓
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- MEDIA --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- PHOTO --}}
                        <div class="rounded-2xl border border-gray-200 p-5">
                            <label class="block font-extrabold mb-2">Photo</label>
                            <p class="text-sm text-slate-500 mb-3">Taille max : <b>2MB</b> — Formats : JPG, PNG, WEBP</p>

                            @if($sel->machineDetail?->photo)
                                <img src="{{ asset('storage/'.$sel->machineDetail->photo) }}"
                                     class="w-full h-44 object-cover rounded-2xl mb-4 border">
                            @else
                                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6 text-center text-slate-500 mb-4">
                                    Aucune photo ajoutée.
                                </div>
                            @endif

                            <input type="file"
                                   name="photo[{{ $sel->id }}]"
                                   accept="image/*"
                                   data-kind="photo"
                                   data-sel="{{ $sel->id }}"
                                   class="w-full border border-gray-200 p-3 rounded-2xl">

                            <div id="photoError{{ $sel->id }}" class="upload-error"></div>
                        </div>

                        {{-- VIDEO --}}
                        <div class="rounded-2xl border border-gray-200 p-5">
                            <label class="block font-extrabold mb-2">Vidéo</label>
                            <p class="text-sm text-slate-500 mb-3">Taille max : <b>30MB</b> — Formats : MP4, MOV, AVI</p>

                            @if($sel->machineDetail?->video)
                                <video controls class="w-full h-44 rounded-2xl mb-4 border object-cover">
                                    <source src="{{ asset('storage/'.$sel->machineDetail->video) }}">
                                </video>
                            @else
                                <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6 text-center text-slate-500 mb-4">
                                    Aucune vidéo ajoutée.
                                </div>
                            @endif

                            <input type="file"
                                   name="video[{{ $sel->id }}]"
                                   accept="video/*"
                                   data-kind="video"
                                   data-sel="{{ $sel->id }}"
                                   class="w-full border border-gray-200 p-3 rounded-2xl">

                            <div id="videoError{{ $sel->id }}" class="upload-error"></div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

        {{-- ================= RESERVATION ================= --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="font-extrabold">Modifier la réservation</div>
                <div class="text-sm text-slate-500">Choisissez une date puis un créneau.</div>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-2">Date souhaitée</label>
                    <input type="date"
                           name="date_souhaite"
                           id="datePicker"
                           value="{{ old('date_souhaite', optional($reservation)->date_souhaite) }}"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           min="{{ now()->format('Y-m-d') }}">
                </div>

                <div>
                    <label class="block text-sm font-extrabold text-slate-700 mb-3">Heure souhaitée</label>

                    <div id="hoursContainer" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <p class="col-span-full text-slate-500 text-center bg-gray-50 border border-gray-200 rounded-2xl p-4">
                            Choisissez une date pour afficher les heures.
                        </p>
                    </div>

                    <input type="hidden"
                           name="hour"
                           id="selectedHour"
                           value="{{ old('hour', optional($reservation)->hour ? substr(optional($reservation)->hour,0,5) : '') }}">
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <a href="{{ route('client.entretiens') }}"
                   class="px-6 py-3 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold text-center w-full md:w-auto">
                    Annuler
                </a>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <button type="submit"
                            name="action"
                            value="convert_to_remplacer"
                            id="btnConvert"
                            class="px-6 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
                        Convertir en Remplacement
                    </button>

                    <button type="submit"
                            name="action"
                            value="save"
                            id="btnSave"
                            class="px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
                        Enregistrer la commande
                    </button>
                </div>
            </div>

            <div id="globalUploadError"
                 class="mt-4 hidden bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 font-semibold">
                Un ou plusieurs fichiers dépassent la taille autorisée. Corrigez avant d’enregistrer.
            </div>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // =========================
    // Reservation hours (your code)
    // =========================
    const datePicker = document.getElementById('datePicker');
    const hoursContainer = document.getElementById('hoursContainer');
    const selectedHourInput = document.getElementById('selectedHour');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    window.addEventListener('DOMContentLoaded', () => {
        const existingHour = selectedHourInput.value;
        if (existingHour) {
            fetchHours(datePicker.value, existingHour);
        } else if (datePicker.value) {
            fetchHours(datePicker.value);
        }
    });

    datePicker.addEventListener('change', async () => {
        const date = datePicker.value;
        if (!date) return;

        selectedHourInput.value = '';
        hoursContainer.innerHTML = '<p class="col-span-full text-slate-500 text-center">Chargement des heures...</p>';
        fetchHours(date);
    });

    async function fetchHours(date, selectedHour = null) {
        try {
            const response = await axios.post('{{ route('getAvailableHours') }}', { date });
            const data = response.data;

            hoursContainer.innerHTML = '';

            if (data.allTaken) {
                hoursContainer.innerHTML =
                    '<p class="col-span-full text-rose-700 font-bold text-center">Cette journée est entièrement réservée.</p>';
                return;
            }

            data.hours.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = h.hour;

                btn.className = `
                    px-3 py-3 rounded-2xl border text-sm font-bold transition
                    ${h.available
                        ? 'bg-white border-gray-200 hover:bg-sky-50 hover:border-sky-200 text-slate-800'
                        : 'bg-gray-100 border-gray-200 text-slate-400 cursor-not-allowed'}
                `;

                if (h.available) {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('#hoursContainer button')
                            .forEach(b => b.classList.remove('ring-4', 'ring-sky-200', 'border-sky-300', 'bg-sky-50'));

                        btn.classList.add('ring-4', 'ring-sky-200', 'border-sky-300', 'bg-sky-50');
                        selectedHourInput.value = h.hour;
                    });
                }

                if (selectedHour && h.hour === selectedHour) {
                    btn.classList.add('ring-4', 'ring-sky-200', 'border-sky-300', 'bg-sky-50');
                    selectedHourInput.value = h.hour;
                }

                hoursContainer.appendChild(btn);
            });
        } catch (error) {
            console.error(error);
            hoursContainer.innerHTML =
                '<p class="col-span-full text-rose-700 text-center">Erreur lors du chargement des heures.</p>';
        }
    }

    // =========================
    // ✅ Upload validation + disable buttons
    // =========================
    const MAX_PHOTO_MB = 2;
    const MAX_VIDEO_MB = 30;

    const btnSave = document.getElementById('btnSave');
    const btnConvert = document.getElementById('btnConvert');
    const globalUploadError = document.getElementById('globalUploadError');
    const form = document.getElementById('editCommandeForm');

    function bytesToMB(bytes){ return bytes / (1024 * 1024); }

    function showInlineError(kind, selId, msg){
        const id = (kind === 'photo') ? `photoError${selId}` : `videoError${selId}`;
        const el = document.getElementById(id);
        if(!el) return;
        el.textContent = msg;
        el.style.display = 'block';
    }

    function clearInlineError(kind, selId){
        const id = (kind === 'photo') ? `photoError${selId}` : `videoError${selId}`;
        const el = document.getElementById(id);
        if(!el) return;
        el.textContent = '';
        el.style.display = 'none';
    }

    function hasAnyUploadError(){
        return !!document.querySelector('.upload-error[style*="block"]');
    }

    function setButtonsDisabled(disabled){
        const clsDisabled = 'opacity-60 cursor-not-allowed';
        [btnSave, btnConvert].forEach(btn => {
            if(!btn) return;
            btn.disabled = disabled;
            if(disabled){
                btn.classList.add(...clsDisabled.split(' '));
            } else {
                btn.classList.remove(...clsDisabled.split(' '));
            }
        });

        if(globalUploadError){
            globalUploadError.classList.toggle('hidden', !disabled);
        }
    }

    function validateOneFileInput(input){
        const kind = input.dataset.kind; // photo | video
        const selId = input.dataset.sel;
        const file = input.files && input.files[0] ? input.files[0] : null;

        clearInlineError(kind, selId);

        if(!file){
            // no file selected => ok
            return true;
        }

        const sizeMB = bytesToMB(file.size);
        const maxMB = (kind === 'photo') ? MAX_PHOTO_MB : MAX_VIDEO_MB;

        if(sizeMB > maxMB){
            showInlineError(kind, selId, `Fichier trop grand: ${sizeMB.toFixed(1)}MB. Max autorisé: ${maxMB}MB.`);
            input.value = ''; // clear file
            return false;
        }

        // type check
        if(kind === 'photo' && !file.type.startsWith('image/')){
            showInlineError(kind, selId, 'Format invalide. Merci de choisir une image (JPG/PNG/WEBP).');
            input.value = '';
            return false;
        }

        if(kind === 'video' && !file.type.startsWith('video/')){
            showInlineError(kind, selId, 'Format invalide. Merci de choisir une vidéo (MP4/MOV/AVI).');
            input.value = '';
            return false;
        }

        return true;
    }

    function validateAllFilesAndToggleButtons(){
        const inputs = form.querySelectorAll('input[type="file"][data-kind][data-sel]');
        let ok = true;

        inputs.forEach(inp => {
            // validate current selected file only (if any)
            if(inp.files && inp.files.length){
                if(!validateOneFileInput(inp)) ok = false;
            }
        });

        // if any error in DOM => disable
        const anyErr = hasAnyUploadError() || !ok;
        setButtonsDisabled(anyErr);
        return !anyErr;
    }

    // validate on change
    document.addEventListener('DOMContentLoaded', () => {
        const inputs = form.querySelectorAll('input[type="file"][data-kind][data-sel]');
        inputs.forEach(inp => {
            inp.addEventListener('change', () => {
                validateOneFileInput(inp);
                validateAllFilesAndToggleButtons();
            });
        });

        // initial state
        validateAllFilesAndToggleButtons();

        // prevent submit if errors exist
        form.addEventListener('submit', (e) => {
            const ok = validateAllFilesAndToggleButtons();
            if(!ok){
                e.preventDefault();
                const firstErr = document.querySelector('.upload-error[style*="block"]');
                if(firstErr) firstErr.scrollIntoView({behavior:'smooth', block:'center'});
            }
        });
    });
</script>
@endsection