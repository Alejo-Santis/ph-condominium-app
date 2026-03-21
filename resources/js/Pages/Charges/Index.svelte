<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import Badge from '../../Components/Badge.svelte';

    let { charges = [] } = $props();

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
    <title>Charges</title>
</svelte:head>

<AppLayout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Charges</h1>
                <p class="text-gray-500 mt-1">Manage unit charges and billing</p>
            </div>
            <Button href="/charges/create" as="a" variant="primary">
                + New Charge
            </Button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Unit</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Due Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {#each charges.data as charge}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {charge.unit?.tower?.name} - {charge.unit?.number}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{charge.description}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                                {formatCurrency(charge.amount)}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{charge.due_date}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded text-xs font-medium {getStatusColor(charge.status)}">
                                    {charge.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href={`/charges/${charge.id}`} class="text-blue-600 hover:text-blue-800">View</a>
                                <a href={`/charges/${charge.id}/edit`} class="text-gray-600 hover:text-gray-800">Edit</a>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
        </div>
    </div>
</AppLayout>
