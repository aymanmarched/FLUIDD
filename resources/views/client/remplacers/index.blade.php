@extends('client.menu')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="text-sm text-slate-500">Remplacement</div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Mes commandes de remplacement</h2>
                <p class="text-slate-600 mt-1">Suivez l’avancement et le paiement de vos demandes.</p>
            </div>

            <a href="{{ url('/remplacer') }}" target="_blank"
               class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-soft transition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                          d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                          clip-rule="evenodd" />
                </svg>
                Nouvelle commande
            </a>
        </div>
    </div>

    @if($commandes->isEmpty())
        <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-10 text-center">
            <div class="font-extrabold text-lg">Aucune commande de remplacement</div>
            <p class="text-slate-500 mt-1">Créez une nouvelle commande pour commencer.</p>
        </div>
    @else

        {{-- =========================
            DESKTOP TABLE (unchanged)
           ========================= --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-[980px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-sm font-semibold text-slate-700">
                            <th class="px-6 py-4">Référence</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Paiement</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @foreach($commandes as $cmd)
                            @php
                                $badge = match($cmd->status_code) {
                                    'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'in_progress' => 'bg-sky-50 text-sky-700 border-sky-100',
                                    'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-100',
                                    default       => 'bg-gray-50 text-slate-700 border-gray-200',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50/60">
                                <td class="px-6 py-4 font-extrabold text-slate-900">{{ $cmd->reference }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $cmd->date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-extrabold text-emerald-700">
                                    {{ number_format($cmd->total, 2) }} MAD
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border font-semibold {{ $badge }}">
                                        {{ $cmd->status_label }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    @if($cmd->is_paid)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                                            Payée
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm border bg-rose-50 text-rose-700 border-rose-100 font-semibold">
                                            Non payée
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('client.remplacers.show', $cmd->reference) }}"
                                           class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white shadow-soft transition"
                                           title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                                <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                            </svg>
                                        </a>

                                        @if($cmd->can_edit)
                                            <a href="{{ route('client.remplacers.edit', $cmd->reference) }}"
                                               class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white shadow-soft transition"
                                               title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if(!$cmd->is_paid)
                                            <a href="{{ route('client.payments.create', ['type' => 'remplacer', 'reference' => $cmd->reference]) }}"
                                               class="inline-flex items-center justify-center px-4 h-10 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-soft transition">
                                                Payer
                                                @if(($cmd->pay_discount_percent ?? 0) > 0)
                                                    <span class="ml-2 text-xs bg-white/20 px-2 py-0.5 rounded-full">-{{ $cmd->pay_discount_percent }}%</span>
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        {{-- =========================
            MOBILE CARDS (new)
           ========================= --}}
        <div class="md:hidden space-y-3">
            @foreach($commandes as $cmd)
                @php
                    $badge = match($cmd->status_code) {
                        'completed'   => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-100',
                        'cancelled'   => 'bg-rose-50 text-rose-700 border-rose-100',
                        default       => 'bg-gray-50 text-slate-700 border-gray-200',
                    };
                @endphp

                <div class="bg-white border border-gray-200 rounded-2xl shadow-soft overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs text-slate-500 font-semibold">Référence</div>
                                <div class="font-extrabold text-slate-900 truncate">{{ $cmd->reference }}</div>

                                <div class="mt-3 grid grid-cols-2 gap-3">
                                    <div class="min-w-0">
                                        <div class="text-xs text-slate-500 font-semibold">Date</div>
                                        <div class="text-sm font-semibold text-slate-800">{{ $cmd->date->format('d/m/Y') }}</div>
                                    </div>

                                    <div class="min-w-0 text-right">
                                        <div class="text-xs text-slate-500 font-semibold">Total</div>
                                        <div class="text-sm font-extrabold text-emerald-700">{{ number_format($cmd->total, 2) }} MAD</div>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0 flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs border font-semibold {{ $badge }}">
                                    {{ $cmd->status_label }}
                                </span>

                                @if($cmd->is_paid)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs border bg-emerald-50 text-emerald-700 border-emerald-100 font-semibold">
                                        Payée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs border bg-rose-50 text-rose-700 border-rose-100 font-semibold">
                                        Non payée
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('client.remplacers.show', $cmd->reference) }}"
                               class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-sky-600 hover:bg-sky-700 text-white font-semibold shadow-soft transition flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                                Voir
                            </a>

                            @if($cmd->can_edit)
                                <a href="{{ route('client.remplacers.edit', $cmd->reference) }}"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-semibold shadow-soft transition flex-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z" />
                                    </svg>
                                    Modifier
                                </a>
                            @endif

                            @if(!$cmd->is_paid)
                                <a href="{{ route('client.payments.create', ['type' => 'remplacer', 'reference' => $cmd->reference]) }}"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-soft transition w-full">
                                    Payer
                                    @if(($cmd->pay_discount_percent ?? 0) > 0)
                                        <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">-{{ $cmd->pay_discount_percent }}%</span>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
