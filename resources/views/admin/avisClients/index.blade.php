{{-- resources/views/admin/avisclients/index.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Avis Clients')

@section('content')
    <div class="mb-6 sm:mb-8">
        <div class="flex items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 rounded-2xl bg-amber-50 border border-amber-200 items-center justify-center">
                        <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                clip-rule="evenodd" />
                        </svg>
                    </span>
                    Liste des Avis des Clients
                </h1>
                <p class="text-sm text-slate-500 mt-1">Consultez, ouvrez ou supprimez les avis reçus.</p>
            </div>
        </div>
    </div>

    {{-- Desktop / Tablet table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Nom complet</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Téléphone</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Évaluation</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Date d'ajout</th>
                    <th class="px-6 py-3 text-right font-semibold text-slate-600">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @foreach($avis_clients as $avis_client)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-700">{{ $avis_client->id }}</td>

                        <td class="px-6 py-4 text-slate-900 font-semibold">
                            {{ $avis_client->nom }} {{ $avis_client->prenom }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $avis_client->telephone }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $avis_client->stars ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                            @endfor
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $avis_client->created_at->format('d/m/Y - H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                {{-- View --}}
                                <a href="{{ route('admin.AvisClients.show', $avis_client->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition"
                                   title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd"
                                            d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('admin.AvisClients.destroy', $avis_client->id) }}" method="POST"
                                      onsubmit="return confirmDelete(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition"
                                            title="Supprimer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 bg-white border-t border-slate-200">
            <div class="flex items-center gap-2">
                <span class="text-slate-600 text-sm">Afficher</span>
                <select onchange="window.location='?perPage='+this.value"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 bg-white">
                    <option value="10" @if(request('perPage') == 10) selected @endif>10 lignes</option>
                    <option value="25" @if(request('perPage') == 25) selected @endif>25 lignes</option>
                    <option value="50" @if(request('perPage') == 50) selected @endif>50 lignes</option>
                </select>
            </div>

            <div>
                {{ $avis_clients->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden space-y-3">
        @foreach($avis_clients as $avis_client)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm text-slate-500">ID #{{ $avis_client->id }}</div>
                        <div class="text-base font-extrabold text-slate-900 leading-tight">
                            {{ $avis_client->nom }} {{ $avis_client->prenom }}
                        </div>
                    </div>

                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $avis_client->telephone }}
                    </span>
                </div>

                <div class="mt-3 flex items-center justify-between">
                    <div class="text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $avis_client->stars ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                        @endfor
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $avis_client->created_at->format('d/m/Y - H:i') }}
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.AvisClients.show', $avis_client->id) }}"
                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                            <path fill-rule="evenodd"
                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        Voir
                    </a>

                    <form action="{{ route('admin.AvisClients.destroy', $avis_client->id) }}" method="POST"
                          onsubmit="return confirmDelete(this)" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold shadow-sm transition">
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
        @endforeach

        {{-- Mobile pagination + perPage --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex flex-col gap-3">
            <div class="flex items-center gap-2">
                <span class="text-slate-600 text-sm">Afficher</span>
                <select onchange="window.location='?perPage='+this.value"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2 bg-white w-full">
                    <option value="10" @if(request('perPage') == 10) selected @endif>10 lignes</option>
                    <option value="25" @if(request('perPage') == 25) selected @endif>25 lignes</option>
                    <option value="50" @if(request('perPage') == 50) selected @endif>50 lignes</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                {{ $avis_clients->links('vendor.pagination.tailwind') }}
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
