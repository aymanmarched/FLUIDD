@php
    $grouped = $mission->recommendations->groupBy('machine_id');
@endphp

<div class="bg-white border rounded-2xl p-5 shadow-sm">
    <div class="font-extrabold text-gray-900 mb-4">Détails des machines & recommandations</div>

    <div class="space-y-6">
        @forelse($grouped as $machineId => $recs)
            @php
                $machine = $recs->first()->machine;
                $recommendedIds = $recs->pluck('marque_id')->filter()->unique()->values();
            @endphp

            @if($machine)
                @include('client.proposals.partials.machine_card', [
                    'machine' => $machine,
                    'recommendedIds' => $recommendedIds
                ])
            @endif
        @empty
            <div class="text-sm text-red-600">Aucune recommandation.</div>
        @endforelse
    </div>
</div>
