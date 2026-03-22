<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { towers = [] } = $props();

    const items = towers.data ?? [];
</script>

<svelte:head>
    <title>Torres — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Torres</h1>
            <p class="text-sm text-slate-500 mt-1">Administra las torres de cada propiedad</p>
        </div>
        <Button href="/towers/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva torre
        </Button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{towers.total ?? items.length} torres</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Torre</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Propiedad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pisos</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Servicios</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as tower}
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                         style="background:linear-gradient(135deg,#fef3c7,#fde68a)">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-slate-900">{tower.name}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{tower.property?.name ?? '—'}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1 text-slate-700 font-semibold">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7M12 3v18"/>
                                    </svg>
                                    {tower.floors}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    {#if tower.has_elevator}
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                            Ascensor
                                        </span>
                                    {/if}
                                    {#if tower.has_parking}
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18m-9 5h9"/></svg>
                                            Parqueadero
                                        </span>
                                    {/if}
                                    {#if !tower.has_elevator && !tower.has_parking}
                                        <span class="text-slate-300 text-xs">—</span>
                                    {/if}
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/towers/${tower.uuid}`} title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/towers/${tower.uuid}/edit`} title="Editar"
                                       class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay torres registradas</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Crea la primera torre del sistema</p>
                <Button href="/towers/create" as="a" variant="primary">Nueva torre</Button>
            </div>
        {/if}
    </div>
</AppLayout>
