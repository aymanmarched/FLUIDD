{{-- resources/views/admin/clients/entretiencammnde.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Commande Entretien')

@section('content')
    <div class="max-w-5xl mx-auto">

        <div class="mb-4 sm:mb-6">
            <a href="{{ route('admin.clients.show', $client->id) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Retour
            </a>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 mb-6">
            Commande :
            <span class="text-indigo-700">{{ $reference }}</span>
        </h2>

        {{-- Selected packs --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-extrabold text-slate-900">Packs Sélectionnés</h3>
                <p class="text-sm text-slate-500 mt-0.5">Type d'entretien et prix.</p>
            </div>

            <div class="p-4 sm:p-6 space-y-4">
                @foreach($selections as $sel)
                    <div class="p-4 sm:p-5 border border-slate-200 bg-slate-50 rounded-2xl hover:shadow-sm transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="text-base sm:text-lg font-extrabold text-slate-900">
                                    {{ $sel->machine->name }}
                                </h4>
                                <p class="text-sm text-slate-700 mt-1">
                                    <span class="font-semibold text-slate-800">Type d'entretien :</span>
                                    {{ $sel->type->name ?? '-' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-slate-500">Prix</div>
                                <div class="text-sm font-extrabold text-emerald-700">
                                    {{ $sel->type->prix ?? '-' }} MAD
                                </div>
                            </div>
                        </div>

                        @if(is_array($sel->type->caracteres ?? []))
                            <div class="mt-3 space-y-1 text-sm text-slate-700">
                                @foreach($sel->type->caracteres as $c)
                                    <div class="flex items-start gap-2">
                                        <span class="text-indigo-600">•</span>
                                        <span>{{ $c }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Machine details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg font-extrabold text-slate-900">Détails des Machines</h3>
                <p class="text-sm text-slate-500 mt-0.5">Photos / vidéos envoyées par le client.</p>
            </div>

            <div class="p-4 sm:p-6 space-y-6">
                @foreach($client->machineDetails as $detail)
                    <div class="p-4 sm:p-5 border border-slate-200 bg-slate-50 rounded-2xl">
                        <p class="text-slate-800 font-semibold">
                            <span class="text-slate-500">Machine :</span>
                            {{ $detail->machine->machine ?? '—' }}
                        </p>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- PHOTO --}}
                            @if($detail->photo)
                                <button type="button"
                                        class="text-left bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow transition"
                                        onclick="openMediaModal('{{ asset('storage/' . $detail->photo) }}', 'image')">
                                    <div class="flex items-center gap-2 font-extrabold text-slate-900 mb-3">
                                        <span class="inline-flex h-9 w-9 rounded-xl bg-indigo-50 border border-indigo-200 items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" class="w-5 h-5 text-indigo-700">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                                            </svg>
                                        </span>
                                        Photo
                                    </div>
                                    <img src="{{ asset('storage/' . $detail->photo) }}"
                                         class="w-full rounded-xl border border-slate-200 object-cover h-44">
                                    <div class="mt-2 text-xs text-slate-500">Appuyez pour agrandir</div>
                                </button>
                            @endif

                            {{-- VIDEO --}}
                            @if($detail->video)
                                <button type="button"
                                        class="text-left bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow transition"
                                        onclick="openMediaModal('{{ asset('storage/' . $detail->video) }}', 'video')">
                                    <div class="flex items-center gap-2 font-extrabold text-slate-900 mb-3">
                                        <span class="inline-flex h-9 w-9 rounded-xl bg-rose-50 border border-rose-200 items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" class="w-5 h-5 text-rose-700">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15.75 10.5l4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9A2.25 2.25 0 0 0 15.75 16.5v-9A2.25 2.25 0 0 0 13.5 5.25h-9A2.25 2.25 0 0 0 2.25 7.5v9A2.25 2.25 0 0 0 4.5 18.75Z" />
                                            </svg>
                                        </span>
                                        Vidéo
                                    </div>
                                    <video class="w-full rounded-xl border border-slate-200 h-44 object-cover" muted>
                                        <source src="{{ asset('storage/' . $detail->video) }}" type="video/mp4">
                                    </video>
                                    <div class="mt-2 text-xs text-slate-500">Appuyez pour lire</div>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Reservation --}}
        @if($reservation)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
                <div class="px-5 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-lg font-extrabold text-slate-900">Réservation</h3>
                </div>

                <div class="p-4 sm:p-6 text-slate-700 space-y-2">
                    <p><span class="font-semibold text-slate-800">Date souhaitée :</span> {{ $reservation->date_souhaite }}</p>
                    <p><span class="font-semibold text-slate-800">Créneau :</span> {{ substr($reservation->hour, 0, 5) }}</p>
                </div>
            </div>
        @endif

        {{-- Media modal --}}
        <div id="mediaModal" class="fixed inset-0 bg-black/80 hidden z-50 items-center justify-center p-4">
            <button class="absolute top-5 right-5 text-white text-3xl font-bold focus:outline-none"
                    onclick="closeMediaModal()">&times;</button>

            <div id="mediaContentWrapper" class="max-h-full max-w-full flex items-center justify-center relative">
                <img id="modalImage" class="max-h-[85vh] max-w-[95vw] rounded-2xl hidden">
                <video id="modalVideo" class="max-h-[85vh] max-w-[95vw] rounded-2xl hidden" controls autoplay></video>
            </div>
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
                    video.pause();
                    video.currentTime = 0;
                } else {
                    video.src = src;
                    video.classList.remove('hidden');
                    img.classList.add('hidden');
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeMediaModal() {
                const modal = document.getElementById('mediaModal');
                const video = document.getElementById('modalVideo');

                modal.classList.add('hidden');
                modal.classList.remove('flex');

                video.pause();
                video.currentTime = 0;
                video.src = '';
            }

            document.getElementById('mediaModal').addEventListener('click', function (e) {
                const content = document.getElementById('mediaContentWrapper');
                if (!content.contains(e.target)) closeMediaModal();
            });
        </script>
    </div>
@endsection
