<script>
    import PortalLayout from '@/Layouts/PortalLayout.svelte';

    let { person, charges, stats } = $props();

    const statusLabel = {
        pending:        { label: 'Pendiente',       class: 'bg-amber-50 text-amber-700 ring-amber-200' },
        link_generated: { label: 'Link generado',   class: 'bg-blue-50 text-blue-700 ring-blue-200' },
        paid:           { label: 'Pagado',           class: 'bg-emerald-50 text-emerald-700 ring-emerald-200' },
        overdue:        { label: 'Vencido',          class: 'bg-red-50 text-red-700 ring-red-200' },
        cancelled:      { label: 'Cancelado',        class: 'bg-slate-100 text-slate-500 ring-slate-200' },
    };

    function fmt(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount);
    }

    function fmtMonth(dateStr) {
        if (!dateStr) return '—';
        return new Date(dateStr).toLocaleDateString('es-CO', { month: 'long', year: 'numeric' });
    }

    let filter = $state('all');

    let filtered = $derived(
        filter === 'all' ? charges : charges.filter(c => c.status === filter)
    );
</script>

<svelte:head><title>Mis cobros — Portal PH</title></svelte:head>

<PortalLayout>

    <!-- Welcome -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">
            Hola, {person.first_name} 👋
        </h1>
        <p class="text-slate-500 mt-1">Aquí puedes ver y gestionar tus cobros del conjunto.</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pendientes</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{stats.pending}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Vencidos</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{stats.overdue}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Pagados</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{stats.paid}</p>
        </div>
        <div class="bg-white rounded-2xl border border-red-100 p-4 shadow-sm" style="background:#fff5f5">
            <p class="text-xs font-semibold text-red-400 uppercase tracking-wide">Por pagar</p>
            <p class="text-lg font-bold text-red-600 mt-1">{fmt(stats.total_pending_amount)}</p>
        </div>
    </div>

    <!-- Alert vencidos -->
    {#if stats.overdue > 0}
        <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3.5">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm text-red-700 font-medium">
                Tienes {stats.overdue} cobro{stats.overdue > 1 ? 's' : ''} vencido{stats.overdue > 1 ? 's' : ''}. Por favor regulariza tu situación.
            </p>
        </div>
    {/if}

    <!-- Charges list -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <h2 class="font-semibold text-slate-700 text-sm">Mis cobros</h2>
            <!-- Filter tabs -->
            <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                {#each [['all','Todos'], ['pending','Pendientes'], ['overdue','Vencidos'], ['paid','Pagados']] as [val, label]}
                    <button onclick={() => filter = val}
                            class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                                {filter === val ? 'bg-white shadow text-slate-700' : 'text-slate-400 hover:text-slate-600'}">
                        {label}
                    </button>
                {/each}
            </div>
        </div>

        {#if filtered.length === 0}
            <div class="py-14 text-center">
                <p class="text-sm text-slate-500">No hay cobros en esta categoría</p>
            </div>
        {:else}
            <div class="divide-y divide-slate-100">
                {#each filtered as charge (charge.uuid)}
                    {@const st = statusLabel[charge.status] ?? statusLabel.pending}
                    {@const paymentUrl = charge.latest_transaction?.payment_url}
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-slate-50 transition-colors">
                        <!-- Month icon -->
                        <div class="w-10 h-10 rounded-xl flex flex-col items-center justify-center flex-shrink-0 bg-indigo-50">
                            <span class="text-[10px] font-bold text-indigo-400 uppercase leading-none">
                                {new Date(charge.billing_month).toLocaleDateString('es-CO', { month: 'short' })}
                            </span>
                            <span class="text-sm font-bold text-indigo-600 leading-none mt-0.5">
                                {new Date(charge.billing_month).getFullYear().toString().slice(2)}
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{charge.concept}</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {charge.unit?.tower?.name ?? ''} · Unidad {charge.unit?.number ?? ''}
                                · {fmtMonth(charge.billing_month)}
                            </p>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-slate-800">{fmt(charge.amount)}</p>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full ring-1 {st.class}">{st.label}</span>
                        </div>

                        <!-- Pay button -->
                        {#if paymentUrl && charge.status !== 'paid' && charge.status !== 'cancelled'}
                            <a href={paymentUrl} target="_blank" rel="noopener noreferrer"
                               class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Pagar
                            </a>
                        {/if}
                    </div>
                {/each}
            </div>
        {/if}
    </div>

</PortalLayout>
