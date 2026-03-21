<script>
    import { page } from '@inertiajs/svelte';
    import { Link } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { properties = [] } = $props();
</script>

<svelte:head>
    <title>Properties</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Properties</h1>
                <p class="text-gray-500 mt-1">Manage all condominium properties</p>
            </div>
            <Button href="/properties/create" as="a" variant="primary">
                + New Property
            </Button>
        </div>

        <!-- Properties Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Address</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">City</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {#each properties.data as property}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {property.name}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {property.address}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {property.city}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <Badge status="active" variant="success">Active</Badge>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href={`/properties/${property.id}`} class="text-blue-600 hover:text-blue-800">View</a>
                                <a href={`/properties/${property.id}/edit`} class="text-gray-600 hover:text-gray-800">Edit</a>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>

        <!-- Pagination info -->
        {#if properties.total}
            <div class="text-sm text-gray-600">
                Showing {properties.from} to {properties.to} of {properties.total} properties
            </div>
        {/if}
    </div>
</AppLayout>
