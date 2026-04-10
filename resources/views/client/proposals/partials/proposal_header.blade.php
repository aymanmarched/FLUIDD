<div class="bg-white border rounded-2xl p-5 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-xl font-extrabold text-gray-900">Proposition de remplacement</div>
            <div class="text-sm text-gray-600 mt-1">
                Référence entretien: <strong>{{ $proposal->old_reference }}</strong>
            </div>
            <div class="text-sm text-gray-600">
                Status: <strong class="uppercase">{{ $proposal->status }}</strong>
            </div>
        </div>

        <span class="px-3 py-1 rounded-full text-xs font-extrabold
            {{ $proposal->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
            {{ $proposal->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
            {{ $proposal->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
        ">
            {{ strtoupper($proposal->status) }}
        </span>
    </div>

    @if($proposal->status === 'accepted' && $proposal->new_reference)
        <div class="mt-4">
            <a href="{{ route('client.remplacers.show', $proposal->new_reference) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                Voir la nouvelle commande
                <span class="opacity-90">({{ $proposal->new_reference }})</span>
            </a>
        </div>
    @endif
</div>
