<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { properties = [] } = $props();

    const items = properties.data ?? [];

    function initials(name) {
        return name?.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase() ?? '?';
    }
</script>

<svelte:head>
    <title>Propiedades — Portal PH</title>
</svelte:head>

<AppLayout>
    <!-- Page header -->
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Propiedades</h1>
            <p class="text-sm text-slate-500 mt-1">Gestiona los conjuntos residenciales del sistema</p>
        </div>
        <Button href="/properties/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva propiedad
        </Button>
    </div>

    <!-- Stats strip -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-slate-900">{properties.total ?? items.length}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Activas</p>
                <p class="text-xl font-bold text-slate-900">{items.filter(p => p.status === 'active').length}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Con Wompi</p>
                <p class="text-xl font-bold text-slate-900">—</p>
            </div>
        </div>
    </div>

    <!-- Table card -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wide">
                    {properties.total ?? items.length} registros
                </span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Propiedad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Dirección</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ciudad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as property}
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs text-indigo-700 shadow-sm"
                                         style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe)">
                                        {initials(property.name)}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-none">{property.name}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">NIT {property.nit ?? '—'}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{property.address}</td>
                            <td class="px-5 py-3.5 text-slate-600">{property.city}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {property.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'}">
                                    <span class="w-1.5 h-1.5 rounded-full {property.status === 'active' ? 'bg-emerald-500' : 'bg-slate-400'}"></span>
                                    {property.status === 'active' ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/properties/${property.uuid}`}
                                       title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/properties/${property.uuid}/payment-config`}
                                       title="Configurar Wompi"
                                       class="p-2 rounded-lg text-slate-400 hover:text-violet-600 hover:bg-violet-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </a>
                                    <a href={`/properties/${property.uuid}/edit`}
                                       title="Editar"
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
            {#if properties.total}
                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-400">Mostrando {properties.from}–{properties.to} de {properties.total}</span>
                </div>
            {/if}
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay propiedades registradas</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Crea la primera propiedad del sistema</p>
                <Button href="/properties/create" as="a" variant="primary">Nueva propiedad</Button>
            </div>
        {/if}
    </div>
</AppLayout>
