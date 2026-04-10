{{-- resources/views/admin/admins/index.blade.php --}}
@extends('admin.layout')

@section('page_title', 'Admins')

@section('content')
@php
    $isSuper = auth()->user()->role === 'superadmin';
@endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Admins</h1>
        <p class="text-sm text-slate-500 mt-1">Gestion des utilisateurs administrateurs.</p>
    </div>

    @if($isSuper)
        <a href="{{ route('admin.admins.create') }}"
           class="inline-flex justify-center px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold shadow-sm">
            + Ajouter un admin
        </a>
    @endif
</div>

{{-- ✅ MOBILE VIEW (cards) --}}
<div class="grid grid-cols-1 gap-3 sm:hidden">
    @foreach($admins as $a)
        @php
            $online = $a->last_activity && $a->last_activity >= $threshold;
        @endphp

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <div class="font-semibold text-slate-900 truncate">{{ $a->name }}</div>
                    <div class="text-sm text-slate-500 truncate">{{ $a->email }}</div>
                    <div class="text-sm text-slate-500 mt-1">{{ $a->phone }}</div>
                </div>

                <div class="flex flex-col items-end gap-2 shrink-0">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                        {{ $a->role === 'superadmin'
                            ? 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200'
                            : 'bg-indigo-50 text-indigo-700 border-indigo-200' }}">
                        {{ strtoupper($a->role) }}
                    </span>

                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                        {{ $online
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                            : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                        {{ $online ? 'ONLINE' : 'OFFLINE' }}
                    </span>
                </div>
            </div>

            @if($isSuper)
                <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    @if($a->role === 'admin')
                        <a href="{{ route('admin.admins.edit', $a->id) }}"
                           class="inline-flex items-center justify-center h-10 w-10 rounded-xl
                                  bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 transition"
                           title="Modifier">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path
                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                <path
                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                            </svg>
                        </a>

                        <form action="{{ route('admin.admins.destroy', $a->id) }}" method="POST"
                              onsubmit="return confirm('Supprimer cet admin ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center justify-center h-10 w-10 rounded-xl
                                       bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 transition"
                                title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                        d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <span class="text-xs text-slate-400">—</span>
                    @endif
                </div>
            @endif
        </div>
    @endforeach
</div>

{{-- ✅ DESKTOP/TABLET VIEW (table) --}}
<div class="hidden sm:block bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Nom</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Téléphone</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Rôle</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Statut</th>
                    @if($isSuper)
                        <th class="px-4 py-3 text-right font-semibold text-slate-700">Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200">
                @foreach($admins as $a)
                    @php
                        $online = $a->last_activity && $a->last_activity >= $threshold;
                    @endphp

                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-800 font-medium whitespace-nowrap">{{ $a->name }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $a->email }}</td>
                        <td class="px-4 py-3 text-slate-700 whitespace-nowrap">{{ $a->phone }}</td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                                {{ $a->role === 'superadmin'
                                    ? 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200'
                                    : 'bg-indigo-50 text-indigo-700 border-indigo-200' }}">
                                {{ strtoupper($a->role) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border
                                {{ $online
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                {{ $online ? 'ONLINE' : 'OFFLINE' }}
                            </span>
                        </td>

                        @if($isSuper)
                            <td class="px-4 py-3 text-right">
                                @if($a->role === 'admin')
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.admins.edit', $a->id) }}"
                                           class="inline-flex items-center justify-center h-9 w-9 rounded-xl
                                                  bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 transition"
                                           title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                <path
                                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                                <path
                                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.admins.destroy', $a->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Supprimer cet admin ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center h-9 w-9 rounded-xl
                                                       bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 transition"
                                                title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                        d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $admins->links() }}
</div>
@endsection
