<script>
    const features = [
        {
            title: 'Gestión de propiedades',
            desc: 'Administra múltiples propiedades y torres desde un solo panel.',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
        },
        {
            title: 'Control de residentes',
            desc: 'Lleva un registro completo de propietarios, arrendatarios y familiares.',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        },
        {
            title: 'Cobros y cartera',
            desc: 'Genera cobros, controla pagos y gestiona cartera vencida.',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>`,
        },
        {
            title: 'Reportes en tiempo real',
            desc: 'Dashboards y métricas actualizadas para tomar mejores decisiones.',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`,
        },
    ];
</script>

<svelte:head>
    <title>Portal PH — Gestión de Propiedad Horizontal</title>
</svelte:head>

<div class="min-h-screen bg-slate-50 flex flex-col">

    <!-- Nav -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">PH</span>
                </div>
                <span class="font-bold text-slate-900 text-lg">Portal PH</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="/login" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition px-3 py-2">
                    Iniciar sesión
                </a>
                <a href="/register" class="text-sm font-semibold bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Registrarse
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <div class="flex-1 max-w-7xl w-full mx-auto px-6 py-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                    Plataforma de administración
                </span>
                <h1 class="text-5xl font-bold text-slate-900 leading-tight mb-5">
                    Gestiona tu propiedad horizontal de forma
                    <span class="text-indigo-600">inteligente</span>
                </h1>
                <p class="text-lg text-slate-500 leading-relaxed mb-8">
                    Centraliza la administración de propiedades, torres, unidades, residentes y cobros en una sola plataforma moderna y fácil de usar.
                </p>
                <div class="flex items-center gap-4">
                    <a href="/register" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm">
                        Comenzar gratis
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="/login" class="inline-flex items-center gap-2 px-6 py-3 text-slate-700 font-medium rounded-lg border border-slate-300 hover:bg-slate-50 transition text-sm">
                        Ver demo
                    </a>
                </div>
            </div>

            <!-- Features grid -->
            <div class="grid grid-cols-2 gap-4">
                {#each features as feature, i}
                    <div class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md hover:border-indigo-200 transition-all duration-200 {i === 0 ? 'col-span-2' : ''}">
                        <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-3">
                            {@html feature.icon}
                        </div>
                        <h3 class="font-semibold text-slate-900 text-sm mb-1">{feature.title}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{feature.desc}</p>
                    </div>
                {/each}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-indigo-600 rounded flex items-center justify-center">
                    <span class="text-white font-bold text-xs">PH</span>
                </div>
                <span class="text-slate-600 text-sm font-medium">Portal PH</span>
            </div>
            <p class="text-slate-400 text-sm">© 2026 Portal PH. Todos los derechos reservados.</p>
        </div>
    </footer>
</div>
