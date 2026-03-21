<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { tower = {} } = $props();
</script>

<svelte:head>
    <title>{tower.name}</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{tower.name}</h1>
                <p class="text-gray-500 mt-1">Code: {tower.code}</p>
            </div>
            <div class="space-x-3">
                <Button href={`/towers/${tower.id}/edit`} as="a" variant="secondary">
                    Edit
                </Button>
                <Button href="/towers" as="a" variant="ghost">
                    Back
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Property</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{tower.property?.name}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Floors</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{tower.floors}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Units</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{tower.units ? tower.units.length : 0}</p>
            </div>
        </div>
    </div>
</AppLayout>
