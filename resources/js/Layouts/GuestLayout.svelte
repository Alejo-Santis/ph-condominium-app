<script>
    let { title = '' } = $props();
</script>

<div class="min-h-screen bg-slate-50 flex">

    <!-- Left panel — branding -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12" style="background-color: #0f172a;">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">PH</span>
            </div>
            <span class="text-white font-semibold text-lg">Portal PH</span>
        </div>

        <div>
            <h2 class="text-4xl font-bold text-white leading-tight">
                Gestión inteligente<br/>de tu propiedad<br/>horizontal.
            </h2>
            <p class="mt-4 text-slate-400 text-lg leading-relaxed">
                Administra propiedades, torres, unidades, residentes y cobros desde un solo lugar.
            </p>

            <div class="mt-10 space-y-4">
                {#each ['Gestión de propiedades y torres', 'Control de residentes y propietarios', 'Cobros y cartera automatizada'] as feature}
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 bg-indigo-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-slate-300 text-sm">{feature}</span>
                    </div>
                {/each}
            </div>
        </div>

        <p class="text-slate-600 text-sm">© 2026 Portal PH. Todos los derechos reservados.</p>
    </div>

    <!-- Right panel — form -->
    <div class="flex-1 flex flex-col justify-center items-center p-8">
        <!-- Mobile logo -->
        <div class="lg:hidden mb-8 text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 rounded-lg mb-3">
                <span class="text-white font-bold text-sm">PH</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Portal PH</h1>
        </div>

        <div class="w-full max-w-md">
            {#if title}
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">{title}</h2>
                </div>
            {/if}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <slot />
            </div>
        </div>
    </div>
</div>
