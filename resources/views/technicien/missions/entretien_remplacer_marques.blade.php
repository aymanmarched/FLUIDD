{{-- resources/views/technicien/missions/entretien_remplacer_marques.blade.php --}}
@extends('technicien.menu')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">

    <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-zinc-500 font-semibold">Proposition remplacement</div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-zinc-900">
                    Mission <span class="text-orange-600">{{ $mission->reference }}</span>
                </h1>
                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-orange-200 bg-orange-50 text-orange-900">
                    Choisir une marque par machine
                </div>
            </div>

            <button form="sendProposal"
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition w-full md:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
                Envoyer au client
            </button>
        </div>
    </div>

    <form id="sendProposal" method="POST" action="{{ route('technicien.missions.entretien.remplacer.marques.send', $mission) }}">
        @csrf

        @foreach($selections as $selection)
            @php
                $entMachine = $selection->machine;
                $remId = $entMachine->remplacer_machine_id ?? null;
                $remMachine = $remId ? ($replacementMachines[$remId] ?? null) : null;
            @endphp

            <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-zinc-500 font-extrabold">Machine à remplacer</div>
                        <div class="text-xl font-extrabold text-zinc-900">{{ $entMachine->name ?? 'Machine' }}</div>

                        @if($remMachine)
                            <div class="mt-2 text-sm text-zinc-600 font-semibold">
                                Remplacement: <span class="text-orange-700 font-extrabold">{{ $remMachine->name }}</span>
                            </div>
                        @endif
                    </div>

                    @if(!$remMachine)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border border-red-200 bg-red-50 text-red-800">
                            Aucun remplacement lié
                        </span>
                    @endif
                </div>

                @if(!$remMachine)
                    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 font-semibold">
                        remplacer_machine_id manquant: impossible de proposer une marque.
                    </div>
                @else
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($remMachine->marques as $m)
                            <label class="group relative rounded-2xl border border-zinc-200 bg-white p-5 cursor-pointer transition hover:shadow-soft
                                           has-[:checked]:ring-2 has-[:checked]:ring-orange-500
                                           has-[:checked]:border-orange-200 has-[:checked]:bg-orange-50">

                                <div class="absolute top-3 right-3 hidden group-has-[:checked]:flex
                                            items-center justify-center w-8 h-8 rounded-full bg-orange-600 text-white font-extrabold shadow-soft">
                                    ✓
                                </div>

                                <div class="space-y-3">
                                    @if($m->image)
                                        <img src="{{ asset('storage/' . $m->image) }}"
                                             class="rounded-xl w-full h-40 object-cover border border-zinc-200"
                                             alt="{{ $m->nom }}">
                                    @else
                                        <div class="rounded-xl w-full h-40 bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-500 font-semibold">
                                            Sans image
                                        </div>
                                    @endif

                                    <div class="flex items-start justify-between gap-3">
                                        <h4 class="text-lg font-extrabold text-zinc-900">{{ $m->nom }}</h4>
                                        <div class="text-right">
                                            <div class="text-xs text-zinc-500 font-bold uppercase">Prix</div>
                                            <div class="text-lg font-extrabold text-emerald-700">
                                                {{ number_format($m->prix ?? 0, 2) }} <span class="text-xs text-zinc-500">MAD</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if(is_array($m->caractere))
                                        <div class="space-y-2">
                                            @foreach($m->caractere as $c)
                                                <div class="flex items-start gap-2 text-sm text-zinc-800">
                                                    <span class="mt-1 h-2 w-2 rounded-full bg-orange-500"></span>
                                                    <span>{{ $c }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <input type="radio"
                                           name="marques[{{ $selection->id }}]"
                                           value="{{ $m->id }}"
                                           class="sr-only"
                                           required>
                                </div>

                                <div class="mt-4">
                                    <span class="inline-flex items-center justify-center w-full px-4 py-3 rounded-2xl border border-zinc-200 bg-white text-zinc-900 font-extrabold
                                                 group-has-[:checked]:border-orange-600 group-has-[:checked]:bg-orange-600 group-has-[:checked]:text-white transition">
                                        Choisir
                                    </span>
                                </div>

                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        <div class="sticky bottom-4">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white/90 backdrop-blur border border-zinc-200 rounded-2xl shadow-soft p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-sm text-zinc-600 font-semibold">
                        Vérifiez les choix, puis envoyez la proposition.
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto px-7 py-4 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold transition">
                        Envoyer la proposition
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
