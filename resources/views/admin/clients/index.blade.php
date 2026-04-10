{{-- resources/views/admin/clients/index.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Clients')

@section('content')
    <div class="mb-6 sm:mb-8">
        <div class="flex items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 rounded-2xl bg-indigo-50 border border-indigo-200 items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                  d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                  clip-rule="evenodd" />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>
                    </span>
                    Liste des Clients
                </h1>
                <p class="text-slate-500 text-sm mt-1">Gestion des clients et consultations rapides.</p>
            </div>
        </div>
    </div>

    {{-- Desktop / Tablet table --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Nom</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Téléphone</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Ville</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Inscription</th>
                    <th class="px-6 py-3 text-right font-semibold text-slate-600">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @foreach($clients as $client)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-900 font-semibold">
                            {{ $client->nom }} {{ $client->prenom }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $client->telephone }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                {{ $client->email ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-700">
                            {{ $client->ville->name ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $client->created_at->format('d/m/Y - H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.clients.show', $client->id) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition"
                                   title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
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
                {{ $clients->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden space-y-3">
        @foreach($clients as $client)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-base font-extrabold text-slate-900 leading-tight">
                            {{ $client->nom }} {{ $client->prenom }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Inscription: {{ $client->created_at->format('d/m/Y - H:i') }}
                        </div>
                    </div>

                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $client->telephone }}
                    </span>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-2 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500">Email</span>
                        <span class="text-slate-800 font-semibold truncate max-w-[60%]">{{ $client->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-500">Ville</span>
                        <span class="text-slate-800 font-semibold">{{ $client->ville->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.clients.show', $client->id) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                        </svg>
                        Voir
                    </a>
                </div>
            </div>
        @endforeach

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
                {{ $clients->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
