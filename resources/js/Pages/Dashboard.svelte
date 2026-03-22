<script>
    import AppLayout from '../Layouts/AppLayout.svelte';
    import { page } from '@inertiajs/svelte';

    let { stats = {}, recent_charges = [] } = $props();

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(amount ?? 0);
    }

    function formatDate(date) {
        if (!date) return '—';
        return new Date(date + 'T00:00:00').toLocaleDateString('es-CO', { day: 'numeric', month: 'short' });
    }

    const statusLabel = { pending: 'Pendiente', link_generated: 'Link generado', paid: 'Pagado', overdue: 'Vencido', cancelled: 'Cancelado' };
    const statusColor = {
        paid: 'bg-emerald-50 text-emerald-700',
        pending: 'bg-amber-50 text-amber-700',
        link_generated: 'bg-blue-50 text-blue-700',
        overdue: 'bg-red-50 text-red-700',
        cancelled: 'bg-slate-100 text-slate-500',
    };

    const cards = [
        {
            label: 'Propiedades',
            value: stats.properties ?? 0,
            href: '/properties',
            color: 'text-indigo-600 bg-indigo-50',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
        },
        {
            label: 'Unidades',
            value: stats.units ?? 0,
            href: '/units',
            color: 'text-violet-600 bg-violet-50',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
        },
        {
            label: 'Personas',
            value: stats.persons ?? 0,
            href: '/persons',
            color: 'text-sky-600 bg-sky-50',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        },
        {
            label: 'Cobros pendientes',
            value: stats.pending_charges ?? 0,
            href: '/charges',
            color: 'text-amber-600 bg-amber-50',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>`,
        },
    ];

    const quickActions = [
        { label: 'Nueva propiedad', href: '/properties/create', style: 'bg-indigo-600 hover:bg-indigo-700 text-white' },
        { label: 'Nuevo cobro', href: '/charges/create', style: 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700' },
        { label: 'Agregar persona', href: '/persons/create', style: 'bg-slate-100 hover:bg-slate-200 text-slate-700' },
    ];
</script>

<svelte:head>
    <title>Dashboard — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-500 mt-1 text-sm">Bienvenido, <span class="font-medium text-slate-700">{$page.props.auth.user.name}</span></p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        {#each cards as card}
            <a href={card.href} class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition-shadow duration-200 block">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-slate-500">{card.label}</p>
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center {card.color}">
                        {@html card.icon}
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900">{card.value}</p>
            </a>
        {/each}
    </div>

    <!-- Cobros vencidos alert -->
    {#if (stats.overdue_charges ?? 0) > 0}
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-5 py-3.5">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm text-red-700">
                <span class="font-semibold">{stats.overdue_charges}</span> cobro{stats.overdue_charges !== 1 ? 's' : ''} vencido{stats.overdue_charges !== 1 ? 's' : ''} requieren atención.
                <a href="/charges" class="underline font-medium ml-1">Ver cobros</a>
            </p>
        </div>
    {/if}

    <!-- Content grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Recent charges -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Cobros recientes</h2>
                <a href="/charges" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Ver todos</a>
            </div>
            {#if recent_charges.length > 0}
                <div class="divide-y divide-slate-50">
                    {#each recent_charges as charge}
                        <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-slate-50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{charge.concept}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {charge.unit?.tower?.name ?? ''} · Apto {charge.unit?.number ?? ''} · {charge.person?.first_name ?? ''} {charge.person?.last_name ?? ''}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="text-sm font-semibold text-slate-900">
                                    {new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(charge.amount)}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {statusColor[charge.status] ?? statusColor.pending}">
                                    {statusLabel[charge.status] ?? charge.status}
                                </span>
                            </div>
                        </div>
                    {/each}
                </div>
            {:else}
                <div class="py-10 text-center text-sm text-slate-400">No hay cobros registrados aún</div>
            {/if}
        </div>

        <!-- Right panel -->
        <div class="space-y-5">
            <!-- Quick actions -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-semibold text-slate-900">Acciones rápidas</h2>
                </div>
                <div class="p-5 space-y-2.5">
                    {#each quickActions as action}
                        <a href={action.href} class="flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {action.style}">
                            {action.label}
                        </a>
                    {/each}
                </div>
            </div>

            <!-- Revenue card -->
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Recaudado este mes</p>
                <p class="text-2xl font-bold text-slate-900">{formatCurrency(stats.paid_this_month)}</p>
                <p class="text-xs text-slate-400 mt-1">Cobros pagados en {new Date().toLocaleDateString('es-CO', { month: 'long', year: 'numeric' })}</p>
            </div>
        </div>
    </div>
</AppLayout>
