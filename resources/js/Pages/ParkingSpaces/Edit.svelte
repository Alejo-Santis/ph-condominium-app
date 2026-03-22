<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    let { parkingSpace = {}, properties = [], towers = [], units = [] } = $props();

    const form = useForm({
        property_id: parkingSpace.property_id || '',
        tower_id:    parkingSpace.tower_id    || '',
        unit_id:     parkingSpace.unit_id     || '',
        code:        parkingSpace.code        || '',
        type:        parkingSpace.type        || 'vehicle',
        status:      parkingSpace.status      || 'available',
    });

    let filteredTowers = $derived(
        $form.property_id ? towers.filter(t => String(t.property_id) === String($form.property_id)) : towers
    );
    let filteredUnits = $derived(
        $form.tower_id ? units.filter(u => String(u.tower_id) === String($form.tower_id)) : units
    );

    function submit() {
        $form.put(`/parking-spaces/${parkingSpace.uuid}`);
    }

    const selectClass = (err) =>
        `w-full px-4 py-2.5 border ${err ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition`;
</script>

<svelte:head>
    <title>Editar parqueadero {parkingSpace.code} — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/parking-spaces" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900">Editar parqueadero</h1>
            <p class="text-sm text-slate-500 mt-0.5">Código: {parkingSpace.code}</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 shadow-sm">

            <div class="px-6 py-5">
                <h2 class="text-sm font-bold text-slate-700 mb-4">Ubicación</h2>
                <div class="space-y-4">
                    <div>
                        <label for="property_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Propiedad <span class="text-red-500">*</span>
                        </label>
                        <select id="property_id" bind:value={$form.property_id} class={selectClass($form.errors.property_id)}
                                onchange={() => { $form.tower_id = ''; $form.unit_id = ''; }}>
                            <option value="">Selecciona una propiedad</option>
                            {#each properties as p}
                                <option value={p.id}>{p.name}</option>
                            {/each}
                        </select>
                        {#if $form.errors.property_id}<p class="mt-1.5 text-xs text-red-600">{$form.errors.property_id}</p>{/if}
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="tower_id" class="block text-sm font-medium text-slate-700 mb-1.5">Torre</label>
                            <select id="tower_id" bind:value={$form.tower_id} class={selectClass($form.errors.tower_id)}
                                    onchange={() => { $form.unit_id = ''; }}>
                                <option value="">Sin torre</option>
                                {#each filteredTowers as t}
                                    <option value={t.id}>{t.name}</option>
                                {/each}
                            </select>
                        </div>
                        <div>
                            <label for="unit_id" class="block text-sm font-medium text-slate-700 mb-1.5">Unidad asignada</label>
                            <select id="unit_id" bind:value={$form.unit_id} class={selectClass($form.errors.unit_id)}>
                                <option value="">Sin asignar</option>
                                {#each filteredUnits as u}
                                    <option value={u.id}>{u.tower?.name ?? ''} — Apto {u.number}</option>
                                {/each}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-bold text-slate-700 mb-4">Identificación</h2>
                <div>
                    <label for="code" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Código <span class="text-red-500">*</span>
                    </label>
                    <TextInput id="code" bind:value={$form.code} error={$form.errors.code} />
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-bold text-slate-700 mb-4">Tipo y estado</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <select id="type" bind:value={$form.type} class={selectClass($form.errors.type)}>
                            <option value="vehicle">🚗 Vehículo</option>
                            <option value="motorcycle">🏍️ Motocicleta</option>
                            <option value="bicycle">🚲 Bicicleta</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
                        <select id="status" bind:value={$form.status} class={selectClass($form.errors.status)}>
                            <option value="available">Disponible</option>
                            <option value="assigned">Asignado</option>
                            <option value="blocked">Bloqueado</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/parking-spaces" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Guardar cambios'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
