<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { person = {} } = $props();

    const docTypeLabel = { cc: 'Cédula de Ciudadanía', ce: 'Cédula de Extranjería', nit: 'NIT', passport: 'Pasaporte' };

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount ?? 0);
    }
</script>

<svelte:head>
    <title>{person.first_name} {person.last_name} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/persons" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex items-center gap-3 flex-1">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-indigo-700 font-bold text-sm">{person.first_name?.charAt(0)}{person.last_name?.charAt(0)}</span>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-slate-900">{person.first_name} {person.last_name}</h1>
                <p class="text-sm text-slate-500 mt-0.5">{docTypeLabel[person.document_type] ?? person.document_type} · {person.document_number}</p>
            </div>
        </div>
        <Button href={`/persons/${person.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Datos de contacto</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Correo electrónico</dt>
                    <dd class="mt-1 text-sm text-slate-900">{person.email ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Teléfono</dt>
                    <dd class="mt-1 text-sm text-slate-900">{person.phone ?? '—'}</dd>
                </div>
                {#if person.alt_phone}
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Teléfono alternativo</dt>
                        <dd class="mt-1 text-sm text-slate-900">{person.alt_phone}</dd>
                    </div>
                {/if}
            </dl>
        </div>

        <!-- Units -->
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Unidades vinculadas</h2>
            {#if person.units && person.units.length > 0}
                <div class="space-y-2">
                    {#each person.units as unit}
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-slate-900">{unit.tower?.name ?? '—'} · Apto {unit.number}</p>
                                <p class="text-xs text-slate-500 capitalize">{unit.pivot?.role === 'owner' ? 'Propietario' : 'Arrendatario'} · Piso {unit.floor}</p>
                            </div>
                            <a href={`/units/${unit.uuid}`} class="ml-auto text-xs text-indigo-600 hover:text-indigo-800 font-medium">Ver unidad</a>
                        </div>
                    {/each}
                </div>
            {:else}
                <p class="text-sm text-slate-400">Sin unidades vinculadas</p>
            {/if}
        </div>

        <!-- Charges -->
        {#if person.charges && person.charges.length > 0}
            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Cobros</h2>
                <div class="space-y-2">
                    {#each person.charges as charge}
                        <div class="flex items-center justify-between text-sm py-1">
                            <span class="text-slate-700">{charge.concept}</span>
                            <span class="font-semibold text-slate-900">{formatCurrency(charge.amount)}</span>
                        </div>
                    {/each}
                </div>
            </div>
        {/if}
    </div>
</AppLayout>
