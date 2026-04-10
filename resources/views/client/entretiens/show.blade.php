@extends('client.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Détails commande</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">
                    Commande : <span class="text-sky-700">{{ $reference }}</span>
                </h2>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <a href="{{ route('client.entretiens') }}"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Retour
                </a>

                <a href="{{ route('client.entretiens.edit', $reference) }}"
                   class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold shadow-soft transition w-full sm:w-auto">
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <!-- Packs -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="font-extrabold">Packs sélectionnés</div>
            <div class="text-sm text-slate-500">Détails des packs et caractéristiques</div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($selections as $sel)
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs text-slate-500">Machine</div>
                            <div class="text-lg font-extrabold truncate">{{ $sel->machine->name }}</div>
                            <div class="text-sm text-slate-700 mt-1">
                                <span class="font-semibold">Type :</span> {{ $sel->type->name ?? '-' }}
                            </div>
                        </div>

                        <div class="text-right">
                            <div class="text-xs text-slate-500">Prix</div>
                            <div class="font-extrabold text-emerald-700">
                                {{ optional($sel->type)->prix ?? '-' }} MAD
                            </div>
                        </div>
                    </div>

                    @if($sel->type && is_array($sel->type->caracteres))
                        <div class="mt-4 space-y-1 text-sm text-slate-700">
                            @foreach($sel->type->caracteres as $c)
                                <div class="flex items-start gap-2">
                                    <span class="text-sky-600">•</span>
                                    <span>{{ $c }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="px-6 pb-6">
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 text-right">
                <div class="text-sm text-emerald-800">Total</div>
                <div class="text-2xl font-extrabold text-emerald-900">
                    {{ number_format($total, 2) }} MAD
                </div>
            </div>
        </div>
    </div>

    <!-- Machine details + media -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="font-extrabold">Détails des machines</div>
            <div class="text-sm text-slate-500">Photos et vidéos associées</div>
        </div>

        <div class="p-6 space-y-6">
            @foreach($client->machineDetails->where('reference', $reference) as $detail)
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
                    <div class="text-sm text-slate-600">
                        <span class="font-semibold text-slate-900">Machine :</span> {{ $detail->machine->machine ?? '—' }}
                    </div>

                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if($detail->photo)
                            <button type="button"
                                    class="text-left rounded-2xl border border-gray-200 bg-white p-4 hover:shadow-soft transition"
                                    onclick="openMediaModal('{{ asset('storage/' . $detail->photo) }}', 'image')">
                                <div class="flex items-center gap-2 font-bold text-slate-700 mb-2">
                                    <span class="h-9 w-9 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center">📷</span>
                                    Photo
                                </div>
                                <img src="{{ asset('storage/' . $detail->photo) }}"
                                     class="w-full rounded-2xl border object-cover h-40">
                            </button>
                        @endif

                        @if($detail->video)
                            <button type="button"
                                    class="text-left rounded-2xl border border-gray-200 bg-white p-4 hover:shadow-soft transition"
                                    onclick="openMediaModal('{{ asset('storage/' . $detail->video) }}', 'video')">
                                <div class="flex items-center gap-2 font-bold text-slate-700 mb-2">
                                    <span class="h-9 w-9 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center">🎥</span>
                                    Vidéo
                                </div>
                                <video class="w-full rounded-2xl border h-40 object-cover" muted>
                                    <source src="{{ asset('storage/' . $detail->video) }}" type="video/mp4">
                                </video>
                            </button>
                        @endif
                    </div>

                    @if(!$detail->photo && !$detail->video)
                        <div class="mt-4 border border-dashed border-gray-200 rounded-2xl p-6 text-center text-slate-500">
                            Aucun média disponible pour cette machine.
                        </div>
                    @endif
                </div>
            @endforeach

            <!-- Modal -->
            <div id="mediaModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4">
                <button class="absolute top-5 right-5 text-white text-3xl font-bold" onclick="closeMediaModal()">&times;</button>

                <div id="mediaContentWrapper" class="max-h-[90vh] max-w-[95vw] flex items-center justify-center">
                    <img id="modalImage" class="max-h-[90vh] max-w-[95vw] rounded-2xl hidden">
                    <video id="modalVideo" class="max-h-[90vh] max-w-[95vw] rounded-2xl hidden" controls autoplay></video>
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

</div>

<script>
    function openMediaModal(src, type) {
        const modal = document.getElementById('mediaModal');
        const img = document.getElementById('modalImage');
        const video = document.getElementById('modalVideo');

        if (type === 'image') {
            img.src = src;
            img.classList.remove('hidden');
            video.classList.add('hidden');
        } else {
            video.src = src;
            video.classList.remove('hidden');
            img.classList.add('hidden');
        }
        modal.classList.remove('hidden');
    }

    function closeMediaModal() {
        const modal = document.getElementById('mediaModal');
        const video = document.getElementById('modalVideo');

        modal.classList.add('hidden');
        video.pause();
        video.currentTime = 0;
    }

    document.getElementById('mediaModal').addEventListener('click', function (e) {
        const content = document.getElementById('mediaContentWrapper');
        if (!content.contains(e.target)) closeMediaModal();
    });
</script>
@endsection
