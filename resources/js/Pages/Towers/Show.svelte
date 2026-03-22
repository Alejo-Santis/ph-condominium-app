<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { tower = {} } = $props();
</script>

<svelte:head>
    <title>{tower.name} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/towers" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-semibold text-slate-900">{tower.name}</h1>
            <p class="text-sm text-slate-500 mt-0.5">{tower.property?.name ?? '—'}</p>
        </div>
        <Button href={`/towers/${tower.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pisos</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{tower.floors ?? 0}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Unidades</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{tower.units ? tower.units.length : 0}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Estado</p>
            <div class="mt-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                    {tower.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'}">
                    <span class="w-1.5 h-1.5 rounded-full {tower.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'}"></span>
                    {tower.status === 'active' ? 'Activo' : 'Inactivo'}
                </span>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Detalles de la torre</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Propiedad</dt>
                    <dd class="mt-1 text-sm text-slate-900">{tower.property?.name ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Pisos</dt>
                    <dd class="mt-1 text-sm text-slate-900">{tower.floors ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Ascensor</dt>
                    <dd class="mt-1 text-sm text-slate-900">{tower.has_elevator ? 'Sí' : 'No'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Parqueadero</dt>
                    <dd class="mt-1 text-sm text-slate-900">{tower.has_parking ? 'Sí' : 'No'}</dd>
                </div>
                {#if tower.description}
                    <div class="col-span-2">
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Descripción</dt>
                        <dd class="mt-1 text-sm text-slate-900">{tower.description}</dd>
                    </div>
                {/if}
            </dl>
        </div>
    </div>
</AppLayout>
