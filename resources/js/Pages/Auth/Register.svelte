<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Button from '../../Components/Button.svelte';
    import GuestLayout from '../../Layouts/GuestLayout.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    let showPassword = $state(false);
    let showConfirm = $state(false);

    async function submit() {
        $form.post('/register', {
            onFinish: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<svelte:head>
    <title>Registro — Portal PH</title>
</svelte:head>

<GuestLayout title="Create your account">
    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-5">
        <TextInput
            id="name"
            name="name"
            type="text"
            bind:value={$form.name}
            placeholder="Nombre completo"
            required
            error={$form.errors.name}
        >
            <span slot="label">Nombre completo</span>
        </TextInput>

        <TextInput
            id="email"
            name="email"
            type="email"
            bind:value={$form.email}
            placeholder="correo@ejemplo.com"
            required
            error={$form.errors.email}
        >
            <span slot="label">Correo electrónico</span>
        </TextInput>

        <!-- Password con toggle -->
        <div class="flex flex-col space-y-1.5">
            <label for="password" class="text-sm font-medium text-slate-700">
                Contraseña <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input
                    id="password"
                    name="password"
                    type={showPassword ? 'text' : 'password'}
                    bind:value={$form.password}
                    placeholder="••••••••"
                    required
                    class="w-full px-3.5 py-2.5 pr-11 border text-sm rounded-lg text-slate-900 placeholder-slate-400
                        focus:outline-none focus:ring-2 transition duration-150
                        {$form.errors.password
                            ? 'border-red-300 focus:border-red-400 focus:ring-red-200'
                            : 'border-slate-300 focus:border-indigo-400 focus:ring-indigo-100'}"
                />
                <button type="button" onclick={() => (showPassword = !showPassword)}
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" tabindex="-1">
                    {#if showPassword}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    {:else}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {/if}
                </button>
            </div>
            {#if $form.errors.password}
                <p class="text-xs text-red-600 font-medium">{$form.errors.password}</p>
            {/if}
        </div>

        <!-- Confirmar contraseña con toggle -->
        <div class="flex flex-col space-y-1.5">
            <label for="password_confirmation" class="text-sm font-medium text-slate-700">
                Confirmar contraseña <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type={showConfirm ? 'text' : 'password'}
                    bind:value={$form.password_confirmation}
                    placeholder="••••••••"
                    required
                    class="w-full px-3.5 py-2.5 pr-11 border text-sm rounded-lg text-slate-900 placeholder-slate-400
                        focus:outline-none focus:ring-2 transition duration-150
                        {$form.errors.password_confirmation
                            ? 'border-red-300 focus:border-red-400 focus:ring-red-200'
                            : 'border-slate-300 focus:border-indigo-400 focus:ring-indigo-100'}"
                />
                <button type="button" onclick={() => (showConfirm = !showConfirm)}
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition" tabindex="-1">
                    {#if showConfirm}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    {:else}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {/if}
                </button>
            </div>
            {#if $form.errors.password_confirmation}
                <p class="text-xs text-red-600 font-medium">{$form.errors.password_confirmation}</p>
            {/if}
        </div>

        <Button as="button" type="submit" variant="primary" disabled={$form.processing} class="w-full justify-center">
            {#if $form.processing}
                Creando cuenta...
            {:else}
                Registrarse
            {/if}
        </Button>

        <p class="text-center text-sm text-slate-500">
            ¿Ya tienes cuenta?
            <a href="/login" class="text-indigo-600 font-semibold hover:text-indigo-500 transition">
                Inicia sesión aquí
            </a>
        </p>
    </form>
</GuestLayout>
