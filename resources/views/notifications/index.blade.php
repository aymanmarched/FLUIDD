@extends(in_array(auth()->user()->role, ['admin', 'superadmin']) ? 'admin.layout' : 'technicien.menu')
@section('page_title', 'Notifications')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Header -->
        <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="text-sm text-zinc-500">Centre</div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Notifications</h1>
                    <p class="text-zinc-600 mt-1">Consultez les dernières mises à jour.</p>
                </div>

                <form method="POST" action="{{ route('notifications.read_all') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-zinc-900 hover:bg-zinc-800 text-white font-extrabold shadow-soft transition">
                        Tout marquer comme lu
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M2.25 12a9.75 9.75 0 1 1 18.235 4.725.75.75 0 0 1-1.298-.75A8.25 8.25 0 1 0 4.563 15.97a.75.75 0 1 1-1.298.75A9.708 9.708 0 0 1 2.25 12Z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M16.28 8.22a.75.75 0 0 1 0 1.06l-5.25 5.25a.75.75 0 0 1-1.06 0l-2.25-2.25a.75.75 0 1 1 1.06-1.06l1.72 1.72 4.72-4.72a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="bg-white border border-dashed border-zinc-200 rounded-2xl p-10 text-center">
                <div class="mx-auto h-12 w-12 rounded-2xl bg-orange-100 text-orange-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0m6 0H9" />
                    </svg>
                </div>
                <div class="font-extrabold text-lg">Aucune notification</div>
                <p class="text-zinc-500 mt-1">Les mises à jour apparaîtront ici.</p>
            </div>
        @else

            <!-- DESKTOP LIST -->
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden hidden md:block">
                @foreach($notifications as $n)
                    @php $d = $n->data; @endphp

                    <a href="{{ route('notifications.show', $n->id) }}" class="block p-6 border-b border-zinc-200 hover:bg-zinc-50/60 transition
                               {{ $n->read_at ? '' : 'bg-orange-50/60' }}">

                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <div class="font-extrabold text-zinc-900 truncate">
                                        {{ $d['title'] ?? 'Notification' }}
                                    </div>
                                    @if(!$n->read_at)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-orange-600 text-white">
                                            Nouveau
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-zinc-600 mt-1">
                                    <span class="font-semibold text-zinc-800">Client:</span> {{ $d['client_name'] ?? '-' }}
                                    <span class="text-zinc-400">—</span>
                                    <span class="font-semibold text-zinc-800">Réf:</span>
                                    {{ $d['reference'] ?? ($d['reference_new'] ?? '-') }}
                                </div>

                                @if(!empty($d['changes']))
                                    <div class="text-sm text-zinc-500 mt-2">
                                        {{ count($d['changes']) }} changement(s)
                                    </div>
                                @endif
                            </div>

                            <div class="shrink-0 text-xs text-zinc-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($d['changed_at'] ?? $n->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- MOBILE CARDS -->
            <div class="md:hidden space-y-3">
                @foreach($notifications as $n)
                    @php $d = $n->data; @endphp

                    <a href="{{ route('notifications.show', $n->id) }}" class="block bg-white border border-zinc-200 rounded-2xl shadow-soft overflow-hidden transition hover:bg-zinc-50
                               {{ $n->read_at ? '' : 'ring-1 ring-orange-200 bg-orange-50/40' }}">

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <div class="font-extrabold text-zinc-900 truncate">
                                            {{ $d['title'] ?? 'Notification' }}
                                        </div>
                                        @if(!$n->read_at)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-orange-600 text-white">
                                                Nouveau
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 text-sm text-zinc-700">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-zinc-500 font-semibold">Client</span>
                                            <span class="font-semibold text-zinc-900 truncate max-w-[65%]">
                                                {{ $d['client_name'] ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between gap-3 mt-1">
                                            <span class="text-zinc-500 font-semibold">Réf</span>
                                            <span
                                                class="font-mono text-xs text-zinc-700 bg-white border border-zinc-200 px-2 py-1 rounded-full truncate max-w-[65%]">
                                                {{ $d['reference'] ?? ($d['reference_new'] ?? '-') }}
                                            </span>
                                        </div>
                                    </div>

                                    @if(!empty($d['changes']))
                                        <div
                                            class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs border border-zinc-200 bg-zinc-50 text-zinc-700 font-semibold">
                                            {{ count($d['changes']) }} changement(s)
                                        </div>
                                    @endif
                                </div>

                                <div class="shrink-0 text-xs text-zinc-500 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($d['changed_at'] ?? $n->created_at)->diffForHumans() }}
                                </div>
                            </div>

                            <div
                                class="mt-4 inline-flex items-center justify-center w-full gap-2 px-4 py-2 rounded-2xl bg-orange-600 hover:bg-orange-700 text-white font-extrabold shadow-soft transition">
                                Ouvrir
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M13.5 4.5a.75.75 0 0 1 .75-.75h6a.75.75 0 0 1 .75.75v6a.75.75 0 0 1-1.5 0V6.31l-9.22 9.22a.75.75 0 1 1-1.06-1.06l9.22-9.22H14.25a.75.75 0 0 1-.75-.75Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M3.75 7.5A3.75 3.75 0 0 1 7.5 3.75h3a.75.75 0 0 1 0 1.5h-3A2.25 2.25 0 0 0 5.25 7.5v9A2.25 2.25 0 0 0 7.5 18.75h9A2.25 2.25 0 0 0 18.75 16.5v-3a.75.75 0 0 1 1.5 0v3A3.75 3.75 0 0 1 16.5 20.25h-9A3.75 3.75 0 0 1 3.75 16.5v-9Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($notifications->hasPages())
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-soft p-4">
                    {{ $notifications->links() }}
                </div>
            @endif

        @endif

    </div>
@endsection