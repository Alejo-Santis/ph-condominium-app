<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    let { person = {} } = $props();

    const form = useForm({
        document_type: person.document_type || '',
        document_number: person.document_number || '',
        first_name: person.first_name || '',
        last_name: person.last_name || '',
        email: person.email || '',
        phone: person.phone || '',
        alt_phone: person.alt_phone || '',
    });

    function submit() {
        $form.put(`/persons/${person.uuid}`);
    }
</script>

<svelte:head>
    <title>Editar persona — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/persons" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Editar persona</h1>
            <p class="text-sm text-slate-500 mt-0.5">{person.first_name} {person.last_name}</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Identificación</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="document_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tipo de documento <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="document_type"
                            bind:value={$form.document_type}
                            class="w-full px-4 py-2.5 border {$form.errors.document_type ? 'border-red-300' : 'border-slate-300'} rounded-lg text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                            <option value="">Seleccionar</option>
                            <option value="cc">Cédula de Ciudadanía</option>
                            <option value="ce">Cédula de Extranjería</option>
                            <option value="nit">NIT</option>
                            <option value="passport">Pasaporte</option>
                        </select>
                        {#if $form.errors.document_type}
                            <p class="mt-1.5 text-xs text-red-600">{$form.errors.document_type}</p>
                        {/if}
                    </div>
                    <div>
                        <label for="document_number" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Número de documento <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="document_number" bind:value={$form.document_number} error={$form.errors.document_number} />
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Datos personales</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nombres <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="first_name" bind:value={$form.first_name} error={$form.errors.first_name} />
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Apellidos <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="last_name" bind:value={$form.last_name} error={$form.errors.last_name} />
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Correo electrónico <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="email" type="email" bind:value={$form.email} error={$form.errors.email} />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Teléfono</label>
                            <TextInput id="phone" bind:value={$form.phone} error={$form.errors.phone} />
                        </div>
                        <div>
                            <label for="alt_phone" class="block text-sm font-medium text-slate-700 mb-1.5">Teléfono alternativo</label>
                            <TextInput id="alt_phone" bind:value={$form.alt_phone} error={$form.errors.alt_phone} />
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/persons" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Guardar cambios'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
