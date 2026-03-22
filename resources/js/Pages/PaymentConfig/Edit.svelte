<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    let { property = {}, config = null } = $props();

    const form = useForm({
        wompi_public_key:  config?.wompi_public_key  ?? '',
        wompi_private_key: '',
        wompi_secret:      '',
        webhook_secret:    '',
        currency:          config?.currency   ?? 'COP',
        is_active:         config?.is_active  ?? false,
    });

    function submit() {
        $form.put(`/properties/${property.uuid}/payment-config`);
    }
</script>

<svelte:head>
    <title>Configuración Wompi — {property.name}</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href={`/properties/${property.uuid}`} class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Configuración de pagos Wompi</h1>
            <p class="text-sm text-slate-500 mt-0.5">{property.name}</p>
        </div>
    </div>

    {#if config}
        <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm max-w-2xl">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Configuración activa. Los campos de claves sensibles solo se actualizarán si escribes un nuevo valor.
        </div>
    {/if}

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <!-- Public key + status -->
            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-1">Claves de API Wompi</h2>
                <p class="text-xs text-slate-400 mb-4">Encuéntralas en tu dashboard de Wompi → Desarrolladores → Llaves de API.</p>
                <div class="space-y-4">
                    <div>
                        <label for="wompi_public_key" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Llave pública <span class="text-red-500">*</span>
                        </label>
                        <TextInput
                            id="wompi_public_key"
                            bind:value={$form.wompi_public_key}
                            placeholder="pub_stagtest_..."
                            error={$form.errors.wompi_public_key}
                        />
                    </div>
                    <div>
                        <label for="wompi_private_key" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Llave privada
                            {#if config?.has_private_key}
                                <span class="ml-2 text-xs font-normal text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">ya configurada</span>
                            {/if}
                        </label>
                        <TextInput
                            id="wompi_private_key"
                            type="password"
                            bind:value={$form.wompi_private_key}
                            placeholder={config?.has_private_key ? '••••••••  (dejar vacío para no cambiar)' : 'prv_stagtest_...'}
                            error={$form.errors.wompi_private_key}
                        />
                    </div>
                    <div>
                        <label for="wompi_secret" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Secret de integridad
                            {#if config?.has_secret}
                                <span class="ml-2 text-xs font-normal text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">ya configurado</span>
                            {/if}
                        </label>
                        <TextInput
                            id="wompi_secret"
                            type="password"
                            bind:value={$form.wompi_secret}
                            placeholder={config?.has_secret ? '••••••••  (dejar vacío para no cambiar)' : 'stagtest_integrity_...'}
                            error={$form.errors.wompi_secret}
                        />
                    </div>
                    <div>
                        <label for="webhook_secret" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Secret de webhook
                            {#if config?.has_webhook}
                                <span class="ml-2 text-xs font-normal text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">ya configurado</span>
                            {/if}
                        </label>
                        <TextInput
                            id="webhook_secret"
                            type="password"
                            bind:value={$form.webhook_secret}
                            placeholder={config?.has_webhook ? '••••••••  (dejar vacío para no cambiar)' : 'stagtest_events_...'}
                            error={$form.errors.webhook_secret}
                        />
                        <p class="mt-1.5 text-xs text-slate-400">Usado para verificar la autenticidad de los eventos enviados por Wompi.</p>
                    </div>
                </div>
            </div>

            <!-- Currency + active -->
            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Opciones</h2>
                <div class="space-y-4">
                    <div>
                        <label for="currency" class="block text-sm font-medium text-slate-700 mb-1.5">Moneda</label>
                        <select
                            id="currency"
                            bind:value={$form.currency}
                            class="w-48 px-4 py-2.5 border {$form.errors.currency ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="COP">COP — Peso colombiano</option>
                            <option value="USD">USD — Dólar americano</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            role="switch"
                            aria-checked={$form.is_active}
                            onclick={() => $form.is_active = !$form.is_active}
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 {$form.is_active ? 'bg-indigo-600' : 'bg-slate-200'}"
                        >
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {$form.is_active ? 'translate-x-6' : 'translate-x-1'}"></span>
                        </button>
                        <span class="text-sm font-medium text-slate-700">Activar integración Wompi</span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href={`/properties/${property.uuid}`} as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Guardar configuración'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
