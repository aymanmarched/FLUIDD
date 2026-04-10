{{-- ===================== --}}
{{-- ✅ technicians/index.blade.php --}}
{{-- ===================== --}}
@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">

   {{-- ✅ COOL MOBILE HEADER (stack on mobile, same row on desktop) --}}
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

    {{-- Left --}}
    <div class="min-w-0 flex items-start gap-3">
        <span class="shrink-0 inline-flex items-center justify-center w-12 h-12 sm:w-10 sm:h-10 rounded-2xl
                     bg-emerald-600/10 border border-emerald-100 shadow-sm">
            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path fill-rule="evenodd"
                      d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75Z"
                      clip-rule="evenodd" />
            </svg>
        </span>

        <div class="min-w-0">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Techniciens
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Gestion des comptes techniciens.
            </p>
        </div>
    </div>

    {{-- Right (mobile full width button) --}}
    <div class="flex items-center gap-3 sm:justify-end">
        <a href="{{ route('admin.technicians.create') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3
                  rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold
                  shadow-sm transition active:scale-[0.98]">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z"
                      clip-rule="evenodd" />
            </svg>
            Ajouter
        </a>
    </div>

</div>


    {{-- ✅ MOBILE CARDS --}}
    <div class="sm:hidden space-y-3">
        @forelse($technicians as $tech)
            <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-4 space-y-3">

                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-2xl bg-indigo-600/10 border border-indigo-100 flex items-center justify-center shrink-0">
                                <span class="font-extrabold text-indigo-700">
                                    {{ strtoupper(substr($tech->name, 0, 1)) }}
                                </span>
                            </div>

                            <div class="min-w-0">
                                <div class="text-base font-extrabold text-slate-900 truncate">{{ $tech->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">Créé: {{ $tech->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        <a href="{{ route('admin.technicians.show', $tech->id) }}"
                           class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                <path fill-rule="evenodd"
                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-500 uppercase">Téléphone</div>
                            <div class="text-sm font-semibold text-slate-900 truncate">{{ $tech->phone }}</div>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-500 uppercase">Email</div>
                            <div class="text-sm font-semibold text-slate-900 break-words">{{ $tech->email }}</div>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3" x-data="{ show:false }">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-[11px] font-extrabold text-slate-500 uppercase">Mot de passe</div>
                                <div class="font-mono text-sm text-slate-900" x-text="show ? '{{ $tech->password_visible }}' : '••••••••'"></div>
                            </div>

                            <button type="button" @click="show=!show"
                                    class="w-11 h-11 rounded-2xl bg-white border border-slate-200 hover:bg-slate-100 flex items-center justify-center">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-slate-700">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                                </svg>
                                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 3l18 18M10.584 10.59a2 2 0 102.827 2.828m1.414-1.414A6 6 0 006 12m.318-2.498A10.05 10.05 0 0112 6c4.994 0 9.163 3.676 9.682 8.502" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <!-- <a href="{{ route('admin.technicians.show', $tech->id) }}"
                           class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm">
                            Voir
                        </a> -->
                        <a href="{{ route('admin.technicians.edit', $tech->id) }}"
                           class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm">
                                                Modifier

                        </a>
                        <form action="{{ route('admin.technicians.destroy', $tech->id) }}" method="POST" onsubmit="confirmDelete(this)">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold shadow-sm">
                                                        Supprimer

                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-sm text-slate-500 text-center py-10">Aucun technicien.</div>
        @endforelse
    </div>

    {{-- ✅ DESKTOP TABLE --}}
    <div class="hidden sm:block bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-600 uppercase text-xs">
                        <th class="px-6 py-3 text-left font-extrabold">Nom</th>
                        <th class="px-6 py-3 text-left font-extrabold">Email</th>
                        <th class="px-6 py-3 text-left font-extrabold">Téléphone</th>
                        <th class="px-6 py-3 text-left font-extrabold">Mot de passe</th>
                        <th class="px-6 py-3 text-left font-extrabold">Créé le</th>
                        <th class="px-6 py-3 text-left font-extrabold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-200">
                    @foreach($technicians as $tech)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-900 max-w-[180px] truncate">{{ $tech->name }}</td>
                            <td class="px-6 py-4 text-slate-700 max-w-[240px] truncate">{{ $tech->email }}</td>
                            <td class="px-6 py-4 text-slate-700 max-w-[160px] truncate">{{ $tech->phone }}</td>

                            <td class="px-6 py-4">
                                <div x-data="{ show:false }" class="flex items-center gap-3">
                                    <span x-text="show ? '{{ $tech->password_visible }}' : '••••••••'" class="font-mono text-slate-800"></span>
                                    <button type="button" @click="show=!show"
                                            class="p-2 rounded-xl hover:bg-slate-100 border border-slate-200">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M3 3l18 18M10.584 10.59a2 2 0 102.827 2.828m1.414-1.414A6 6 0 006 12m.318-2.498A10.05 10.05 0 0112 6c4.994 0 9.163 3.676 9.682 8.502" />
                                        </svg>
                                    </button>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-700">{{ $tech->created_at->format('d/m/Y') }}</td>

                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.technicians.show', $tech->id) }}"
                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.technicians.edit', $tech->id) }}"
                                       class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.technicians.destroy', $tech->id) }}" method="POST" onsubmit="confirmDelete(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold shadow-sm">
                                            Del
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Supprimer ce technicien ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Oui, supprimer'
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    }
</script>
@endsection