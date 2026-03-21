<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Textarea from '../../Components/Textarea.svelte';
    import InputLabel from '../../Components/InputLabel.svelte';
    import InputError from '../../Components/InputError.svelte';

    let { property = {} } = $props();

    const form = useForm({
        name: property.name || '',
        address: property.address || '',
        city: property.city || '',
        province: property.province || '',
        postal_code: property.postal_code || '',
        country: property.country || '',
        common_areas: property.common_areas || '',
    });

    async function submit() {
        $form.put(`/properties/${property.id}`);
    }
</script>

<svelte:head>
    <title>Edit Property</title>
</svelte:head>

<AppLayout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Property</h1>

        <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <InputLabel for="name">Property Name *</InputLabel>
                <TextInput
                    id="name"
                    bind:value={$form.name}
                    placeholder="e.g., Towers of Paradise"
                    error={$form.errors.name}
                />
                {#if $form.errors.name}
                    <InputError message={$form.errors.name} />
                {/if}
            </div>

            <div>
                <InputLabel for="address">Address *</InputLabel>
                <TextInput
                    id="address"
                    bind:value={$form.address}
                    placeholder="Street address"
                    error={$form.errors.address}
                />
                {#if $form.errors.address}
                    <InputError message={$form.errors.address} />
                {/if}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="city">City *</InputLabel>
                    <TextInput
                        id="city"
                        bind:value={$form.city}
                        error={$form.errors.city}
                    />
                    {#if $form.errors.city}
                        <InputError message={$form.errors.city} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="province">Province *</InputLabel>
                    <TextInput
                        id="province"
                        bind:value={$form.province}
                        error={$form.errors.province}
                    />
                    {#if $form.errors.province}
                        <InputError message={$form.errors.province} />
                    {/if}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="postal_code">Postal Code *</InputLabel>
                    <TextInput
                        id="postal_code"
                        bind:value={$form.postal_code}
                        error={$form.errors.postal_code}
                    />
                    {#if $form.errors.postal_code}
                        <InputError message={$form.errors.postal_code} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="country">Country *</InputLabel>
                    <TextInput
                        id="country"
                        bind:value={$form.country}
                        error={$form.errors.country}
                    />
                    {#if $form.errors.country}
                        <InputError message={$form.errors.country} />
                    {/if}
                </div>
            </div>

            <div>
                <InputLabel for="common_areas">Common Areas</InputLabel>
                <Textarea
                    id="common_areas"
                    bind:value={$form.common_areas}
                    placeholder="Describe common areas (gym, pool, garden, etc.)"
                    error={$form.errors.common_areas}
                />
                {#if $form.errors.common_areas}
                    <InputError message={$form.errors.common_areas} />
                {/if}
            </div>

            <div class="flex gap-4 pt-4">
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Saving...' : 'Save Changes'}
                </Button>
                <Button href="/properties" as="a" variant="secondary">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
</AppLayout>
