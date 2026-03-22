<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { parkingSpaces = [] } = $props();
    const items = parkingSpaces.data ?? [];

    const typeConfig = {
        vehicle:    { label: 'Vehículo',    class: 'bg-blue-50 text-blue-700',    icon: '🚗' },
        motorcycle: { label: 'Motocicleta', class: 'bg-orange-50 text-orange-700', icon: '🏍️' },
        bicycle:    { label: 'Bicicleta',   class: 'bg-emerald-50 text-emerald-700', icon: '🚲' },
    };

    const statusConfig = {
        available: { label: 'Disponible', class: 'bg-emerald-50 text-emerald-700', dot: 'bg-emerald-500' },
        assigned:  { label: 'Asignado',   class: 'bg-blue-50 text-blue-700',       dot: 'bg-blue-500' },
        blocked:   { label: 'Bloqueado',  class: 'bg-red-50 text-red-700',         dot: 'bg-red-400' },
    };
</script>

<svelte:head>
    <title>Parqueaderos — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Parqueaderos</h1>
            <p class="text-sm text-slate-500 mt-1">Gestión de espacios de parqueo por propiedad</p>
        </div>
        <Button href="/parking-spaces/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo parqueadero
        </Button>
    </div>

    <!-- Quick stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        {#each [
            { key: 'available', label: 'Disponibles' },
            { key: 'assigned',  label: 'Asignados'   },
            { key: 'blocked',   label: 'Bloqueados'  },
        ] as stat}
            {@const sc = statusConfig[stat.key]}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm flex items-center gap-4">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 {sc.dot}"></span>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{stat.label}</p>
                    <p class="text-2xl font-bold text-slate-900">{items.filter(p => p.status === stat.key).length}</p>
                </div>
            </div>
        {/each}
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{parkingSpaces.total ?? items.length} parqueaderos</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Código</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Propiedad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Torre</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unidad asignada</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipo</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as ps}
                        {@const tc = typeConfig[ps.type]}
                        {@const sc = statusConfig[ps.status]}
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-base"
                                         style="background:#f8fafc; border:1px solid #e2e8f0">
                                        {tc?.icon ?? '🅿️'}
                                    </div>
                                    <span class="font-bold text-slate-900 font-mono">{ps.code}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{ps.property?.name ?? '—'}</td>
                            <td class="px-5 py-3.5 text-slate-600">{ps.tower?.name ?? '—'}</td>
                            <td class="px-5 py-3.5">
                                {#if ps.unit}
                                    <a href={`/units/${ps.unit.uuid}`} class="text-indigo-600 hover:text-indigo-800 font-medium text-xs">
                                        Apto {ps.unit.number}
                                    </a>
                                {:else}
                                    <span class="text-slate-300 text-xs">Sin asignar</span>
                                {/if}
                            </td>
                            <td class="px-5 py-3.5">
                                {#if tc}
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold {tc.class}">{tc.label}</span>
                                {/if}
                            </td>
                            <td class="px-5 py-3.5">
                                {#if sc}
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {sc.class}">
                                        <span class="w-1.5 h-1.5 rounded-full {sc.dot}"></span>
                                        {sc.label}
                                    </span>
                                {/if}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/parking-spaces/${ps.uuid}`} title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/parking-spaces/${ps.uuid}/edit`} title="Editar"
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
            {#if parkingSpaces.total}
                <div class="px-5 py-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Mostrando {parkingSpaces.from}–{parkingSpaces.to} de {parkingSpaces.total}</span>
                </div>
            {/if}
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl border border-slate-200">
                    🅿️
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay parqueaderos registrados</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Registra los espacios de parqueo de la propiedad</p>
                <Button href="/parking-spaces/create" as="a" variant="primary">Nuevo parqueadero</Button>
            </div>
        {/if}
    </div>
</AppLayout>
