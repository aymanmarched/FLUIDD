@if($proposal->status === 'pending')
    <div class="flex flex-col sm:flex-row gap-3">
        <form method="POST" action="{{ route('client.proposals.accept', $proposal->token) }}">
            @csrf
            <button class="w-full px-5 py-3 bg-green-600 text-white rounded-2xl font-extrabold hover:bg-green-700 transition">
                Accepter & convertir
            </button>
        </form>

        <form method="POST" action="{{ route('client.proposals.reject', $proposal->token) }}">
            @csrf
            <button class="w-full px-5 py-3 bg-red-600 text-white rounded-2xl font-extrabold hover:bg-red-700 transition">
                Refuser
            </button>
        </form>
    </div>
@endif
