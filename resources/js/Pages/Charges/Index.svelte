<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { charges = [] } = $props();

    const items = charges.data ?? [];

    const statusConfig = {
        paid:           { label: 'Pagado',         class: 'bg-emerald-50 text-emerald-700', dot: 'bg-emerald-500' },
        pending:        { label: 'Pendiente',       class: 'bg-amber-50 text-amber-700',    dot: 'bg-amber-500' },
        link_generated: { label: 'Link generado',   class: 'bg-blue-50 text-blue-700',      dot: 'bg-blue-500' },
        overdue:        { label: 'Vencido',         class: 'bg-red-50 text-red-700',        dot: 'bg-red-500' },
        cancelled:      { label: 'Cancelado',       class: 'bg-slate-100 text-slate-500',   dot: 'bg-slate-400' },
    };

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount);
    }

    function formatDate(date) {
        if (!date) return '—';
        return new Date(date + 'T00:00:00').toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' });
    }
</script>

<svelte:head>
    <title>Cobros — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Cobros</h1>
            <p class="text-sm text-slate-500 mt-1">Facturación y cartera de unidades</p>
        </div>
        <Button href="/charges/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo cobro
        </Button>
    </div>

    <!-- Quick stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        {#each [
            { key: 'pending',  label: 'Pendientes',  color: 'amber'   },
            { key: 'overdue',  label: 'Vencidos',    color: 'red'     },
            { key: 'paid',     label: 'Pagados',     color: 'emerald' },
            { key: 'link_generated', label: 'Con link', color: 'blue' },
        ] as stat}
            {@const count = items.filter(c => c.status === stat.key).length}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{stat.label}</span>
                    <span class="w-2 h-2 rounded-full {statusConfig[stat.key]?.dot}"></span>
                </div>
                <p class="text-2xl font-bold text-slate-900">{count}</p>
            </div>
        {/each}
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{charges.total ?? items.length} cobros</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unidad</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Concepto</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Valor</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mes</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Estado</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as charge}
                        {@const sc = statusConfig[charge.status] ?? statusConfig.pending}
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                         style="background:linear-gradient(135deg,#f0fdf4,#dcfce7)">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-none">{charge.unit?.tower?.name ?? ''} · Apto {charge.unit?.number ?? ''}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{charge.person?.first_name ?? ''} {charge.person?.last_name ?? ''}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 max-w-[200px] truncate">{charge.concept}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-slate-900 tabular-nums">{formatCurrency(charge.amount)}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 text-xs">{formatDate(charge.billing_month)}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {sc.class}">
                                    <span class="w-1.5 h-1.5 rounded-full {sc.dot}"></span>
                                    {sc.label}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/charges/${charge.uuid}`} title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/charges/${charge.uuid}/edit`} title="Editar"
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
            {#if charges.total}
                <div class="px-5 py-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Mostrando {charges.from}–{charges.to} de {charges.total}</span>
                </div>
            {/if}
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay cobros registrados</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Genera el primer cobro para una unidad</p>
                <Button href="/charges/create" as="a" variant="primary">Nuevo cobro</Button>
            </div>
        {/if}
    </div>
</AppLayout>
