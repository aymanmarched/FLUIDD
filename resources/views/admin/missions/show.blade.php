{{-- resources/views/admin/commandes/missions/show.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Détails mission')

@section('content')
@php
    $steps = $mission->steps ?? collect();
    $stepsByNo = $steps->keyBy('step_no');
    $statusLabel = strtoupper($mission->status ?? '—');
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-6">

    {{-- Header card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900 break-words">
                    Mission {{ $mission->reference }} <span class="text-slate-400 font-bold">—</span>
                    <span class="text-slate-800">{{ strtoupper($mission->kind) }}</span>
                </h1>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                 bg-slate-100 text-slate-700 border border-slate-200">
                        Status : {{ $statusLabel }}
                    </span>

                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                 bg-indigo-50 text-indigo-800 border border-indigo-200">
                        Step : {{ $mission->current_step }}/2
                    </span>
                </div>

                @if($client)
                    <div class="mt-4 text-sm text-slate-600">
                        Client :
                        <span class="font-semibold text-slate-900">{{ $client->nom }} {{ $client->prenom }}</span>
                        @if($client->ville)
                            <span class="text-slate-400">—</span>
                            Ville :
                            <span class="font-semibold text-slate-900">{{ $client->ville->name }}</span>
                        @endif
                    </div>
                @endif

                @if($reservation)
                    <div class="mt-1 text-sm text-slate-600">
                        RDV :
                        <span class="font-semibold text-slate-900">{{ $reservation->date_souhaite }}</span>
                        à
                        <span class="font-semibold text-slate-900">{{ $reservation->hour }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ route('admin.commandes') }}"
               class="shrink-0 inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                      bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                ← Retour
            </a>
        </div>
    </div>

    {{-- Steps --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">Détails des étapes</h2>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-700">
                2 étapes
            </span>
        </div>

        <div class="space-y-4">
            @for($i=1; $i<=2; $i++)
                @php $st = $stepsByNo->get($i); @endphp

                <div class="border border-slate-200 rounded-2xl p-4 sm:p-5 bg-slate-50">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="text-sm font-extrabold text-slate-900">Step {{ $i }}</div>

                            @if($st)
                                <div class="mt-1 text-xs text-slate-500">
                                    Enregistrée
                                    @if(!empty($st->updated_at))
                                        — {{ optional($st->updated_at)->format('d/m/Y H:i') }}
                                    @endif
                                </div>
                            @else
                                <div class="mt-1 text-xs text-slate-500">Aucune donnée</div>
                            @endif
                        </div>

                        @if($st && $st->media_path)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        bg-emerald-50 text-emerald-800 border border-emerald-200">
                                Média ajouté
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        bg-slate-100 text-slate-700 border border-slate-200">
                                Pas de média
                            </span>
                        @endif
                    </div>

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-slate-700 mb-2">Commentaire</div>
                        <div class="bg-white border border-slate-200 rounded-xl p-3 text-sm text-slate-800">
                            {{ $st?->comment ?? '-' }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-sm font-semibold text-slate-700 mb-2">Média</div>

                        @if($st && $st->media_path)
                            @php $url = asset('storage/'.$st->media_path); @endphp

                            <div class="bg-white border border-slate-200 rounded-xl p-3">
                                @if($st->media_type === 'video')
                                    <video class="w-full rounded-xl border border-slate-200" controls playsinline>
                                        <source src="{{ $url }}">
                                    </video>
                                @else
                                    <img class="w-full rounded-xl border border-slate-200"
                                         src="{{ $url }}" alt="media step {{ $i }}">
                                @endif

                                <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                    <a href="{{ $url }}" target="_blank"
                                       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                                              bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
                                        Ouvrir le média
                                    </a>

                                    <a href="{{ $url }}" download
                                       class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                                              bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
                                        Télécharger
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-sm text-slate-500 bg-white border border-slate-200 rounded-xl p-3">
                                -
                            </div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <div class="md:hidden sticky bottom-3">
        <div class="bg-white/95 backdrop-blur border border-slate-200 rounded-2xl p-3 shadow-sm">
            <a href="{{ route('admin.commandes') }}"
               class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl
                      bg-slate-900 hover:bg-slate-800 text-white font-semibold">
                Retour planning
            </a>
        </div>
    </div>

    <div class="hidden md:block">
        <a href="{{ route('admin.commandes') }}"
           class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl
                  bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold shadow-sm">
            Retour planning
        </a>
    </div>

</div>
@endsection