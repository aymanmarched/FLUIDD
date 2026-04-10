{{-- resources/views/admin/avisclients/show.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Détails avis')

@section('content')
    <div class="max-w-3xl mx-auto px-1 sm:px-0">
        <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Détails de l'avis client
            </h2>
            <p class="text-sm text-slate-500 mt-1">Informations du client et contenu de l'avis.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 text-slate-700">

                <div class="flex items-start gap-2">
                    <div class="text-xs font-semibold uppercase text-slate-500 mt-0.5">Date d'ajout</div>
                    <div class="font-bold text-slate-900">{{ $avis_clients->created_at->format('d/m/Y - H:i') }}</div>
                </div>

                <div class="flex items-start gap-2">
                    <div class="text-xs font-semibold uppercase text-slate-500 mt-0.5">Nom</div>
                    <div class="font-bold text-slate-900">{{ $avis_clients->nom }} {{ $avis_clients->prenom }}</div>
                </div>

                <div class="flex items-start gap-2">
                    <div class="text-xs font-semibold uppercase text-slate-500 mt-0.5">Téléphone</div>
                    <div class="font-bold text-slate-900">{{ $avis_clients->telephone }}</div>
                </div>

                <div class="flex items-start gap-2">
                    <div class="text-xs font-semibold uppercase text-slate-500 mt-0.5">Évaluation</div>
                    <div class="text-lg font-extrabold">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $avis_clients->stars ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                        @endfor
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <div class="text-xs font-semibold uppercase text-slate-500 mb-1">Avis</div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-slate-900 font-semibold leading-relaxed">
                        {{ $avis_clients->message }}
                    </div>
                </div>
            </div>

            <div class="mt-6 sm:mt-8 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                <a href="{{ route('admin.AvisClients') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Retour
                </a>

                <form action="{{ route('admin.AvisClients.destroy', $avis_clients->id) }}" method="POST"
                      onsubmit="return confirmDelete(this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                clip-rule="evenodd" />
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(form) {
            event.preventDefault();

            Swal.fire({
                title: 'Supprimer cet avis client ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#4f46e5',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
            return false;
        }
    </script>
@endsection
