<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { property = {}, stats = {} } = $props();
</script>

<svelte:head>
    <title>{property.name} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/properties" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-semibold text-slate-900">{property.name}</h1>
            <p class="text-sm text-slate-500 mt-0.5">{property.address}, {property.city}</p>
        </div>
        <Button href={`/properties/${property.uuid}/payment-config`} as="a" variant="secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Configurar Wompi
        </Button>
        <Button href={`/properties/${property.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Torres</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{stats.towers ?? 0}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Unidades</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{stats.units ?? 0}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Parqueaderos</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{stats.parking_spaces ?? 0}</p>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Información del conjunto</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">NIT</dt>
                    <dd class="mt-1 text-sm text-slate-900 font-medium">{property.nit ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Estado</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                            {property.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'}">
                            <span class="w-1.5 h-1.5 rounded-full {property.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'}"></span>
                            {property.status === 'active' ? 'Activo' : 'Inactivo'}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Ciudad</dt>
                    <dd class="mt-1 text-sm text-slate-900">{property.city ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Departamento</dt>
                    <dd class="mt-1 text-sm text-slate-900">{property.department ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Correo administración</dt>
                    <dd class="mt-1 text-sm text-slate-900">{property.admin_email ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Teléfono</dt>
                    <dd class="mt-1 text-sm text-slate-900">{property.phone ?? '—'}</dd>
                </div>
            </dl>
        </div>
    </div>
</AppLayout>
