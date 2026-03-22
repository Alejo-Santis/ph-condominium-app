<script>
    import { router } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { charge = {}, canGenerateLink = false, latestPaymentUrl = null } = $props();

    let generating = $state(false);

    const statusLabel = { pending: 'Pendiente', link_generated: 'Link generado', paid: 'Pagado', overdue: 'Vencido', cancelled: 'Cancelado' };
    const statusColor = {
        paid:           'bg-emerald-50 text-emerald-700',
        pending:        'bg-amber-50 text-amber-700',
        link_generated: 'bg-blue-50 text-blue-700',
        overdue:        'bg-red-50 text-red-700',
        cancelled:      'bg-slate-100 text-slate-500',
    };
    const statusDot = {
        paid: 'bg-emerald-500', pending: 'bg-amber-500', link_generated: 'bg-blue-500',
        overdue: 'bg-red-500', cancelled: 'bg-slate-400',
    };

    const originLabel = { manual: 'Manual', external_api: 'API externa' };

    const wompiStatus = {
        APPROVED:   { label: 'Aprobada',   class: 'bg-emerald-50 text-emerald-700' },
        PENDING:    { label: 'Pendiente',  class: 'bg-amber-50 text-amber-700' },
        PROCESSING: { label: 'Procesando', class: 'bg-blue-50 text-blue-700' },
        DECLINED:   { label: 'Rechazada',  class: 'bg-red-50 text-red-700' },
        VOIDED:     { label: 'Anulada',    class: 'bg-slate-100 text-slate-500' },
    };

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount ?? 0);
    }

    function formatDate(date) {
        if (!date) return '—';
        return new Date(date + 'T00:00:00').toLocaleDateString('es-CO', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function generateLink() {
        generating = true;
        router.post(`/charges/${charge.uuid}/generate-link`, {}, {
            onFinish: () => (generating = false),
        });
    }
</script>

<svelte:head>
    <title>Cobro #{charge.id} — Portal PH</title>
</svelte:head>

<AppLayout>
    <!-- Header -->
    <div class="mb-6 flex items-center gap-3">
        <a href="/charges" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-xl font-bold text-slate-900">{charge.concept ?? 'Cobro'}</h1>
            <p class="text-sm text-slate-500 mt-0.5">
                {charge.unit?.tower?.name ?? ''} · Apto {charge.unit?.number ?? ''} · {charge.person?.first_name ?? ''} {charge.person?.last_name ?? ''}
            </p>
        </div>
        <div class="flex items-center gap-2">
            {#if latestPaymentUrl}
                <a href={latestPaymentUrl} target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition-all shadow-sm"
                   style="background:linear-gradient(135deg,#059669,#10b981)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Abrir link de pago
                </a>
            {/if}
            {#if canGenerateLink}
                <Button variant="primary" onclick={generateLink} disabled={generating}>
                    {#if generating}
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        Generando...
                    {:else}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Generar link Wompi
                    {/if}
                </Button>
            {/if}
            <Button href={`/charges/${charge.uuid}/edit`} as="a" variant="secondary">Editar</Button>
        </div>
    </div>

    <!-- Amount + Status -->
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Valor</p>
            <p class="text-3xl font-bold text-slate-900">{formatCurrency(charge.amount)}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Estado</p>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold {statusColor[charge.status] ?? statusColor.pending}">
                <span class="w-1.5 h-1.5 rounded-full {statusDot[charge.status] ?? statusDot.pending}"></span>
                {statusLabel[charge.status] ?? charge.status}
            </span>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 shadow-sm">
        <!-- Charge detail -->
        <div class="px-6 py-5">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Detalle del cobro</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Concepto</dt>
                    <dd class="mt-1 text-sm text-slate-900">{charge.concept ?? '—'}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Mes de facturación</dt>
                    <dd class="mt-1 text-sm text-slate-900">{formatDate(charge.billing_month)}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Origen</dt>
                    <dd class="mt-1 text-sm text-slate-900">{originLabel[charge.origin] ?? charge.origin ?? '—'}</dd>
                </div>
                {#if charge.external_reference}
                    <div>
                        <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Referencia externa</dt>
                        <dd class="mt-1 text-sm font-mono text-slate-700">{charge.external_reference}</dd>
                    </div>
                {/if}
            </dl>
        </div>

        <!-- Recipient -->
        <div class="px-6 py-5">
            <h2 class="text-sm font-bold text-slate-700 mb-4">Destinatario</h2>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4">
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Persona</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        {#if charge.person}
                            <a href={`/persons/${charge.person.uuid}`} class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {charge.person.first_name} {charge.person.last_name}
                            </a>
                        {:else}—{/if}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Unidad</dt>
                    <dd class="mt-1 text-sm text-slate-900">
                        {#if charge.unit}
                            <a href={`/units/${charge.unit.uuid}`} class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {charge.unit.tower?.name ?? ''} · Apto {charge.unit.number}
                            </a>
                        {:else}—{/if}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Payment transactions -->
        {#if charge.paymentTransactions && charge.paymentTransactions.length > 0}
            <div class="px-6 py-5">
                <h2 class="text-sm font-bold text-slate-700 mb-4">Transacciones de pago</h2>
                <div class="space-y-2">
                    {#each charge.paymentTransactions as tx}
                        {@const ws = wompiStatus[tx.wompi_status] ?? { label: tx.wompi_status ?? 'Pendiente', class: 'bg-slate-100 text-slate-500' }}
                        <div class="flex items-center justify-between text-sm py-2 px-3 rounded-lg bg-slate-50">
                            <div>
                                <p class="font-mono text-xs text-slate-500">{tx.wompi_reference ?? tx.wompi_link_id ?? '—'}</p>
                                {#if tx.payment_url}
                                    <a href={tx.payment_url} target="_blank" rel="noopener"
                                       class="text-xs text-indigo-600 hover:text-indigo-800 mt-0.5 inline-flex items-center gap-1">
                                        Ver link
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                {/if}
                            </div>
                            <div class="flex items-center gap-3">
                                {#if tx.amount_paid}
                                    <span class="font-bold text-slate-900">{formatCurrency(tx.amount_paid)}</span>
                                {/if}
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold {ws.class}">{ws.label}</span>
                            </div>
                        </div>
                    {/each}
                </div>
            </div>
        {/if}
    </div>
</AppLayout>
