<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Button from '../../Components/Button.svelte';
    import GuestLayout from '../../Layouts/GuestLayout.svelte';

    let { email = '', token = '' } = $props();

    const form = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    async function submit() {
        $form.post('/reset-password', {
            onFinish: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<svelte:head>
    <title>Reset Password</title>
</svelte:head>

<GuestLayout title="Reset your password">
    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-6">
        <TextInput
            id="email"
            name="email"
            type="email"
            bind:value={$form.email}
            required
            disabled
        >
            <span slot="label">Email</span>
        </TextInput>

        <TextInput
            id="password"
            name="password"
            type="password"
            bind:value={$form.password}
            placeholder="New password"
            required
            error={$form.errors.password}
        >
            <span slot="label">Password</span>
        </TextInput>

        <TextInput
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            bind:value={$form.password_confirmation}
            placeholder="Confirm password"
            required
            error={$form.errors.password_confirmation}
        >
            <span slot="label">Confirm Password</span>
        </TextInput>

        <Button as="button" type="submit" variant="primary" disabled={$form.processing} class="w-full justify-center">
            {#if $form.processing}
                Resetting...
            {:else}
                Reset Password
            {/if}
        </Button>
    </form>
</GuestLayout>
