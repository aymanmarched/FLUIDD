@extends('admin.layout')

@section('content')
    @php
        // sécurité si variables pas passées
        $settings = $settings ?? null;
        $socials = $socials ?? collect();
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-r from-sky-50 via-white to-indigo-50"></div>
            <div class="relative p-6 md:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <div
                            class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase text-slate-500">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Administration
                        </div>
                        <h1 class="mt-2 text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900">
                            Paramètres du site
                        </h1>
                        <p class="mt-1 text-slate-600">
                            Gestion du header et footer (logo, adresse, contact, réseaux sociaux).
                        </p>
                    </div>

                    <a href="/" target="_blank"
                        class="inline-flex items-center justify-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:bg-slate-50 font-extrabold text-slate-800 transition">
                        <span
                            class="h-10 w-10 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </span>
                        <span>Voir le site</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash global --}}
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-4 text-emerald-800">
                <div class="font-extrabold">Succès</div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        {{-- =========================================================
        1) INFORMATIONS GÉNÉRALES
        ========================================================== --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <div class="font-extrabold text-slate-900">Informations générales</div>
                    <div class="text-sm text-slate-500">Nom de la société + logo (header).</div>
                </div>
                <span
                    class="hidden sm:inline-flex px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-600">Header</span>
            </div>

            {{-- Aperçu --}}
            <div class="px-6 pt-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 flex items-center gap-4">
                    <div
                        class="h-14 w-14 rounded-2xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden">
                        @if(!empty($settings?->logo))
                            <img src="{{ asset('storage/' . $settings->logo) }}" class="h-12 w-12 object-contain" alt="Logo">
                        @else
                            <div class="text-xs text-slate-400">Logo</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Aperçu</div>
                        <div class="font-extrabold text-slate-900 truncate">
                            {{ $settings->company_name ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.site-settings.update.general') }}" enctype="multipart/form-data"
                class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-extrabold text-slate-700">Nom de la société</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $settings->company_name ?? '') }}"
                        placeholder="Ex : Climatisation Maroc" class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between gap-3">
                        <label class="block text-sm font-extrabold text-slate-700">Logo</label>
                        <span class="text-xs text-slate-500">Max 2MB · JPG/PNG/WEBP</span>
                    </div>

                    <input type="file" name="logo" accept="image/*" id="logoInput" class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white
                                  focus:outline-none focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                    <p id="logoError" class="text-sm text-rose-700 mt-2 hidden"></p>
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button id="saveGeneral" type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-sm transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- =========================================================
        2) ADRESSE + GOOGLE MAPS
        ========================================================== --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <div class="font-extrabold text-slate-900">Adresse & Google Maps</div>
                    <div class="text-sm text-slate-500">Adresse affichée + iframe Google Maps.</div>
                </div>
                <span
                    class="hidden sm:inline-flex px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-600">Footer</span>
            </div>

            {{-- Aperçu --}}
            <div class="px-6 pt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Adresse enregistrée</div>
                    <div class="mt-2 text-slate-900 font-extrabold">
                        {{ $settings->footer_address_line1 ?? '—' }}
                    </div>
                    <div class="text-slate-700">
                        @if(!empty($settings?->footer_address_line2)) {{ $settings->footer_address_line2 }} · @endif
                        {{ $settings->footer_city ?? '' }} @if(!empty($settings?->footer_country)) ,
                        {{ $settings->footer_country }} @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Map enregistrée</div>
                    <div class="mt-3 rounded-2xl overflow-hidden border border-slate-200 bg-white">
                        @if(!empty($settings?->footer_map_embed_url))
                            <iframe class="w-full h-44" loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="{{ $settings->footer_map_embed_url }}"></iframe>
                        @else
                            <div class="h-44 flex items-center justify-center text-slate-500">Non configurée</div>
                        @endif
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.site-settings.update.address') }}"
                class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                @csrf

                <div class="space-y-3">
                    <input name="footer_address_line1"
                        value="{{ old('footer_address_line1', $settings->footer_address_line1 ?? '') }}"
                        placeholder="Adresse ligne 1"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">

                    <input name="footer_address_line2"
                        value="{{ old('footer_address_line2', $settings->footer_address_line2 ?? '') }}"
                        placeholder="Adresse ligne 2 (optionnel)"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input name="footer_city" value="{{ old('footer_city', $settings->footer_city ?? '') }}"
                            placeholder="Ville"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                        <input name="footer_country" value="{{ old('footer_country', $settings->footer_country ?? '') }}"
                            placeholder="Pays"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-extrabold text-slate-700">Google Maps (src de l’iframe)</label>
                    <textarea name="footer_map_embed_url" rows="6"
                        placeholder="Colle uniquement le src (Google Maps → Share → Embed map)"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">{{ old('footer_map_embed_url', $settings->footer_map_embed_url ?? '') }}</textarea>
                    <p class="text-xs text-slate-500">Exemple : https://www.google.com/maps/embed?pb=...</p>
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-sm transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- =========================================================
        3) CONTACT
        ========================================================== --}}
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <div class="font-extrabold text-slate-900">Contact</div>
                    <div class="text-sm text-slate-500">Email + téléphone (footer).</div>
                </div>
                <span
                    class="hidden sm:inline-flex px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-600">Footer</span>
            </div>

            {{-- Aperçu --}}
            <div class="px-6 pt-6">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Email</div>
                        <div class="mt-1 font-extrabold text-slate-900">{{ $settings->footer_email ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Téléphone</div>
                        <div class="mt-1 font-extrabold text-slate-900">{{ $settings->footer_phone ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.site-settings.update.contact') }}"
                class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-extrabold text-slate-700">Email</label>
                    <input name="footer_email" value="{{ old('footer_email', $settings->footer_email ?? '') }}"
                        placeholder="ex: contact@site.com"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-extrabold text-slate-700">Téléphone</label>
                    <input name="footer_phone" value="{{ old('footer_phone', $settings->footer_phone ?? '') }}"
                        placeholder="ex: +212 6xx xx xx xx"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300">
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold shadow-sm transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        {{-- =========================================================
        4) RÉSEAUX SOCIAUX
        ========================================================== --}}

        {{-- =========================================================
    4) RÉSEAUX SOCIAUX (NEW UX)
========================================================== --}}
<div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
            <div class="font-extrabold text-slate-900">Réseaux sociaux</div>
            <div class="text-sm text-slate-500">Liste + édition rapide + suppression + ajout.</div>
        </div>
        <span class="hidden sm:inline-flex px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-600">
            Footer
        </span>
    </div>

    {{-- Déjà enregistrés --}}
    <div class="p-6" x-data="{ openId: null }">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="text-xs text-slate-500 font-bold uppercase tracking-wider">Déjà enregistrés</div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($socials as $soc)
                    @php $color = $soc->color ?: '#111827'; @endphp

                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start gap-3">
                            @php
    $rawColor = trim((string) ($soc->color ?: ''));
    $isGradient = str_contains(strtolower($rawColor), 'gradient');
@endphp

<span
    class="social-icon w-10 h-10 rounded-2xl border border-slate-200 flex items-center justify-center"
    style="
        {{ $isGradient
            ? "background: {$rawColor}; color: #ffffff;"
            : "background: transparent; color: {$rawColor};" }}
    ">
    {!! $soc->icon_svg !!}
</span>
<style>
  /* ✅ ensures nested svg uses currentColor */
  .social-icon svg,
  .social-icon svg path { fill: currentColor; stroke: currentColor; }
</style>
                            <div class="min-w-0 flex-1">
                                <div class="font-extrabold truncate" style="color: {{ $color }};">
                                    {{ $soc->name }}
                                </div>
                                <div class="text-xs truncate" style="color: {{ $color }}; opacity:.7;">
                                    {{ $soc->url }}
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-xs font-extrabold px-2 py-1 rounded-full border
                                        {{ $soc->is_enabled ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-slate-100 border-slate-200 text-slate-600' }}">
                                        {{ $soc->is_enabled ? 'Actif' : 'Inactif' }}
                                    </span>

                                    <span class="text-xs font-semibold text-slate-500">
                                        Ordre: {{ $soc->sort_order }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button type="button"
                                        @click="openId = (openId === {{ $soc->id }} ? null : {{ $soc->id }})"
                                        class="px-3 py-2 rounded-xl bg-slate-900 text-white font-extrabold hover:bg-slate-800 transition">
                                    Edit
                                </button>

                                <form method="POST"
                                      action="{{ route('admin.site-settings.socials.destroy', $soc->id) }}"
                                      onsubmit="return confirm('Supprimer ce réseau social ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full px-3 py-2 rounded-xl bg-rose-50 text-rose-700 font-extrabold border border-rose-100 hover:bg-rose-100 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Inline edit form (ONLY for this card) --}}
                        <div x-show="openId === {{ $soc->id }}" x-transition class="mt-4">
                            <form method="POST"
                                  action="{{ route('admin.site-settings.socials.update', $soc->id) }}"
                                  class="rounded-2xl border border-slate-200 bg-slate-50 p-4 space-y-3">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input name="name" value="{{ old('name', $soc->name) }}"
                                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                           placeholder="Nom (ex: Facebook)">

                                    <input name="url" value="{{ old('url', $soc->url) }}"
                                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                           placeholder="URL">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <input name="color" value="{{ old('color', $soc->color) }}"
                                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                           placeholder="Couleur (ex: #1877F2)">

                                    <input type="number" min="0" name="sort_order"
                                           value="{{ old('sort_order', $soc->sort_order) }}"
                                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                           placeholder="Ordre">

                                    {{-- checkbox: send 0 always --}}
                                    <input type="hidden" name="is_enabled" value="0">
                                    <label class="flex items-center justify-between gap-2 px-4 py-3 rounded-2xl border border-slate-200 bg-white">
                                        <span class="font-extrabold text-slate-700">Actif</span>
                                        <input type="checkbox" name="is_enabled" value="1"
                                               class="h-5 w-5 rounded border-slate-300"
                                               {{ old('is_enabled', $soc->is_enabled) ? 'checked' : '' }}>
                                    </label>
                                </div>

                                <textarea rows="4" name="icon_svg"
                                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                                          placeholder="Code SVG (sans script)">{{ old('icon_svg', $soc->icon_svg) }}</textarea>

                                <div class="flex justify-end gap-2">
                                    <button type="button"
                                            @click="openId = null"
                                            class="px-5 py-2.5 rounded-2xl border border-slate-200 bg-white font-extrabold hover:bg-slate-50 transition">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                            class="px-5 py-2.5 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-extrabold transition">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-500">Aucun réseau social enregistré.</div>
                @endforelse
            </div>
        </div>

        {{-- Ajouter un réseau (ONE simple form) --}}
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div>
                    <div class="font-extrabold text-slate-900">Ajouter un réseau</div>
                    <div class="text-sm text-slate-500">Ajoute un nouveau lien (Facebook, Instagram...).</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.site-settings.socials.store') }}" class="space-y-3">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input name="name"
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           placeholder="Nom (ex: Facebook)">

                    <input name="url"
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           placeholder="URL (ex: https://facebook.com/...)">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input name="color" value="#1877F2"
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           placeholder="Couleur ou gradient (ex: #1877F2 ou linear-gradient(45deg, #833AB4, #FD1D1D, #FCAF45))">

                    <input type="number" min="0" name="sort_order" value="0"
                           class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                           placeholder="Ordre">

                    <input type="hidden" name="is_enabled" value="0">
                    <label class="flex items-center justify-between gap-2 px-4 py-3 rounded-2xl border border-slate-200 bg-white">
                        <span class="font-extrabold text-slate-700">Actif</span>
                        <input type="checkbox" name="is_enabled" value="1" checked
                               class="h-5 w-5 rounded border-slate-300">
                    </label>
                </div>

                <textarea rows="4" name="icon_svg"
                          class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white focus:ring-4 focus:ring-sky-100 focus:border-sky-300"
                          placeholder="Code SVG (sans script)"></textarea>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-extrabold transition">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    </div>

    <script>
        // validation logo (form général)
        const input = document.getElementById('logoInput');
        const errorBox = document.getElementById('logoError');
        const saveGeneral = document.getElementById('saveGeneral');
        const MAX = 2 * 1024 * 1024; // 2MB

        function setError(msg) {
            errorBox.textContent = msg;
            errorBox.classList.remove('hidden');
            saveGeneral.disabled = true;
            saveGeneral.classList.add('opacity-60', 'cursor-not-allowed');
        }
        function clearError() {
            errorBox.textContent = '';
            errorBox.classList.add('hidden');
            saveGeneral.disabled = false;
            saveGeneral.classList.remove('opacity-60', 'cursor-not-allowed');
        }

        input?.addEventListener('change', () => {
            clearError();
            const f = input.files?.[0];
            if (!f) return;
            if (f.size > MAX) {
                setError('Le fichier est trop grand. Maximum 2MB.');
                input.value = '';
            }
        });
    </script>
@endsection