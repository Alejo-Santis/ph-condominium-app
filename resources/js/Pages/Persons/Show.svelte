<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { person = {} } = $props();
</script>

<svelte:head>
    <title>{person.first_name} {person.last_name}</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{person.first_name} {person.last_name}</h1>
                <p class="text-gray-500 mt-1">{person.document_number}</p>
            </div>
            <div class="space-x-3">
                <Button href={`/persons/${person.id}/edit`} as="a" variant="secondary">
                    Edit
                </Button>
                <Button href="/persons" as="a" variant="ghost">
                    Back
                </Button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <dl class="grid grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-600">Email</dt>
                    <dd class="mt-1 text-gray-900">{person.email}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Phone</dt>
                    <dd class="mt-1 text-gray-900">{person.phone || 'N/A'}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm font-medium text-gray-600">Address</dt>
                    <dd class="mt-1 text-gray-900">{person.address || 'N/A'}</dd>
                </div>
            </dl>
        </div>
    </div>
</AppLayout>
