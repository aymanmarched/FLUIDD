{{-- resources/views/admin/machines/index.blade.php --}}
@extends('admin.layout')

@section('content')

    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-4 sm:p-8" x-data="{ tab: 'machine' }">

        <h1 class="text-2xl sm:text-3xl flex items-center font-extrabold mb-6">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 mr-3 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
            Gestion des Machines
        </h1>

        {{-- Tabs --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-8">
            <button @click="tab='machine'"
                :class="tab=='machine' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-semibold transition">
                Remplacer mon Machine
            </button>

            <button @click="tab='type'"
                :class="tab=='type' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-semibold transition">
                Marques du mes machine
            </button>
        </div>

        {{-- ========================= --}}
        {{-- TAB 1 — MACHINES --}}
        {{-- ========================= --}}
        <div x-show="tab=='machine'" class="space-y-6">

            <h2 class="text-lg sm:text-xl font-extrabold text-blue-700">Ajouter une Machine</h2>

            <form id="machineForm" action="{{ route('admin.machines.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="name" placeholder="Nom"
                        class="w-full border border-slate-300 rounded-xl p-3 bg-white" required>

                    <input type="file" name="image" class="w-full border border-slate-300 rounded-xl p-3 bg-white"
                        accept="image/*" required>
                </div>

                {{-- Garantie --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4">
                    <div class="flex items-center justify-between">
                        <label class="text-base sm:text-lg font-extrabold text-slate-900">Garantie (période)</label>
                        <span class="text-xs text-slate-500 hidden sm:inline">Ex: 1 an 6 mois • 30 jours</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-3">
                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1 block">Années</label>
                            <input type="number" name="garantie_years" min="0" placeholder="0"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-xl bg-white">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1 block">Mois</label>
                            <input type="number" name="garantie_months" min="0" max="12" placeholder="0"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-xl bg-white">
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-slate-500 mb-1 block">Jours</label>
                            <input type="number" name="garantie_days" min="0" max="365" placeholder="0"
                                class="w-full px-3 py-2.5 border border-slate-300 rounded-xl bg-white">
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 mt-3 sm:hidden">
                        Exemple : <span class="font-semibold">1 an 6 mois</span> ou <span class="font-semibold">30
                            jours</span>
                    </p>
                </div>

                {{-- Marques --}}
                <label class="block font-bold text-slate-800 mt-2">Sélectionner les marques :</label>

                <div id="typesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($marques as $marque)
                        <label
                            class="flex items-center gap-3 p-3 border border-slate-200 rounded-2xl cursor-pointer bg-white hover:bg-slate-50 transition">
                            <input type="checkbox" name="marques[]" value="{{ $marque->id }}"
                                class="marque-checkbox accent-blue-600 w-5 h-5">
                            <div class="min-w-0">
                                <p class="font-extrabold text-slate-900 truncate">{{ $marque->nom }}</p>
                                <p class="text-sm text-slate-500">{{ $marque->prix }} MAD</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <button
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm">
                    Ajouter
                </button>
            </form>

            <hr class="my-6">

            <h2 class="text-lg sm:text-xl font-extrabold text-blue-700">Liste des Machines</h2>

            {{-- ===== Mobile Cards ===== --}}
            <div class="sm:hidden space-y-4">
                @foreach($machines as $machine)
                    @php
                        $totalDays = (int) ($machine->garantie_period_days ?? 0);
                        $years = intdiv($totalDays, 365);
                        $remainingAfterYears = $totalDays % 365;
                        $months = intdiv($remainingAfterYears, 30);
                        $days = $remainingAfterYears % 30;

                        $parts = [];
                        if ($years > 0)
                            $parts[] = $years . ' ' . ($years === 1 ? 'an' : 'ans');
                        if ($months > 0)
                            $parts[] = $months . ' mois';
                        if ($days > 0 || empty($parts))
                            $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');
                        $garantieText = implode(' ', $parts);
                    @endphp

                    <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        {{-- Top accent bar --}}
                        <div class="h-1.5 bg-gradient-to-r from-indigo-500 via-sky-500 to-emerald-500"></div>

                        <div class="p-4">
                            {{-- Header --}}
                            <div class="flex items-start gap-3">
                                <div class="relative shrink-0">
                                    <div
                                        class="absolute -inset-1 rounded-3xl bg-gradient-to-br from-indigo-200 via-sky-200 to-emerald-200 blur-md opacity-60">
                                    </div>
                                    <img src="{{ asset('storage/' . $machine->image) }}" alt="{{ $machine->name }}"
                                        class="relative w-16 h-16 rounded-3xl object-cover border border-slate-200 shadow-sm">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <h3 class="text-base font-extrabold text-slate-900 leading-snug break-words">
                                                {{ $machine->name }}
                                            </h3>

                                            <div
                                                class="mt-2 inline-flex items-center gap-2 rounded-2xl bg-slate-50 border border-slate-200 px-3 py-1.5">
                                                <span
                                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Garantie</span>
                                                <span class="text-sm font-extrabold text-slate-900">{{ $garantieText }}</span>
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <!-- <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ route('admin.machines.edit', $machine->id) }}"
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm active:scale-95 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.machines.destroy', $machine->id) }}" method="POST"
                                              onsubmit="confirmDeleteMachine(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm active:scale-95 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div> -->
                                    </div>
                                </div>
                            </div>

                            {{-- Marques section --}}
                            <div class="mt-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-xs font-extrabold text-slate-600 uppercase tracking-wide">
                                        Marques ({{ $machine->marques->count() }})
                                    </div>
                                </div>

                                @if($machine->marques->count())
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($machine->marques as $mq)
                                            <span
                                                class="inline-flex items-center gap-2 rounded-2xl bg-emerald-50 border border-emerald-100 px-3 py-1.5">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                                <span class="text-xs font-extrabold text-slate-800">{{ $mq->nom }}</span>
                                                <span class="text-xs font-bold text-emerald-700">• {{ $mq->prix }} DH</span>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-2 text-sm text-slate-500 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                        Aucune marque liée à cette machine.
                                    </div>
                                @endif
                            </div>

                            {{-- Quick actions row (optional feel) --}}
                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <a href="{{ route('admin.machines.edit', $machine->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-800 shadow-sm active:scale-[0.99] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-amber-600">
                                        <path
                                            d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                        <path
                                            d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>
                                    Modifier
                                </a>

                                <button type="button" onclick="confirmDeleteMachine(this.closest('form'))"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-rose-600 hover:bg-rose-700 px-4 py-3 text-sm font-extrabold text-white shadow-sm active:scale-[0.99] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4">
                                        <path fill-rule="evenodd"
                                            d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ===== Desktop Table ===== --}}
            <div class="hidden sm:block">
                <table class="w-full border-collapse mt-4">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-3 border">Image</th>
                            <th class="p-3 border">Nom</th>
                            <th class="p-3 border">Marque</th>
                            <th class="p-3 border">Garantie Période</th>
                            <th class="p-3 border">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($machines as $machine)
                            @php
                                $totalDays = (int) ($machine->garantie_period_days ?? 0);
                                $years = intdiv($totalDays, 365);
                                $remainingAfterYears = $totalDays % 365;
                                $months = intdiv($remainingAfterYears, 30);
                                $days = $remainingAfterYears % 30;

                                $parts = [];
                                if ($years > 0)
                                    $parts[] = $years . ' ' . ($years === 1 ? 'an' : 'ans');
                                if ($months > 0)
                                    $parts[] = $months . ' mois';
                                if ($days > 0 || empty($parts))
                                    $parts[] = $days . ' ' . ($days === 1 ? 'jour' : 'jours');
                                $garantieText = implode(' ', $parts);
                            @endphp

                            <tr class="border hover:bg-gray-50">
                                <td class="p-3 border">
                                    <img src="{{ asset('storage/' . $machine->image) }}" alt="{{ $machine->name }}"
                                        class="w-20 h-20 object-cover rounded shadow">
                                </td>

                                <td class="p-3 border font-semibold">{{ $machine->name }}</td>

                                <td class="p-3 border text-sm text-gray-800">
                                    @foreach($machine->marques as $mq)
                                        <div><strong>{{ $mq->nom }}</strong> — {{ $mq->prix }} Dh</div>
                                    @endforeach
                                </td>

                                <td class="p-3 border font-semibold">{{ $garantieText }}</td>

                                <td class="p-3 border">
                                    <div class="flex justify-end gap-2 items-center">
                                        <a href="{{ route('admin.machines.edit', $machine->id) }}"
                                            class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg shadow transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="size-6">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path
                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.machines.destroy', $machine->id) }}" method="POST"
                                            onsubmit="confirmDeleteMachine(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg shadow transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                    class="size-6">
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

        {{-- ========================= --}}
        {{-- TAB 2 — MARQUES --}}
        {{-- ========================= --}}
        <div x-show="tab=='type'" class="space-y-6" x-cloak>

            <h2 class="text-lg sm:text-xl font-extrabold text-blue-700">Ajouter une Marque</h2>

            <form action="{{ route('admin.marques.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4 bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="file" name="image" class="w-full border border-slate-300 rounded-xl p-3 bg-white"
                        accept="image/*" required>

                    <input type="text" name="nom" placeholder="Nom du Marque"
                        class="w-full border border-slate-300 rounded-xl p-3 bg-white" required>
                </div>

                <input type="text" name="caractere" placeholder="Caractères séparés par des virgules"
                    class="w-full border border-slate-300 rounded-xl p-3 bg-white" required>

                <input type="number" step="0.01" name="prix" placeholder="Prix" min="0" maxlength="10"
                    class="w-full border border-slate-300 rounded-xl p-3 bg-white" required>

                <button
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-bold shadow-sm">
                    Ajouter
                </button>
            </form>

            <hr class="my-6">

            <h2 class="text-lg sm:text-xl font-extrabold text-blue-700">Liste des Marques</h2>

            {{-- Mobile cards --}}
            <div class="sm:hidden space-y-4">
                @foreach($marques as $marque)
                    <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                        <div class="h-1.5 bg-gradient-to-r from-amber-500 via-rose-500 to-indigo-500"></div>

                        <div class="p-4">
                            <div class="flex items-start gap-3">
                                <div class="relative shrink-0">
                                    <div
                                        class="absolute -inset-1 rounded-3xl bg-gradient-to-br from-amber-200 via-rose-200 to-indigo-200 blur-md opacity-60">
                                    </div>
                                    <img src="{{ asset('storage/' . $marque->image) }}" alt="{{ $marque->nom }}"
                                        class="relative w-16 h-16 rounded-3xl object-cover border border-slate-200 shadow-sm">
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="text-base font-extrabold text-slate-900 truncate">{{ $marque->nom }}
                                            </div>

                                            <div
                                                class="mt-2 inline-flex items-center gap-2 rounded-2xl bg-slate-50 border border-slate-200 px-3 py-1.5">
                                                <span
                                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Prix</span>
                                                <span class="text-sm font-extrabold text-slate-900">{{ $marque->prix }}
                                                    DH</span>
                                            </div>
                                        </div>

                                        <!-- <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ route('admin.marques.edit', $marque->id) }}"
                                           class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm active:scale-95 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.marques.destroy', $marque->id) }}" method="POST"
                                              onsubmit="confirmDeleteMarque(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm active:scale-95 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                                          clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div> -->
                                    </div>
                                </div>
                            </div>

                            {{-- Caractères --}}
                            <div class="mt-4 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                <div class="text-xs font-extrabold text-slate-600 uppercase tracking-wide mb-2">
                                    Caractères
                                </div>

                                @if(is_array($marque->caractere) && count($marque->caractere))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($marque->caractere as $c)
                                            <span
                                                class="inline-flex items-center rounded-2xl bg-white border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-800">
                                                {{ $c }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-sm text-slate-500">-</div>
                                @endif
                            </div>
{{-- Quick actions row (optional feel) --}}
                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <a href="{{ route('admin.marques.edit', $marque->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-800 shadow-sm active:scale-[0.99] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-amber-600">
                                        <path
                                            d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                        <path
                                            d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                    </svg>
                                    Modifier
                                </a>
<form action="{{ route('admin.marques.destroy', $marque->id) }}" method="POST"
                                            onsubmit="confirmDeleteMarque(this)">
                                            @csrf
                                            @method('DELETE')
                                <button type="button" onclick="confirmDeleteMarque(this.closest('form'))"
                                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-rose-600 hover:bg-rose-700 px-4 py-3 text-sm font-extrabold text-white shadow-sm active:scale-[0.99] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4">
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
                @endforeach
            </div>

            {{-- Desktop table --}}
            <div class="hidden sm:block">
                <table class="w-full border-collapse mt-6">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-3 border">Image</th>
                            <th class="p-3 border">Nom du Marque</th>
                            <th class="p-3 border">Caractères</th>
                            <th class="p-3 border">Prix</th>
                            <th class="p-3 border">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($marques as $marque)
                            <tr class="border hover:bg-gray-50">
                                <td class="p-3 border">
                                    <img src="{{ asset('storage/' . $marque->image) }}" alt="{{ $marque->nom }}"
                                        class="w-20 h-20 object-cover rounded shadow">
                                </td>

                                <td class="p-3 border font-semibold">{{ $marque->nom }}</td>

                                <td class="p-3 border text-sm leading-6">
                                    @if(is_array($marque->caractere))
                                        @foreach($marque->caractere as $c)
                                            • {{ $c }} <br>
                                        @endforeach
                                    @endif
                                </td>

                                <td class="p-3 border font-medium text-gray-700">{{ $marque->prix }} DH</td>

                                <td class="p-3 border">
                                    <div class="flex justify-end gap-2 items-center">
                                        <a href="{{ route('admin.marques.edit', $marque->id) }}"
                                            class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg shadow transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="size-6">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path
                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.marques.destroy', $marque->id) }}" method="POST"
                                            onsubmit="confirmDeleteMarque(this)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg shadow transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                    class="size-6">
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

    <script>
        document.getElementById('machineForm').addEventListener('submit', function (e) {
            const checkboxes = document.querySelectorAll('input[name="marques[]"]');
            const checked = Array.from(checkboxes).some(cb => cb.checked);

            if (!checked) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une marque.');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDeleteMachine(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Supprimer ce machine ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        }

        function confirmDeleteMarque(form) {
            event.preventDefault();
            Swal.fire({
                title: 'Supprimer ce marque ?',
                text: "Cette action est irréversible !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        }
    </script>




@endsection