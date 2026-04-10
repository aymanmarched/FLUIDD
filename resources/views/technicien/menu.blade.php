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
    <title>{{ $companyName }} – Technicien Dashboard</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine -->
    <style>[x-cloak]{display:none !important;}</style>
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
<div
    x-data="{
        open: true,         // desktop collapse
        mobileOpen: false,  // mobile drawer
        isDesktop() { return window.matchMedia('(min-width: 1024px)').matches },
        init() {
            this.open = true;        // keep desktop behavior
            this.mobileOpen = false; // mobile closed by default

            window.addEventListener('resize', () => {
                if (this.isDesktop()) this.mobileOpen = false;
            });
        }
    }"
    x-init="init()"
    class="min-h-screen w-full"
>

    <!-- Mobile top bar -->
    <div class="lg:hidden sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-zinc-200">
        <div class="px-4 py-3 flex items-center justify-between">
    
            @php
    $full = trim(auth()->user()->technician->name ?? auth()->user()->name ?? '');

    // split by spaces (handles multiple spaces)
    $parts = preg_split('/\s+/', $full, -1, PREG_SPLIT_NO_EMPTY);

    $first = $parts[0] ?? '';
    $last  = count($parts) > 1 ? $parts[count($parts) - 1] : '';

    // If only one word => take 2 first letters
    if ($last === '') {
        $initials = strtoupper(mb_substr($first, 0, 2));
    } else {
        $initials = strtoupper(mb_substr($first, 0, 1) . mb_substr($last, 0, 1));
    }

    if ($initials === '') $initials = 'FO';
@endphp
    <button
                @click="mobileOpen = true"
                class="inline-flex items-center justify-center h-11 w-11 rounded-xl border border-zinc-200 bg-white hover:bg-zinc-50 transition"
                aria-label="Open menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-zinc-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/>
                </svg>
            </button>

            <div class="flex items-center gap-2">
                <div class="h-10 w-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center font-extrabold">
                    {{ $initials }}
                </div>
                <div class="leading-tight">
                    <div class="text-sm font-extrabold">             {{ $full }}
</div>
                    <div class="text-xs text-zinc-500">Technicien</div>
                </div>
            </div>

            <a href="{{ route('technicien.profile') }}"
               class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-orange-600 text-white hover:bg-orange-700 transition"
               aria-label="Profile">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Mobile overlay + drawer -->
    <div x-cloak x-show="mobileOpen" class="lg:hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50" @click="mobileOpen = false"></div>

        <aside class="absolute left-0 top-0 h-full w-[86%] max-w-[340px] bg-zinc-950 text-white shadow-soft2">
            <div class="p-4 border-b border-white/10 flex items-center justify-between">
       
            <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-orange-600/20 text-orange-300 flex items-center justify-center font-extrabold">
                        {{ $initials }}
                    </div>
                    <div>
                        <div class="font-extrabold">             {{ $full }}
</div>
                        <div class="text-xs text-white/60">Technicien Panel</div>
                    </div>
                </div>
                <button @click="mobileOpen = false" class="h-10 w-10 rounded-xl bg-white/10 hover:bg-white/15 transition inline-flex items-center justify-center" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- MOBILE NAV (your new mobile version) -->
            <nav class="p-4 space-y-2">
                <a href="/" target="_blank"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
</svg>

                    </span>
                    Web Site
                </a>

                <a href="{{ route('technicien.profile') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                   {{ Route::is(['technicien.profile.*', 'technicien.profile']) ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 {{ Route::is(['technicien.profile.*', 'technicien.profile']) ? 'text-white' : 'text-white/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                        </svg>
                    </span>
                    Home
                </a>

                @php $user = auth()->user(); @endphp
                @if($user && $user->role !== 'client')
                    <div x-data="notifCounter({{ $user->unreadNotifications()->count() }}, '{{ route('notifications.unread_count', [], false) }}')"
                         x-init="start()">
                        <a href="{{ route('notifications.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                           {{ Route::is('notifications.*') ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                                </svg>
                            </span>
                            <span class="flex-1">Notifications</span>
                            <span x-show="count > 0" x-text="count"
                                  class="text-xs px-2 py-1 rounded-full bg-white text-zinc-900 font-extrabold"></span>
                        </a>
                    </div>
                @endif

                <a href="{{ route('technicien.commandes') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                   {{ Route::is(['technicien.commandes.*', 'technicien.commandes']) ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
  <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
</svg>

                    </span>
                    Commandes
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold text-white">
                        <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
