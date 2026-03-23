<script>
    import { page, router } from '@inertiajs/svelte';

    let { children } = $props();

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
        info:    { bg: '#eff6ff', border: '#93c5fd', icon: '#3b82f6', text: '#1e40af',
            svg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
    };

    let lastFlashKey = '';
    $effect(() => {
        const flash = $page.props.flash ?? {};
        const key = JSON.stringify(flash);
        if (key === lastFlashKey || key === '{}') return;
        lastFlashKey = key;
        if (flash.success) addToast('success', flash.success);
        if (flash.error)   addToast('error',   flash.error);
        if (flash.info)    addToast('info',     flash.info);
    });
</script>

<div class="min-h-screen" style="background:#f1f5f9">

    <!-- Top navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-20"
            style="box-shadow:0 1px 4px rgba(0,0,0,0.05)">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 flex items-center h-14 gap-4">

            <!-- Logo -->
            <a href="/portal" class="flex items-center gap-2.5 flex-shrink-0">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);">
                    <span class="text-white font-bold text-xs">PH</span>
                </div>
                <span class="font-bold text-slate-800 text-sm hidden sm:block">Portal Residente</span>
            </a>

            <div class="flex-1"></div>

            <!-- User -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-slate-700 leading-none">{$page.props.auth.user.name}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{$page.props.auth.user.email}</p>
                </div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                     style="background: linear-gradient(135deg, #6366f1, #a78bfa)">
                    {$page.props.auth.user.name.charAt(0).toUpperCase()}
                </div>
                <button onclick={() => router.post('/logout')}
                        class="flex items-center gap-1.5 text-xs text-slate-400 hover:text-red-500 transition-colors px-2 py-1.5 rounded-lg hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:block">Salir</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        {@render children?.()}
    </main>

    <!-- Toasts -->
    <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2.5 pointer-events-none" style="min-width:300px;max-width:380px">
        {#each toasts as toast (toast.id)}
            {@const cfg = toastConfig[toast.type] ?? toastConfig.info}
            <div class="flex items-start gap-3 px-4 py-3.5 rounded-xl border shadow-xl pointer-events-auto"
                 style="background:{cfg.bg}; border-color:{cfg.border}; animation:slideIn 0.25s ease-out"
                 role="alert">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="{cfg.icon}" viewBox="0 0 24 24">
                    {@html cfg.svg}
                </svg>
                <p class="text-sm font-medium flex-1 leading-snug" style="color:{cfg.text}">{toast.message}</p>
                <button onclick={() => removeToast(toast.id)} class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity" style="color:{cfg.text}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        {/each}
    </div>
</div>

<style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
