@extends('client.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Remplacement</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Modifier la commande : <span class="text-sky-700">{{ $reference }}</span>
                </h2>
                <p class="text-slate-600 mt-1">Modifiez les marques choisies et la réservation.</p>
            </div>

            <a href="{{ route('client.remplacers.show', $reference) }}"
               class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition">
                Annuler
            </a>
        </div>
    </div>

    {{-- ERRORS --}}
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl p-5">
            <div class="font-extrabold mb-2">Veuillez corriger :</div>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('client.remplacers.update', $reference) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- MACHINES --}}
        @foreach($selections as $s)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-sm text-slate-500">Machine</div>
                            <div class="text-xl font-extrabold text-slate-900 truncate">{{ $s->machine->name }}</div>
                        </div>

                        <label class="inline-flex items-center gap-2 text-rose-700 font-semibold bg-rose-50 border border-rose-100 px-4 py-2 rounded-2xl">
                            <input type="checkbox" name="remove[{{ $s->id }}]" value="1" class="rounded accent-rose-600">
                            Supprimer cette machine
                        </label>
                    </div>
                </div>

                <div class="p-6">
                    <div class="text-sm font-extrabold text-slate-700 mb-3">Choisir une marque</div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($s->machine->marques as $marque)
                            <label class="relative p-5 rounded-2xl border cursor-pointer transition hover:bg-gray-50
                                {{ $s->marque_id == $marque->id ? 'border-sky-300 bg-sky-50' : 'border-gray-200 bg-white' }}">
                                <input type="radio"
                                       name="marque_id[{{ $s->id }}]"
                                       value="{{ $marque->id }}"
                                       class="sr-only peer"
                                       {{ $s->marque_id == $marque->id ? 'checked' : '' }}>

                                <span class="absolute top-4 right-4 hidden peer-checked:flex items-center justify-center
                                             w-8 h-8 rounded-2xl bg-sky-600 text-white font-extrabold shadow-soft">
                                    ✓
                                </span>

                                <div class="font-extrabold text-slate-900 text-lg">
                                    {{ $marque->nom }}
                                </div>

                                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-sm border
                                            bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                                    {{ number_format($marque->prix, 2) }} MAD
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        {{-- RESERVATION --}}
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
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('client.remplacers.show', $reference) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition">
                Annuler
            </a>

            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-soft transition">
                Enregistrer
            </button>
        </div>

    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const datePicker = document.getElementById('datePicker');
    const hoursContainer = document.getElementById('hoursContainer');
    const selectedHourInput = document.getElementById('selectedHour');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';

    window.addEventListener('DOMContentLoaded', () => {
        if (datePicker.value) fetchHours(datePicker.value, selectedHourInput.value);
    });

    datePicker.addEventListener('change', () => {
        if (!datePicker.value) return;
        selectedHourInput.value = '';
        hoursContainer.innerHTML =
            '<p class="col-span-full text-slate-500 text-center bg-gray-50 border border-gray-200 rounded-2xl p-4">Chargement...</p>';
        fetchHours(datePicker.value);
    });

    async function fetchHours(date, selectedHour = null) {
        try {
            const response = await axios.post('{{ route("service.remplacer.getAvailableHours") }}', { date });
            const data = response.data;

            hoursContainer.innerHTML = '';

            if (data.allTaken) {
                hoursContainer.innerHTML =
                    '<p class="col-span-full text-rose-700 font-extrabold text-center bg-rose-50 border border-rose-100 rounded-2xl p-4">Cette journée est entièrement réservée.</p>';
                return;
            }

            data.hours.forEach(h => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = h.hour;

                btn.className = `
                    px-3 py-3 rounded-2xl border font-extrabold transition
                    ${h.available
                        ? 'bg-emerald-600 text-white hover:bg-emerald-700 border-emerald-600'
                        : 'bg-gray-100 text-slate-400 border-gray-200 cursor-not-allowed'}
                `;

                if (h.available) {
                    btn.addEventListener('click', () => {
                        document.querySelectorAll('#hoursContainer button')
                            .forEach(b => b.classList.remove('ring-4', 'ring-sky-200', 'border-sky-400'));

                        btn.classList.add('ring-4', 'ring-sky-200', 'border-sky-400');
                        selectedHourInput.value = h.hour;
                    });
                }

                if (selectedHour && h.hour === selectedHour) {
                    btn.classList.add('ring-4', 'ring-sky-200', 'border-sky-400');
                    selectedHourInput.value = h.hour;
                }

                hoursContainer.appendChild(btn);
            });

        } catch (error) {
            console.error(error);
            hoursContainer.innerHTML =
                '<p class="col-span-full text-rose-700 text-center bg-rose-50 border border-rose-100 rounded-2xl p-4">Erreur lors du chargement des heures.</p>';
        }
    }
</script>
@endsection
