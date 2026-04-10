{{-- resources/views/admin/entretenir/editype.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Modifier type')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">

    {{-- Header --}}
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h1 class="flex items-center gap-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 break-words">
                Modifier Type d’Équipement
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                     class="w-7 h-7 sm:w-9 sm:h-9 text-emerald-600">
                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                </svg>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Modifier les informations du type (nom, caractères, prix).</p>
        </div>

        <a href="{{ route('admin.entretenir') }}"
           class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                  bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
            ← Retour
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
        <form action="{{ route('type.update', $type->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nom du type</label>
                <input type="text" name="name" value="{{ old('name', $type->name) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-300 outline-none">
            </div>

            {{-- Caracteres --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Caractères</label>
                <input type="text" name="caracteres"
                       value="{{ old('caracteres', implode(',', $type->caracteres ?? [])) }}"
                       placeholder="Ex: Rapide,Silencieux,Économique"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-300 outline-none">
                <p class="text-xs text-slate-500 mt-1">Séparez par des virgules.</p>
            </div>

            {{-- Prix --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Prix (DH)</label>
                <input type="number" min="0" step="0.01" name="prix" value="{{ old('prix', $type->prix) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white
                              focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-300 outline-none">
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
