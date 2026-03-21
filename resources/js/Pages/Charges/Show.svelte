<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { charge = {} } = $props();

    function getStatusColor(status) {
        const colors = {
            paid: 'bg-green-100 text-green-800',
            pending: 'bg-yellow-100 text-yellow-800',
            overdue: 'bg-red-100 text-red-800',
            cancelled: 'bg-gray-100 text-gray-800',
        };
        return colors[status] || colors.pending;
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
        }).format(amount);
    }
</script>

<svelte:head>
    <title>Charge Details</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Charge Details</h1>
                <p class="text-gray-500 mt-1">{charge.unit?.tower?.name} - {charge.unit?.number}</p>
            </div>
            <div class="space-x-3">
                <Button href={`/charges/${charge.id}/edit`} as="a" variant="secondary">
                    Edit
                </Button>
                <Button href="/charges" as="a" variant="ghost">
                    Back
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Amount</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{formatCurrency(charge.amount)}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm font-medium">Status</p>
                <div class="mt-2">
                    <span class="px-3 py-1 rounded text-sm font-medium {getStatusColor(charge.status)}">
                        {charge.status}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <dl class="grid grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-600">Description</dt>
                    <dd class="mt-1 text-gray-900">{charge.description}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Type</dt>
                    <dd class="mt-1 text-gray-900">{charge.charge_type}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-600">Due Date</dt>
                    <dd class="mt-1 text-gray-900">{charge.due_date}</dd>
                </div>
            </dl>
        </div>
    </div>
</AppLayout>
