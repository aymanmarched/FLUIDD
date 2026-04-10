@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-3xl">
        <div class="rounded-2xl border border-white/60 bg-white/85 p-5 shadow-xl backdrop-blur md:p-6">

            <div class="mb-6 text-center">
                <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    Étape 5
                </span>

                <h2 class="text-2xl font-extrabold text-slate-900 md:text-3xl">
                    Choisissez une date et une heure
                </h2>

                <p class="mt-3 text-sm leading-6 text-slate-700">
                    Sélectionnez la date souhaitée pour afficher les heures disponibles.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('service.entretien.entretenir.step5.store', $client->id) }}"
                  id="reservationForm">
                @csrf
                <input type="hidden" name="reference" value="{{ $reference }}">

                <label class="mb-2 block text-sm font-extrabold text-slate-700">Date souhaitée</label>
                <input type="date"
                       name="date_souhaite"
                       id="datePicker"
                       class="mb-5 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:ring-4 focus:ring-blue-100"
                       required>

                <label class="mb-3 block text-sm font-extrabold text-slate-700">Heure souhaitée</label>
                <div id="hoursContainer" class="grid grid-cols-2 gap-3 sm:grid-cols-4 mb-5">
                    <p class="col-span-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-center text-sm text-slate-500">
                        Veuillez choisir une date pour voir les heures disponibles.
                    </p>
                </div>

                <input type="hidden" name="hour" id="selectedHour">

                <div class="mt-6 text-center">
                    <button type="submit"
                            id="submitBtn"
                            disabled
                            class="rounded-xl bg-slate-400 px-8 py-3.5 text-sm font-extrabold text-white cursor-not-allowed transition">
                        Valider
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
const datePicker = document.getElementById('datePicker');
const hoursContainer = document.getElementById('hoursContainer');
const selectedHourInput = document.getElementById('selectedHour');
const submitBtn = document.getElementById('submitBtn');

axios.defaults.headers.common['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
datePicker.setAttribute('min', new Date().toISOString().split('T')[0]);

datePicker.addEventListener('change', async () => {
    const date = datePicker.value;
    if (!date) return;

    selectedHourInput.value = '';
    submitBtn.disabled = true;
    submitBtn.className = 'rounded-xl bg-slate-400 px-8 py-3.5 text-sm font-extrabold text-white cursor-not-allowed transition';
    hoursContainer.innerHTML = '';

    try {
        const referenceInput = document.querySelector('input[name="reference"]');
        const response = await axios.post('{{ route('getAvailableHours') }}', {
            date: date,
            reference: referenceInput.value
        });

        const data = response.data;

        if (data.allTaken) {
            hoursContainer.innerHTML =
                '<p class="col-span-full rounded-xl border border-red-200 bg-red-50 px-4 py-4 text-center font-bold text-red-600">Cette journée est entièrement réservée.</p>';
            return;
        }

        data.hours.forEach(h => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = h.hour;

            btn.className = h.available
                ? 'rounded-xl bg-green-500 px-3 py-3 text-sm font-bold text-white transition hover:bg-green-600'
                : 'rounded-xl bg-slate-200 px-3 py-3 text-sm font-bold text-slate-500 cursor-not-allowed';

            if (h.available) {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('#hoursContainer button')
                        .forEach(b => b.classList.remove('ring-4', 'ring-blue-400'));

                    btn.classList.add('ring-4', 'ring-blue-400');
                    selectedHourInput.value = h.hour;

                    submitBtn.disabled = false;
                    submitBtn.className = 'rounded-xl bg-primary px-8 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700';
                });
            }

            hoursContainer.appendChild(btn);
        });

    } catch (error) {
        console.error(error);
        hoursContainer.innerHTML =
            '<p class="col-span-full rounded-xl border border-red-200 bg-red-50 px-4 py-4 text-center text-red-600">Erreur lors du chargement des heures.</p>';
    }
});
</script>
@endsection