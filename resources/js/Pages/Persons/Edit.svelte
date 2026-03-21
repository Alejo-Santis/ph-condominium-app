<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Textarea from '../../Components/Textarea.svelte';
    import InputLabel from '../../Components/InputLabel.svelte';
    import InputError from '../../Components/InputError.svelte';
    import Select from '../../Components/Select.svelte';

    let { person = {} } = $props();

    const form = useForm({
        document_type: person.document_type || '',
        document_number: person.document_number || '',
        first_name: person.first_name || '',
        last_name: person.last_name || '',
        email: person.email || '',
        phone: person.phone || '',
        address: person.address || '',
        type: person.type || '',
    });

    async function submit() {
        $form.put(`/persons/${person.id}`);
    }
</script>

<svelte:head>
    <title>Edit Resident</title>
</svelte:head>

<AppLayout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Edit Resident</h1>

        <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="bg-white rounded-lg shadow p-6 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="document_type">Document Type *</InputLabel>
                    <Select
                        id="document_type"
                        bind:value={$form.document_type}
                        error={$form.errors.document_type}
                    >
                        <option value="">Select type</option>
                        <option value="cc">Cédula de Ciudadanía</option>
                        <option value="ce">Cédula de Extranjería</option>
                        <option value="pasaporte">Pasaporte</option>
                        <option value="nit">NIT</option>
                    </Select>
                    {#if $form.errors.document_type}
                        <InputError message={$form.errors.document_type} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="document_number">Document Number *</InputLabel>
                    <TextInput
                        id="document_number"
                        bind:value={$form.document_number}
                        error={$form.errors.document_number}
                    />
                    {#if $form.errors.document_number}
                        <InputError message={$form.errors.document_number} />
                    {/if}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="first_name">First Name *</InputLabel>
                    <TextInput
                        id="first_name"
                        bind:value={$form.first_name}
                        error={$form.errors.first_name}
                    />
                    {#if $form.errors.first_name}
                        <InputError message={$form.errors.first_name} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="last_name">Last Name *</InputLabel>
                    <TextInput
                        id="last_name"
                        bind:value={$form.last_name}
                        error={$form.errors.last_name}
                    />
                    {#if $form.errors.last_name}
                        <InputError message={$form.errors.last_name} />
                    {/if}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="email">Email *</InputLabel>
                    <TextInput
                        id="email"
                        type="email"
                        bind:value={$form.email}
                        error={$form.errors.email}
                    />
                    {#if $form.errors.email}
                        <InputError message={$form.errors.email} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="phone">Phone</InputLabel>
                    <TextInput
                        id="phone"
                        bind:value={$form.phone}
                        error={$form.errors.phone}
                    />
                    {#if $form.errors.phone}
                        <InputError message={$form.errors.phone} />
                    {/if}
                </div>
            </div>

            <div>
                <InputLabel for="address">Address</InputLabel>
                <Textarea
                    id="address"
                    bind:value={$form.address}
                    error={$form.errors.address}
                />
                {#if $form.errors.address}
                    <InputError message={$form.errors.address} />
                {/if}
            </div>

            <div>
                <InputLabel for="type">Resident Type *</InputLabel>
                <Select
                    id="type"
                    bind:value={$form.type}
                    error={$form.errors.type}
                >
                    <option value="">Select type</option>
                    <option value="owner">Owner</option>
                    <option value="tenant">Tenant</option>
                    <option value="family_member">Family Member</option>
                </Select>
                {#if $form.errors.type}
                    <InputError message={$form.errors.type} />
                {/if}
            </div>

            <div class="flex gap-4 pt-4">
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Saving...' : 'Save Changes'}
                </Button>
                <Button href="/persons" as="a" variant="secondary">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
</AppLayout>
