@extends('technicien.menu')

@section('content')
@php /** @var \App\Models\Technician $technician */ @endphp

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Title -->
    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="text-sm text-zinc-500">Paramètres</div>
        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Modifier mon profil</h1>
        <p class="text-zinc-600 mt-1">Mettez à jour vos informations et sécurisez votre compte.</p>
    </div>

    <!-- Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-5 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('technicien.profile.update') }}" method="POST"
          class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6 md:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-extrabold text-zinc-700 mb-2">Nom complet *</label>
                <input type="text" name="name" value="{{ old('name', $technician->name) }}" required
                       class="w-full px-4 py-4 rounded-2xl border border-zinc-200
                              focus:outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-300">
                @error('name')
                    <p class="text-rose-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email readonly -->
            <div class="md:col-span-2">
                <label class="block text-sm font-extrabold text-zinc-700 mb-2">Email</label>
                <input type="email" value="{{ $technician->email }}"
                       class="w-full px-4 py-4 rounded-2xl border border-zinc-200 bg-zinc-50 text-zinc-600 cursor-not-allowed"
                       readonly>
                <p class="text-xs text-zinc-500 mt-2">L’email ne peut pas être modifié.</p>
            </div>

            <!-- Phone -->
            <div class="md:col-span-1">
                <label class="block text-sm font-extrabold text-zinc-700 mb-2">Téléphone *</label>
                <input type="text" name="phone" value="{{ old('phone', $technician->phone) }}" required
                       class="w-full px-4 py-4 rounded-2xl border border-zinc-200
                              focus:outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-300">
                @error('phone')
                    <p class="text-rose-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="md:col-span-1">
                <label class="block text-sm font-extrabold text-zinc-700 mb-2">Nouveau mot de passe</label>
                <input type="password" name="password" placeholder="Laisser vide pour ne pas changer"
                       class="w-full px-4 py-4 rounded-2xl border border-zinc-200
                              focus:outline-none focus:ring-4 focus:ring-orange-100 focus:border-orange-300">
                @error('password')
                    <p class="text-rose-600 text-sm mt-2 font-semibold">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <!-- Actions -->
        <div class="pt-6 border-t border-zinc-200 flex flex-col sm:flex-row gap-3 sm:justify-between">
            <a href="{{ route('technicien.profile') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-4 rounded-2xl border border-zinc-200 hover:bg-zinc-50 font-extrabold transition">
                Annuler
            </a>

            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold shadow-soft transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-2M7 12l5-5 5 5M12 7v13" />
                </svg>
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
