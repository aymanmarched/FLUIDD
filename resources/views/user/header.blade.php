<!-- resources/views/home.blade.php -->
<!DOCTYPE html>
<html lang="fr">
@php
    use Illuminate\Support\Str;

    $companyName = $settings->company_name ?? config('app.name', 'Fluid');
    $logoPath = $settings->logo ?? null;

    $logoSrc = $logoPath
        ? (Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : asset('storage/' . $logoPath))
        : asset('images/logo.png');


    $iconEntretien = <<<'SVG'
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-primary">
          <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75Z" clip-rule="evenodd"/>
          <path d="M4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"/>
          <path d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z"/>
          <path fill-rule="evenodd" d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
        </svg>
        SVG;

    $iconGarantie = <<<'SVG'
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-secondary">
          <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
        </svg>
        SVG;

    $iconRemplacer = <<<'SVG'
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-rose-600">
          <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25Z"/>
          <path d="M13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z"/>
          <path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
          <path d="M15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z"/>
          <path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/>
        </svg>
        SVG;

    $iconReparation = <<<'SVG'
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-indigo-700">
          <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd"/>
        </svg>
        SVG;

    $menu = [
        'Entretien' => [
            ['label' => 'Entretenir ma maison', 'route' => 'service.show', 'icon' => $iconEntretien],
            ['label' => 'Activer ma garantie', 'route' => 'garantie.create', 'icon' => $iconGarantie],
            ['label' => 'Remplacer mon machine', 'route' => 'service.remplacer.step1', 'icon' => $iconRemplacer],
        ],
        'Réparation' => [
            ['label' => 'Réparer mon appareil', 'route' => null, 'icon' => $iconReparation],
        ],
    ];


    // For navbar logo/name prefer site settings if you pass it, fallback to $settings
    $navLogo = $siteSettings?->logo ? asset('storage/' . $siteSettings->logo) : ($settings?->logo ? asset('storage/' . $settings->logo) : asset('images/logo.png'));
    $navName = $siteSettings?->company_name ?? ($settings?->company_name ?? 'Fluid.ma');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ $logoSrc }}">

    <title>{{ $companyName }} – Réparateur Installateur</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E90FF',   // Cool Blue
                        secondary: '#FFB703', // Warm Amber
                        accent: '#F8F9FA',    // Soft Gray
                    }
                }
            }
        }
    </script>

    <style>
        /* keep your fixed header logic */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
            background: transparent;
        }

        .navbar.scrolled {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            background: #F8F9FA;
            background: linear-gradient(90deg, rgba(248, 249, 250, 1) 0%, rgba(180, 191, 202, 1) 100%);
        }

        body {
            padding-top: 100px;
            /* height of navbar */
        }
    </style>

    <script>
        window.addEventListener('scroll', function () {
            const nav = document.querySelector('.navbar');
            if (!nav) return;
            if (window.scrollY > 20) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });
    </script>

    <!-- LOCATION -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body class="bg-gradient-to-br from-accent to-[#b4bfca] min-h-screen">

    <!-- =========================
         NAVIGATION (modern + pro)
         ========================= -->
    <nav class="navbar w-full">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
            <div class="flex items-center justify-between py-3">

                <!-- LOGO -->
                <a href="/" class="flex items-center gap-3">
                    <div
                        class="h-12 w-12 lg:h-14 lg:w-14 rounded-2xl bg-transparent border border-transparent shadow-none flex items-center justify-center overflow-hidden">
                        <img src="{{ $navLogo }}"
                            class="h-10 w-10 lg:h-12 lg:w-12 object-contain transition-transform duration-300 ease-out hover:scale-110"
                            alt="{{ $navName }}" onerror="this.src='{{ asset('images/logo.png') }}'">
                    </div>

                    <div class="hidden lg:block leading-tight">
                        <div class="text-base font-extrabold text-slate-900 tracking-wide">{{ $navName }}</div>
                        <div class="text-xs font-semibold text-slate-600">Installation · Dépannage · Entretien</div>
                    </div>
                </a>

                <!-- DESKTOP NAV -->
                <div class="hidden md:flex items-center gap-8 text-[15px] font-extrabold text-slate-800">

                    @foreach($menu as $title => $items)
                        <div x-data="{ open:false }" @mouseenter="open=true" @mouseleave="open=false" class="relative">
                            <button type="button"
                                class="group inline-flex items-center gap-1.5 px-3 py-2 rounded-2xl hover:bg-transparent transition">
                                <span class="relative">
                                    {{ $title }}
                                    <span
                                        class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                                </span>

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-slate-500 transition-transform duration-300"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="absolute left-0 mt-3 w-72 rounded-2xl border border-slate-200 bg-white/95 backdrop-blur shadow-xl overflow-hidden z-50">

                                <div class="p-2">
                                    @foreach($items as $item)
                                        @php
                                            $icon = $item['icon'] ?? '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z"/></svg>';
                                            $href = '#';

                                            if ($item['route'] === 'service.show') {
                                                $href = route('service.show', [
                                                    'category' => Str::slug($title),
                                                    'item' => Str::slug($item['label'])
                                                ]);
                                            } elseif (!empty($item['route'])) {
                                                $href = route($item['route']);
                                            }
                                        @endphp

                                        <a href="{{ $href }}"
                                            class="group flex items-start gap-3 px-3 py-3 rounded-2xl hover:bg-slate-50 transition">
                                            <span
                                                class="mt-0.5 h-9 w-9 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                                                {!! $icon !!}
                                            </span>

                                            <div class="min-w-0">
                                                <div class="font-extrabold text-slate-900 group-hover:text-primary transition">
                                                    {{ $item['label'] }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $item['route'] ? 'Accéder au service' : 'En savoir plus' }}
                                                </div>
                                            </div>

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 text-slate-400 ml-auto mt-1 group-hover:text-slate-600 transition"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <a href="/Contactez_Nous"
                        class="group inline-flex items-center gap-2 px-3 py-2 rounded-2xl hover:bg-transparent transition">
                        <span class="relative">
                            Contactez-nous
                            <span
                                class="absolute -bottom-1 left-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
                        </span>
                        <!-- <span
                            class="h-6 w-6 rounded-xl bg-white/70 border border-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-700" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M1.5 8.67v8.58A2.25 2.25 0 0 0 3.75 19.5h16.5A2.25 2.25 0 0 0 22.5 17.25V8.67l-8.7 5.08a3.75 3.75 0 0 1-3.8 0L1.5 8.67Z" />
                                <path
                                    d="M22.5 6.75A2.25 2.25 0 0 0 20.25 4.5H3.75A2.25 2.25 0 0 0 1.5 6.75v.2l9.36 5.46a2.25 2.25 0 0 0 2.28 0L22.5 6.95v-.2Z" />
                            </svg>
                        </span> -->
                    </a>
                </div>

                <!-- RIGHT SIDE (Desktop): profile or auth buttons -->
                <div class="hidden md:flex items-center gap-3">

                    @if(auth()->check())
                        <div x-data="{ open:false }" class="relative">
                            <button @click="open=!open"
                                class="group inline-flex items-center gap-3 px-3 py-2 rounded-2xl bg-white/70 border border-white shadow-sm hover:bg-white transition">
                                <img src="{{ asset('images/profile.png') }}"
                                    class="w-9 h-9 rounded-2xl object-cover border border-slate-200" alt="Avatar">
                                <div class="hidden lg:block text-left leading-tight">
                                    <div class="text-sm font-extrabold text-slate-900 truncate max-w-[160px]">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div class="text-xs font-semibold text-slate-500">
                                        {{ auth()->user()->role }}
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500 transition-transform"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open=false" x-transition
                                class="absolute right-0 mt-3 w-56 rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden z-50">
                                @php
                                    $profileRoute = match (auth()->user()->role) {
                                        'client' => 'client.dashboard',
                                        'admin', 'superadmin' => 'admin.home',
                                        'technicien' => 'technicien.profile',
                                        default => null
                                    };
                                @endphp

                                @if($profileRoute)
                                    <a href="{{ route($profileRoute) }}"
                                        class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition">
                                        <span
                                            class="h-9 w-9 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700"
                                                viewBox="0 0 24 24" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                        <div>
                                            <div class="font-extrabold text-slate-900">Profil</div>
                                            <div class="text-xs text-slate-500">Accéder au tableau de bord</div>
                                        </div>
                                    </a>
                                @endif

                                <div class="h-px bg-slate-100"></div>

                                <form action="{{ route('logout') }}" method="POST" class="p-2">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-3 rounded-2xl bg-rose-50 text-rose-700 font-extrabold hover:bg-rose-100 transition">
                                        <span
                                            class="h-9 w-9 rounded-2xl bg-white border border-rose-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>

                                        </span>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('auth.page') }}"
                            class="group relative inline-flex items-center gap-3 overflow-hidden rounded-2xl border border-white/70 bg-white/80 px-5 py-2.5 text-slate-900 font-extrabold shadow-[0_8px_30px_rgba(15,23,42,0.08)] backdrop-blur transition-all duration-300 hover:-translate-y-0.5 hover:bg-white hover:shadow-[0_12px_35px_rgba(30,144,255,0.18)]">

                            <span
                                class="absolute inset-0 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 opacity-0 transition-opacity duration-300 group-hover:opacity-100"></span>

                            <span
                                class="relative flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-secondary text-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M15.75 3a3 3 0 0 1 3 3v1.5a.75.75 0 0 1-1.5 0V6a1.5 1.5 0 0 0-1.5-1.5h-7.5A1.5 1.5 0 0 0 6.75 6v12a1.5 1.5 0 0 0 1.5 1.5h7.5a1.5 1.5 0 0 0 1.5-1.5v-1.5a.75.75 0 0 1 1.5 0V18a3 3 0 0 1-3 3h-7.5a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h7.5Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M10.72 8.47a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H3.75a.75.75 0 0 1 0-1.5h8.69l-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </span>

                            <span class="relative flex flex-col leading-tight">
                                <span class="text-sm font-extrabold text-slate-900">Espace client</span>
                                <span class="text-[11px] font-semibold text-slate-500">Connexion ou inscription</span>
                            </span>

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="relative w-4 h-4 text-slate-400 transition-transform duration-300 group-hover:translate-x-1 group-hover:text-primary"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                    @endif
                </div>

                <!-- MOBILE MENU -->
                <div class="md:hidden relative" x-data="{ open:false }">
                    <style>
                        [x-cloak] {
                            display: none !important;
                        }
                    </style>

                    <button @click="open = !open"
                        class="relative inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-white/70 border border-white shadow-sm hover:bg-white transition duration-300">

                        <!-- Menu icon -->
                        <svg x-cloak x-show="!open" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-75 rotate-[-90deg]"
                            x-transition:enter-end="opacity-100 scale-100 rotate-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 rotate-0"
                            x-transition:leave-end="opacity-0 scale-75 rotate-90" xmlns="http://www.w3.org/2000/svg"
                            class="absolute w-6 h-6 text-slate-800" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.5 6.75h15m-15 5.25h15m-15 5.25h15" />
                        </svg>

                        <!-- X icon -->
                        <svg x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-75 rotate-[-90deg]"
                            x-transition:enter-end="opacity-100 scale-100 rotate-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 rotate-0"
                            x-transition:leave-end="opacity-0 scale-75 rotate-90" xmlns="http://www.w3.org/2000/svg"
                            class="absolute w-6 h-6 text-slate-800" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-250"
                        x-transition:enter-start="opacity-0 -translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-4"
                        class="absolute right-0 top-[56px] w-[92vw] max-w-sm z-50">
                        <div class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
                            <div class="p-4 border-b border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-12 w-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden">
                                        <img src="{{ $navLogo }}" class="h-10 w-10 object-contain" alt="{{ $navName }}">
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-extrabold text-slate-900 truncate">{{ $navName }}</div>
                                        <div class="text-xs text-slate-500">Menu</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 space-y-4">

                                @foreach ($menu as $title => $items)
                                    <div x-data="{ openSub: false }"
                                        class="rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                        <button type="button" @click="openSub = !openSub"
                                            class="w-full flex items-center justify-between px-4 py-3 font-extrabold text-slate-900">
                                            <span>{{ $title }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-5 h-5 text-slate-500 transition-transform"
                                                :class="{ 'rotate-180': openSub }" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>

                                        <div x-show="openSub" x-transition class="px-2 pb-2">
                                            @foreach($items as $item)
                                                @if($item['route'] === 'service.show')
                                                    <a href="{{ route('service.show', ['category' => Str::slug($title), 'item' => Str::slug($item['label'])]) }}"
                                                        class="block px-3 py-3 rounded-2xl hover:bg-white transition font-semibold text-slate-700">
                                                        {{ $item['label'] }}
                                                    </a>
                                                @else
                                                    <a href="{{ $item['route'] ? route($item['route']) : '#' }}"
                                                        class="block px-3 py-3 rounded-2xl hover:bg-white transition font-semibold text-slate-700">
                                                        {{ $item['label'] }}
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <a href="/Contactez_Nous"
                                    class="flex items-center justify-between px-4 py-3 rounded-2xl bg-primary text-white font-extrabold">
                                    Contactez-nous
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>

                                @if(auth()->check())
                                    @php
                                        $profileRoute = match (auth()->user()->role) {
                                            'client' => 'client.dashboard',
                                            'admin', 'superadmin' => 'admin.home',
                                            'technicien' => 'technicien.profile',
                                            default => null
                                        };
                                    @endphp

                                    <div class="rounded-2xl border border-slate-200 bg-white p-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset('images/profile.png') }}"
                                                class="w-10 h-10 rounded-2xl border border-slate-200" alt="Avatar">
                                            <div class="min-w-0">
                                                <div class="font-extrabold text-slate-900 truncate">
                                                    {{ auth()->user()->name }}
                                                </div>
                                                <div class="text-xs text-slate-500">{{ auth()->user()->role }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 grid grid-cols-2 gap-2">
                                            @if($profileRoute)
                                                <a href="{{ route($profileRoute) }}"
                                                    class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-slate-900 text-white font-extrabold">
                                                    Profil
                                                </a>
                                            @endif

                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-rose-50 text-rose-700 font-extrabold border border-rose-100">
                                                    Déconnexion
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                               @else
    <a href="{{ route('auth.page') }}"
        class="group flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white px-4 py-3 shadow-sm transition hover:shadow-md">
        
        <div class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-primary to-secondary text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M15.75 3a3 3 0 0 1 3 3v1.5a.75.75 0 0 1-1.5 0V6a1.5 1.5 0 0 0-1.5-1.5h-7.5A1.5 1.5 0 0 0 6.75 6v12a1.5 1.5 0 0 0 1.5 1.5h7.5a1.5 1.5 0 0 0 1.5-1.5v-1.5a.75.75 0 0 1 1.5 0V18a3 3 0 0 1-3 3h-7.5a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3h7.5Z"
                        clip-rule="evenodd" />
                    <path fill-rule="evenodd"
                        d="M10.72 8.47a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H3.75a.75.75 0 0 1 0-1.5h8.69l-1.72-1.72a.75.75 0 0 1 0-1.06Z"
                        clip-rule="evenodd" />
                </svg>
            </span>

            <div>
                <div class="font-extrabold text-slate-900">Espace client</div>
                <div class="text-xs text-slate-500">Connexion ou inscription</div>
            </div>
        </div>

        <svg xmlns="http://www.w3.org/2000/svg"
            class="w-5 h-5 text-slate-400 transition group-hover:translate-x-1 group-hover:text-primary"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>
<script>
(function () {
    function blockAction(e, message = 'Action désactivée.') {
        e.preventDefault();
        alert(message);
        return false;
    }

    document.addEventListener('contextmenu', function (e) {
        return blockAction(e, 'Clic droit désactivé.');
    });

    document.addEventListener('keydown', function (e) {
        const key = e.key.toLowerCase();

        if (key === 'f12') {
            return blockAction(e, 'Cette action est désactivée.');
        }

        if ((e.ctrlKey || e.metaKey) && key === 'u') {
            return blockAction(e, 'Affichage du code source désactivé.');
        }

        if ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i', 'j', 'c'].includes(key)) {
            return blockAction(e, 'Outils développeur désactivés.');
        }

        if ((e.ctrlKey || e.metaKey) && key === 's') {
            return blockAction(e, 'Enregistrement désactivé.');
        }
    });
})();
</script>
</body>

</html>