<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Button from '../../Components/Button.svelte';
    import Alert from '../../Components/Alert.svelte';
    import GuestLayout from '../../Layouts/GuestLayout.svelte';

    let { status = '' } = $props();

    const form = useForm({
        email: '',
    });

    async function submit() {
        $form.post('/forgot-password');
    }
</script>

<svelte:head>
    <title>Forgot Password</title>
</svelte:head>

<GuestLayout title="Forgot your password?">
    <p class="text-sm text-gray-600 mb-6">
        No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    {#if status}
        <Alert type="success" message={status} class="mb-6" />
    {/if}

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-6">
        <TextInput
            id="email"
            name="email"
            type="email"
            bind:value={$form.email}
            placeholder="Email address"
            required
            error={$form.errors.email}
        >
            <span slot="label">Email Address</span>
        </TextInput>

        <Button as="button" type="submit" variant="primary" disabled={$form.processing} class="w-full justify-center">
            {#if $form.processing}
                Sending...
            {:else}
                Email Password Reset Link
            {/if}
        </Button>
    </form>
</GuestLayout>
