<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Textarea from '../../Components/Textarea.svelte';

    let { tower = {}, properties = [] } = $props();

    const form = useForm({
        property_id: tower.property_id || '',
        name: tower.name || '',
        floors: tower.floors || '',
        has_elevator: tower.has_elevator ?? false,
        has_parking: tower.has_parking ?? false,
        description: tower.description || '',
    });

    function submit() {
        $form.put(`/towers/${tower.uuid}`);
    }
</script>

<svelte:head>
    <title>Editar torre — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/towers" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Editar torre</h1>
            <p class="text-sm text-slate-500 mt-0.5">{tower.name}</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Propiedad asociada</h2>
                <label for="property_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Propiedad <span class="text-red-500">*</span>
                </label>
                <select
                    id="property_id"
                    bind:value={$form.property_id}
                    class="w-full px-4 py-2.5 border {$form.errors.property_id ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                >
                    <option value="">Selecciona una propiedad</option>
                    {#each properties as prop}
                        <option value={prop.id}>{prop.name}</option>
                    {/each}
                </select>
                {#if $form.errors.property_id}
                    <p class="mt-1.5 text-xs text-red-600">{$form.errors.property_id}</p>
                {/if}
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Datos de la torre</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="name" bind:value={$form.name} error={$form.errors.name} />
                        </div>
                        <div>
                            <label for="floors" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Número de pisos <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="floors" type="number" bind:value={$form.floors} error={$form.errors.floors} />
                        </div>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Descripción</label>
                        <Textarea id="description" bind:value={$form.description} rows={2} error={$form.errors.description} />
                    </div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" bind:checked={$form.has_elevator} class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" />
                            <span class="text-sm text-slate-700">Tiene ascensor</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" bind:checked={$form.has_parking} class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500" />
                            <span class="text-sm text-slate-700">Tiene parqueadero</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/towers" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Guardar cambios'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
