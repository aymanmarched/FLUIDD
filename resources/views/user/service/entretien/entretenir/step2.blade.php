@extends('user.header')

@section('content')
<section class="w-full bg-gradient-to-br from-accent to-[#b4bfca] px-4 py-6 md:px-8 md:py-8">
    <div class="mx-auto max-w-6xl">

        <div class="mx-auto mb-6 max-w-3xl text-center">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/60 bg-white/70 px-4 py-2 text-xs font-bold text-slate-700 shadow-sm backdrop-blur">
                <span class="h-2 w-2 rounded-full bg-primary"></span>
                Étape 2
            </span>

            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 md:text-3xl">
                Choisissez un type pour chaque machine sélectionnée
            </h2>
        </div>

        <form method="POST" action="{{ route('service.entretien.entretenir.step2.store') }}">
            @csrf
            <input type="hidden" name="reference" value="{{ $reference }}">

            @foreach($selections as $selection)
                @php
                    $machine = $selection->machine;
                    $types = $machine->types;
                @endphp

                <div class="mb-6 rounded-2xl border border-slate-200 bg-white/85 p-4 shadow-sm backdrop-blur md:p-5">
                    <div class="mb-5 flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-900">
                                {{ $machine->name }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Sélectionnez le type adapté à cette machine.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach($types as $t)
                            <label class="group relative flex cursor-pointer flex-col rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md has-[:checked]:border-primary has-[:checked]:bg-blue-50">
                                <div class="absolute right-3 top-3 hidden h-7 w-7 items-center justify-center rounded-full bg-primary text-sm font-extrabold text-white shadow group-has-[:checked]:flex">
                                    ✓
                                </div>

                                <div class="flex-1">
                                    <h4 class="text-base font-extrabold text-slate-900 mb-2">{{ $t->name }}</h4>

                                    <div class="space-y-1 text-sm text-slate-600">
                                        @if(is_array($t->caracteres))
                                            @foreach($t->caracteres as $c)
                                                <p><span class="font-bold text-secondary">•</span> {{ $c }}</p>
                                            @endforeach
                                        @endif
                                    </div>

                                    <p class="mt-4 text-right text-lg font-extrabold text-secondary">
                                        {{ $t->prix }} MAD
                                    </p>
                                </div>

                                <div class="mt-4 hidden items-center justify-between rounded-xl border border-slate-200 bg-white px-3 py-2">
                                    <span class="text-sm font-semibold text-slate-700">Choisir</span>
                                    <input type="radio" name="types[{{ $selection->id }}]" value="{{ $t->id }}"
                                        class="h-5 w-5 accent-[#1E90FF]" required>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

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