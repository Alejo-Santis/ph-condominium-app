<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    let { towers = [] } = $props();

    const form = useForm({
        tower_id: '',
        number: '',
        floor: '',
        area_sqm: '',
        type: 'residential',
    });

    function submit() {
        $form.post('/units');
    }
</script>

<svelte:head>
    <title>Nueva unidad — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/units" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Nueva unidad</h1>
            <p class="text-sm text-slate-500 mt-0.5">Registra un apartamento o local</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Torre asociada</h2>
                <label for="tower_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Torre <span class="text-red-500">*</span>
                </label>
                <select
                    id="tower_id"
                    bind:value={$form.tower_id}
                    class="w-full px-4 py-2.5 border {$form.errors.tower_id ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                >
                    <option value="">Selecciona una torre</option>
                    {#each towers as tower}
                        <option value={tower.id}>{tower.name} — {tower.property?.name}</option>
                    {/each}
                </select>
                {#if $form.errors.tower_id}
                    <p class="mt-1.5 text-xs text-red-600">{$form.errors.tower_id}</p>
                {/if}
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Identificación</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="number" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Número de unidad <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="number" bind:value={$form.number} placeholder="101" error={$form.errors.number} />
                    </div>
                    <div>
                        <label for="floor" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Piso <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="floor" type="number" bind:value={$form.floor} placeholder="1" error={$form.errors.floor} />
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Características</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="area_sqm" class="block text-sm font-medium text-slate-700 mb-1.5">Área m²</label>
                        <TextInput id="area_sqm" type="number" bind:value={$form.area_sqm} placeholder="75" error={$form.errors.area_sqm} />
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="type"
                            bind:value={$form.type}
                            class="w-full px-4 py-2.5 border {$form.errors.type ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="residential">Residencial</option>
                            <option value="commercial">Comercial</option>
                            <option value="mixed">Mixto</option>
                        </select>
                        {#if $form.errors.type}
                            <p class="mt-1.5 text-xs text-red-600">{$form.errors.type}</p>
                        {/if}
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/units" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Crear unidad'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
