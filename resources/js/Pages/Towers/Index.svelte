<script>
    import { page } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { towers = [] } = $props();
</script>

<svelte:head>
    <title>Towers</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Towers</h1>
                <p class="text-gray-500 mt-1">Manage building towers</p>
            </div>
            <Button href="/towers/create" as="a" variant="primary">
                + New Tower
            </Button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Property</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Floors</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {#each towers.data as tower}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{tower.name}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{tower.code}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{tower.property?.name}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{tower.floors}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href={`/towers/${tower.id}`} class="text-blue-600 hover:text-blue-800">View</a>
                                <a href={`/towers/${tower.id}/edit`} class="text-gray-600 hover:text-gray-800">Edit</a>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </div>
</AppLayout>
