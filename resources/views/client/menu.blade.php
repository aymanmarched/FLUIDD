{{-- resources/views/client/menu.blade.php --}}
<!DOCTYPE html>
<html lang="en">

@php
    use Illuminate\Support\Str;
@endphp
@php
    $companyName = $settings->company_name ?? config('app.name', 'Fluid');
    $logoPath = $settings->logo ?? null;

    $logoSrc = $logoPath
        ? (Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : asset('storage/' . $logoPath))
        : asset('images/logo.png');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ $logoSrc }}?v={{ time() }}">
<link rel="shortcut icon" href="{{ $logoSrc }}?v={{ time() }}">
<link rel="apple-touch-icon" href="{{ $logoSrc }}">
    <title>{{ $companyName }} – Client Dashboard</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    boxShadow: {
                        soft: '0 10px 30px rgba(0,0,0,.06)',
                        soft2: '0 12px 24px rgba(0,0,0,.08)',
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans bg-zinc-50 text-zinc-900">
    @php
        // Client display name + initials (same logic as technicien)
      
    // Client display name + initials from (nom + prenom)
    $full = trim(
        (auth()->user()->client->nom ?? '') . ' ' . (auth()->user()->client->prenom ?? '')
    );

    // fallback if client relation missing
    if ($full === '') {
        $full = trim(auth()->user()->name ?? '');
    }

    $parts = preg_split('/\s+/', $full, -1, PREG_SPLIT_NO_EMPTY);
    $first = $parts[0] ?? '';
    $last  = count($parts) > 1 ? $parts[count($parts) - 1] : '';

    // Adil Hassan => AH | Adil => AD
    if ($first === '' && $last === '') {
        $initials = 'CP';
    } elseif ($last === '') {
        $initials = strtoupper(mb_substr($first, 0, 2));
    } else {
        $initials = strtoupper(mb_substr($first, 0, 1) . mb_substr($last, 0, 1));
    }



        $unreadCount = auth()->user()?->unreadNotifications()->count() ?? 0;
        //         {{-- PATCH: keep your current logique/layout, but apply OLD NAV colors/hover/shadow styles --}}
        // {{-- 1) Add these helper classes once (after $unreadCount) --}}

        $linkBase = "group flex items-center gap-3 px-4 py-3 rounded-2xl transition";
        $iconBase = "h-10 w-10 rounded-xl flex items-center justify-center transition";
        // OLD NAV feel:
        // - inactive: text-slate + hover bg gray + subtle border
        // - active: bg-sky-50 + border-sky-100 + shadow-sm + text-sky
        $active = "bg-sky-50 text-sky-700 border border-sky-100 shadow-sm";
        $inactive = "text-slate-700 hover:bg-gray-50 border border-transparent hover:border-gray-200";


    @endphp

    <div x-data="{
        open: true,         // desktop collapse
        mobileOpen: false,  // mobile drawer
        isDesktop() { return window.matchMedia('(min-width: 1024px)').matches },
        init() {
            this.open = true;
            this.mobileOpen = false;

            window.addEventListener('resize', () => {
                if (this.isDesktop()) this.mobileOpen = false;
            });
        }
    }" x-init="init()" class="min-h-screen w-full">

        <!-- Mobile top bar -->
        <div class="lg:hidden sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-zinc-200">
            <div class="px-4 py-3 flex items-center justify-between">
                <button @click="mobileOpen = true"
                    class="inline-flex items-center justify-center h-11 w-11 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 transition"
                    aria-label="Open menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-700" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18" />
                    </svg>
                </button>

                <div class="flex items-center gap-2 min-w-0">
                    <div
                        class="h-10 w-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="leading-tight min-w-0">
                        <div class="text-sm font-extrabold truncate">{{ $full }}</div>
                        <div class="text-xs text-zinc-500">Client</div>
                    </div>
                </div>

                <a href="{{ route('client.profile.edit') }}"
                    class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-sky-600 text-white hover:bg-sky-700 transition"
                    aria-label="Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Mobile overlay + drawer -->
        <div x-cloak x-show="mobileOpen" class="lg:hidden fixed inset-0 z-50">
            <div class="absolute inset-0 bg-black/50" @click="mobileOpen = false"></div>

            <aside class="absolute left-0 top-0 h-full w-[86%] max-w-[340px] bg-white text-zinc-900 shadow-soft2">
                <div class="p-4 border-b border-zinc-200 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="h-10 w-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold shrink-0">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <div class="font-extrabold truncate">{{ $full }}</div>
                            <div class="text-xs text-zinc-500">Client Panel</div>
                        </div>
                    </div>

                    <button @click="mobileOpen = false"
                        class="h-10 w-10 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 transition inline-flex items-center justify-center"
                        aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-700" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- MOBILE NAV -->
                {{-- 2) MOBILE NAV: replace ONLY the <nav class="p-4 space-y-2"> ... </nav> with this --}}
                <nav class="p-4 space-y-1">

                    <!-- Website -->
                    <a href="/" target="_blank" class="{{ $linkBase }} {{ $inactive }}">
                        <span class="{{ $iconBase }} bg-sky-100 text-sky-700 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </span>
                        Web Site
                    </a>

                    <!-- Home -->
                    <a href="{{ route('client.dashboard') }}"
                        class="{{ $linkBase }} {{ Route::is('client.dashboard') ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is('client.dashboard') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                            </svg>
                        </span>
                        Home
                    </a>

                    <!-- Notifications -->
                    <a href="{{ url('/client/notifications') }}"
                        class="{{ $linkBase }} {{ request()->is('client/notifications*') ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ request()->is('client/notifications*') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 0 1-6 0m6 0H9" />
                            </svg>
                        </span>
                        <span class="flex-1">Notifications</span>

                        @if($unreadCount > 0)
                            <span
                                class="ml-auto inline-flex items-center justify-center text-xs font-bold bg-red-600 text-white rounded-full px-2 py-1">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Entretiens -->
                    <a href="{{ route('client.entretiens') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.entretiens.*', 'client.entretiens']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.entretiens.*', 'client.entretiens']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            {{-- keep your icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                            </svg>
                        </span>
                        Mes Entretiens
                    </a>

                    <!-- Remplacements -->
                    <a href="{{ route('client.remplacers') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.remplacers.*', 'client.remplacers']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.remplacers.*', 'client.remplacers']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            {{-- keep your icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>
                        </span>
                        Mes Remplacements
                    </a>

                    <!-- Garanties -->
                    <a href="{{ route('client.garanties') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.garanties.*', 'client.garanties']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.garanties.*', 'client.garanties']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            {{-- keep your icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>

                        </span>
                        Mes Garanties
                    </a>
                      <!-- Garanties -->
                   <a href="{{ route('client.avis.index') }}"
   class="{{ $linkBase }} {{ Route::is('client.avis.*') ? $active : $inactive }}">
     
    <span class="{{ $iconBase }} {{ Route::is('client.avis.*') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            {{-- keep your icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h6m-6 3h9M6.75 3.75h10.5A2.25 2.25 0 0 1 19.5 6v12a2.25 2.25 0 0 1-2.25 2.25H9l-3.75 3v-3H6.75A2.25 2.25 0 0 1 4.5 18V6a2.25 2.25 0 0 1 2.25-2.25Z" />
 </svg>

                        </span>
                       Mes avis
                    </a>

                    <!-- Logout -->
                    <form action="{{ route('logout') }}" method="POST" class="pt-2">
                        @csrf
                        <button type="submit" class="{{ $linkBase }} {{ $inactive }} w-full">
                            <span class="{{ $iconBase }} bg-gray-100 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                </svg>
                            </span>
                            Logout
                        </button>
                    </form>

                </nav>


                <div class="p-4 border-t border-zinc-200 text-xs text-zinc-500 text-center">
                    © {{ date('Y') }} Client Portal
                </div>
            </aside>
        </div>

        <!-- DESKTOP + MAIN WRAPPER (same logic as technicien) -->
        <div class="flex min-h-[calc(100vh-64px)] lg:min-h-screen w-full">

            <!-- DESKTOP SIDEBAR -->
            <aside
                class="hidden lg:flex relative bg-white text-zinc-900 flex-col border-r border-zinc-200 shadow-soft2 transition-all duration-300"
                :class="open ? 'w-72' : 'w-24'">
                <!-- Collapse -->
                <button @click="open = !open"
                    class="absolute -right-3 top-5 z-50 h-10 w-10 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition inline-flex items-center justify-center">
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-700" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Brand -->
                <div class="p-6 border-b border-zinc-200 flex items-center gap-3">
                    <div
                        class="h-11 w-11 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center font-extrabold">
                        {{ $initials }}
                    </div>

                    <div x-show="open" class="min-w-0">
                        <div class="text-base font-extrabold leading-tight truncate">{{ $full }}</div>
                        <div class="text-xs text-zinc-500">Client Panel</div>
                    </div>
                </div>

                <!-- NAV -->
                {{-- 3) DESKTOP NAV: replace ONLY the <nav class="flex-1 p-4 space-y-2"> ... </nav> with this --}}
                <nav class="flex-1 p-4 space-y-1">

                    <a href="/" target="_blank" class="{{ $linkBase }} {{ $inactive }}">
                        <span class="{{ $iconBase }} bg-sky-100 text-sky-700 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </span>
                        <span x-show="open" class="transition-all origin-left font-medium">Web Site</span>
                    </a>

                    <a href="{{ route('client.dashboard') }}"
                        class="{{ $linkBase }} {{ Route::is('client.dashboard') ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is('client.dashboard') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
                            </svg>
                        </span>
                        <span x-show="open" class="transition-all origin-left font-semibold">Home</span>
                    </a>

                    <a href="{{ url('/client/notifications') }}"
                        class="{{ $linkBase }} {{ request()->is('client/notifications*') ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ request()->is('client/notifications*') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 0 1-6 0m6 0H9" />
                            </svg>
                        </span>

                        <span x-show="open" class="flex-1 transition-all origin-left font-medium">Notifications</span>

                        @if($unreadCount > 0)
                            <span x-show="open"
                                class="ml-auto inline-flex items-center justify-center text-xs font-bold bg-red-600 text-white rounded-full px-2 py-1">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('client.entretiens') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.entretiens.*', 'client.entretiens']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.entretiens.*', 'client.entretiens']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                            </svg>

                        </span>
                        <span x-show="open" class="transition-all origin-left font-medium">Mes Entretiens</span>
                    </a>

                    <a href="{{ route('client.remplacers') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.remplacers.*', 'client.remplacers']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.remplacers.*', 'client.remplacers']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                            </svg>

                        </span>
                        <span x-show="open" class="transition-all origin-left font-medium">Mes Remplacements</span>
                    </a>

                    <a href="{{ route('client.garanties') }}"
                        class="{{ $linkBase }} {{ Route::is(['client.garanties.*', 'client.garanties']) ? $active : $inactive }}">
                        <span
                            class="{{ $iconBase }} {{ Route::is(['client.garanties.*', 'client.garanties']) ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>

                        </span>
                        <span x-show="open" class="transition-all origin-left font-medium">Mes Garanties</span>
                    </a>
                    <a href="{{ route('client.avis.index') }}"
   class="{{ $linkBase }} {{ Route::is('client.avis.*') ? $active : $inactive }}">
    <span class="{{ $iconBase }} {{ Route::is('client.avis.*') ? 'bg-sky-100 text-sky-700 shadow-sm' : 'bg-gray-100 text-slate-500 group-hover:text-slate-700' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h6m-6 3h9M6.75 3.75h10.5A2.25 2.25 0 0 1 19.5 6v12a2.25 2.25 0 0 1-2.25 2.25H9l-3.75 3v-3H6.75A2.25 2.25 0 0 1 4.5 18V6a2.25 2.25 0 0 1 2.25-2.25Z" />
        </svg>
    </span>
    <span x-show="open" class="transition-all origin-left font-medium">Mes avis</span>
</a>

                    <form action="{{ route('logout') }}" method="POST" class="pt-2">
                        @csrf
                        <button type="submit" class="{{ $linkBase }} {{ $inactive }} w-full">
                            <span class="{{ $iconBase }} bg-gray-100 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                </svg>
                            </span>
                            <span x-show="open" class="transition-all origin-left font-semibold">Logout</span>
                        </button>
                    </form>

                </nav>


                <div class="p-4 border-t border-zinc-200 text-xs text-zinc-500 text-center" x-show="open">
                    © {{ date('Y') }} Client Portal
                </div>
            </aside>

            <!-- MAIN -->
            <main class="flex-1 overflow-y-auto">
                <div class="p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>

    </div>
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