<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Textarea from '../../Components/Textarea.svelte';
    import InputLabel from '../../Components/InputLabel.svelte';
    import InputError from '../../Components/InputError.svelte';
    import Select from '../../Components/Select.svelte';

    let { units = [] } = $props();

    const form = useForm({
        unit_id: '',
        description: '',
        amount: '',
        due_date: '',
        status: 'pending',
        charge_type: '',
    });

    async function submit() {
        $form.post('/charges');
    }
</script>

<svelte:head>
    <title>Create Charge</title>
</svelte:head>

<AppLayout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create New Charge</h1>

        <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <InputLabel for="unit_id">Unit *</InputLabel>
                <Select
                    id="unit_id"
                    bind:value={$form.unit_id}
                    error={$form.errors.unit_id}
                >
                    <option value="">Select a unit</option>
                    {#each units as unit}
                        <option value={unit.id}>
                            {unit.tower?.name} - {unit.number}
                        </option>
                    {/each}
                </Select>
                {#if $form.errors.unit_id}
                    <InputError message={$form.errors.unit_id} />
                {/if}
            </div>

            <div>
                <InputLabel for="description">Description *</InputLabel>
                <Textarea
                    id="description"
                    bind:value={$form.description}
                    placeholder="e.g., Monthly administration fee"
                    error={$form.errors.description}
                />
                {#if $form.errors.description}
                    <InputError message={$form.errors.description} />
                {/if}
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="amount">Amount (COP) *</InputLabel>
                    <TextInput
                        id="amount"
                        type="number"
                        bind:value={$form.amount}
                        placeholder="e.g., 250000"
                        error={$form.errors.amount}
                    />
                    {#if $form.errors.amount}
                        <InputError message={$form.errors.amount} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="due_date">Due Date *</InputLabel>
                    <TextInput
                        id="due_date"
                        type="date"
                        bind:value={$form.due_date}
                        error={$form.errors.due_date}
                    />
                    {#if $form.errors.due_date}
                        <InputError message={$form.errors.due_date} />
                    {/if}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="charge_type">Charge Type *</InputLabel>
                    <Select
                        id="charge_type"
                        bind:value={$form.charge_type}
                        error={$form.errors.charge_type}
                    >
                        <option value="">Select type</option>
                        <option value="administration">Administration</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="special">Special</option>
                        <option value="utility">Utility</option>
                    </Select>
                    {#if $form.errors.charge_type}
                        <InputError message={$form.errors.charge_type} />
                    {/if}
                </div>

                <div>
                    <InputLabel for="status">Status *</InputLabel>
                    <Select
                        id="status"
                        bind:value={$form.status}
                        error={$form.errors.status}
                    >
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="overdue">Overdue</option>
                        <option value="cancelled">Cancelled</option>
                    </Select>
                    {#if $form.errors.status}
                        <InputError message={$form.errors.status} />
                    {/if}
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Creating...' : 'Create Charge'}
                </Button>
                <Button href="/charges" as="a" variant="secondary">
                    Cancel
                </Button>
            </div>
        </form>
    </div>
</AppLayout>
