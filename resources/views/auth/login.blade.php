{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
@php
    use Illuminate\Support\Str;

    $site = $siteSettings ?? $settings ?? null;
    $companyName = $site->company_name ?? config('app.name', 'Fluid');
    $logoPath = $site->logo ?? null;

    $logoSrc = $logoPath
        ? (Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : asset('storage/' . $logoPath))
        : asset('images/logo.png');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName }} – Connexion</title>

    <link rel="icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ $logoSrc }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E90FF',
                        secondary: '#FFB703',
                        accent: '#F8F9FA',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background:
                radial-gradient(circle at top right, rgba(30,144,255,.18), transparent 30%),
                radial-gradient(circle at bottom left, rgba(255,183,3,.18), transparent 30%),
                linear-gradient(135deg, #f8f9fa 0%, #eaf3ff 100%);
        }
    </style>
</head>

<body class="min-h-screen px-4 py-6 md:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- top navbar --}}
        <div class="mb-6 flex items-center justify-between gap-4">
            <a href="/" class="flex items-center gap-3">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-lg border border-white/70">
                    <img src="{{ $logoSrc }}" alt="{{ $companyName }}" class="h-10 w-10 object-contain"
                         onerror="this.src='{{ asset('images/logo.png') }}'">
                </div>
                <div class="hidden sm:block">
                    <div class="text-lg font-extrabold text-slate-900">{{ $companyName }}</div>
                    <div class="text-sm text-slate-500">Espace client</div>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <span class="hidden md:inline text-sm text-slate-600">Vous n’avez pas encore de compte ?</span>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-secondary px-5 py-3 text-sm font-extrabold text-slate-900 shadow-lg transition hover:bg-yellow-400 hover:scale-[1.02]">
                    Créer un compte
                </a>
            </div>
        </div>

        {{-- main card --}}
        <div class="overflow-hidden rounded-[32px] bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.12)] backdrop-blur border border-white/70">
            <div class="grid min-h-[680px] lg:grid-cols-2">

                {{-- left panel --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-primary via-blue-600 to-secondary px-6 py-10 text-white lg:px-12">
                    <div class="absolute -left-16 -top-16 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-16 -right-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>

                    <div class="relative flex h-full flex-col justify-center">
                        <div class="mx-auto max-w-md text-center lg:text-left">
                            <div class="mb-6 inline-flex items-center rounded-full bg-white/15 px-4 py-2 text-sm font-bold backdrop-blur">
                                Bon retour sur {{ $companyName }}
                            </div>

                            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">
                                Connectez-vous à votre compte
                            </h2>

                            <p class="mt-5 text-base md:text-lg text-white/90 leading-relaxed">
                                Retrouvez votre espace client, consultez vos informations et gérez vos demandes
                                d’entretien en quelques clics.
                            </p>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur border border-white/15">
                                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 12 8.954 5.296a2.25 2.25 0 0 1 3.182 0L18.75 12M4.5 9.75V19.5A1.5 1.5 0 0 0 6 21h12a1.5 1.5 0 0 0 1.5-1.5V9.75" />
                                        </svg>
                                    </div>
                                    <h3 class="font-extrabold text-lg">Accès rapide</h3>
                                    <p class="mt-2 text-sm text-white/85">Connectez-vous rapidement à votre espace client.</p>
                                </div>

                                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur border border-white/15">
                                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v7.5A1.5 1.5 0 0 1 18 21H6a1.5 1.5 0 0 1-1.5-1.5V12A1.5 1.5 0 0 1 6 10.5Z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-extrabold text-lg">Connexion sécurisée</h3>
                                    <p class="mt-2 text-sm text-white/85">Accès protégé à vos informations personnelles.</p>
                                </div>
                            </div>

                            <div class="mt-10">
                                <p class="text-sm text-white/85 mb-3">Pas encore inscrit ?</p>
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 font-extrabold text-slate-900 transition hover:scale-[1.02] hover:bg-slate-100">
                                    Créer un compte
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- right form --}}
                <div class="px-5 py-8 sm:px-8 md:px-10 lg:px-12">
                    <div class="mx-auto flex h-full w-full max-w-xl flex-col justify-center">
                        <div class="mb-8">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                                Connexion
                            </h1>
                            <p class="mt-2 text-slate-500">
                                Entrez vos identifiants pour accéder à votre compte.
                            </p>
                        </div>

                        <x-validation-errors class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700" />

                        @if (session('status'))
                            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Mot de passe</label>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                            </div>

                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <label for="remember_me" class="inline-flex items-center gap-3">
                                    <input id="remember_me" type="checkbox" name="remember"
                                        class="h-5 w-5 rounded border-slate-300 text-primary focus:ring-primary">
                                    <span class="text-sm text-slate-600">Enregistrer le mot de passe</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-sm font-semibold text-primary transition hover:text-blue-700">
                                        Oublié mon mot de passe ?
                                    </a>
                                @endif
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-6 py-4 text-base font-extrabold text-white shadow-[0_14px_34px_rgba(30,144,255,0.32)] transition duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-[0_18px_40px_rgba(30,144,255,0.40)]">
                                    Se connecter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>