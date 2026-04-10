{{-- resources/views/admin/clientsremplacer/index.blade.php --}}
@extends('admin.layout')

@section('page_title', "Commandes remplacement")

@section('content')
    <div class="mb-6 sm:mb-8">
        <div class="flex items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 rounded-2xl bg-indigo-50 border border-indigo-200 items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </span>
                    Liste des Commandes Remplacement
                </h1>
                <p class="text-slate-500 text-sm mt-1">Gestion des clients et consultations rapides.</p>
            </div>
        </div>
    </div>

    {{-- Desktop/tablet --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Référence</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Client</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Téléphone</th>
                    <th class="px-6 py-3 text-left font-semibold text-slate-600">Inscription</th>
                    <th class="px-6 py-3 text-right font-semibold text-slate-600">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @foreach($commandes as $c)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-900 font-semibold">{{ $c->reference }}</td>

                        <td class="px-6 py-4 text-slate-800 font-semibold">
                            {{ $c->client?->nom ?? '—' }} {{ $c->client?->prenom ?? '' }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $c->client?->telephone ?? '—' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-slate-500">
                            {{ $c->client?->created_at?->format('d/m/Y - H:i') ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end">
                                <a href="{{ route('admin.clientsremplacer.show', $c->reference) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition"
                                   title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75
                                            12.001 3.75c4.97 0 9.185 3.223
                                            10.675 7.69.12.362.12.752 0 1.113-1.487
                                            4.471-5.705 7.697-10.677
                                            7.697-4.97 0-9.186-3.223-10.675-7.69a1.762
                                            1.762 0 0 1 0-1.113ZM17.25 12a5.25
                                            5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
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
                {{ $commandes->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden space-y-3">
        @foreach($commandes as $c)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs text-slate-500">Référence</div>
                        <div class="text-base font-extrabold text-slate-900 break-words">{{ $c->reference }}</div>

                        <div class="mt-3 text-xs text-slate-500">Client</div>
                        <div class="text-sm font-semibold text-slate-800 truncate">
                            {{ $c->client?->nom ?? '—' }} {{ $c->client?->prenom ?? '' }}
                        </div>
                    </div>

                    <span class="shrink-0 inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $c->client?->telephone ?? '—' }}
                    </span>
                </div>

                <div class="mt-3 flex items-center justify-between gap-3 text-sm">
                    <span class="text-slate-500">Inscription</span>
                    <span class="text-slate-800 font-semibold">
                        {{ $c->client?->created_at?->format('d/m/Y') ?? '-' }}
                    </span>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.clientsremplacer.show', $c->reference) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition">
                        Voir détails
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
                {{ $commandes->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
@endsection
