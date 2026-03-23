<script>
    import AppLayout from '@/Layouts/AppLayout.svelte';
    import { useForm, page } from '@inertiajs/svelte';

    let { property, apiKeys } = $props();

    // New key revealed once after generation
    let revealedKey = $state($page.props.flash?.new_api_key ?? null);

    const form = useForm({ name: '' });

    function submit() {
        $form.post(`/properties/${property.uuid}/api-keys`, {
            onSuccess: () => { $form.reset('name'); },
        });
    }

    function toggle(key) {
        $page.router?.patch(`/properties/${property.uuid}/api-keys/${key.uuid}/toggle`);
    }

    function destroy(key) {
        if (!confirm(`¿Eliminar la key "${key.name}"? Esta acción no se puede deshacer.`)) return;
        $page.router?.delete(`/properties/${property.uuid}/api-keys/${key.uuid}`);
    }

    function copyKey() {
        navigator.clipboard.writeText(revealedKey);
    }

    function statusBadge(active) {
        return active
            ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200'
            : 'bg-slate-100 text-slate-500 ring-1 ring-slate-200';
    }
</script>

<AppLayout>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="/properties/{property.uuid}"
               class="p-2 rounded-lg hover:bg-white border border-transparent hover:border-slate-200 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">API Keys</h1>
                <p class="text-sm text-slate-500 mt-0.5">{property.name} — acceso para sistemas externos</p>
            </div>
        </div>
    </div>

    <!-- Revealed key banner -->
    {#if revealedKey}
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-amber-800 mb-1">Copia esta clave ahora — no se mostrará de nuevo</p>
                    <div class="flex items-center gap-2 mt-2">
                        <code class="flex-1 bg-white border border-amber-200 rounded-lg px-3 py-2 text-sm font-mono text-slate-800 break-all">
                            {revealedKey}
                        </code>
                        <button onclick={copyKey}
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-semibold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copiar
                        </button>
                    </div>
                </div>
                <button onclick={() => revealedKey = null} class="text-amber-400 hover:text-amber-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    {/if}

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Keys list -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-700 text-sm">Claves generadas</h2>
                    <span class="text-xs text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full font-medium">{apiKeys.length} total</span>
                </div>

                {#if apiKeys.length === 0}
                    <div class="py-16 text-center">
                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-500">No hay API Keys generadas</p>
                        <p class="text-xs text-slate-400 mt-1">Genera una clave para conectar sistemas externos</p>
                    </div>
                {:else}
                    <div class="divide-y divide-slate-100">
                        {#each apiKeys as key (key.uuid)}
                            <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors group">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate">{key.name}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Creada {new Date(key.created_at).toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' })}
                                        {#if key.last_used_at}
                                            · Último uso {new Date(key.last_used_at).toLocaleDateString('es-CO', { day: 'numeric', month: 'short' })}
                                        {:else}
                                            · Nunca usada
                                        {/if}
                                    </p>
                                </div>
                                <span class="flex-shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {statusBadge(key.is_active)}">
                                    {key.is_active ? 'Activa' : 'Inactiva'}
                                </span>
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick={() => toggle(key)}
                                            class="p-2 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors"
                                            title={key.is_active ? 'Desactivar' : 'Activar'}>
                                        {#if key.is_active}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        {:else}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        {/if}
                                    </button>
                                    <button onclick={() => destroy(key)}
                                            class="p-2 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors"
                                            title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        {/each}
                    </div>
                {/if}
            </div>
        </div>

        <!-- Sidebar: generate + docs -->
        <div class="space-y-5">

            <!-- Generate form -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="font-semibold text-slate-700 text-sm mb-4">Generar nueva clave</h3>
                <form onsubmit={(e) => { e.preventDefault(); submit(); }}>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nombre descriptivo</label>
                        <input type="text" bind:value={$form.name} placeholder="Ej: ERP Administrativo"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition bg-slate-50"/>
                        {#if $form.errors.name}
                            <p class="text-xs text-red-500 mt-1">{$form.errors.name}</p>
                        {/if}
                    </div>
                    <button type="submit" disabled={$form.processing}
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Generar API Key
                    </button>
                </form>
            </div>

            <!-- Docs -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="font-semibold text-slate-700 text-sm mb-3">Cómo usar la API</h3>
                <p class="text-xs text-slate-500 mb-3">Incluye el header en cada request:</p>
                <code class="block bg-slate-50 border border-slate-200 rounded-lg px-3 py-2.5 text-xs font-mono text-slate-700 break-all">
                    X-Api-Key: tu-clave-aquí
                </code>
                <div class="mt-4 space-y-2.5">
                    {#each [
                        { method: 'GET', path: '/api/v1/units', desc: 'Listar unidades' },
                        { method: 'GET', path: '/api/v1/charges', desc: 'Listar cobros' },
                        { method: 'POST', path: '/api/v1/charges', desc: 'Crear cobro' },
                        { method: 'GET', path: '/api/v1/charges/{ref}', desc: 'Consultar cobro' },
                    ] as ep}
                        <div class="flex items-start gap-2">
                            <span class="flex-shrink-0 text-[10px] font-bold px-1.5 py-0.5 rounded mt-0.5
                                {ep.method === 'GET' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700'}">
                                {ep.method}
                            </span>
                            <div>
                                <code class="text-xs text-slate-600 font-mono">{ep.path}</code>
                                <p class="text-xs text-slate-400">{ep.desc}</p>
                            </div>
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    </div>
</AppLayout>
