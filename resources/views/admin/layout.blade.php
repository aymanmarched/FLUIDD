<!-- resources/views/admin/layout.blade.php -->
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
    <title>{{ $companyName }} – Admin Dashbord</title>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 text-slate-800 font-sans h-screen overflow-hidden">
    <div x-data="{ open: true, mobileOpen: false }" class="flex h-full w-full">

        <!-- Backdrop -->
        <div x-show="mobileOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/30 lg:hidden"
            @click="mobileOpen = false"></div>

        <!-- SIDEBAR -->
        <aside :class="[
                open ? 'lg:w-72' : 'lg:w-20',
                mobileOpen ? 'translate-x-0' : '-translate-x-full'
            ]" class="fixed lg:static z-50 inset-y-0 left-0 w-72 lg:translate-x-0
                   bg-white border-r border-slate-200
                   flex flex-col transition-all duration-300">

            <!-- Top -->
            <div class="relative">
                <!-- Mobile close -->
                <button @click="mobileOpen = false"
                    class="lg:hidden absolute right-3 top-3 p-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Collapse (desktop) -->
                <button @click="open = !open" class="hidden lg:flex absolute -right-3 top-6 z-50 p-2 rounded-full
                           bg-white border border-slate-200 shadow-sm hover:bg-slate-50 transition">
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Brand -->
                <a href="{{ route('admin.home') }}" class="flex items-center gap-3 px-5 py-6 border-b border-slate-200 transition
                    {{ Route::is('admin.home') ? 'bg-indigo-50' : 'hover:bg-slate-50' }}">
                    <div
                        class="h-10 w-10 rounded-2xl bg-indigo-600/10 border border-indigo-200 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>

                    </div>

                    <div x-show="open" class="origin-left transition-all">
                        <h1 class="text-lg font-extrabold tracking-wide text-slate-900">Admin Panel</h1>
                        <p class="text-xs text-slate-500 -mt-0.5">Operations Suite</p>
                    </div>
                </a>
            </div>

            <!-- NAV -->
            <nav class="flex-1 p-3 lg:p-4 space-y-1.5 overflow-y-auto">

                <!-- Website -->
                <!-- <a href="/" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                           border-transparent hover:border-slate-200 hover:bg-slate-50">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </div>
                    <span x-show="open" class="flex-1 text-left text-sm font-medium text-slate-700">Web Site</span>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a> -->
                <a href="{{ route('admin.site-settings.edit') }}" 
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is('admin.site-settings.edit') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                       <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                    </div>
                    <span x-show="open" class="text-sm font-medium">Web Site</span>
                </a>
                @php
                    $user = auth()->user();
                @endphp

                @if($user && in_array($user->role, ['admin', 'superadmin']))
                    <div x-data="notifCounter({{ $user->unreadNotifications()->count() }}, '{{ route('notifications.unread_count', [], false) }}')"
                        x-init="start()">
                        <a href="{{ route('notifications.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                                                    {{ Route::is('notifications.*') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                            <div
                                class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                </svg>


                            </div>

                            <span x-show="open" class="flex-1 text-left text-sm font-medium">
                                Notifications
                            </span>

                            <span x-show="count > 0" x-text="count"
                                class="text-xs px-2 py-0.5 rounded-full bg-indigo-600 text-white">
                            </span>
                        </a>
                    </div>

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
                                                headers: {
                                                    'Accept': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                },
                                                cache: 'no-store',
                                            });

                                            const ct = res.headers.get('content-type') || '';
                                            if (!res.ok) return;
                                            if (!ct.includes('application/json')) return;

                                            const data = await res.json();
                                            this.count = Number(data.count ?? 0);
                                        } catch (e) { }
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

                <!-- Admins -->
                <a href="{{ route('admin.admins.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is('admin.admins.*') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z" />
                        </svg>
                    </div>
                    <span x-show="open" class="text-sm font-medium">Admins</span>
                </a>

                <!-- Technicien -->
                <a href="{{ route('admin.technicians') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(patterns: ['admin.technicians.*', 'admin.technicians']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-700" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                                clip-rule="evenodd" />
                            <path
                                d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z" />
                            <path fill-rule="evenodd"
                                d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                        </svg>
                    </div>
                    <span x-show="open" class="text-sm font-medium">Technicien</span>
                </a>

                <!-- Entretenir -->
                <a href="{{ route('admin.entretenir') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.entretenir.*', 'admin.entretenir']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <!-- <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
                        </svg> -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Entretenir</span>
                </a>

                <!-- Machine -->
                <a href="{{ route('admin.machines') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.machines.*', 'admin.machines']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                        </svg>
                    </div>
                    <span x-show="open" class="text-sm font-medium">Machine</span>
                </a>

                <!-- Clients -->
                <a href="{{ route('admin.clients') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.clients.*', 'admin.clients']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                clip-rule="evenodd" />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>


                    </div>
                    <span x-show="open" class="text-sm font-medium">Clients</span>
                </a>

                <!-- Commandes -->
                <a href="{{ route('admin.commandes') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(patterns: ['admin.commandes.*', 'admin.commandes']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Commandes</span>
                </a>

                <!-- Commandes d'entretien -->
                <!-- <a href="{{ route('admin.clientsentretien') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.clientsentretien.*', 'admin.clientsentretien']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>


                    </div>
                    <span x-show="open" class="text-sm font-medium">Commandes d'entretien</span>
                </a> -->

                <!-- Commandes de remplacer -->
                <!-- <a href="{{ route('admin.clientsremplacer') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.clientsremplacer.*', 'admin.clientsremplacer']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-fuchsia-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Commandes de remplacer</span>
                </a> -->

                <!-- Garanties -->
                <a href="{{ route('admin.garanties') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is(['admin.garanties.*', 'admin.garanties']) ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Garanties</span>
                </a>

                <!-- Clients Message -->
                <a href="{{ route('admin.clientsMessage') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is('admin.clientsMessage') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Clients Message</span>
                </a>

                <!-- Avis Clients -->
                <a href="{{ route('admin.AvisClients') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                    {{ Route::is('admin.AvisClients.*', 'admin.AvisClients') ? 'bg-indigo-50 border-indigo-200 text-indigo-700' : 'border-transparent hover:border-slate-200 hover:bg-slate-50' }}">
                    <div
                        class="h-9 w-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>

                    </div>
                    <span x-show="open" class="text-sm font-medium">Avis Clients</span>
                </a>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl border transition
                               border-transparent hover:border-slate-200 hover:bg-slate-50">
                        <div
                            class="h-9 w-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" viewBox="0 0 24 24"
                                fill="currentColor" class="size-6">
                                <path fill-rule="evenodd"
                                    d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z"
                                    clip-rule="evenodd" />
                            </svg>

                        </div>
                        <span x-show="open" class="text-sm font-semibold text-slate-800">Logout</span>
                    </button>
                </form>
            </nav>

            <!-- Footer hint -->
            <div class="hidden lg:block px-4 pb-4">
                <div x-show="open" class="text-xs text-slate-400 border-t border-slate-200 pt-3">
                    © {{ date('Y') }} Admin Dashboard
                </div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="flex-1 w-full">
            <!-- TOPBAR -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center gap-3">
                    <!-- Mobile burger -->
                    <button
                        class="lg:hidden p-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 shadow-sm"
                        @click="mobileOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="flex-1">
                        <div class="text-xs text-slate-500">Admin</div>
                        <div class="text-lg font-semibold tracking-tight text-slate-900">
                            @yield('page_title', 'Dashboard')
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div
                            class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-white border border-slate-200">
                            <span class="text-xs text-slate-500">Signed in</span>
                            <span class="text-sm font-semibold text-slate-800">
                                {{ auth()->user()->name ?? 'Admin' }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="h-[calc(100vh-73px)] overflow-y-auto px-4 sm:px-6 lg:px-8 py-6">
                @yield('content')
            </main>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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