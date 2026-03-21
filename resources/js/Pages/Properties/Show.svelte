<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { property = {}, stats = {} } = $props();
</script>

<svelte:head>
    <title>{property.name}</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{property.name}</h1>
                <p class="text-gray-500 mt-1">{property.address}, {property.city}</p>
            </div>
            <div class="space-x-3">
                <Button href={`/properties/${property.id}/edit`} as="a" variant="secondary">
                    Edit
                </Button>
                <Button href="/properties" as="a" variant="ghost">
                    Back
                </Button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Towers</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{stats.towers || 0}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Units</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{stats.units || 0}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Parking Spaces</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{stats.parking_spaces || 0}</p>
            </div>
        </div>

        <!-- Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Property Details</h2>
            <dl class="grid grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-600">City</dt>
                    <dd class="mt-1 text-gray-900">{property.city}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Province</dt>
                    <dd class="mt-1 text-gray-900">{property.province}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Postal Code</dt>
                    <dd class="mt-1 text-gray-900">{property.postal_code}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Country</dt>
                    <dd class="mt-1 text-gray-900">{property.country}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-sm font-medium text-gray-600">Common Areas</dt>
                    <dd class="mt-1 text-gray-900">{property.common_areas || 'N/A'}</dd>
                </div>
            </dl>
        </div>
    </div>
</AppLayout>
