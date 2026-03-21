<script>
    import { page } from '@inertiajs/svelte';
    import { Link } from '@inertiajs/svelte';

    let sidebarOpen = $state(false);
    let userMenuOpen = $state(false);

    const navigation = [
        {
            name: 'Dashboard',
            href: '/dashboard',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
        },
        {
            name: 'Propiedades',
            href: '/properties',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
        },
        {
            name: 'Torres',
            href: '/towers',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>`,
        },
        {
            name: 'Unidades',
            href: '/units',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
        },
        {
            name: 'Personas',
            href: '/persons',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        },
        {
            name: 'Cobros',
            href: '/charges',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>`,
        },
    ];

    $effect(() => {
        function handleClickOutside(e) {
            if (userMenuOpen && !e.target.closest('[data-user-menu]')) {
                userMenuOpen = false;
            }
        }
        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    });
</script>

<div class="flex h-screen bg-slate-50 overflow-hidden">

    <!-- Mobile overlay -->
    {#if sidebarOpen}
        <div
            class="fixed inset-0 bg-black/50 z-20 lg:hidden"
            onclick={() => (sidebarOpen = false)}
            role="button"
            tabindex="-1"
        ></div>
    {/if}

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto"
        style="background-color: #0f172a; {sidebarOpen ? '' : 'transform: translateX(-100%)'}"
        class:translate-x-0={sidebarOpen}
    >
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-sm">PH</span>
            </div>
            <div>
                <span class="text-white font-semibold text-sm leading-none">Portal PH</span>
                <p class="text-slate-400 text-xs mt-0.5">Propiedad Horizontal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            {#each navigation as item}
                {@const isActive = $page.url.startsWith(item.href)}
                <a
                    href={item.href}
                    onclick={() => (sidebarOpen = false)}
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group
                        {isActive
                            ? 'bg-indigo-600 text-white'
                            : 'text-slate-400 hover:bg-white/8 hover:text-white'}"
                >
                    <span class="flex-shrink-0 {isActive ? 'text-white' : 'text-slate-500 group-hover:text-slate-300'}">
                        {@html item.icon}
                    </span>
                    {item.name}
                </a>
            {/each}
        </nav>

        <!-- User section -->
        <div class="px-3 py-4 border-t border-white/10">
            <div data-user-menu class="relative">
                <button
                    onclick={(e) => { e.stopPropagation(); userMenuOpen = !userMenuOpen; }}
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-400 hover:bg-white/8 hover:text-white transition-all"
                >
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-semibold text-xs">
                            {$page.props.auth.user.name.charAt(0).toUpperCase()}
                        </span>
                    </div>
                    <div class="flex-1 text-left min-w-0">
                        <p class="text-slate-200 text-sm font-medium truncate">{$page.props.auth.user.name}</p>
                        <p class="text-slate-500 text-xs truncate">{$page.props.auth.user.email}</p>
                    </div>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                    </svg>
                </button>

                {#if userMenuOpen}
                    <div class="absolute bottom-full left-0 right-0 mb-1 bg-white rounded-lg shadow-xl border border-slate-200 py-1 z-50">
                        <a
                            href="/profile"
                            onclick={() => (userMenuOpen = false)}
                            class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition"
                        >
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi perfil
                        </a>
                        <hr class="my-1 border-slate-100">
                        <form method="POST" action="/logout">
                            <button
                                type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                {/if}
            </div>
        </div>
    </aside>

    <!-- Main area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top bar -->
        <header class="flex items-center gap-4 h-14 px-6 bg-white border-b border-slate-200 flex-shrink-0">
            <button
                onclick={() => (sidebarOpen = !sidebarOpen)}
                class="lg:hidden p-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-md transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex-1"></div>

            <span class="text-xs text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full font-medium">
                {new Date().toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' })}
            </span>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto bg-slate-50">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <slot />
            </div>
        </main>
    </div>
</div>
