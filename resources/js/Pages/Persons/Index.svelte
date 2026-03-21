<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { persons = [] } = $props();

    function getTypeColor(type) {
        return type === 'owner' ? 'bg-blue-100 text-blue-800' : type === 'tenant' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
    }
</script>

<svelte:head>
    <title>Persons</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Residents</h1>
                <p class="text-gray-500 mt-1">Manage residents and owners</p>
            </div>
            <Button href="/persons/create" as="a" variant="primary">
                + New Resident
            </Button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Document</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {#each persons.data as person}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{person.first_name} {person.last_name}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{person.document_number}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{person.email}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium {getTypeColor(person.type)}">
                                    {person.type}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href={`/persons/${person.id}`} class="text-blue-600 hover:text-blue-800">View</a>
                                <a href={`/persons/${person.id}/edit`} class="text-gray-600 hover:text-gray-800">Edit</a>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </div>
</AppLayout>
