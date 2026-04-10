{{-- ✅ PRO MOBILE + RESPONSIVE (cards mobile, table desktop) --}}
@extends('admin.layout')

@section('content')

<div
    x-data="{
        currentTab: 'active',
        client: null,
        activeClients: {{ json_encode($activeClients) }},
        fixedClients: {{ json_encode($fixedClients) }},

        fmtDate(v) {
            if (!v) return '';
            const d = new Date(v);
            return d.toLocaleString('fr-FR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
        },

        initials(name='') {
            const parts = String(name).trim().split(/\s+/).filter(Boolean);
            const a = parts[0]?.[0] || '';
            const b = parts[1]?.[0] || '';
            return (a + b).toUpperCase();
        },

        async markFixed(c) {
            const res = await fetch(`/client/${c.id}/fix`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            c.date_fix = data.date_fix;
            this.fixedClients.push(c);
            this.activeClients = this.activeClients.filter(x => x.id !== c.id);
            this.client = null;
        },

        async unfix(c) {
            await fetch(`/client/${c.id}/unfix`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            this.fixedClients = this.fixedClients.filter(x => x.id !== c.id);
            this.activeClients.push(c);
        }
    }"
    class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6"
>

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">Clients</h1>
            <p class="text-sm text-slate-500 mt-1">Suivi des demandes : en cours / fixés.</p>
        </div>

        {{-- Tabs (compact on mobile) --}}
        <div class="inline-flex rounded-2xl bg-slate-100 p-1 border border-slate-200 shadow-sm">
            <button
                @click="currentTab='active'"
                :class="currentTab==='active' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-800'"
                class="px-3 sm:px-4 py-2 rounded-xl text-sm font-extrabold transition"
            >
                Active
                <span class="ml-2 text-xs px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100"
                      x-text="activeClients.length"></span>
            </button>

            <button
                @click="currentTab='fixed'"
                :class="currentTab==='fixed' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-800'"
                class="px-3 sm:px-4 py-2 rounded-xl text-sm font-extrabold transition"
            >
                Fixed
                <span class="ml-2 text-xs px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100"
                      x-text="fixedClients.length"></span>
            </button>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- MOBILE DETAIL SHEET (professional) --}}
    {{-- ===================== --}}
    <div x-show="client" x-cloak class="fixed inset-0 z-50 sm:hidden" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-black/40" @click="client=null"></div>

        <div
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-y-6 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-6 opacity-0"
            class="absolute inset-x-0 bottom-0 bg-white rounded-t-3xl shadow-2xl border border-slate-200 overflow-hidden"
        >
            <div class="p-4 bg-gradient-to-r from-slate-50 to-white border-b border-slate-200">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 border border-indigo-100 flex items-center justify-center">
                            <span class="font-extrabold text-indigo-700" x-text="initials(client?.name)"></span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-extrabold text-slate-900 truncate" x-text="client?.name"></div>
                            <div class="text-sm text-slate-500 truncate" x-text="client?.service_type || '—'"></div>
                        </div>
                    </div>

                    <button
                        @click="client=null"
                        class="w-10 h-10 rounded-2xl bg-slate-900 text-white inline-flex items-center justify-center shadow-sm"
                        title="Fermer"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-4 space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                        <div class="text-[11px] font-extrabold text-slate-500 uppercase">Téléphone</div>
                        <div class="text-sm font-semibold text-slate-900" x-text="client?.phone || '-'"></div>
                    </div>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                        <div class="text-[11px] font-extrabold text-slate-500 uppercase">Reçu le</div>
                        <div class="text-sm font-semibold text-slate-900" x-text="fmtDate(client?.created_at)"></div>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                    <div class="text-[11px] font-extrabold text-slate-500 uppercase">Email</div>
                    <div class="text-sm font-semibold text-slate-900 break-words" x-text="client?.email || '-'"></div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                    <div class="text-[11px] font-extrabold text-slate-500 uppercase">Adresse</div>
                    <div class="text-sm font-semibold text-slate-900 break-words" x-text="client?.addresse || '-'"></div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                    <div class="text-[11px] font-extrabold text-slate-500 uppercase">Problème</div>
                    <div class="text-sm font-semibold text-slate-900 break-words" x-text="client?.problem || '-'"></div>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-1">
                    <a :href="'tel:' + (client?.phone || '')"
                       class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm transition">
                        Appeler
                    </a>

                    <a :href="'mailto:' + (client?.email || '')"
                       class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm transition">
                        Email
                    </a>
                </div>

                <button
                    @click="markFixed(client)"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 hover:bg-black text-white font-extrabold shadow-sm transition"
                >
                    Mark as Fixed
                </button>

                <div class="pb-2"></div>
            </div>
        </div>
    </div>

    {{-- Desktop detail panel (kept simple) --}}
    <div x-show="client" x-transition x-cloak class="hidden sm:block bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h2 class="text-xl font-extrabold text-slate-900 truncate" x-text="client?.name"></h2>
                <p class="text-sm text-slate-500" x-text="client?.service_type || '—'"></p>
            </div>
            <button @click="client=null" class="px-4 py-2 rounded-2xl bg-rose-600 hover:bg-rose-700 text-white font-extrabold shadow-sm">
                Fermer
            </button>
        </div>

        <div class="p-6 grid grid-cols-2 gap-4">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-extrabold text-slate-500 uppercase">Email</div>
                <div class="font-semibold text-slate-900 break-words" x-text="client?.email || '-'"></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-extrabold text-slate-500 uppercase">Téléphone</div>
                <div class="font-semibold text-slate-900" x-text="client?.phone || '-'"></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 col-span-2">
                <div class="text-xs font-extrabold text-slate-500 uppercase">Adresse</div>
                <div class="font-semibold text-slate-900 break-words" x-text="client?.addresse || '-'"></div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 col-span-2">
                <div class="text-xs font-extrabold text-slate-500 uppercase">Problème</div>
                <div class="font-semibold text-slate-900 break-words" x-text="client?.problem || '-'"></div>
            </div>

            <div class="col-span-2 grid grid-cols-3 gap-3">
                <a :href="'mailto:' + (client?.email || '')"
                   class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm">
                    Email
                </a>
                <a :href="'tel:' + (client?.phone || '')"
                   class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm">
                    Appeler
                </a>
                <button @click="markFixed(client)"
                        class="inline-flex items-center justify-center px-4 py-3 rounded-2xl bg-slate-900 hover:bg-black text-white font-extrabold shadow-sm">
                    Mark Fixed
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- ACTIVE --}}
    {{-- ===================== --}}
    <div x-show="currentTab==='active'" x-transition class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg sm:text-xl font-extrabold text-indigo-800">Clients en cours</h2>
        </div>

        {{-- ✅ PRO MOBILE CARDS --}}
        <div class="sm:hidden space-y-3">
            <template x-for="item in activeClients" :key="item.id">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-indigo-600/10 border border-indigo-100 flex items-center justify-center shrink-0">
                                    <span class="font-extrabold text-indigo-700" x-text="initials(item.name)"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base font-extrabold text-slate-900 truncate" x-text="item.name"></div>
                                    <div class="mt-0.5 text-xs text-slate-500">
                                        Reçu <span class="font-semibold text-slate-700" x-text="fmtDate(item.created_at)"></span>
                                    </div>
                                </div>
                            </div>

                            <button
                                @click="client=item"
                                class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shrink-0"
                                title="Voir"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                    <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                    <path fill-rule="evenodd"
                                          d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                <div class="text-[11px] font-extrabold text-slate-500 uppercase">Téléphone</div>
                                <div class="text-sm font-semibold text-slate-900" x-text="item.phone || '-'"></div>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                <div class="text-[11px] font-extrabold text-slate-500 uppercase">Service</div>
                                <div class="text-sm font-semibold text-slate-900 truncate" x-text="item.service_type || '-'"></div>
                            </div>
                        </div>

                        <div class="mt-2 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-500 uppercase">Adresse</div>
                            <div class="text-sm font-semibold text-slate-900 break-words" x-text="item.addresse || '-'"></div>
                        </div>

                        <div class="mt-2 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-500 uppercase">Problème</div>
                            <div class="text-sm font-semibold text-slate-900 break-words line-clamp-2" x-text="item.problem || '-'"></div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a :href="'tel:' + (item.phone || '')"
                               class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm">
                                Appeler
                            </a>
                            <button @click="client=item"
                                    class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-sm">
                                Détails
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="activeClients.length === 0" class="text-sm text-slate-500 text-center py-10">
                Aucun client actif.
            </div>
        </div>

        {{-- Desktop table --}}
        <div class="hidden sm:block bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-slate-600 uppercase text-xs">
                            <th class="px-6 py-3 text-left font-extrabold">Date Reçu</th>
                            <th class="px-6 py-3 text-left font-extrabold">Name</th>
                            <th class="px-6 py-3 text-left font-extrabold">Email</th>
                            <th class="px-6 py-3 text-left font-extrabold">Phone</th>
                            <th class="px-6 py-3 text-left font-extrabold">Address</th>
                            <th class="px-6 py-3 text-center font-extrabold">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        <template x-for="item in activeClients" :key="item.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-slate-700" x-text="fmtDate(item.created_at)"></td>
                                <td class="px-6 py-4 font-semibold text-slate-900" x-text="item.name"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.email"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.phone"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.addresse"></td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="client=item"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                            <path fill-rule="evenodd"
                                                  d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===================== --}}
    {{-- FIXED --}}
    {{-- ===================== --}}
    <div x-show="currentTab==='fixed'" x-transition class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg sm:text-xl font-extrabold text-emerald-800">Clients fixés</h2>
        </div>

        {{-- ✅ PRO MOBILE CARDS --}}
        <div class="sm:hidden space-y-3">
            <template x-for="item in fixedClients" :key="item.id">
                <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-emerald-600/10 border border-emerald-100 flex items-center justify-center shrink-0">
                                    <span class="font-extrabold text-emerald-700" x-text="initials(item.name)"></span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-base font-extrabold text-slate-900 truncate" x-text="item.name"></div>
                                    <div class="mt-0.5 text-xs text-slate-500">
                                        Fix <span class="font-semibold text-slate-700" x-text="fmtDate(item.date_fix)"></span>
                                    </div>
                                </div>
                            </div>

                            <button
                                @click="unfix(item)"
                                class="inline-flex items-center justify-center px-3 py-2 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm shrink-0"
                            >
                                Unfix
                            </button>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                <div class="text-[11px] font-extrabold text-slate-500 uppercase">Téléphone</div>
                                <div class="text-sm font-semibold text-slate-900" x-text="item.phone || '-'"></div>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-3">
                                <div class="text-[11px] font-extrabold text-slate-500 uppercase">Service</div>
                                <div class="text-sm font-semibold text-slate-900 truncate" x-text="item.service_type || '-'"></div>
                            </div>
                        </div>

                        <div class="mt-2 bg-slate-50 border border-slate-200 rounded-2xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-500 uppercase">Problème</div>
                            <div class="text-sm font-semibold text-slate-900 break-words line-clamp-2" x-text="item.problem || '-'"></div>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a :href="'tel:' + (item.phone || '')"
                               class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold shadow-sm">
                                Appeler
                            </a>
                            <button @click="unfix(item)"
                                    class="inline-flex items-center justify-center px-3 py-3 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <div x-show="fixedClients.length === 0" class="text-sm text-slate-500 text-center py-10">
                Aucun client fixé.
            </div>
        </div>

        {{-- Desktop table --}}
        <div class="hidden sm:block bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-slate-600 uppercase text-xs">
                            <th class="px-6 py-3 text-left font-extrabold">Name</th>
                            <th class="px-6 py-3 text-left font-extrabold">Phone</th>
                            <th class="px-6 py-3 text-left font-extrabold">Service</th>
                            <th class="px-6 py-3 text-left font-extrabold">Problem</th>
                            <th class="px-6 py-3 text-left font-extrabold">Date Fix</th>
                            <th class="px-6 py-3 text-center font-extrabold">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        <template x-for="item in fixedClients" :key="item.id">
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-semibold text-slate-900" x-text="item.name"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.phone"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.service_type"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="item.problem"></td>
                                <td class="px-6 py-4 text-slate-700" x-text="fmtDate(item.date_fix)"></td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="unfix(item)"
                                            class="inline-flex items-center justify-center px-4 py-2 rounded-2xl bg-yellow-500 hover:bg-yellow-600 text-white font-extrabold shadow-sm transition">
                                        Modifier
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection
