@extends('client.menu')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

  <!-- Header -->
  <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="text-sm text-slate-500">Centre</div>
        <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Notifications</h2>
        <p class="text-slate-600 mt-1">Suivez les mises à jour de vos commandes et garanties.</p>
      </div>

      <form method="POST" action="{{ route('client.notifications.readAll') }}" class="w-full sm:w-auto">
        @csrf
        <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl border border-gray-200 hover:bg-gray-50 font-semibold transition">
          Tout marquer comme lu
        </button>
      </form>
    </div>
  </div>

  <!-- List -->
  <div class="space-y-3">
    @forelse($notifications as $n)
      @php
        $isUnread = is_null($n->read_at);
      @endphp

      <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">

          <!-- Left -->
          <div class="flex gap-4 min-w-0">
            <!-- Icon -->
            <div class="shrink-0 h-11 w-11 rounded-2xl flex items-center justify-center
                        {{ $isUnread ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-slate-600' }}">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                   viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9" />
              </svg>
            </div>

            <!-- Content -->
            <div class="min-w-0">
              <div class="flex flex-wrap items-center gap-2">
                <div class="font-extrabold text-slate-900 truncate">
                  {{ $n->data['title'] ?? 'Notification' }}
                </div>

                @if($isUnread)
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-sky-600 text-white">
                    Nouveau
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-slate-600 border border-gray-200">
                    Lu
                  </span>
                @endif
              </div>

              @if(!empty($n->data['message']))
                <div class="text-sm text-slate-600 mt-1 break-words">
                  {{ $n->data['message'] }}
                </div>
              @endif

              <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-slate-500">
                @if(!empty($n->data['reference']))
                  <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-50 border border-gray-200">
                    <span class="font-semibold">Référence:</span> {{ $n->data['reference'] }}
                  </span>
                @endif

                <span class="inline-flex items-center gap-2">
                  <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                  {{ optional($n->created_at)->diffForHumans() }}
                </span>
              </div>
            </div>
          </div>

          <!-- Right -->
          <form method="POST" action="{{ route('client.notifications.read', $n->id) }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 rounded-2xl
                           {{ $isUnread ? 'bg-sky-600 hover:bg-sky-700 text-white' : 'border border-gray-200 hover:bg-gray-50 text-slate-800' }}
                           font-semibold shadow-soft transition">
              Ouvrir
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                   viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </form>

        </div>
      </div>
    @empty
      <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-10 text-center">
        <div class="mx-auto h-12 w-12 rounded-2xl bg-sky-100 text-sky-700 flex items-center justify-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
               viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0 1 18 14.158V11a6.002 6.002 0 0 0-4-5.659V5a2 2 0 1 0-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 1 1-6 0m6 0H9" />
          </svg>
        </div>
        <div class="font-extrabold text-lg">Aucune notification</div>
        <p class="text-slate-500 mt-1">Vous êtes à jour.</p>
      </div>
    @endforelse
  </div>

  <!-- Pagination -->
   @if ($notifications->hasPages())
  <div class="bg-white border border-gray-200 rounded-2xl shadow-soft p-4 overflow-x-auto">
    {{ $notifications->links() }}
  </div>
  @endif

</div>
@endsection