</svg>

                        </span>
                        Logout
                    </button>
                </form>
            </nav>

            <div class="p-4 border-t border-white/10 text-xs text-white/60 text-center">
                © {{ date('Y') }} Field Ops
            </div>
        </aside>
    </div>

    <!-- DESKTOP + MAIN WRAPPER -->
    <div class="flex min-h-[calc(100vh-64px)] lg:min-h-screen w-full">

        <!-- DESKTOP SIDEBAR (YOUR LAST DESKTOP VERSION, kept) -->
        <aside
            class="hidden lg:flex relative bg-zinc-950 text-white flex-col border-r border-white/10 shadow-soft2 transition-all duration-300"
            :class="open ? 'w-72' : 'w-24'"
        >
            <!-- Collapse -->
            <button @click="open = !open"
                    class="absolute -right-3 top-5 z-50 h-10 w-10 bg-zinc-900 border border-white/10 rounded-xl hover:bg-zinc-800 transition inline-flex items-center justify-center">
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Brand -->
          <div class="p-6 border-b border-white/10 flex items-center gap-3">
   @php
    $full = trim(auth()->user()->technician->name ?? auth()->user()->name ?? '');

    // split by spaces (handles multiple spaces)
    $parts = preg_split('/\s+/', $full, -1, PREG_SPLIT_NO_EMPTY);

    $first = $parts[0] ?? '';
    $last  = count($parts) > 1 ? $parts[count($parts) - 1] : '';

    // If only one word => take 2 first letters
    if ($last === '') {
        $initials = strtoupper(mb_substr($first, 0, 2));
    } else {
        $initials = strtoupper(mb_substr($first, 0, 1) . mb_substr($last, 0, 1));
    }

    if ($initials === '') $initials = 'FO';
@endphp

    <div class="h-11 w-11 rounded-2xl bg-orange-600/20 text-orange-300 flex items-center justify-center font-extrabold">
        {{ $initials }}
    </div>

    <div x-show="open" class="min-w-0">
        <div class="text-base font-extrabold leading-tight truncate">
             {{ $full }}
        </div>
        <div class="text-xs text-white/60">Technicien Panel</div>
    </div>
</div>


            <!-- NAV (keep your desktop nav as-is) -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="/" target="_blank"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
</svg>

                    </span>
                    <span x-show="open" class="transition-all origin-left">Web Site</span>
                </a>

                <a href="{{ route('technicien.profile') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                   {{ Route::is(['technicien.profile.*', 'technicien.profile']) ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 {{ Route::is(['technicien.profile.*', 'technicien.profile']) ? 'text-white' : 'text-white/70' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                        </svg>
                    </span>
                    <span x-show="open" class="transition-all origin-left">Home</span>
                </a>

                @php $user = auth()->user(); @endphp
                @if($user && $user->role !== 'client')
                    <div x-data="notifCounter({{ $user->unreadNotifications()->count() }}, '{{ route('notifications.unread_count', [], false) }}')" x-init="start()">
                        <a href="{{ route('notifications.index') }}"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                           {{ Route::is('notifications.*') ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                            <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/>
                                </svg>
                            </span>

                            <span x-show="open" class="flex-1 transition-all origin-left">Notifications</span>

                            <span x-show="count > 0" x-text="count"
                                  class="text-xs px-2 py-1 rounded-full bg-white text-zinc-900 font-extrabold"></span>
                        </a>
                    </div>
                @endif

                <a href="{{ route('technicien.commandes') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-semibold
                   {{ Route::is(['technicien.commandes.*', 'technicien.commandes']) ? 'bg-orange-600 text-white' : 'hover:bg-white/10 text-white' }}">
                    <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
  <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
</svg>

                    </span>
                    <span x-show="open" class="transition-all origin-left">Commandes</span>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/10 transition font-semibold text-white">
                        <span class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
</svg>

                        </span>
                        <span x-show="open" class="transition-all origin-left">Logout</span>
                    </button>
                </form>
            </nav>

            <div class="p-4 border-t border-white/10 text-xs text-white/60 text-center" x-show="open">
                © {{ date('Y') }} Field Ops
            </div>
        </aside>

        <!-- MAIN (always visible on mobile + desktop) -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>

    </div>

</div>

@php $user = auth()->user(); @endphp
@if($user && $user->role !== 'client')
@once
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notifCounter', (initialCount, url) => ({
        count: Number(initialCount || 0),
        timer: null,
        async refresh() {
            try {
                const res = await fetch(url + '?_=' + Date.now(), {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    cache: 'no-store',
                });
                const ct = res.headers.get('content-type') || '';
                if (!res.ok) return;
                if (!ct.includes('application/json')) return;
                const data = await res.json();
                this.count = Number(data.count ?? 0);
            } catch (e) {}
        },
        start() {
            this.refresh();
            this.timer = setInterval(() => this.refresh(), 4000);
        }
    }));
});
</script>
@endonce
@endif
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
