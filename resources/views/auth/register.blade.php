{{-- resources/views/auth/register.blade.php --}}
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
    <title>{{ $companyName }} – Créer un compte</title>

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
                radial-gradient(circle at top left, rgba(30,144,255,.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(255,183,3,.18), transparent 30%),
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
                <span class="hidden md:inline text-sm text-slate-600">Vous avez déjà un compte ?</span>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-extrabold text-white shadow-lg transition hover:bg-blue-700 hover:scale-[1.02]">
                    Connexion
                </a>
            </div>
        </div>

        {{-- main card --}}
        <div class="overflow-hidden rounded-[32px] bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.12)] backdrop-blur border border-white/70">
            <div class="grid min-h-[760px] lg:grid-cols-2">

                {{-- left form --}}
                <div class="order-2 lg:order-1 px-5 py-8 sm:px-8 md:px-10 lg:px-12">
                    <div class="mx-auto w-full max-w-2xl">
                        <div class="mb-8">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900">
                                Créer un compte client
                            </h1>
                            <p class="mt-2 text-slate-500">
                                Remplissez vos informations pour accéder à votre espace client.
                            </p>
                        </div>

                        <x-validation-errors class="mb-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700" />

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Nom</label>
                                    <input type="text" name="nom" value="{{ old('nom') }}" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Prénom</label>
                                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Téléphone (+212)</label>
                                    <input type="text" name="telephone" value="{{ old('telephone') }}" maxlength="9"
                                        pattern="^(6|7)[0-9]{8}$" placeholder="6XXXXXXXX" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                    <p class="mt-2 text-xs text-slate-500">Format : 6XXXXXXXX ou 7XXXXXXXX</p>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Ville</label>
                                    <select name="ville_id"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                        <option value="">-- Choisir --</option>
                                        @foreach($villes as $v)
                                            <option value="{{ $v->id }}" {{ old('ville_id') == $v->id ? 'selected' : '' }}>
                                                {{ $v->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Adresse précise</label>
                                    <input type="text" name="adresse" value="{{ old('adresse') }}"
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Mot de passe</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-bold text-slate-700">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-6 py-4 text-base font-extrabold text-white shadow-[0_14px_34px_rgba(30,144,255,0.32)] transition duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-[0_18px_40px_rgba(30,144,255,0.40)]">
                                    Créer mon compte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- right panel --}}
                <div class="order-1 lg:order-2 relative overflow-hidden bg-gradient-to-br from-primary via-blue-600 to-secondary px-6 py-10 text-white lg:px-12">
                    <div class="absolute -left-16 -top-16 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-16 -right-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>

                    <div class="relative flex h-full flex-col justify-center">
                        <div class="mx-auto max-w-md text-center lg:text-left">
                            <div class="mb-6 inline-flex items-center rounded-full bg-white/15 px-4 py-2 text-sm font-bold backdrop-blur">
                                Bienvenue chez {{ $companyName }}
                            </div>

                            <h2 class="text-4xl md:text-5xl font-extrabold leading-tight">
                                Créez votre espace client
                            </h2>

                            <p class="mt-5 text-base md:text-lg text-white/90 leading-relaxed">
                                Suivez vos demandes d’entretien, accédez à vos informations et profitez d’un service
                                simple, rapide et professionnel.
                            </p>

                            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur border border-white/15">
                                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 7.5 12 3l9 4.5M4.5 9.75V18a2.25 2.25 0 0 0 2.25 2.25h10.5A2.25 2.25 0 0 0 19.5 18V9.75" />
                                        </svg>
                                    </div>
                                    <h3 class="font-extrabold text-lg">Service structuré</h3>
                                    <p class="mt-2 text-sm text-white/85">Un parcours clair pour gérer vos installations.</p>
                                </div>

                                <div class="rounded-3xl bg-white/12 p-5 backdrop-blur border border-white/15">
                                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6A11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.286Z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-extrabold text-lg">Fiable et sécurisé</h3>
                                    <p class="mt-2 text-sm text-white/85">Vos données et vos demandes sont bien organisées.</p>
                                </div>
                            </div>

                            <div class="mt-10">
                                <p class="text-sm text-white/85 mb-3">Vous avez déjà un compte ?</p>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 font-extrabold text-slate-900 transition hover:scale-[1.02] hover:bg-slate-100">
                                    Se connecter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>