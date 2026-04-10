@extends('user.header')

@section('content')

    <!-- HERO SECTION 1 -->
    <section class="px-10 lg:px-20 py-10 flex flex-col lg:flex-row items-center justify-between bg-transparant">

        <!-- LEFT COLUMN -->
        <div class="max-w-2xl space-y-6">

            <!-- TITLE -->
            <h1 class="text-2xl md:text-3xl font-extrabold leading-tight text-gray-900 uppercase tracking-wide">
                Le premier service de maintenance thermique structuré,
                <span class="text-[#1E90FF] ">transparent et fiable au Maroc,</span>
                <span class="block text-[#FFB703] ">qui sécurise vos installations et réduit vos pannes.</span>
            </h1>

            <!-- SUBTEXT -->
            <p class="text-xl text-gray-600 leading-relaxed">
                Une expertise dédiée à la performance, la sécurité et la longévité
                de vos installations thermiques, avec un service professionnel et constant.
            </p>

            <!-- CTA BUTTONS -->
            <div class="flex flex-wrap gap-6 pt-4 justify-center">

                <!-- PRIMARY CTA -->
                <a href="{{ url('/service/entretien/entretenir-ma-maison') }}">

                    <button
                        class="flex items-center gap-3 bg-[#1E90FF] hover:bg-[#0F6EC4] shadow-lg hover:shadow-xl 
                                                               text-white font-bold px-10 py-4 text-xl rounded-2xl transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
                        </svg>
                        Entretenir Ma Maison
                    </button>
                </a>

                <!-- SECONDARY CTA -->
                <a href="{{ url('/service/entretien/activer-ma-garantie') }}">

                    <button
                        class="flex items-center gap-3 border-2 border-yellow-300 hover:text-gray-900 
                                                               bg-yellow-300 text-gray-700 font-bold px-10 py-4 hover:bg-yellow-400 hover:border-yellow-400
                                                               text-xl rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>

                        Activer Ma Garantie
                    </button>
                </a>

                <!-- give it defrent style -->
                <a href="{{ url('/remplacer') }}">


                    <button
                        class="flex items-center gap-3 border-2 border-red-500 hover:text-white
                                                               bg-red-500 text-gray-200 font-bold px-10 py-4 hover:bg-red-700 hover:border-red-700
                                                               text-xl rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>


                        Remplacer mon machine
                    </button>
                </a>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="flex flex-col items-center mt-12 lg:mt-0 space-y-8">

            <!-- IMAGE -->
            <div>
                <img src="{{ asset('images/homelg.png') }}" alt="Climatisation"
                    class="w-[540px] drop-shadow-2xl animate-fadeIn">
            </div>

            <!-- INFO STRIP -->
            <div class="bg-white shadow-xl border border-gray-100 rounded-2xl 
                                                    px-8 py-4 flex items-center gap-6 w-fit">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-[#FFB703]" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z"
                        clip-rule="evenodd" />
                </svg>

                <div>
                    <p class="font-bold text-gray-900 text-lg">Lundi – Samedi</p>
                    <p class="text-gray-600 text-sm">08:30 – 18:00</p>
                </div>
            </div>

        </div>

    </section>
    <!-- SECTION 2 -->
    <section
        class="p-10  md:px-20 bg-accent flex flex-col items-center justify-between border-b-2 border-accent space-y-20">

        <div class="bg-accent p-8 rounded-3xl text-center">
            <img src="{{ asset('images/garantie.png') }}" class="w-32 mx-auto  ">
            <h3 class="text-2xl font-bold mb-4">Garantie 100%</h3>
            <p class="text-gray-700 text-lg">Toutes nos installations et réparations sont couvertes par une garantie
                complète.</p>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-center  gap-12 w-full">

            <!-- LEFT TEXT AREA -->
            <div class="flex flex-col max-w-xl space-y-6 mx-6">

                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Installation de Climatisation
                </h1>

                <p class="text-lg text-gray-700 leading-relaxed">
                    Profitez d’un confort optimal toute l’année grâce à nos solutions de climatisation
                    performantes et parfaitement adaptées à votre espace. Nous assurons une installation
                    rapide, sécurisée et conforme aux normes, tout en vous offrant un service fiable pour
                    garantir la longévité et l’efficacité de votre équipement.
                </p>
            </div>

            <!-- RIGHT IMAGE -->
            <div class="rounded-tr-[25%] rounded-bl-[25%] overflow-hidden shadow-2xl">
                <img src="{{ asset('images/bg1.jpg') }}" alt="AC Installation" class="w-full h-full object-cover">
            </div>

        </div>




        <div class="flex flex-col md:flex-row items-center justify-center  gap-12 w-full">



            <!-- RIGHT IMAGE -->
            <div class="rounded-tr-[25%] rounded-bl-[25%] overflow-hidden shadow-2xl">
                <img src="{{ asset('images/bg3.jpg') }}" alt="Réparation climatisation" class="w-full h-full object-cover">
            </div>
            <!-- LEFT TEXT AREA -->
            <div class="flex flex-col max-w-xl space-y-6 mx-6">

                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Dépannage de Climatisation
                </h1>

                <p class="text-lg text-gray-700 leading-relaxed">
                    Votre climatiseur ne fonctionne plus correctement ? Bruits étranges, perte de
                    performance, fuite ou impossibilité de refroidir votre espace ? Nos techniciens
                    interviennent rapidement pour diagnostiquer l’origine du problème et effectuer
                    un dépannage fiable et durable. Nous réparons toutes marques et tous modèles
                    afin de garantir votre confort au plus vite.
                </p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-center  gap-12 w-full">


            <!-- LEFT TEXT AREA -->
            <div class="flex flex-col max-w-xl space-y-6 mx-6">

                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                    Entretien de Climatisation
                </h1>

                <p class="text-lg text-gray-700 leading-relaxed">
                    Pour garantir le bon fonctionnement de votre climatiseur tout au long de l’année,
                    un entretien régulier est indispensable. Nettoyage des filtres, contrôle des fluides,
                    vérification des performances et détection des fuites : nous assurons un entretien
                    complet pour optimiser la durée de vie de votre appareil et réduire votre consommation
                    énergétique.
                </p>
            </div>
            <!-- RIGHT IMAGE -->
            <div class="rounded-tr-[25%] rounded-bl-[25%] overflow-hidden shadow-2xl">
                <img src="{{ asset('images/bg2.webp') }}" alt="Entretien climatisation" class="w-full h-full object-cover">
            </div>

        </div>
    </section>
    <!-- SECTION 3 -->

   <section class="w-full py-20 px-6 md:px-10 bg-white">
    <h2 class="text-center text-3xl md:text-4xl font-bold text-gray-900 mb-10">
        Nous réparons toutes les marques
    </h2>

    @php
        $validMarques = collect($marques)->filter(function ($m) {
            $img = (string) ($m->image ?? '');
            return $img !== '' && strlen($img) <= 255;
        })->values();
    @endphp

    <div class="max-w-7xl mx-auto">

        {{-- MOBILE VERSION --}}
        <div class="md:hidden">
            <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <div id="mobileBrandsTrack" class="flex transition-transform duration-700 ease-in-out">
                    @foreach($validMarques->chunk(4) as $chunk)
                        <div class="min-w-full p-4">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($chunk as $m)
                                    @php
                                        $img = (string) ($m->image ?? '');
                                        $src = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                                            ? $img
                                            : asset('storage/' . $img);
                                    @endphp

                                    <div
                                        class="h-24 rounded-2xl border border-gray-100 bg-gray-50 flex items-center justify-center p-3 shadow-sm hover:shadow-md transition duration-300">
                                        <img src="{{ $src }}" alt="{{ $m->nom }}" title="{{ $m->nom }}" loading="lazy"
                                            class="max-h-12 w-auto object-contain opacity-80 hover:opacity-100 hover:scale-110 transition duration-300"
                                            onerror="this.style.display='none'">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($validMarques->chunk(4)->count() > 1)
                <div id="mobileBrandsDots" class="flex items-center justify-center gap-2 mt-5">
                    @foreach($validMarques->chunk(4) as $i => $chunk)
                        <button type="button"
                            class="mobile-brand-dot h-2.5 w-2.5 rounded-full bg-gray-300 transition-all duration-300 {{ $i === 0 ? '!w-6 bg-[#1E90FF]' : '' }}"
                            data-index="{{ $i }}"></button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- DESKTOP VERSION --}}
        <div class="hidden md:block">
            <!-- <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white"> -->
            <div class="relative overflow-hidden rounded-2xl">
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-white to-transparent z-10">
                </div>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-white to-transparent z-10">
                </div>

                <div id="marqueeTrack" class="marquee-track flex items-center gap-10 py-6 will-change-transform">
                    @foreach($validMarques as $m)
                        @php
                            $img = (string) ($m->image ?? '');
                            $src = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://'])
                                ? $img
                                : asset('storage/' . $img);
                        @endphp

                        <div class="shrink-0 flex items-center justify-center px-2">
                            <img src="{{ $src }}" alt="{{ $m->nom }}" title="{{ $m->nom }}" loading="lazy"
                                class="h-14 md:h-32 w-auto object-contain opacity-80 hover:opacity-100 hover:scale-110 transition duration-300"
                                onerror="this.style.display='none'">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .marquee-track {
            animation: marquee 40s linear infinite;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        @media (prefers-reduced-motion: reduce) {
            .marquee-track {
                animation: none;
            }

            #mobileBrandsTrack {
                transition: none !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // DESKTOP MARQUEE
            const track = document.getElementById('marqueeTrack');
            if (track && !track.dataset.cloned) {
                track.dataset.cloned = "1";
                track.innerHTML += track.innerHTML;
            }

            // MOBILE SLIDER
            const mobileTrack = document.getElementById('mobileBrandsTrack');
            const dots = document.querySelectorAll('.mobile-brand-dot');

            if (!mobileTrack) return;

            const totalSlides = mobileTrack.children.length;
            if (totalSlides <= 1) return;

            let current = 0;

            function updateMobileSlider(index) {
                current = index;
                mobileTrack.style.transform = `translateX(-${current * 100}%)`;

                dots.forEach((dot, i) => {
                    dot.classList.remove('!w-6', 'bg-[#1E90FF]');
                    dot.classList.add('bg-gray-300');

                    if (i === current) {
                        dot.classList.add('!w-6', 'bg-[#1E90FF]');
                        dot.classList.remove('bg-gray-300');
                    }
                });
            }

            let interval = setInterval(() => {
                updateMobileSlider((current + 1) % totalSlides);
            }, 2800);

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    clearInterval(interval);
                    updateMobileSlider(i);

                    interval = setInterval(() => {
                        updateMobileSlider((current + 1) % totalSlides);
                    }, 2800);
                });
            });
        });
    </script>
