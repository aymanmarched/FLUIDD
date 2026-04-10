@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-5 md:px-8 md:py-7">
    <div class="mx-auto max-w-5xl">

        <div class="mx-auto mb-5 max-w-3xl text-center">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                Étape 2
            </span>

            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                Choisissez une marque pour chaque machine
            </h2>
        </div>

        <form method="POST" action="{{ route('service.remplacer.step2.store') }}">
            @csrf
            <input type="hidden" name="reference" value="{{ $reference }}">

            @foreach($selections as $selection)
                @php
                    $machine = $selection->machine;
                @endphp

                <div class="mb-5 rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm backdrop-blur md:p-5">
                    <div class="mb-4 border-b border-slate-100 pb-3">
                        <h3 class="text-lg font-extrabold text-slate-900 md:text-xl">
                            {{ $machine->name }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Sélectionnez la marque souhaitée pour cette machine.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($selection->machine->marques as $m)
                            <label class="group relative flex cursor-pointer flex-col rounded-xl border border-slate-200 bg-slate-50 p-3 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-blue-50">

                                <div class="absolute right-3 top-3 hidden h-7 w-7 items-center justify-center rounded-full bg-primary text-sm font-extrabold text-white shadow group-has-[:checked]:flex">
                                    ✓
                                </div>

                                <div class="flex-1">
                                    @if($m->image)
                                        <div class="mb-3 flex h-28 items-center justify-center overflow-hidden rounded-lg border border-slate-100 bg-white md:h-32">
                                            <img src="{{ asset('storage/' . $m->image) }}"
                                                 class="max-h-full w-full object-contain p-2"
                                                 alt="{{ $m->nom }}">
                                        </div>
                                    @endif

                                    <h4 class="mb-2 text-base font-extrabold text-slate-900">
                                        {{ $m->nom }}
                                    </h4>

                                    <div class="space-y-1 text-[13px] leading-5 text-slate-600">
                                        @if(is_array($m->caractere))
                                            @foreach(collect($m->caractere)->take(4) as $c)
                                                <p><span class="font-bold text-secondary">•</span> {{ $c }}</p>
                                            @endforeach
                                        @endif
                                    </div>

                                    <p class="mt-3 text-right text-base font-extrabold text-secondary md:text-lg">
                                        {{ $m->prix }} MAD
                                    </p>
                                </div>

                                <div class="mt-3 hidden items-center justify-between rounded-lg border border-slate-200 bg-white px-3 py-2">
                                    <span class="text-sm font-semibold text-slate-700">Choisir</span>
                                    <input type="radio"
                                           name="marques[{{ $selection->id }}]"
                                           value="{{ $m->id }}"
                                           class="h-4 w-4 accent-[#1E90FF]"
                                           required>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="mt-6 text-center">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-8 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                    Continuer
                </button>
            </div>
        </form>
    </div>
</section>
@endsection