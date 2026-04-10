{{-- ===================== --}}
{{-- ✅ technicians/show.blade.php --}}
{{-- ===================== --}}
@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-0 py-6 space-y-6">

    {{-- ✅ COOL MOBILE HEADER (details page) --}}
<div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

    <div class="min-w-0 flex items-start gap-3">
        <span class="shrink-0 inline-flex items-center justify-center w-12 h-12 sm:w-10 sm:h-10 rounded-2xl
                     bg-indigo-600/10 border border-indigo-100 shadow-sm">
            <svg class="w-6 h-6 text-indigo-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16.5 8.25V6.75A4.5 4.5 0 0 0 12 2.25a4.5 4.5 0 0 0-4.5 4.5v1.5m-1.5 0h12a1.5 1.5 0 0 1 1.5 1.5v9.75a1.5 1.5 0 0 1-1.5 1.5h-12a1.5 1.5 0 0 1-1.5-1.5V9.75A1.5 1.5 0 0 1 6 8.25Z" />
            </svg>
        </span>

        <div class="min-w-0">
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Détails du Technicien
            </h1>
            <p class="text-sm text-slate-500 mt-1">Informations du compte.</p>
        </div>
    </div>

    {{-- Mobile: full width button | Desktop: icon button --}}
    <div class="flex sm:justify-end">
        <a href="{{ route('admin.technicians') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                  px-4 py-3 sm:px-0 sm:py-0
                  rounded-2xl sm:rounded-2xl
                  bg-slate-900 hover:bg-black text-white font-extrabold
                  sm:w-11 sm:h-11 shadow-sm transition active:scale-[0.98]"
           title="Retour">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd"
                      d="M15.78 19.28a.75.75 0 0 1-1.06 0l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 1 1 1.06 1.06L10.31 12l5.47 5.47a.75.75 0 0 1 0 1.06Z"
                      clip-rule="evenodd" />
            </svg>

            <span class="sm:hidden">Retour</span>
        </a>
    </div>

</div>


    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 sm:p-7 bg-slate-50 border-b border-slate-200 flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-100 flex items-center justify-center">
                <span class="font-extrabold text-indigo-700">{{ strtoupper(substr($technician->name,0,1)) }}</span>
            </div>
            <div class="min-w-0">
                <div class="text-lg font-extrabold text-slate-900 truncate">{{ $technician->name }}</div>
                <div class="text-sm text-slate-500">Créé le {{ $technician->created_at->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="p-5 sm:p-7 space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-extrabold text-slate-500 uppercase">Email</div>
                    <div class="font-semibold text-slate-900 break-words">{{ $technician->email }}</div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                    <div class="text-xs font-extrabold text-slate-500 uppercase">Téléphone</div>
                    <div class="font-semibold text-slate-900">{{ $technician->phone }}</div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:col-span-2" x-data="{ show:false }">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-xs font-extrabold text-slate-500 uppercase">Mot de passe</div>
                            <div class="font-mono text-slate-900" x-text="show ? '{{ $technician->password_visible }}' : '••••••••'"></div>
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
            </div>

            <div class="pt-2 flex flex-col sm:flex-row gap-3 sm:justify-end">
                <a href="{{ route('admin.technicians.edit', $technician->id) }}"
                   class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm transition">
                    Modifier
                </a>

                <form action="{{ route('admin.technicians.destroy', $technician->id) }}" method="POST" onsubmit="confirmDelete(this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold shadow-sm transition">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(form) {
        event.preventDefault();
        Swal.fire({
            title: 'Supprimer le technicien ?',
            text: "Cette action est irréversible.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Oui, supprimer'
        }).then((result) => { if (result.isConfirmed) form.submit(); });
    }
</script>
@endsection