</section>

    <!-- SECTION 4 -->
    <section class="w-full py-20 bg-gradient-to-br from-accent to-[#b4bfca] overflow-hidden">

        <h2 class="text-center text-4xl font-bold text-gray-800 mb-6">
            Avis Clients
        </h2>

        <!-- Swiper Container -->
        <div class="max-w-7xl mx-auto px-4">

            <div class="swiper myAvisSwiper !overflow-visible">
                <div class="swiper-wrapper items-stretch">

                    @foreach($avis as $a)
                        <div class="swiper-slide h-auto">

                            <!-- CARD -->
                            <div class="bg-white p-8 h-full
                                                                                           rounded-bl-[25%] rounded-tr-[25%]
                                                                                           shadow-[rgba(0,0,0,0.15)_1.95px_1.95px_2.6px]
                                                                                           mx-3 flex flex-col">

                                <!-- HEADER -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <img src="{{ asset('images/logo.png') }}" class="w-10">
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold">
                                                {{ $a->nom }} {{ $a->prenom }}
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $a->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <img src="{{ asset('images/GOOGLE.png') }}" class="w-8">
                                </div>

                                <div class="border-t border-gray-300 my-3"></div>

                                <!-- STARS -->
                                <div class="text-yellow-400 text-xl mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $a->stars ? 'text-yellow-400' : 'text-gray-300' }}">
                                            ★
                                        </span>
                                    @endfor
                                </div>

                                <!-- MESSAGE -->
                                <p class="text-gray-700 text-lg leading-relaxed flex-1">
                                    {{ $a->message }}
                                </p>

                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- Pagination -->
                <div class="swiper-pagination !relative mt-12"></div>
            </div>

        </div>

        <!-- ADD REVIEW -->


        <div class="max-w-xl mx-auto mt-5">

            <!-- ADD REVIEW BUTTON -->
            <!-- <button id="toggleAvis" class="w-full mb-6 bg-white/60 backdrop-blur-xl text-gray-800  text-lg  py-3 px-10 rounded-full font-bold
                                               hover:bg-white transition shadow-lg">
                                Ajouter un avis
                            </button> -->

            <!-- FORM -->
            <form id="avisForm" action="{{ route('avis.client.store') }}" method="POST"
                class="hidden bg-white shadow-xl rounded-2xl p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <input name="nom" placeholder="Nom" required class="border p-2 rounded-lg">
                    <input name="prenom" placeholder="Prénom" required class="border p-2 rounded-lg">
                </div>

                <input name="telephone" placeholder="Téléphone" required class="border p-2 rounded-lg w-full">

                <!-- STARS -->
                <div>
                    <label class="block font-semibold mb-2">Votre Évaluation</label>
                    <div id="stars" class="flex gap-2 text-3xl cursor-pointer">
                        @for($i = 1; $i <= 5; $i++)
                            <span data-value="{{ $i }}" class="star text-gray-300 hover:scale-110 transition">
                                ★
                            </span>
                        @endfor
                    </div>
                    <input type="hidden" name="stars" id="starsInput" required>
                </div>

                <textarea name="message" placeholder="Votre message" class="border p-2 rounded-lg w-full"></textarea>

                <button type="submit"
                    class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 transition">
                    Envoyer l'avis
                </button>
            </form>
        </div>

        <!-- SUCCESS / EDIT MODAL -->
        @if(session('avis'))
            @php $avis = session('avis'); @endphp
            <div id="avisModal" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
                <div class="bg-white p-8 rounded-2xl max-w-md w-full relative">

                    <!-- Close Button -->
                    <button id="closeModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900"><svg
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd"
                                d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <h2 class="text-2xl font-bold mb-4 text-green-700">Avis ajouté avec succès !</h2>

                    <div class="mb-4">
                        <p><strong>Nom :</strong> {{ $avis->nom }}</p>
                        <p><strong>Prénom :</strong> {{ $avis->prenom }}</p>
                        <p><strong>Téléphone :</strong> {{ $avis->telephone }}</p>
                        <p><strong>Évaluation :</strong>
                            @for($i = 1; $i <= 5; $i++)
                                <span class="edit-star {{ $i <= $avis->stars ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </p>
                        <p><strong>Votre Avis :</strong> {{ $avis->message }}</p>
                    </div>


                    <div class="flex justify-end gap-3">
                        <button id="editAvisBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                            Modifier
                        </button>
                        <a href="{{ url()->current() }}"
                            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">Fermer</a>
                    </div>

                    <!-- EDIT FORM (Hidden by default) -->
                    <form id="editAvisForm" action="{{ route('avis.client.update', $avis->id) }}" method="POST"
                        class="hidden mt-4 space-y-3">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-3">
                            <input name="nom" value="{{ $avis->nom }}" required class="border p-2 rounded-lg">
                            <input name="prenom" value="{{ $avis->prenom }}" required class="border p-2 rounded-lg">
                        </div>

                        <input name="telephone" value="{{ $avis->telephone }}" required class="border p-2 rounded-lg w-full">

                        <!-- Stars -->
                        <div>
                            <label class="block font-semibold mb-2">Votre note</label>
                            <div id="editStars" class="flex gap-2 text-3xl cursor-pointer">
                                @for($i = 1; $i <= 5; $i++)
                                    <span data-value="{{ $i }}"
                                        class="edit-star {{ $i <= $avis->stars ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                            <input type="hidden" name="stars" id="editStarsInput" value="{{ $avis->stars }}" required>
                        </div>

                        <textarea name="message" class="border p-2 rounded-lg w-full">{{ $avis->message }}</textarea>

                        <button type="submit"
                            class="w-full bg-green-600 text-white py-3 rounded-xl font-bold hover:bg-green-700 transition">
                            Enregistrer les modifications
                        </button>
                    </form>

                </div>
            </div>
        @endif



    </section>

    <!-- FOOTER -->


    <!-- FOOTER -->
    @php
        use Illuminate\Support\Str;

        $companyName = $settings->company_name ??($settings?->company_name ?? 'Fluid.ma');

        $logoPath = $settings->logo ?? null;

        $logoSrc = $logoPath
            ? (Str::startsWith($logoPath, ['http://', 'https://']) ? $logoPath : asset('storage/' . $logoPath))
            : asset('images/logo.png');

        $s = $siteSettings ?? $settings ?? null;

        $addr1 = $s->footer_address_line1 ?? '';
        $addr2 = $s->footer_address_line2 ?? '';
        $city = $s->footer_city ?? '';
        $country = $s->footer_country ?? '';

        $mapSrc = $s->footer_map_embed_url ?? null;

        $email = $s->footer_email ?? null;
        $phone = $s->footer_phone ?? null;

        $socials = \App\Models\SiteSocialLink::where('is_enabled', true)
            ->orderBy('sort_order')
            ->get();
    @endphp

    <style>
        .footer-social-icon svg,
        .footer-social-icon svg * {
            fill: currentColor !important;
            stroke: currentColor !important;
        }
    </style>

    <footer class="relative overflow-hidden bg-[#0B2E63] text-white">
        <!-- decorative background -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#0B2E63] via-[#123F85] to-[#0A2550]"></div>

        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-[#1E90FF]/20 blur-3xl"></div>
        <div class="absolute top-10 right-0 h-52 w-52 rounded-full bg-[#FFB703]/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 h-40 w-80 bg-[#FFB703] rounded-tr-[100px] opacity-90"></div>
        <div class="absolute bottom-0 right-0 h-40 w-96 bg-[#1E90FF] rounded-tl-[120px] opacity-80"></div>

        <div class="relative max-w-7xl mx-auto px-6 md:px-12 lg:px-16 py-16">

            <!-- top -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">

                <!-- column 1 : logo + address + map -->
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-4 mb-6">
                <a href="/" class="flex items-center gap-3">

                        <div
                             class="h-20 w-20    flex items-center justify-center ">
                            <!-- class="h-16 w-16 rounded-2xl bg-white/10 border border-white/20 backdrop-blur flex items-center justify-center overflow-hidden shadow-lg"> -->
                            <img src="{{ $logoSrc }}" alt="{{ $companyName }}" class="h-18 w-18 object-contain transition-transform duration-300 ease-out hover:scale-110"
                                onerror="this.src='{{ asset('images/logo.png') }}'">
                        </div>
                </a>

                        <div>
                            <h3 class="text-2xl font-extrabold tracking-wide text-white">
                                {{ $companyName }}
                            </h3>
                            <p class="text-sm text-blue-100">
                                Installation · Dépannage · Entretien
                            </p>
                        </div>

                    </div>

                    <div class="mb-5">
                        <div class="flex items-start gap-3 mb-3">
                            <div class="mt-1 text-[#FFB703]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="m11.54 22.351.07.04.028.016a.75.75 0 0 0 .724 0l.027-.015.071-.041a16.98 16.98 0 0 0 1.145-.742 19.57 19.57 0 0 0 2.682-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 1 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.57 19.57 0 0 0 2.682 2.282 16.98 16.98 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-lg font-bold text-white">Localisation</h4>

                                @if(trim($addr1 . $addr2 . $city . $country) !== '')
                                    <p class="text-blue-50 leading-relaxed">
                                        {{ $addr1 }}
                                        @if($addr2) , {{ $addr2 }} @endif
                                        @if($city) , {{ $city }} @endif
                                        @if($country) , {{ $country }} @endif
                                    </p>
                                @else
                                    <p class="text-blue-100/80">Adresse non configurée.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-white/15 bg-white/10 backdrop-blur shadow-2xl">
                        @if($mapSrc)
                            <iframe class="w-full h-56" loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade" src="{{ $mapSrc }}">
                            </iframe>
                        @else
                            <div class="h-56 flex items-center justify-center text-blue-100/80">
                                Carte non configurée
                            </div>
                        @endif
                    </div>
                </div>

                <!-- column 2 : social + links -->
                <div class="lg:col-span-4 lg:px-4">
                    <div class="mb-8">
                        <h4 class="text-3xl font-extrabold text-[#FFB703] mb-4">Suivez-nous</h4>
                        <div class="h-px bg-white/15 mb-5"></div>

                        <div class="flex flex-wrap gap-4">
                            @forelse($socials as $soc)
                                @php
                                    $color = trim((string) ($soc->color ?: '#ffffff'));
                                    $isGradient = str_contains(strtolower($color), 'gradient');
                                @endphp

                                <a href="{{ $soc->url }}" target="_blank" rel="noopener" title="{{ $soc->name }}"
                                    aria-label="{{ $soc->name }}" class="group h-14 w-14 rounded-2xl border border-white/15 flex items-center justify-center transition duration-300 shadow-lg hover:-translate-y-1 hover:scale-105
                                          {{ $isGradient ? '' : 'bg-white/10 hover:bg-white/20' }}"
                                    style="{{ $isGradient ? "background: {$color};" : '' }}">

                                    <span class="footer-social-icon flex items-center justify-center [&_svg]:w-10 [&_svg]:h-10"
                                        style="color: {{ $isGradient ? '#ffffff' : $color }};">
                                        {!! $soc->icon_svg !!}
                                    </span>
                                </a>
                            @empty
                                <div class="text-blue-100/80">Aucun réseau social configuré.</div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h4 class="text-3xl font-extrabold text-[#FFB703] mb-4">Liens utiles</h4>
                        <div class="h-px bg-white/15 mb-5"></div>

                        <ul class="space-y-3 text-lg">
                            <!-- <li>
                                    <a href="/" class="text-blue-50 hover:text-[#FFB703] transition font-medium">
                                        • Accueil
                                    </a>
                                </li> -->
                            <!-- <li>
                                    <a href="/Contactez_Nous" class="text-blue-50 hover:text-[#FFB703] transition font-medium">
                                        • Contactez-nous
                                    </a>
                                </li> -->
                            <li>
                                <a href="{{ url('/service/entretien/entretenir-ma-maison') }}"
                                    class="text-blue-50 hover:text-[#FFB703] transition font-medium">
                                    • Entretenir ma maison
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/service/entretien/activer-ma-garantie') }}"
                                    class="text-blue-50 hover:text-[#FFB703] transition font-medium">
                                    • Activer ma garantie
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/remplacer') }}"
                                    class="text-blue-50 hover:text-[#FFB703] transition font-medium">
                                    • Remplacer mon machine
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- column 3 : contact -->
                <div class="lg:col-span-4">
                    <h4 class="text-3xl font-extrabold text-[#FFB703] mb-4">Contact</h4>
                    <div class="h-px bg-white/15 mb-5"></div>

                    <div class="space-y-4">
                        @if($email)
                            <a href="mailto:{{ $email }}"
                                class="flex items-center gap-4 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 px-5 py-4 transition shadow-lg">
                                <div
                                    class="h-12 w-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M1.5 8.67v8.58A2.25 2.25 0 0 0 3.75 19.5h16.5A2.25 2.25 0 0 0 22.5 17.25V8.67l-8.7 5.08a3.75 3.75 0 0 1-3.8 0L1.5 8.67Z" />
                                        <path
                                            d="M22.5 6.75A2.25 2.25 0 0 0 20.25 4.5H3.75A2.25 2.25 0 0 0 1.5 6.75v.2l9.36 5.46a2.25 2.25 0 0 0 2.28 0L22.5 6.95v-.2Z" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm text-blue-100/80">Email</div>
                                    <div class="font-bold text-xl text-white truncate">{{ $email }}</div>
                                </div>
                            </a>
                        @endif

                        @if($phone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"
                                class="flex items-center gap-4 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/15 px-5 py-4 transition shadow-lg">
                                <div
                                    class="h-12 w-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <div class="text-sm text-blue-100/80">Téléphone</div>
                                    <div class="font-bold text-xl text-white truncate">{{ $phone }}</div>
                                </div>
                            </a>
                        @endif

                        @if(!$email && !$phone)
                            <div class="text-blue-100/80">Contact non configuré.</div>
                        @endif
                    </div>

                    <div class="mt-6 rounded-2xl border border-white/15 bg-white/10 px-5 py-4 shadow-lg">
                        <div class="text-sm text-blue-100/80 mb-1">Horaires</div>
                        <div class="font-bold text-lg text-white">Lundi – Samedi</div>
                        <div class="text-blue-50">08:30 – 18:00</div>
                    </div>

                    <div class="mt-6 mb-6 sm:mb-2">
                        <a href="/Contactez_Nous"
                            class="inline-flex items-center gap-3 rounded-2xl bg-[#FFB703] hover:bg-[#e6a602] text-slate-900 font-extrabold px-6 py-4 transition shadow-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M1.5 8.67v8.58A2.25 2.25 0 0 0 3.75 19.5h16.5A2.25 2.25 0 0 0 22.5 17.25V8.67l-8.7 5.08a3.75 3.75 0 0 1-3.8 0L1.5 8.67Z" />
                                <path
                                    d="M22.5 6.75A2.25 2.25 0 0 0 20.25 4.5H3.75A2.25 2.25 0 0 0 1.5 6.75v.2l9.36 5.46a2.25 2.25 0 0 0 2.28 0L22.5 6.95v-.2Z" />
                            </svg>
                            Contactez-nous
                        </a>
                    </div>
                </div>

            </div>

            <!-- bottom -->
            <div class="mt-12 pt-6 border-t border-white/15 flex flex-col md:flex-row items-center justify-center gap-4">
                <p class="text-blue-100 text-sm md:text-base text-center md:text-left">
                    © 2026 <a href="/" class="font-extrabold text-white hover:text-[#FFB703] transition">{{ $companyName }}</a>. Tous droits réservés.
                </p>

                <!-- <div class="flex items-center gap-4 text-sm md:text-base">
                        <a href="/" class="text-blue-100 hover:text-[#FFB703] transition">Accueil</a>
                        <span class="text-white/30">•</span>
                        <a href="/Contactez_Nous" class="text-blue-100 hover:text-[#FFB703] transition">Contact</a>
                    </div> -->
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Toggle add review form
            const toggleBtn = document.getElementById('toggleAvis');
            const avisForm = document.getElementById('avisForm');

            toggleBtn.addEventListener('click', () => {
                avisForm.classList.toggle('hidden');
            });

            // Star selection (Add)
            const stars = document.querySelectorAll('#stars .star');
            const starsInput = document.getElementById('starsInput');
            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const val = star.dataset.value;
                    starsInput.value = val;
                    stars.forEach(s => s.classList.toggle('text-yellow-400', s.dataset.value <= val));
                    stars.forEach(s => s.classList.toggle('text-gray-300', s.dataset.value > val));
                });
            });

            // Modal close
            const modal = document.getElementById('avisModal');
            if (modal) {
                document.getElementById('closeModal').addEventListener('click', () => modal.remove());

                // Edit button
                const editBtn = document.getElementById('editAvisBtn');
                const editForm = document.getElementById('editAvisForm');
                editBtn.addEventListener('click', () => {
                    editForm.classList.remove('hidden');
                });

                // Edit stars
                const editStars = document.querySelectorAll('#editStars .edit-star');
                const editStarsInput = document.getElementById('editStarsInput');
                editStars.forEach(star => {
                    star.addEventListener('click', () => {
                        const val = star.dataset.value;
                        editStarsInput.value = val;
                        editStars.forEach(s => s.classList.toggle('text-yellow-400', s.dataset.value <= val));
                        editStars.forEach(s => s.classList.toggle('text-gray-300', s.dataset.value > val));
                    });
                });
            }
        });
    </script>


    <script>
        new Swiper('.myAvisSwiper', {
            loop: true,
            spaceBetween: 30,

            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>

@endsection