@php
    use Illuminate\Support\Str;

    $site = $siteSettings ?? $settings ?? null;
    $companyName = $site->company_name ?? config('app.name', 'Fluid');
    $logoPath = $site->logo ?? null;

    $logoSrc = $logoPath
        ? (Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : asset('storage/' . $logoPath))
        : asset('images/logo.png');

    $loginErrors = $errors->getBag('login');
    $registerErrors = $errors->getBag('register');

    $startPanel = $registerErrors->any()
        ? 'register'
        : ($loginErrors->any() ? 'login' : old('mode', 'login'));

    $isRegister = $startPanel === 'register';

$inputBase = 'w-full rounded-xl border px-4 py-3 text-[13px] text-slate-800 placeholder:text-slate-500 outline-none transition duration-200 bg-slate-100 focus:bg-white focus:border-[#1E90FF] focus:ring-4 focus:ring-[#1E90FF]/10';    $inputState = fn ($hasError) => $hasError ? 'border-rose-300 bg-rose-50' : 'border-transparent';
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $companyName }} – Authentification</title>

    <link rel="icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="shortcut icon" href="{{ $logoSrc }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ $logoSrc }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff] px-4 py-5 font-montserrat md:px-5">
    <div class="mx-auto flex w-full max-w-[920px] flex-col items-center">
        <div class="mb-5 flex items-center gap-2.5 self-center md:self-center">
            <a href="{{ route('home') }}"
               class="group relative flex h-16 w-16 items-center justify-center overflow-hidden rounded-[20px] border border-white/90 bg-white/85 text-decoration-none shadow-[0_10px_28px_rgba(15,23,42,0.10)] backdrop-blur-md transition duration-300 hover:-translate-y-1 hover:scale-105 hover:border-sky-300/60 hover:shadow-[0_18px_34px_rgba(30,144,255,0.18),0_8px_18px_rgba(251,191,36,0.12)] before:absolute before:inset-0 before:bg-gradient-to-br before:from-blue-500/15 before:to-amber-300/15 before:opacity-0 before:transition before:duration-300 hover:before:opacity-100 after:absolute after:-left-[120%] after:-top-[60%] after:h-[220%] after:w-[90%] after:rotate-[18deg] after:bg-gradient-to-r after:from-transparent after:via-white/55 after:to-transparent after:transition-all after:duration-700 hover:after:left-[140%]">
                <img src="{{ $logoSrc }}"
                     alt="{{ $companyName }}"
                     onerror="this.src='{{ asset('images/logo.png') }}'"
                     class="relative z-10 h-11 w-11 object-contain transition duration-300 group-hover:scale-110 group-hover:rotate-3 group-hover:drop-shadow-[0_6px_10px_rgba(30,144,255,0.18)]">
            </a>

            <div>
                <div class="text-[18px] font-bold text-slate-900">{{ $companyName }}</div>
                <div class="text-[13px] text-slate-500">Espace client</div>
            </div>
        </div>

        <div id="container"
             class="relative w-full max-w-[920px] overflow-hidden rounded-[28px] bg-white shadow-[0_10px_30px_rgba(0,0,0,0.18)] min-h-[760px] md:min-h-[580px] md:rounded-[30px] {{ $isRegister ? 'active' : '' }}">

            {{-- REGISTER PANEL --}}
            <div id="signUpPanel"
                 class="absolute left-0 top-[190px] flex h-[calc(100%-190px)] w-full items-start justify-start overflow-y-auto bg-white px-[18px] pb-5 pt-[22px] transition-all duration-500 ease-in-out md:top-0 md:h-full md:w-1/2 md:items-center md:justify-center md:px-[34px] md:py-[30px]
                 {{ $isRegister
                    ? 'z-[5] translate-y-0 opacity-100 visible pointer-events-auto md:translate-x-full'
                    : 'z-[1] translate-y-6 opacity-0 invisible pointer-events-none md:translate-x-0'
                 }}">
                <form method="POST" action="{{ route('auth.submit') }}" id="registerForm" class="w-full max-w-full md:max-w-[430px]">
                    @csrf
                    <input type="hidden" name="mode" value="register">

                    <h1 class="mb-1 text-[21px] font-extrabold leading-tight text-slate-900 md:text-[22px]">
                        Créer un compte
                    </h1>
                    <div class="mb-4 text-[12px] leading-5 text-slate-500 md:mb-[18px] md:text-[13px]">
                        Remplissez vos informations pour créer votre espace client.
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div class="w-full">
                            <input type="text"
                                   name="nom"
                                   placeholder="Nom"
                                   value="{{ old('mode') === 'register' ? old('nom') : '' }}"
                                   class="{{ $inputBase }} {{ $inputState($registerErrors->has('nom')) }}"
                                   required>
                            @if ($registerErrors->has('nom'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('nom') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <input type="text"
                                   name="prenom"
                                   placeholder="Prénom"
                                   value="{{ old('mode') === 'register' ? old('prenom') : '' }}"
                                   class="{{ $inputBase }} {{ $inputState($registerErrors->has('prenom')) }}"
                                   required>
                            @if ($registerErrors->has('prenom'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('prenom') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <input type="text"
                                   name="telephone"
                                   maxlength="10"
                                   pattern="^(0)?[6-7][0-9]{8}$"
                                   placeholder="Téléphone (+212)"
                                   value="{{ old('mode') === 'register' ? old('telephone') : '' }}"
                                   class="{{ $inputBase }} {{ $inputState($registerErrors->has('telephone')) }}"
                                   required>
                            @if ($registerErrors->has('telephone'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('telephone') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <input type="email"
                                   name="email"
                                   placeholder="Email"
                                   value="{{ old('mode') === 'register' ? old('email') : '' }}"
                                   class="{{ $inputBase }} {{ $inputState($registerErrors->has('email')) }}"
                                   required>
                            @if ($registerErrors->has('email'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('email') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <select name="ville_id"
                                    class="{{ $inputBase }} {{ $inputState($registerErrors->has('ville_id')) }}">
                                <option value="">-- Choisir --</option>
                                @foreach($villes as $v)
                                    <option value="{{ $v->id }}" {{ old('mode') === 'register' && old('ville_id') == $v->id ? 'selected' : '' }}>
                                        {{ $v->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($registerErrors->has('ville_id'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('ville_id') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <input type="text"
                                   name="adresse"
                                   placeholder="Adresse"
                                   value="{{ old('mode') === 'register' ? old('adresse') : '' }}"
                                   class="{{ $inputBase }} {{ $inputState($registerErrors->has('adresse')) }}">
                            @if ($registerErrors->has('adresse'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('adresse') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <div class="relative">
                                <input type="password"
                                       name="password"
                                       id="password"
                                       placeholder="Mot de passe"
                                       class="{{ $inputBase }} pr-11 {{ $inputState($registerErrors->has('password')) }}"
                                       required>
                                <button type="button"
                                        class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 bg-transparent p-0 text-[15px] text-slate-500 transition hover:text-amber-500"
                                        data-target="password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @if ($registerErrors->has('password'))
                                <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                    <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                    <span>{{ $registerErrors->first('password') }}</span>
                                </small>
                            @endif
                        </div>

                        <div class="w-full">
                            <div class="relative">
                                <input type="password"
                                       name="password_confirmation"
                                       id="password_confirmation"
                                       placeholder="Confirmer le mot de passe"
                                       class="{{ $inputBase }} pr-11 border-transparent"
                                       required>
                                <button type="button"
                                        class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 bg-transparent p-0 text-[15px] text-slate-500 transition hover:text-amber-500"
                                        data-target="password_confirmation">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <small id="password-message"
                                   class="hidden w-full items-start gap-2 rounded-xl border px-3 py-2 text-[12px] font-semibold leading-[1.45]">
                                <i id="password-message-icon" class="fa-solid fa-circle-info mt-[1px] shrink-0 text-[12px]"></i>
                                <span id="password-message-text"></span>
                            </small>
                        </div>
                    </div>

                    <button type="submit"
                            id="register-submit"
                            class="mt-4 w-full rounded-xl border border-transparent bg-[#1E90FF] px-[18px] py-3 text-[13px] font-bold uppercase tracking-[0.4px] text-white transition duration-200 hover:-translate-y-[1px] hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-65 disabled:transform-none">
                        Créer mon compte
                    </button>
                </form>
            </div>

            {{-- LOGIN PANEL --}}
            <div id="signInPanel"
                 class="absolute left-0 top-[190px] flex h-[calc(100%-190px)] w-full items-start justify-start overflow-y-auto bg-white px-[18px] pb-5 pt-[22px] transition-all duration-500 ease-in-out md:top-0 md:h-full md:w-1/2 md:items-center md:justify-center md:px-[34px] md:py-[30px]
                 {{ $isRegister
                    ? 'z-[2] translate-y-6 opacity-0 invisible pointer-events-none md:translate-x-full'
                    : 'z-[3] translate-y-0 opacity-100 visible pointer-events-auto md:translate-x-0'
                 }}">
                <form method="POST" action="{{ route('auth.submit') }}" class="w-full max-w-full md:max-w-[390px]">
                    @csrf
                    <input type="hidden" name="mode" value="login">

                    <h1 class="mb-1 text-[21px] font-extrabold leading-tight text-slate-900 md:text-[22px]">
                        Connexion
                    </h1>
                    <div class="mb-4 text-[12px] leading-5 text-slate-500 md:mb-[18px] md:text-[13px]">
                        Entrez vos identifiants pour accéder à votre espace client.
                    </div>

                    @if (session('status'))
                        <small class="mb-3 flex w-full items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-emerald-700">
                            <i class="fa-solid fa-circle-check mt-[1px] shrink-0 text-[12px]"></i>
                            <span>{{ session('status') }}</span>
                        </small>
                    @endif

                    <div class="mb-3 w-full">
                        <input type="email"
                               name="email"
                               placeholder="Email"
                               value="{{ old('mode') === 'login' ? old('email') : '' }}"
                               class="{{ $inputBase }} {{ $inputState($loginErrors->has('email')) }}"
                               required>
                        @if ($loginErrors->has('email'))
                            <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                <span>{{ $loginErrors->first('email') }}</span>
                            </small>
                        @endif
                    </div>

                    <div class="mb-3 w-full">
                        <div class="relative">
                            <input type="password"
                                   name="password"
                                   id="login_password"
                                   placeholder="Mot de passe"
                                   class="{{ $inputBase }} pr-11 {{ $inputState($loginErrors->has('password')) }}"
                                   required>
                            <button type="button"
                                    class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 bg-transparent p-0 text-[15px] text-slate-500 transition hover:text-amber-500"
                                    data-target="login_password">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>

                        @if ($loginErrors->has('password'))
                            <small class="mt-2 flex w-full items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[12px] font-semibold leading-[1.45] text-rose-700">
                                <i class="fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]"></i>
                                <span>{{ $loginErrors->first('password') }}</span>
                            </small>
                        @endif
                    </div>

                    <div class="mb-2 mt-[2px] w-full">
                        <label class="flex cursor-pointer items-start gap-2.5 text-[13px] leading-[1.35] text-slate-700">
                            <input type="checkbox"
                                   name="remember"
                                   {{ old('mode') === 'login' && old('remember') ? 'checked' : '' }}
                                   class="mt-[2px] h-4 w-4 shrink-0 accent-[#1E90FF]">
                            <span>Enregistrer le mot de passe</span>
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="mt-2 inline-block text-[13px] text-slate-700 transition hover:text-amber-500">
                            Oublié mon mot de passe ?
                        </a>
                    @endif

                    <button type="submit"
                            class="mt-4 w-full rounded-xl border border-transparent bg-[#1E90FF] px-[18px] py-3 text-[13px] font-bold uppercase tracking-[0.4px] text-white transition duration-200 hover:-translate-y-[1px] hover:bg-blue-700">
                        Se connecter
                    </button>
                </form>
            </div>

            {{-- TOGGLE CONTAINER --}}
            <div id="toggleContainer"
                 class="absolute left-0 top-0 z-10 h-[190px] w-full overflow-hidden rounded-b-[32px] transition-all duration-500 ease-in-out md:left-1/2 md:h-full md:w-1/2 md:rounded-b-none
                 {{ $isRegister ? 'md:-translate-x-full md:rounded-r-[100px] md:rounded-l-none' : 'md:translate-x-0 md:rounded-l-[150px]' }}">
                <div id="toggleBg"
                     class="relative left-0 h-full w-full bg-gradient-to-r from-[#1E90FF] to-[#2563eb] text-white transition-transform duration-500 ease-in-out md:-left-full md:w-[200%] {{ $isRegister ? 'md:translate-x-1/2' : 'md:translate-x-0' }}">

                   <div id="toggleLeftPanel"
     class="absolute left-0 top-0 flex h-full w-full flex-col items-center justify-center px-5 text-center transition-all duration-500 ease-in-out md:w-1/2 md:px-[30px]
     {{ $isRegister ? 'translate-y-0 opacity-100 visible pointer-events-auto md:translate-x-0' : '-translate-y-4 opacity-0 invisible pointer-events-none md:-translate-x-[200%]' }}">
    <h1 class="mb-3 text-[22px] font-extrabold md:text-[26px]">Bon Retour !</h1>
    <p class="mb-3 max-w-[260px] text-[13px] leading-6 md:max-w-[280px] md:text-[14px]">
        Connectez-vous pour accéder à votre espace client et profiter de toutes les fonctionnalités du site.
    </p>
    <button class="rounded-lg border border-white bg-transparent px-7 py-2.5 text-[11px] font-semibold uppercase tracking-[0.5px] text-white transition hover:bg-white/10 md:px-[45px] md:text-[12px]"
            id="login"
            type="button">
        Connexion
    </button>
</div>

<div id="toggleRightPanel"
     class="absolute right-0 top-0 flex h-full w-full flex-col items-center justify-center px-5 text-center transition-all duration-500 ease-in-out md:w-1/2 md:px-[30px]
     {{ $isRegister ? 'translate-y-4 opacity-0 invisible pointer-events-none md:translate-x-[200%]' : 'translate-y-0 opacity-100 visible pointer-events-auto md:translate-x-0' }}">
    <h1 class="mb-3 text-[22px] font-extrabold md:text-[26px]">Bienvenue !</h1>
    <p class="mb-3 max-w-[260px] text-[13px] leading-6 md:max-w-[280px] md:text-[14px]">
        Créez votre compte pour rejoindre votre espace client et utiliser tous les services disponibles.
    </p>
    <button class="rounded-lg border border-white bg-transparent px-7 py-2.5 text-[11px] font-semibold uppercase tracking-[0.5px] text-white transition hover:bg-white/10 md:px-[45px] md:text-[12px]"
            id="register"
            type="button">
        Inscription
    </button>
</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const container = document.getElementById('container');
        const signInPanel = document.getElementById('signInPanel');
        const signUpPanel = document.getElementById('signUpPanel');
        const toggleContainer = document.getElementById('toggleContainer');
        const toggleBg = document.getElementById('toggleBg');
        const toggleLeftPanel = document.getElementById('toggleLeftPanel');
        const toggleRightPanel = document.getElementById('toggleRightPanel');

        const registerBtn = document.getElementById('register');
        const loginBtn = document.getElementById('login');

        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordMessage = document.getElementById('password-message');
        const passwordMessageText = document.getElementById('password-message-text');
        const passwordMessageIcon = document.getElementById('password-message-icon');
        const registerSubmit = document.getElementById('register-submit');

        function addClasses(el, classes) {
            el.classList.add(...classes);
        }

        function removeClasses(el, classes) {
            el.classList.remove(...classes);
        }

        function setAuthState(isRegister) {
            container.classList.toggle('active', isRegister);

            if (isRegister) {
                addClasses(signInPanel, ['z-[2]', 'translate-y-6', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-full']);
                removeClasses(signInPanel, ['z-[3]', 'translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);

                addClasses(signUpPanel, ['z-[5]', 'translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-full']);
                removeClasses(signUpPanel, ['z-[1]', 'translate-y-6', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-0']);

                addClasses(toggleContainer, ['md:-translate-x-full', 'md:rounded-r-[100px]', 'md:rounded-l-none']);
                removeClasses(toggleContainer, ['md:translate-x-0', 'md:rounded-l-[150px]']);

                addClasses(toggleBg, ['md:translate-x-1/2']);
                removeClasses(toggleBg, ['md:translate-x-0']);

                addClasses(toggleLeftPanel, ['translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);
                removeClasses(toggleLeftPanel, ['-translate-y-4', 'opacity-0', 'invisible', 'pointer-events-none', 'md:-translate-x-[200%]']);

                addClasses(toggleRightPanel, ['translate-y-4', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-[200%]']);
                removeClasses(toggleRightPanel, ['translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);
            } else {
                addClasses(signInPanel, ['z-[3]', 'translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);
                removeClasses(signInPanel, ['z-[2]', 'translate-y-6', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-full']);

                addClasses(signUpPanel, ['z-[1]', 'translate-y-6', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-0']);
                removeClasses(signUpPanel, ['z-[5]', 'translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-full']);

                addClasses(toggleContainer, ['md:translate-x-0', 'md:rounded-l-[150px]']);
                removeClasses(toggleContainer, ['md:-translate-x-full', 'md:rounded-r-[100px]', 'md:rounded-l-none']);

                addClasses(toggleBg, ['md:translate-x-0']);
                removeClasses(toggleBg, ['md:translate-x-1/2']);

                addClasses(toggleLeftPanel, ['-translate-y-4', 'opacity-0', 'invisible', 'pointer-events-none', 'md:-translate-x-[200%]']);
                removeClasses(toggleLeftPanel, ['translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);

                addClasses(toggleRightPanel, ['translate-y-0', 'opacity-100', 'visible', 'pointer-events-auto', 'md:translate-x-0']);
                removeClasses(toggleRightPanel, ['translate-y-4', 'opacity-0', 'invisible', 'pointer-events-none', 'md:translate-x-[200%]']);
            }
        }

        if (registerBtn) {
            registerBtn.addEventListener('click', () => setAuthState(true));
        }

        if (loginBtn) {
            loginBtn.addEventListener('click', () => setAuthState(false));
        }

        function setPasswordMessage(type, text) {
            passwordMessage.classList.remove(
                'hidden',
                'flex',
                'border-slate-200', 'bg-slate-50', 'text-slate-600',
                'border-rose-200', 'bg-rose-50', 'text-rose-700',
                'border-emerald-200', 'bg-emerald-50', 'text-emerald-700'
            );

            if (!text) {
                passwordMessage.classList.add('hidden');
                passwordMessageText.textContent = '';
                return;
            }

            passwordMessage.classList.add('flex');

            if (type === 'error') {
                passwordMessage.classList.add('border-rose-200', 'bg-rose-50', 'text-rose-700');
                passwordMessageIcon.className = 'fa-solid fa-circle-exclamation mt-[1px] shrink-0 text-[12px]';
            } else if (type === 'success') {
                passwordMessage.classList.add('border-emerald-200', 'bg-emerald-50', 'text-emerald-700');
                passwordMessageIcon.className = 'fa-solid fa-circle-check mt-[1px] shrink-0 text-[12px]';
            } else {
                passwordMessage.classList.add('border-slate-200', 'bg-slate-50', 'text-slate-600');
                passwordMessageIcon.className = 'fa-solid fa-circle-info mt-[1px] shrink-0 text-[12px]';
            }

            passwordMessageText.textContent = text;
        }

        function checkPasswords() {
            if (!password || !passwordConfirmation || !registerSubmit) return;

            if (passwordConfirmation.value.length === 0) {
                setPasswordMessage('info', '');
                registerSubmit.disabled = false;
                return;
            }

            if (password.value !== passwordConfirmation.value) {
                setPasswordMessage('error', 'Les mots de passe ne correspondent pas.');
                registerSubmit.disabled = true;
            } else {
                setPasswordMessage('success', 'Les mots de passe correspondent.');
                registerSubmit.disabled = false;
            }
        }

        if (password && passwordConfirmation) {
            password.addEventListener('input', checkPasswords);
            passwordConfirmation.addEventListener('input', checkPasswords);
        }

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = button.querySelector('i');

                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
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