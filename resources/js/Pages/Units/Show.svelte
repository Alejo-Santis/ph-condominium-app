<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { unit = {} } = $props();

    const typeLabel = { residential: 'Residencial', commercial: 'Comercial', mixed: 'Mixto' };
    const statusLabel = { occupied: 'Ocupada', vacant: 'Vacante', for_sale: 'En venta' };
    const statusColor = {
        occupied: 'bg-emerald-50 text-emerald-700',
        vacant: 'bg-slate-100 text-slate-500',
        for_sale: 'bg-blue-50 text-blue-700',
    };
</script>

<svelte:head>
    <title>Apto {unit.number} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/units" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-semibold text-slate-900">Apto {unit.number}</h1>
            <p class="text-sm text-slate-500 mt-0.5">{unit.tower?.name ?? '—'} · Piso {unit.floor}</p>
        </div>
        <Button href={`/units/${unit.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Área</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{unit.area_sqm ?? '—'} <span class="text-base font-normal text-slate-500">m²</span></p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipo</p>
            <p class="text-lg font-semibold text-slate-900 mt-1">{typeLabel[unit.type] ?? unit.type ?? '—'}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</p>
            <div class="mt-1">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {statusColor[unit.status] ?? statusColor.vacant}">
                    {statusLabel[unit.status] ?? unit.status ?? '—'}
                </span>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Datos de la unidad</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Propiedad</dt>
                    <dd class="mt-1 text-sm text-slate-900">{unit.tower?.property?.name ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Torre</dt>
                    <dd class="mt-1 text-sm text-slate-900">{unit.tower?.name ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Número</dt>
                    <dd class="mt-1 text-sm text-slate-900 font-medium">{unit.number}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Piso</dt>
                    <dd class="mt-1 text-sm text-slate-900">{unit.floor}</dd>
                </div>
                {#if unit.coefficient}
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Coeficiente</dt>
                        <dd class="mt-1 text-sm text-slate-900">{unit.coefficient}</dd>
                    </div>
                {/if}
            </dl>
        </div>

        <!-- Residents -->
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Residentes</h2>
            {#if unit.people && unit.people.length > 0}
                <div class="space-y-2">
                    {#each unit.people as person}
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-indigo-700 font-semibold text-xs">{person.first_name.charAt(0)}{person.last_name.charAt(0)}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{person.first_name} {person.last_name}</p>
                                <p class="text-xs text-slate-500">{person.pivot?.role === 'owner' ? 'Propietario' : 'Arrendatario'}</p>
                            </div>
                            <a href={`/persons/${person.id}`} class="ml-auto text-xs text-indigo-600 hover:text-indigo-800">Ver</a>
                        </div>
                    {/each}
                </div>
            {:else}
                <p class="text-sm text-slate-400">Sin residentes asignados</p>
            {/if}
        </div>
    </div>
</AppLayout>
