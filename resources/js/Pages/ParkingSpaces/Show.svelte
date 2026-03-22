<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { parkingSpace = {} } = $props();

    const typeConfig = {
        vehicle:    { label: 'Vehículo',    class: 'bg-blue-50 text-blue-700',     icon: '🚗' },
        motorcycle: { label: 'Motocicleta', class: 'bg-orange-50 text-orange-700', icon: '🏍️' },
        bicycle:    { label: 'Bicicleta',   class: 'bg-emerald-50 text-emerald-700', icon: '🚲' },
    };

    const statusConfig = {
        available: { label: 'Disponible', class: 'bg-emerald-50 text-emerald-700', dot: 'bg-emerald-500' },
        assigned:  { label: 'Asignado',   class: 'bg-blue-50 text-blue-700',       dot: 'bg-blue-500' },
        blocked:   { label: 'Bloqueado',  class: 'bg-red-50 text-red-700',         dot: 'bg-red-400' },
    };

    let tc = $derived(typeConfig[parkingSpace.type] ?? { label: parkingSpace.type, class: 'bg-slate-100 text-slate-600', icon: '🅿️' });
    let sc = $derived(statusConfig[parkingSpace.status] ?? { label: parkingSpace.status, class: 'bg-slate-100 text-slate-500', dot: 'bg-slate-400' });
</script>

<svelte:head>
    <title>Parqueadero {parkingSpace.code} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/parking-spaces" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl border border-slate-200 bg-slate-50">
                {tc.icon}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Parqueadero {parkingSpace.code}</h1>
                <p class="text-sm text-slate-500 mt-0.5">{parkingSpace.property?.name ?? '—'}</p>
            </div>
        </div>
        <Button href={`/parking-spaces/${parkingSpace.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <!-- Status + Type cards -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Estado</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold {sc.class}">
                <span class="w-1.5 h-1.5 rounded-full {sc.dot}"></span>
                {sc.label}
            </span>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Tipo</p>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold {tc.class}">
                {tc.icon} {tc.label}
            </span>
        </div>
    </div>

    <!-- Detail card -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 shadow-sm">
        <div class="px-6 py-5">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Información</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Código</dt>
                    <dd class="mt-1 text-sm font-mono font-bold text-slate-900">{parkingSpace.code}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Propiedad</dt>
                    <dd class="mt-1 text-sm text-slate-900">{parkingSpace.property?.name ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Torre</dt>
                    <dd class="mt-1 text-sm text-slate-900">{parkingSpace.tower?.name ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Unidad asignada</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        {#if parkingSpace.unit}
                            <a href={`/units/${parkingSpace.unit.uuid}`} class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Apto {parkingSpace.unit.number}
                            </a>
                        {:else}
                            <span class="text-slate-400">Sin asignar</span>
                        {/if}
                    </dd>
                </div>
            </dl>
        </div>

        {#if parkingSpace.unit?.people && parkingSpace.unit.people.length > 0}
            <div class="px-6 py-5">
                <h2 class="text-sm font-bold text-slate-700 mb-3">Residentes de la unidad</h2>
                <div class="space-y-2">
                    {#each parkingSpace.unit.people as person}
                        <div class="flex items-center gap-3 p-2.5 bg-slate-50 rounded-lg">
                            <div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">
                                {person.first_name?.charAt(0)}{person.last_name?.charAt(0)}
                            </div>
                            <a href={`/persons/${person.uuid}`} class="text-sm font-medium text-slate-700 hover:text-indigo-600 transition-colors">
                                {person.first_name} {person.last_name}
                            </a>
                        </div>
                    {/each}
                </div>
            </div>
        {/if}
    </div>
</AppLayout>
