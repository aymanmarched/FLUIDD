{{-- resources/views/admin/entretenir/editentretenir.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Modifier entretenir')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">

    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h1 class="flex items-center gap-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 break-words">
                Modifier entretenir
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-7 h-7 sm:w-9 sm:h-9 text-emerald-600">
                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                </svg>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Mettez à jour les informations et les types associés.</p>
        </div>

        <a href="{{ route('admin.entretenir') }}"
           class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                  bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
            ← Retour
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
        <form action="{{ route('entretenir.update', $entretenir->id) }}" method="POST" enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nom</label>
                <input type="text" name="name" value="{{ old('name', $entretenir->name) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-300 outline-none">
            </div>

            {{-- Machine remplacer --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Choisir la machine (Remplacer)
                </label>

                <select name="remplacer_machine_id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                               focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-300 outline-none">
                    <option value="">-- Choisir une machine --</option>
                    @foreach(\App\Models\Machine::orderBy('name')->get() as $rm)
                        <option value="{{ $rm->id }}"
                            {{ old('remplacer_machine_id', $entretenir->remplacer_machine_id) == $rm->id ? 'selected' : '' }}>
                            {{ $rm->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Image --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">Image</div>
                        <div class="text-xs text-slate-500 mt-0.5">Vous pouvez remplacer l’image actuelle.</div>
                    </div>
                </div>

                <div class="mt-3 flex flex-col sm:flex-row gap-4 items-start">
                    <div class="bg-white border border-slate-200 rounded-2xl p-3 shadow-sm">
                        <div class="text-xs font-semibold text-slate-500 mb-2">Image actuelle</div>
                        <img src="{{ asset('storage/' . $entretenir->image) }}"
                             class="w-28 h-28 sm:w-32 sm:h-32 object-cover rounded-xl border border-slate-200"
                             alt="image actuelle">
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nouvelle image (optionnel)</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white">
                        <p class="text-xs text-slate-500 mt-1">PNG/JPG recommandé.</p>
                    </div>
                </div>
            </div>

            {{-- Types --}}
            <div>
                <div class="flex items-center justify-between gap-3 mb-2">
                    <label class="block text-sm font-semibold text-slate-700">Sélectionner les Types d’Équipement</label>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700">
                        {{ $types->count() }} types
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($types as $type)
                        @php
                            $checked = in_array($type->id, $entretenir->types->pluck('id')->toArray());
                        @endphp

                        <label class="flex items-start gap-3 p-3 rounded-2xl border border-slate-200 bg-white
                                      hover:bg-slate-50 transition cursor-pointer">
                            <input type="checkbox" name="type_ids[]" value="{{ $type->id }}"
                                   class="mt-1 h-5 w-5"
                                   {{ $checked ? 'checked' : '' }}>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-800 break-words">{{ $type->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $type->prix }} DH</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="hidden sm:flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.entretenir') }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-xl
                          bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                    Annuler
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-xl
                               bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm">
                    Mettre à jour
                </button>
            </div>

            {{-- Mobile sticky actions --}}
            <div class="sm:hidden sticky bottom-3">
                <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-2xl p-3 shadow-sm">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.entretenir') }}"
                           class="flex-1 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                                  bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold">
                            Annuler
                        </a>

                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                                       bg-emerald-600 hover:bg-emerald-700 text-white font-semibold">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
