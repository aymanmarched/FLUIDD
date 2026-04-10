{{-- resources/views/admin/admins/create.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Ajouter un admin')

@section('content')
@php
    $auth = auth()->user();
    $isSuper = $auth && $auth->role === 'superadmin';
@endphp

<div class="max-w-3xl mx-auto px-1 sm:px-0">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Ajouter un utilisateur (Admin)
            </h1>
            <p class="text-sm text-slate-500 mt-1">Créer un nouvel administrateur.</p>
        </div>

        <a href="{{ route('admin.admins.index') }}"
           class="inline-flex justify-center px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
            ← Retour
        </a>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700">
            <p class="font-semibold mb-2">Veuillez corriger les erreurs :</p>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6">
        <form method="POST" action="{{ route('admin.admins.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white
                              focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white
                              focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white
                              focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">
                <p class="text-xs text-slate-500 mt-1">Ex: +2126XXXXXXXX ou 06XXXXXXXX</p>
            </div>

           <div>
    <label class="block text-sm font-semibold text-slate-700 mb-1">Mot de passe</label>

    <div class="relative">

        <input type="password"
               name="password"
               id="password"
               required
               minlength="6"
               class="w-full px-4 py-3 pr-12 border border-slate-200 rounded-xl bg-white
                      focus:ring-2 focus:ring-indigo-600/20 focus:border-indigo-300 outline-none">

        <button type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-indigo-600">

            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>

            </svg>

            <svg id="eyeClose"
                 xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 hidden"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.24-3.568M6.223 6.223A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.973 9.973 0 01-4.132 5.411M15 12a3 3 0 01-4.24 2.83M9.88 9.88A3 3 0 0115 12M3 3l18 18"/>

            </svg>

        </button>

    </div>

    <p class="text-xs text-slate-500 mt-1">Minimum 6 caractères.</p>
</div>

            {{-- ROLE --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="block text-sm font-semibold text-slate-700">Rôle</label>

                    @if(!$isSuper)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700">
                            Super Admin requis
                        </span>
                    @endif
                </div>

                <div class="mt-3 space-y-2">
                    <label class="flex items-start gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
                        <input type="radio" name="role" value="admin"
                               {{ old('role', 'admin') === 'admin' ? 'checked' : '' }}>
                        <div class="leading-tight">
                            <div class="text-sm font-semibold text-slate-800">Admin</div>
                            <div class="text-xs text-slate-500">Peut voir la liste uniquement.</div>
                        </div>
                    </label>

                    @if($isSuper)
                        <label class="flex items-start gap-3 p-3 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 transition">
                            <input type="radio" name="role" value="superadmin"
                                   {{ old('role') === 'superadmin' ? 'checked' : '' }}>
                            <div class="leading-tight">
                                <div class="text-sm font-semibold text-slate-800">Super Admin</div>
                                <div class="text-xs text-slate-500">Peut ajouter / modifier / supprimer les admins.</div>
                            </div>
                        </label>
                        <p class="text-xs text-slate-500">Seul un Super Admin peut créer un Super Admin.</p>
                    @else
                        <input type="hidden" name="role" value="admin">
                        <p class="text-xs text-slate-500">Seul un Super Admin peut créer un Super Admin.</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-2">
                <a href="{{ route('admin.admins.index') }}"
                   class="inline-flex justify-center px-5 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                    Annuler
                </a>

                <button type="submit"
                        class="inline-flex justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
<script>
function togglePassword() {

    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClose = document.getElementById('eyeClose');

    if (input.type === "password") {
        input.type = "text";
        eyeOpen.classList.add("hidden");
        eyeClose.classList.remove("hidden");
    } else {
        input.type = "password";
        eyeOpen.classList.remove("hidden");
        eyeClose.classList.add("hidden");
    }
}
</script>
@endsection
