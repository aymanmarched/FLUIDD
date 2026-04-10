@extends(in_array(auth()->user()->role, ['admin','superadmin']) ? 'admin.layout' : 'technicien.menu')
@section('page_title', 'Notifications Détails')

@section('content')
    @php $d = $n->data; @endphp

    <div class="max-w-4xl mx-auto p-6">

        <a href="{{ route('notifications.index') }}" class="inline-flex px-4 py-2 bg-gray-800 text-white rounded-lg mb-6">
            Retour
        </a>

        <h1 class="text-2xl font-bold mb-2">{{ $d['title'] ?? 'Notification' }}</h1>

        <div class="bg-white rounded-2xl shadow border p-6 mb-6">
            <p><strong>Client:</strong> {{ $d['client_name'] ?? '-' }}</p>
            <p><strong>Référence:</strong> {{ $d['reference'] ?? '-' }}</p>

            @if(isset($d['reference_old'], $d['reference_new']))
                <p><strong>Ancienne Réf:</strong> {{ $d['reference_old'] }}</p>
                <p><strong>Nouvelle Réf:</strong> {{ $d['reference_new'] }}</p>
            @endif

            <p class="text-sm text-gray-500 mt-2">
                {{ $d['changed_at'] ?? $n->created_at }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow border p-6">
            <h2 class="text-lg font-semibold mb-4">Détails des changements</h2>

            @if(empty($d['changes']))
                <p class="text-gray-600">Aucun détail.</p>
            @else
                <ul class="space-y-3">
                    @foreach($d['changes'] as $c)
                        <li class="p-4 bg-gray-50 border rounded-xl">
                            <div class="font-semibold text-gray-800">
                                {{ $c['field'] }}
                                @if(!empty($c['machine']))
                                    — <span class="text-blue-600">{{ $c['machine'] }}</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-700 mt-1">
                                @if(array_key_exists('from', $c))
                                    <div><strong>Avant:</strong> {{ $c['from'] ?? '-' }}</div>
                                @endif
                                @if(array_key_exists('to', $c))
                                    <div><strong>Après:</strong> {{ $c['to'] ?? '-' }}</div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            @php
                $d = $n->data ?? [];
                $role = auth()->user()->role ?? '';
                $techLink = data_get($d, 'panel_links.technicien');
                $adminLink = data_get($d, 'panel_links.admin');
            @endphp

            {{-- Technicien --}}
            @if($techLink && $role === 'technicien')
                <a href="{{ $techLink }}" class="inline-flex mt-6 px-5 py-2 bg-blue-600 text-white rounded-xl">
                    Ouvrir la commande
                </a>
            @endif

            {{-- Admin --}}
@if($adminLink && in_array($role, ['admin','superadmin']))
                <a href="{{ $adminLink }}" class="inline-flex mt-6 px-5 py-2 bg-blue-600 text-white rounded-xl">
                    Ouvrir la commande
                </a>
            @endif

        </div>

    </div>
@endsection