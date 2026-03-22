<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { units = [] } = $props();

    const items = units.data ?? [];

    const typeConfig = {
        residential: { label: 'Residencial', class: 'bg-emerald-50 text-emerald-700' },
        commercial:  { label: 'Comercial',   class: 'bg-orange-50 text-orange-700' },
        mixed:       { label: 'Mixto',        class: 'bg-violet-50 text-violet-700' },
    };
</script>

<svelte:head>
    <title>Unidades — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Unidades</h1>
            <p class="text-sm text-slate-500 mt-1">Apartamentos y locales por torre</p>
        </div>
        <Button href="/units/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva unidad
        </Button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{units.total ?? items.length} unidades</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unidad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Torre</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Piso</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Área</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as unit}
                        {@const tc = typeConfig[unit.type]}
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                         style="background:linear-gradient(135deg,#d1fae5,#a7f3d0)">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <span class="font-semibold text-slate-900">Apto {unit.number}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{unit.tower?.name ?? '—'}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-slate-700 font-medium">Piso {unit.floor}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 tabular-nums">{unit.area_sqm ? unit.area_sqm + ' m²' : '—'}</td>
                            <td class="px-5 py-3.5">
                                {#if tc}
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {tc.class}">{tc.label}</span>
                                {:else}
                                    <span class="text-slate-300">—</span>
                                {/if}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/units/${unit.uuid}`} title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/units/${unit.uuid}/edit`} title="Editar"
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
            {#if units.total}
                <div class="px-5 py-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Mostrando {units.from}–{units.to} de {units.total}</span>
                </div>
            {/if}
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay unidades registradas</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Crea la primera unidad del sistema</p>
                <Button href="/units/create" as="a" variant="primary">Nueva unidad</Button>
            </div>
        {/if}
    </div>
</AppLayout>
