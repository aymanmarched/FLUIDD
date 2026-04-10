@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-6xl">

        <div class="mx-auto mb-6 max-w-3xl text-center">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                Étape 1
            </span>

            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                Choisissez une ou plusieurs machines
            </h2>

            <p class="mt-3 text-sm leading-6 text-slate-700 md:text-base">
                Sélectionnez les machines concernées pour continuer votre demande d’entretien.
            </p>
        </div>

        <form method="POST" action="{{ route('service.entretien.entretenir.step1.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($machines as $m)
                    <label class="group relative flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg has-[:checked]:border-primary has-[:checked]:bg-blue-50 has-[:checked]:shadow-md">
                        <div class="absolute z-40 right-3 top-3 hidden h-7 w-7 items-center justify-center rounded-full bg-primary text-sm font-extrabold text-white shadow group-has-[:checked]:flex">
                            ✓
                        </div>

                        @if($m->image)
                            <div class="overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                                <img src="{{ asset('storage/' . $m->image) }}"
                                     class="h-40 w-full object-cover transition duration-300 group-hover:scale-[1.00]"
                                     alt="{{ $m->name }}">
                                     <!-- class="h-40 w-full object-cover transition duration-300 group-hover:scale-[1.02]" -->
                            </div>
                        @endif

                        <div class="mt-4 flex-1">
                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $m->name }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $m->machine }}
                            </p>
                        </div>

                        <div class="mt-4 hidden  items-center justify-between rounded-xl border border-transparent bg-transparent x-3 py-2">
                            <span class="text-sm font-semibold text-transparent">Sélectionner</span>
                            <input type="checkbox" name="machines[]" value="{{ $m->id }}" class="h-0 w-0 accent-transparent">
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3.5 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Continuer
                </button>
            </div>
        </form>
    </div>
</section>
@endsection