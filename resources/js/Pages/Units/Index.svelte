<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { units = [] } = $props();
</script>

<svelte:head>
    <title>Units</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Units</h1>
                <p class="text-gray-500 mt-1">Manage residential units</p>
            </div>
            <Button href="/units/create" as="a" variant="primary">
                + New Unit
            </Button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Number</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Tower</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Floor</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Area (m²)</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Bedrooms</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {#each units.data as unit}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">{unit.number}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{unit.tower?.name}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{unit.floor}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{unit.area_m2}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{unit.bedrooms}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href={`/units/${unit.id}`} class="text-blue-600 hover:text-blue-800">View</a>
                                <a href={`/units/${unit.id}/edit`} class="text-gray-600 hover:text-gray-800">Edit</a>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </div>
</AppLayout>
