{{-- ===================== --}}
{{-- ✅ technicians/edit.blade.php --}}
{{-- ===================== --}}
@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-0 py-6">

    <div class="flex items-start justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Modifier Technicien</h1>
            <p class="text-sm text-slate-500 mt-1">Mettre à jour les informations.</p>
        </div>

        <a href="{{ route('admin.technicians') }}"
           class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-slate-900 hover:bg-black text-white shadow-sm transition"
           title="Retour">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd" d="M15.78 19.28a.75.75 0 0 1-1.06 0l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 1 1 1.06 1.06L10.31 12l5.47 5.47a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
            </svg>
        </a>
    </div>

    {{-- ✅ Global errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
            <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-2xl bg-white border border-rose-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="font-extrabold">Veuillez corriger les erreurs</div>
                    <ul class="mt-2 list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.technicians.update', $technician->id) }}" method="POST"
          class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-5 sm:p-7 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- ✅ Nom --}}
                <label class="block">
                    <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wide">Nom</span>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $technician->name) }}"
                           class="mt-2 w-full px-4 py-3 rounded-2xl border bg-white
                           {{ $errors->has('name') ? 'border-rose-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500' }}">

                    @error('name')
                        <p class="mt-2 text-sm font-semibold text-rose-700">{{ $message }}</p>
                    @enderror
                </label>

                {{-- ✅ Téléphone --}}
                <label class="block">
                    <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wide">Téléphone</span>

                    <input type="text"
                           name="phone"
                           value="{{ old('phone', $technician->phone) }}"
                           class="mt-2 w-full px-4 py-3 rounded-2xl border bg-white
                           {{ $errors->has('phone') ? 'border-rose-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500' }}">

                    @error('phone')
                        <p class="mt-2 text-sm font-semibold text-rose-700">{{ $message }}</p>
                    @enderror
                </label>

            </div>

            {{-- ✅ Email (désactivé) --}}
            <label class="block">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wide">Email (non modifiable)</span>

                <input type="email"
                       value="{{ $technician->email }}"
                       disabled
                       class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 bg-slate-100 text-slate-600 cursor-not-allowed">

                <p class="mt-2 text-xs text-slate-500">
                    Pour des raisons de sécurité, l’email ne peut pas être modifié.
                </p>
            </label>

            {{-- ✅ Password --}}
            <label class="block">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wide">Nouveau mot de passe (optionnel)</span>

                <div x-data="{ show:false }" class="mt-2 relative">
                    <input :type="show ? 'text' : 'password'"
                           name="password"
                           class="w-full pr-12 px-4 py-3 rounded-2xl border bg-white
                           {{ $errors->has('password') ? 'border-rose-300 focus:ring-2 focus:ring-rose-500 focus:border-rose-500' : 'border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500' }}"
                           placeholder="Laisser vide si inchangé">

                    <button type="button" @click="show=!show"
                            class="absolute inset-y-0 right-2 my-2 w-10 rounded-xl hover:bg-slate-100 flex items-center justify-center">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-slate-600">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/>
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-indigo-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.59a2 2 0 102.827 2.828m1.414-1.414A6 6 0 006 12m.318-2.498A10.05 10.05 0 0112 6c4.994 0 9.163 3.676 9.682 8.502"/>
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="mt-2 text-sm font-semibold text-rose-700">{{ $message }}</p>
                @enderror
            </label>
        </div>

        <div class="p-5 sm:p-7 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-end">
            <a href="{{ route('admin.technicians') }}"
               class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-extrabold hover:bg-slate-100 transition">
                Annuler
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm transition">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection