<script>
    import { useForm } from '@inertiajs/svelte';
    import Button from '../../Components/Button.svelte';
    import Alert from '../../Components/Alert.svelte';
    import GuestLayout from '../../Layouts/GuestLayout.svelte';

    let { status = '' } = $props();

    const form = useForm({});

    async function submit() {
        $form.post('/email/verification-notification');
    }

    async function logout() {
        $form.post('/logout');
    }
</script>

<svelte:head>
    <title>Verify Email</title>
</svelte:head>

<GuestLayout title="Verify your email">
    <p class="text-sm text-gray-600 mb-6">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed you?
        If you didn't receive the email, we will gladly send you another.
    </p>

    {#if status === 'verification-link-sent'}
        <Alert
            type="success"
            title="Verification link sent"
            message="A fresh verification link has been sent to your email address."
            class="mb-6"
        />
    {/if}

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-4">
        <Button as="button" type="submit" variant="primary" disabled={$form.processing} class="w-full justify-center">
            {#if $form.processing}
                Sending...
            {:else}
                Resend Verification Email
            {/if}
        </Button>

        <Button
            variant="secondary"
            onclick={logout}
            type="button"
            class="w-full"
        >
            Logout
        </Button>
    </form>
</GuestLayout>
