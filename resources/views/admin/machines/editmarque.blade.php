{{-- ✅ COOL + RESPONSIVE (mobile first) --}}
@extends('admin.layout')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-0">

    {{-- Back --}}
    <a href="{{ route('admin.machines') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm transition mb-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        Retour
    </a>

    {{-- Card --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="px-5 sm:px-8 py-5 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Modifier Marque
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Modifiez le nom, l’image, les caractères et le prix.
                    </p>
                </div>

                <div class="shrink-0 hidden sm:flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-emerald-600">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('marques.update', $marque->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="p-5 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Nom --}}
            <div class="space-y-2">
                <label class="text-xs font-extrabold text-slate-600 uppercase tracking-wide">Nom</label>
                <input type="text"
                       name="nom"
                       value="{{ old('nom', $marque->nom) }}"
                       placeholder="Nom du marque"
                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       required>
            </div>

            {{-- Image actuelle + upload --}}
            <div class="rounded-3xl border border-slate-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="text-base font-extrabold text-slate-900">Image</div>
                    <div class="text-sm text-slate-500">Vous pouvez garder l’image actuelle ou la remplacer.</div>
                </div>

                <div class="p-4 sm:p-6 space-y-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $marque->image) }}"
                             alt="{{ $marque->nom }}"
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl object-cover border border-slate-200 shadow-sm">
                        <div class="min-w-0">
                            <div class="font-extrabold text-slate-900 truncate">{{ $marque->nom }}</div>
                            <div class="text-sm text-slate-500 mt-1">Image actuelle</div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-extrabold text-slate-600 uppercase tracking-wide">Remplacer l’image</label>
                        <input type="file"
                               name="image"
                               accept="image/*"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white">
                    </div>
                </div>
            </div>

            {{-- Caractères --}}
            <div class="space-y-2">
                <label class="text-xs font-extrabold text-slate-600 uppercase tracking-wide">Caractères</label>
                <input type="text"
                       name="caractere"
                       value="{{ old('caractere', implode(',', $marque->caractere ?? [])) }}"
                       placeholder="Ex: A+, 220V, Inox..."
                       class="w-full rounded-2xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="text-xs text-slate-500">Séparez par des virgules.</p>
            </div>

            {{-- Prix --}}
            <div class="space-y-2">
                <label class="text-xs font-extrabold text-slate-600 uppercase tracking-wide">Prix</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">DH</span>
                    <input type="number"
                           name="prix"
                           min="0"
                           value="{{ old('prix', $marque->prix) }}"
                           placeholder="Prix"
                           class="w-full pl-12 rounded-2xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('admin.machines') }}"
                   class="w-full sm:w-auto inline-flex justify-center px-5 py-3 rounded-2xl border border-slate-200 bg-white text-slate-700 font-extrabold hover:bg-slate-50 transition">
                    Annuler
                </a>

                <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm transition">
                    Mettre à jour
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
