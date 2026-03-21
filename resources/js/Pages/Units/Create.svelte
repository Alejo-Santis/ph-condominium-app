<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import InputLabel from '../../Components/InputLabel.svelte';
    import InputError from '../../Components/InputError.svelte';
    import Select from '../../Components/Select.svelte';

    let { towers = [] } = $props();

    const form = useForm({
        tower_id: '',
        floor: '',
        number: '',
        area_m2: '',
        bedrooms: '',
        bathrooms: '',
    });

    async function submit() {
        $form.post('/units');
    }
</script>

<svelte:head>
    <title>Create Unit</title>
</svelte:head>

<AppLayout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Unit</h1>

        <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <InputLabel for="tower_id">Tower *</InputLabel>
                <Select
                    id="tower_id"
                    bind:value={$form.tower_id}
                    error={$form.errors.tower_id}
                >
                    <option value="">Select a tower</option>
                    {#each towers as tower}
                        <option value={tower.id}>{tower.name}</option>
                    {/each}
                </Select>
                {#if $form.errors.tower_id}
                    <InputError message={$form.errors.tower_id} />
                {/if}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="floor">Floor *</InputLabel>
                    <TextInput
                        id="floor"
                        type="number"
                        bind:value={$form.floor}
                        placeholder="e.g., 1"
                        error={$form.errors.floor}
                    />
                    {#if $form.errors.floor}
                        <InputError message={$form.errors.floor} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="number">Unit Number *</InputLabel>
                    <TextInput
                        id="number"
                        bind:value={$form.number}
                        placeholder="e.g., 101"
                        error={$form.errors.number}
                    />
                    {#if $form.errors.number}
                        <InputError message={$form.errors.number} />
                    {/if}
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <InputLabel for="area_m2">Area (m²) *</InputLabel>
                    <TextInput
                        id="area_m2"
                        type="number"
                        bind:value={$form.area_m2}
                        placeholder="e.g., 75"
                        error={$form.errors.area_m2}
                    />
                    {#if $form.errors.area_m2}
                        <InputError message={$form.errors.area_m2} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="bedrooms">Bedrooms *</InputLabel>
                    <TextInput
                        id="bedrooms"
                        type="number"
                        bind:value={$form.bedrooms}
                        placeholder="e.g., 2"
                        error={$form.errors.bedrooms}
                    />
                    {#if $form.errors.bedrooms}
                        <InputError message={$form.errors.bedrooms} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="bathrooms">Bathrooms *</InputLabel>
                    <TextInput
                        id="bathrooms"
                        type="number"
                        bind:value={$form.bathrooms}
                        placeholder="e.g., 1"
                        error={$form.errors.bathrooms}
                    />
                    {#if $form.errors.bathrooms}
                        <InputError message={$form.errors.bathrooms} />
                    {/if}
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Creating...' : 'Create Unit'}
                </Button>
                <Button href="/units" as="a" variant="secondary">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
</AppLayout>
