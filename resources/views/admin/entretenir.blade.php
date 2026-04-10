{{-- resources/views/admin/entretenir/index.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Gestion d’Entretien')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-6" x-data="{ tab: 'machine' }">

    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h1 class="flex items-center gap-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 break-words">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-lime-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                </svg>
                Gestion d’Entretien
            </h1>
            <p class="text-sm text-slate-500 mt-1">Gérez les machines d’entretien et les types d’équipement.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-2 mb-6">
        <div class="grid grid-cols-2 gap-2">
            <button type="button"
                @click="tab='machine'"
                :class="tab==='machine' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                class="px-4 py-2.5 rounded-xl font-semibold transition text-sm sm:text-base">
                Entretenir mon Machine
            </button>

            <button type="button"
                @click="tab='type'"
                :class="tab==='type' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                class="px-4 py-2.5 rounded-xl font-semibold transition text-sm sm:text-base">
                Type d’Équipement
            </button>
        </div>
    </div>

    {{-- ========================= --}}
    {{-- TAB 1 — MACHINE --}}
    {{-- ========================= --}}
    <div x-show="tab==='machine'" class="space-y-6" x-cloak>

        {{-- Create card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Ajouter une Machine</h2>
            </div>

            <form id="machineForm" action="{{ route('entretenir.store') }}" method="POST" enctype="multipart/form-data"
                  class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nom</label>
                        <input type="text" name="name" placeholder="Nom" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                      focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Machine</label>
                        <select name="remplacer_machine_id" required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                       focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                            <option value="">-- Choisir une machine --</option>
                            @foreach(\App\Models\Machine::orderBy('name')->get() as $rm)
                                <option value="{{ $rm->id }}">{{ $rm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <label class="block text-sm font-semibold text-slate-700">Types d’Équipement</label>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700">
                            Sélection obligatoire
                        </span>
                    </div>

                    <div id="typesContainer" class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(\App\Models\TypeEquipement::all() as $type)
                            <label class="flex items-start gap-3 p-3 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 transition cursor-pointer">
                                <input type="checkbox" name="type_ids[]" value="{{ $type->id }}" class="mt-1 h-5 w-5">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-800 break-words">{{ $type->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $type->prix }} DH</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Actions --}}
                <div class="hidden sm:flex items-center justify-end gap-3 pt-1">
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                        Ajouter
                    </button>
                </div>

                {{-- Mobile sticky submit --}}
                <div class="sm:hidden sticky bottom-3">
                    <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-2xl p-3 shadow-sm">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                            Ajouter
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- List card --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Liste des Gestions d’Entretien</h2>
                <!-- <p class="text-sm text-slate-500 mt-1">Affichage optimisé mobile (cartes) + desktop (table).</p> -->
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden divide-y divide-slate-200">
                @foreach(\App\Models\EntretenirMonMachine::with(['types', 'remplacerMachine'])->get() as $m)
                    <div class="p-4">
                        <div class="flex gap-3">
                            <img src="{{ asset('storage/' . $m->image) }}"
                                 class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shrink-0"
                                 alt="image">

                            <div class="min-w-0 flex-1">
                                <div class="text-base font-extrabold text-slate-900 break-words">{{ $m->name }}</div>
                                <div class="text-sm text-slate-600 mt-0.5">
                                    Machine: <span class="font-semibold text-slate-800">{{ $m->remplacerMachine?->name ?? '—' }}</span>
                                </div>

                                <div class="mt-2 space-y-1">
                                    @foreach($m->types as $type)
                                        <div class="text-xs rounded-xl px-2.5 py-1 bg-slate-50 border border-slate-200 text-slate-700">
                                            <span class="font-semibold">{{ $type->name }}</span> — {{ $type->prix }} DH
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.entretenir.edit', $m->id) }}"
                                       class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                            <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.entretenir.destroy', $m->id) }}" method="POST"
                                          onsubmit="confirmDeleteEntretien(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nom</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Machine</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Types</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach(\App\Models\EntretenirMonMachine::with(['types', 'remplacerMachine'])->get() as $m)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <img src="{{ asset('storage/' . $m->image) }}"
                                         class="w-20 h-20 object-cover rounded-2xl border border-slate-200"
                                         alt="image">
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $m->name }}</td>

                                <td class="px-6 py-4 text-slate-700">{{ $m->remplacerMachine?->name ?? '—' }}</td>

                                <td class="px-6 py-4 text-sm text-slate-700">
                                    @foreach($m->types as $type)
                                        <div class="text-sm">
                                            <span class="font-semibold">{{ $type->name }}</span> — {{ $type->prix }} DH
                                        </div>
                                    @endforeach
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.entretenir.edit', $m->id) }}"
                                           class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.entretenir.destroy', $m->id) }}" method="POST"
                                              onsubmit="confirmDeleteEntretien(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
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
            </div>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- TAB 2 — TYPE --}}
    {{-- ========================= --}}
    <div x-show="tab==='type'" class="space-y-6" x-cloak>

        {{-- Create type --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-4">Ajouter un Type d’Équipement</h2>

            <form action="{{ route('type.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nom du type</label>
                        <input type="text" name="name" placeholder="Nom du type"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                      focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Caractères</label>
                        <input type="text" name="caracteres" placeholder="Ex: Rapide,Silencieux"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                      focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Prix</label>
                        <input type="number" name="prix" placeholder="Prix" min="0" maxlength="10"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                                      focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                    </div>
                </div>

                <div class="hidden sm:flex justify-end">
                    <button class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                        Ajouter
                    </button>
                </div>

                <div class="sm:hidden sticky bottom-3">
                    <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-2xl p-3 shadow-sm">
                        <button class="w-full px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- List types --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900">Liste des Types d’Équipement</h2>
                <!-- <p class="text-sm text-slate-500 mt-1">Mobile (cartes) + desktop (table).</p> -->
            </div>

            {{-- Mobile cards --}}
            <div class="sm:hidden divide-y divide-slate-200">
                @foreach(\App\Models\TypeEquipement::all() as $t)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-base font-extrabold text-slate-900 break-words">{{ $t->name }}</div>
                                <div class="text-sm text-slate-600 mt-1">
                                    Prix: <span class="font-semibold text-slate-800">{{ $t->prix }} DH</span>
                                </div>

                                <div class="mt-2">
                                    <div class="text-xs font-semibold text-slate-500 mb-1">Caractères</div>
                                    @if(is_array($t->caracteres) && count($t->caracteres))
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($t->caracteres as $c)
                                                <span class="text-xs px-2.5 py-1 rounded-full bg-slate-50 border border-slate-200 text-slate-700">
                                                    {{ $c }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-sm text-slate-500">—</div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('admin.entretenir.type.edit', $t->id) }}"
                                   class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                    </svg>
                                </a>

                                <form action="{{ route('admin.entretenir.type.destroy', $t->id) }}" method="POST"
                                      onsubmit="confirmDeletetype(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-slate-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Nom du Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Caractères</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-600">Prix</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-slate-600">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach(\App\Models\TypeEquipement::all() as $t)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $t->name }}</td>

                                <td class="px-6 py-4 text-sm text-slate-700">
                                    @if(is_array($t->caracteres) && count($t->caracteres))
                                        @foreach($t->caracteres as $c)
                                            <div>• {{ $c }}</div>
                                        @endforeach
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $t->prix }} DH</td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('admin.entretenir.type.edit', $t->id) }}"
                                           class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.entretenir.type.destroy', $t->id) }}" method="POST"
                                              onsubmit="confirmDeletetype(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
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
            </div>

        </div>
    </div>

</div>

{{-- Validate at least one type selected --}}
<script>
document.getElementById('machineForm')?.addEventListener('submit', function (e) {
    const checkboxes = document.querySelectorAll('input[name="type_ids[]"]');
    const checked = Array.from(checkboxes).some(cb => cb.checked);

    if (!checked) {
        e.preventDefault();
        alert('Veuillez sélectionner au moins un type d’équipement.');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDeleteEntretien(form) {
    event.preventDefault();

    Swal.fire({
        title: 'Supprimer ce gestion d’Entretien ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
}

function confirmDeletetype(form) {
    event.preventDefault();

    Swal.fire({
        title: 'Supprimer ce Types d’Équipement ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer'
    }).then((result) => {
        if (result.isConfirmed) form.submit();
    });
}
</script>
@endsection
