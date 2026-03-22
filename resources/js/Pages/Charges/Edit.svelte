<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    let { charge = {}, units = [], persons = [] } = $props();

    const form = useForm({
        unit_id: charge.unit_id || '',
        person_id: charge.person_id || '',
        concept: charge.concept || '',
        amount: charge.amount || '',
        billing_month: charge.billing_month || '',
        status: charge.status || 'pending',
    });

    function submit() {
        $form.put(`/charges/${charge.uuid}`);
    }
</script>

<svelte:head>
    <title>Editar cobro — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/charges" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Editar cobro</h1>
            <p class="text-sm text-slate-500 mt-0.5">#{charge.id}</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Destinatario</h2>
                <div class="space-y-4">
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Unidad <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="unit_id"
                            bind:value={$form.unit_id}
                            class="w-full px-4 py-2.5 border {$form.errors.unit_id ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="">Selecciona una unidad</option>
                            {#each units as unit}
                                <option value={unit.id}>{unit.tower?.name ?? ''} — Apto {unit.number}</option>
                            {/each}
                        </select>
                        {#if $form.errors.unit_id}
                            <p class="mt-1.5 text-xs text-red-600">{$form.errors.unit_id}</p>
                        {/if}
                    </div>
                    <div>
                        <label for="person_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Persona <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="person_id"
                            bind:value={$form.person_id}
                            class="w-full px-4 py-2.5 border {$form.errors.person_id ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="">Selecciona una persona</option>
                            {#each persons as person}
                                <option value={person.id}>{person.first_name} {person.last_name}</option>
                            {/each}
                        </select>
                        {#if $form.errors.person_id}
                            <p class="mt-1.5 text-xs text-red-600">{$form.errors.person_id}</p>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Detalle del cobro</h2>
                <div class="space-y-4">
                    <div>
                        <label for="concept" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Concepto <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="concept" bind:value={$form.concept} error={$form.errors.concept} />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="amount" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Valor (COP) <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="amount" type="number" bind:value={$form.amount} error={$form.errors.amount} />
                        </div>
                        <div>
                            <label for="billing_month" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Mes de facturación <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="billing_month" type="date" bind:value={$form.billing_month} error={$form.errors.billing_month} />
                        </div>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
                        <select
                            id="status"
                            bind:value={$form.status}
                            class="w-full px-4 py-2.5 border {$form.errors.status ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="pending">Pendiente</option>
                            <option value="link_generated">Link generado</option>
                            <option value="paid">Pagado</option>
                            <option value="overdue">Vencido</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/charges" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Guardar cambios'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
