<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { unit = {} } = $props();
</script>

<svelte:head>
    <title>{unit.number}</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Unit {unit.number}</h1>
                <p class="text-gray-500 mt-1">{unit.tower?.name} - Floor {unit.floor}</p>
            </div>
            <div class="space-x-3">
                <Button href={`/units/${unit.id}/edit`} as="a" variant="secondary">
                    Edit
                </Button>
                <Button href="/units" as="a" variant="ghost">
                    Back
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Area</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{unit.area_m2} m²</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Bedrooms</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{unit.bedrooms}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Bathrooms</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{unit.bathrooms}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Residents</p>
                <p class="text-lg font-bold text-gray-900 mt-2">{unit.persons ? unit.persons.length : 0}</p>
            </div>
        </div>
    </div>
</AppLayout>
