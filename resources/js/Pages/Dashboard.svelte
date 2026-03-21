<script>
    import AppLayout from '../Layouts/AppLayout.svelte';
    import { page } from '@inertiajs/svelte';

    const stats = [
        {
            label: 'Propiedades',
            value: '12',
            change: '+2',
            changeType: 'up',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
            color: 'text-indigo-600 bg-indigo-50',
        },
        {
            label: 'Unidades activas',
            value: '347',
            change: '+12',
            changeType: 'up',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
            color: 'text-violet-600 bg-violet-50',
        },
        {
            label: 'Cobros pendientes',
            value: '23',
            change: '-3',
            changeType: 'down',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>`,
            color: 'text-amber-600 bg-amber-50',
        },
        {
            label: 'Ingresos del mes',
            value: '$45.231',
            change: '+5.1%',
            changeType: 'up',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            color: 'text-emerald-600 bg-emerald-50',
        },
    ];

    const recentActivity = [
        { id: 1, description: 'Pago recibido — Apto 301', time: 'Hace 2 horas', type: 'success' },
        { id: 2, description: 'Factura generada — Torre B', time: 'Hace 4 horas', type: 'info' },
        { id: 3, description: 'Nuevo residente registrado', time: 'Ayer', type: 'success' },
        { id: 4, description: 'Pago fallido — Apto 108', time: 'Hace 2 días', type: 'warning' },
        { id: 5, description: 'Cobro vencido — Apto 214', time: 'Hace 3 días', type: 'danger' },
    ];

    const activityDot = {
        success: 'bg-emerald-500',
        info: 'bg-indigo-500',
        warning: 'bg-amber-500',
        danger: 'bg-red-500',
    };

    const quickActions = [
        { label: 'Nueva propiedad', href: '/properties/create', style: 'bg-indigo-600 hover:bg-indigo-700 text-white' },
        { label: 'Crear cobro', href: '/charges/create', style: 'bg-indigo-50 hover:bg-indigo-100 text-indigo-700' },
        { label: 'Agregar persona', href: '/persons/create', style: 'bg-slate-100 hover:bg-slate-200 text-slate-700' },
    ];
</script>

<svelte:head>
    <title>Dashboard — Portal PH</title>
</svelte:head>

<AppLayout>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-500 mt-1 text-sm">Bienvenido de nuevo, <span class="font-medium text-slate-700">{$page.props.auth.user.name}</span></p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        {#each stats as stat}
            <div class="bg-white rounded-xl border border-slate-200 p-5 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-medium text-slate-500">{stat.label}</p>
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center {stat.color}">
                        {@html stat.icon}
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-slate-900">{stat.value}</p>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full
                        {stat.changeType === 'up' ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50'}">
                        {stat.change}
                    </span>
                </div>
            </div>
        {/each}
    </div>

    <!-- Content grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Activity feed -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Actividad reciente</h2>
                <span class="text-xs text-slate-400">Últimos 7 días</span>
            </div>
            <div class="divide-y divide-slate-50">
                {#each recentActivity as activity}
                    <div class="px-5 py-3.5 flex items-center gap-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-2 h-2 rounded-full flex-shrink-0 {activityDot[activity.type]}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{activity.description}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{activity.time}</p>
                        </div>
                    </div>
                {/each}
            </div>
        </div>

        <!-- Quick actions -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-semibold text-slate-900">Acciones rápidas</h2>
            </div>
            <div class="p-5 space-y-2.5">
                {#each quickActions as action}
                    <a
                        href={action.href}
                        class="flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {action.style}"
                    >
                        {action.label}
                    </a>
                {/each}
            </div>

            <!-- Mini stats -->
            <div class="px-5 pb-5">
                <div class="bg-slate-50 rounded-lg p-4 space-y-3">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Resumen</p>
                    {#each [['Torres registradas', '8'], ['Total personas', '214'], ['Cobros este mes', '67']] as [label, value]}
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500">{label}</span>
                            <span class="font-semibold text-slate-800">{value}</span>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </div>
</AppLayout>
