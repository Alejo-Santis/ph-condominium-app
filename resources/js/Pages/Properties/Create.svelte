<script>
    import { useForm } from '@inertiajs/svelte';
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';
    import TextInput from '../../Components/TextInput.svelte';

    const form = useForm({
        name: '',
        nit: '',
        address: '',
        city: '',
        department: '',
        admin_email: '',
        phone: '',
    });

    function submit() {
        $form.post('/properties');
    }
</script>

<svelte:head>
    <title>Nueva propiedad — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="mb-6 flex items-center gap-3">
        <a href="/properties" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Nueva propiedad</h1>
            <p class="text-sm text-slate-500 mt-0.5">Registra un nuevo conjunto residencial</p>
        </div>
    </div>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="max-w-2xl">
        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Información principal</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="name" bind:value={$form.name} placeholder="Ej: Conjunto Los Pinos" error={$form.errors.name} />
                        </div>
                        <div>
                            <label for="nit" class="block text-sm font-medium text-slate-700 mb-1.5">
                                NIT <span class="text-red-500">*</span>
                            </label>
                            <TextInput id="nit" bind:value={$form.nit} placeholder="900123456-1" error={$form.errors.nit} />
                        </div>
                    </div>
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Dirección <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="address" bind:value={$form.address} placeholder="Calle, número, barrio" error={$form.errors.address} />
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Ubicación</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Ciudad <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="city" bind:value={$form.city} placeholder="Bogotá" error={$form.errors.city} />
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Departamento <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="department" bind:value={$form.department} placeholder="Cundinamarca" error={$form.errors.department} />
                    </div>
                </div>
            </div>

            <div class="px-6 py-5">
                <h2 class="text-sm font-semibold text-slate-700 mb-4">Contacto administrativo</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Correo administración <span class="text-red-500">*</span>
                        </label>
                        <TextInput id="admin_email" type="email" bind:value={$form.admin_email} placeholder="admin@conjunto.com" error={$form.errors.admin_email} />
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Teléfono</label>
                        <TextInput id="phone" bind:value={$form.phone} placeholder="601 123 4567" error={$form.errors.phone} />
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex items-center justify-end gap-3 bg-slate-50">
                <Button href="/properties" as="a" variant="secondary">Cancelar</Button>
                <Button type="submit" variant="primary" disabled={$form.processing}>
                    {$form.processing ? 'Guardando...' : 'Crear propiedad'}
                </Button>
            </div>
        </div>
    </form>
</AppLayout>
