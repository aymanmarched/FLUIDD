@props([
    'machine',
    'recommendedIds' => collect(), // collection of marque IDs recommended for this machine
])

@php
    $recommendedIds = collect($recommendedIds)->filter()->values();

    $totalDays = (int) ($machine->garantie_period_days ?? 0);
    $years = intdiv($totalDays, 365);
    $months = intdiv($totalDays % 365, 30);

    $marques = $machine->marques ?? collect();

    // recommended first
    $marques = $marques->sortByDesc(fn($m) => $recommendedIds->contains($m->id));
@endphp

<div class="rounded-3xl overflow-hidden border bg-white shadow-sm">
    <div class="p-6 bg-gradient-to-r from-slate-900 to-slate-700 text-white">
        <div class="flex items-start gap-4">
            @if($machine->image)
                <img src="{{ asset('storage/'.$machine->image) }}"
                     class="w-20 h-20 rounded-2xl object-cover border border-white/20"
                     alt="{{ $machine->name }}">
            @else
                <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center">
                    —
                </div>
            @endif

            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="text-xl font-extrabold tracking-tight">{{ $machine->name }}</h3>

                    @if($totalDays > 0)
                        <span class="text-xs font-black px-3 py-1 rounded-full bg-amber-400 text-slate-900">
                            Garantie
                            @if($years > 0) • {{ $years }} an(s) @endif
                            @if($months > 0) • {{ $months }} mois @endif
                        </span>
                    @endif
                </div>

                <div class="mt-2 text-sm text-white/80">
                    Marques disponibles: <span class="font-bold">{{ $marques->count() }}</span>
                    • Recommandées: <span class="font-bold">{{ $recommendedIds->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($marques->isEmpty())
            <div class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-2xl p-4">
                Aucune marque liée à cette machine.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($marques as $marque)
                    @php $isRec = $recommendedIds->contains($marque->id); @endphp

                    <div class="relative rounded-2xl border p-4 transition
                        {{ $isRec ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-gray-200 bg-white hover:shadow-sm' }}">
                        @if($isRec)
                            <span class="absolute top-3 right-3 text-xs font-black px-3 py-1 rounded-full bg-blue-600 text-white">
                                Recommandée
                            </span>
                        @endif

                        <div class="flex gap-3">
                            @if($marque->image)
                                <img src="{{ asset('storage/'.$marque->image) }}"
                                     class="w-16 h-16 rounded-xl object-cover border bg-white"
                                     alt="{{ $marque->nom }}">
                            @endif

                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="text-lg font-extrabold text-gray-900">{{ $marque->nom }}</div>

                                        @if(is_array($marque->caractere) && count($marque->caractere))
                                            <div class="mt-2 space-y-1 text-sm text-gray-700">
                                                @foreach($marque->caractere as $c)
                                                    <div class="flex gap-2">
                                                        <span class="font-black {{ $isRec ? 'text-blue-600' : 'text-gray-400' }}">•</span>
                                                        <span>{{ $c }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    @if(!is_null($marque->prix))
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500">Prix</div>
                                            <div class="text-lg font-extrabold {{ $isRec ? 'text-blue-700' : 'text-gray-900' }}">
                                                {{ $marque->prix }} MAD
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
