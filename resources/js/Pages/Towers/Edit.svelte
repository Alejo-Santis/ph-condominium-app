<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import InputLabel from '../../Components/InputLabel.svelte';
    import InputError from '../../Components/InputError.svelte';
    import Select from '../../Components/Select.svelte';

    let { tower = {}, properties = [] } = $props();

    const form = useForm({
        property_id: tower.property_id || '',
        name: tower.name || '',
        code: tower.code || '',
        floors: tower.floors || '',
        units_per_floor: tower.units_per_floor || '',
    });

    async function submit() {
        $form.put(`/towers/${tower.id}`);
    }
</script>

<svelte:head>
    <title>Edit Tower</title>
</svelte:head>

<AppLayout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Tower</h1>

        <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <InputLabel for="property_id">Property *</InputLabel>
                <Select
                    id="property_id"
                    bind:value={$form.property_id}
                    error={$form.errors.property_id}
                >
                    <option value="">Select a property</option>
                    {#each properties as prop}
                        <option value={prop.id}>{prop.name}</option>
                    {/each}
                </Select>
                {#if $form.errors.property_id}
                    <InputError message={$form.errors.property_id} />
                {/if}
            </div>

            <div>
                <InputLabel for="name">Tower Name *</InputLabel>
                <TextInput
                    id="name"
                    bind:value={$form.name}
                    error={$form.errors.name}
                />
                {#if $form.errors.name}
                    <InputError message={$form.errors.name} />
                {/if}
            </div>

            <div>
                <InputLabel for="code">Code *</InputLabel>
                <TextInput
                    id="code"
                    bind:value={$form.code}
                    error={$form.errors.code}
                />
                {#if $form.errors.code}
                    <InputError message={$form.errors.code} />
                {/if}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="floors">Floors *</InputLabel>
                    <TextInput
                        id="floors"
                        type="number"
                        bind:value={$form.floors}
                        error={$form.errors.floors}
                    />
                    {#if $form.errors.floors}
                        <InputError message={$form.errors.floors} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="units_per_floor">Units Per Floor *</InputLabel>
                    <TextInput
                        id="units_per_floor"
                        type="number"
                        bind:value={$form.units_per_floor}
                        error={$form.errors.units_per_floor}
                    />
                    {#if $form.errors.units_per_floor}
                        <InputError message={$form.errors.units_per_floor} />
                    {/if}
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Saving...' : 'Save Changes'}
                </Button>
                <Button href="/towers" as="a" variant="secondary">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
</AppLayout>
