<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { charge = {} } = $props();

    const statusLabel = { pending: 'Pendiente', link_generated: 'Link generado', paid: 'Pagado', overdue: 'Vencido', cancelled: 'Cancelado' };
    const statusColor = {
        paid: 'bg-emerald-50 text-emerald-700',
        pending: 'bg-amber-50 text-amber-700',
        link_generated: 'bg-blue-50 text-blue-700',
        overdue: 'bg-red-50 text-red-700',
        cancelled: 'bg-slate-100 text-slate-500',
    };

    const originLabel = { manual: 'Manual', external_api: 'API externa' };

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount ?? 0);
    }

    function formatDate(date) {
        if (!date) return '—';
        return new Date(date + 'T00:00:00').toLocaleDateString('es-CO', { day: 'numeric', month: 'long', year: 'numeric' });
    }
</script>

<svelte:head>
    <title>Cobro #{charge.id} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/charges" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-semibold text-slate-900">{charge.concept ?? 'Cobro'}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {charge.unit?.tower?.name ?? ''} · Apto {charge.unit?.number ?? ''} · {charge.person?.first_name ?? ''} {charge.person?.last_name ?? ''}
            </p>
        </div>
        <Button href={`/charges/${charge.uuid}/edit`} as="a" variant="secondary">Editar</Button>
    </div>

    <!-- Amount + Status -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Valor</p>
            <p class="text-3xl font-bold text-slate-900">{formatCurrency(charge.amount)}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Estado</p>
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {statusColor[charge.status] ?? statusColor.pending}">
                {statusLabel[charge.status] ?? charge.status}
            </span>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Detalle del cobro</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Concepto</dt>
                    <dd class="mt-1 text-sm text-slate-900">{charge.concept ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Mes de facturación</dt>
                    <dd class="mt-1 text-sm text-slate-900">{formatDate(charge.billing_month)}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Origen</dt>
                    <dd class="mt-1 text-sm text-slate-900">{originLabel[charge.origin] ?? charge.origin ?? '—'}</dd>
                </div>
                {#if charge.external_reference}
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Referencia externa</dt>
                        <dd class="mt-1 text-sm font-mono text-slate-700">{charge.external_reference}</dd>
                    </div>
                {/if}
            </dl>
        </div>

        <div class="px-6 py-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">Destinatario</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Persona</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        {#if charge.person}
                            <a href={`/persons/${charge.person.uuid}`} class="text-indigo-600 hover:text-indigo-800">
                                {charge.person.first_name} {charge.person.last_name}
                            </a>
                        {:else}
                            —
                        {/if}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Unidad</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        {#if charge.unit}
                            <a href={`/units/${charge.unit.uuid}`} class="text-indigo-600 hover:text-indigo-800">
                                {charge.unit.tower?.name ?? ''} · Apto {charge.unit.number}
                            </a>
                        {:else}
                            —
                        {/if}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Payment transactions -->
        {#if charge.paymentTransactions && charge.paymentTransactions.length > 0}
            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Transacciones de pago</h2>
                <div class="space-y-2">
                    {#each charge.paymentTransactions as tx}
                        <div class="flex items-center justify-between text-sm py-1">
                            <span class="font-mono text-slate-500 text-xs">{tx.wompi_id ?? tx.id}</span>
                            <span class="font-semibold text-slate-900">{formatCurrency(tx.amount)}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{tx.status}</span>
                        </div>
                    {/each}
                </div>
            </div>
        {/if}
    </div>
</AppLayout>
