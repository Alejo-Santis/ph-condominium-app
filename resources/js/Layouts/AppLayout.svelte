<script>
    import { page, router } from '@inertiajs/svelte';

    let { children } = $props();
    let sidebarOpen = $state(false);
    let userMenuOpen = $state(false);

    const navigation = [
        {
            name: 'Dashboard',
            href: '/dashboard',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
        },
        {
            name: 'Propiedades',
            href: '/properties',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`,
        },
        {
            name: 'Torres',
            href: '/towers',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>`,
        },
        {
            name: 'Unidades',
            href: '/units',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`,
        },
        {
            name: 'Personas',
            href: '/persons',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        },
        {
            name: 'Cobros',
            href: '/charges',
            icon: `<svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>`,
        },
    ];

    const sectionMeta = {
        '/dashboard':   { label: 'Dashboard',    href: '/dashboard' },
        '/properties':  { label: 'Propiedades',  href: '/properties' },
        '/towers':      { label: 'Torres',        href: '/towers' },
        '/units':       { label: 'Unidades',      href: '/units' },
        '/persons':     { label: 'Personas',      href: '/persons' },
        '/charges':     { label: 'Cobros',        href: '/charges' },
    };

    // Build breadcrumbs from URL segments
    let breadcrumbs = $derived(() => {
        const url = $page.url;
        const crumbs = [];

        // Find the base section
        const base = Object.entries(sectionMeta).find(([path]) => url.startsWith(path));
        if (!base) return [{ label: 'Dashboard', href: '/dashboard', active: true }];

        const [basePath, baseMeta] = base;
        const isExact = url === basePath || url === basePath + '/';
        crumbs.push({ label: baseMeta.label, href: baseMeta.href, active: isExact });

        if (!isExact) {
            const rest = url.slice(basePath.length).replace(/^\//, '');
            if (rest === 'create') {
                crumbs.push({ label: 'Nuevo', href: null, active: true });
            } else {
                // It's a uuid segment, possibly with /edit or /payment-config
                const parts = rest.split('/');
                if (parts.length === 1) {
                    crumbs.push({ label: 'Detalle', href: null, active: true });
                } else if (parts[1] === 'edit') {
                    crumbs.push({ label: 'Editar', href: null, active: true });
                } else if (parts[1] === 'payment-config') {
                    crumbs.push({ label: 'Config. Wompi', href: null, active: true });
                } else {
                    crumbs.push({ label: parts[1], href: null, active: true });
                }
            }
        }

        return crumbs;
    });

    // ── Toast notifications ───────────────────────────────────────────────────
    let toasts = $state([]);
    let toastId = 0;

    function addToast(type, message) {
        const id = ++toastId;
        toasts = [...toasts, { id, type, message }];
        setTimeout(() => { toasts = toasts.filter(t => t.id !== id); }, 4000);
    }

    function removeToast(id) {
        toasts = toasts.filter(t => t.id !== id);
    }

    const toastConfig = {
        success: { bg: '#f0fdf4', border: '#86efac', icon: '#22c55e', text: '#15803d',
            svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
        error:   { bg: '#fef2f2', border: '#fca5a5', icon: '#ef4444', text: '#b91c1c',
            svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
        warning: { bg: '#fffbeb', border: '#fcd34d', icon: '#f59e0b', text: '#92400e',
            svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>' },
        info:    { bg: '#eff6ff', border: '#93c5fd', icon: '#3b82f6', text: '#1e40af',
            svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
    };

    // Track the last flash key to avoid re-showing the same message
    let lastFlashKey = '';

    $effect(() => {
        const flash = $page.props.flash ?? {};
        const key = JSON.stringify(flash);
        if (key === lastFlashKey || key === '{}' || key === JSON.stringify({ success: null, error: null, warning: null, info: null })) return;
        lastFlashKey = key;
        if (flash.success) addToast('success', flash.success);
        if (flash.error)   addToast('error',   flash.error);
        if (flash.warning) addToast('warning',  flash.warning);
        if (flash.info)    addToast('info',     flash.info);
    });

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

<div class="flex h-screen overflow-hidden" style="background:#f1f5f9">

    <!-- Mobile overlay -->
    {#if sidebarOpen}
        <div
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-20 lg:hidden"
            onclick={() => (sidebarOpen = false)}
            role="button"
            tabindex="-1"
        ></div>
    {/if}

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-30 w-60 flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto"
        class:-translate-x-full={!sidebarOpen}
        style="background: linear-gradient(160deg, #1e1b4b 0%, #0f172a 100%);"
    >
        <!-- Logo -->
        <div class="flex items-center gap-3 px-5 py-[18px]">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg"
                 style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
                <span class="text-white font-bold text-sm tracking-tight">PH</span>
            </div>
            <div>
                <span class="text-white font-bold text-sm leading-none tracking-tight">Portal PH</span>
                <p class="text-indigo-300/60 text-[11px] mt-0.5 font-medium">Propiedad Horizontal</p>
            </div>
        </div>

        <div class="mx-4 h-px" style="background: rgba(255,255,255,0.07)"></div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="px-3 mb-2.5 text-[10px] font-bold uppercase tracking-widest" style="color:rgba(167,139,250,0.45)">Módulos</p>
            {#each navigation as item}
                {@const isActive = $page.url.startsWith(item.href)}
                <a
                    href={item.href}
                    onclick={() => (sidebarOpen = false)}
                    class="relative flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150 group"
                    style={isActive
                        ? 'color:#e0e7ff; background:rgba(99,102,241,0.18); box-shadow:inset 0 0 0 1px rgba(99,102,241,0.25)'
                        : 'color:#64748b'}
                >
                    {#if isActive}
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 rounded-r bg-indigo-400"></span>
                    {/if}
                    <span class="flex-shrink-0 transition-colors"
                          style={isActive ? 'color:#a5b4fc' : 'color:#475569'}>
                        {@html item.icon}
                    </span>
                    <span class="transition-colors {isActive ? '' : 'group-hover:text-slate-200'}">{item.name}</span>
                </a>
            {/each}
        </nav>

        <!-- User section -->
        <div class="px-3 py-3" style="border-top: 1px solid rgba(255,255,255,0.07)">
            <div data-user-menu class="relative">
                <button
                    onclick={(e) => { e.stopPropagation(); userMenuOpen = !userMenuOpen; }}
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all group"
                    style="color:#94a3b8"
                >
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-white font-bold text-xs"
                         style="background: linear-gradient(135deg, #6366f1, #a78bfa)">
                        {$page.props.auth.user.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 text-left min-w-0">
                        <p class="text-sm font-semibold truncate leading-none" style="color:#e2e8f0">{$page.props.auth.user.name}</p>
                        <p class="text-xs truncate mt-0.5" style="color:#475569">{$page.props.auth.user.email}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 flex-shrink-0" style="color:#475569" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                    </svg>
                </button>

                {#if userMenuOpen}
                    <div class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-xl shadow-2xl border border-slate-100 py-1.5 z-50 overflow-hidden">
                        <a
                            href="/profile"
                            onclick={() => (userMenuOpen = false)}
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition"
                        >
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mi perfil
                        </a>
                        <div class="my-1 mx-3 h-px bg-slate-100"></div>
                        <button
                            onclick={() => router.post('/logout')}
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </div>
                {/if}
            </div>
        </div>
    </aside>

    <!-- Main area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top bar -->
        <header class="flex items-center gap-4 h-14 px-6 bg-white flex-shrink-0"
                style="border-bottom:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.05)">

            <!-- Mobile menu button -->
            <button
                onclick={() => (sidebarOpen = !sidebarOpen)}
                class="lg:hidden p-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-md transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-1.5 text-sm min-w-0">
                {#each breadcrumbs() as crumb, i}
                    {#if i > 0}
                        <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    {/if}
                    {#if crumb.href && !crumb.active}
                        <a href={crumb.href} class="text-slate-400 hover:text-slate-600 font-medium truncate transition-colors">{crumb.label}</a>
                    {:else}
                        <span class="font-semibold text-slate-700 truncate">{crumb.label}</span>
                    {/if}
                {/each}
            </nav>

            <div class="flex-1"></div>

            <!-- Right side actions -->
            <div class="flex items-center gap-2">
                <span class="hidden sm:flex items-center gap-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-full font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {new Date().toLocaleDateString('es-CO', { weekday: 'short', day: 'numeric', month: 'short' })}
                </span>

                <button class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>

                <!-- Avatar -->
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs cursor-pointer"
                     style="background: linear-gradient(135deg, #6366f1, #a78bfa)">
                    {$page.props.auth.user.name.charAt(0).toUpperCase()}
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 overflow-y-auto" style="background:#f1f5f9">
            <div class="px-8 py-7">
                {@render children?.()}
            </div>
        </main>
    </div>

    <!-- Toast notifications -->
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 pointer-events-none" style="min-width:320px; max-width:400px">
        {#each toasts as toast (toast.id)}
            {@const cfg = toastConfig[toast.type] ?? toastConfig.info}
            <div
                class="flex items-start gap-3 px-4 py-3.5 rounded-xl border shadow-xl pointer-events-auto"
                style="background:{cfg.bg}; border-color:{cfg.border}; animation:slideInToast 0.25s ease-out"
                role="alert"
            >
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="{cfg.icon}" viewBox="0 0 24 24">
                    {@html cfg.svg}
                </svg>
                <p class="text-sm font-medium flex-1 leading-snug" style="color:{cfg.text}">{toast.message}</p>
                <button
                    onclick={() => removeToast(toast.id)}
                    class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity"
                    style="color:{cfg.text}"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        {/each}
    </div>
</div>

<style>
    @keyframes slideInToast {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
