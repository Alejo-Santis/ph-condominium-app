<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Button from '../../Components/Button.svelte';
    import GuestLayout from '../../Layouts/GuestLayout.svelte';

    const form = useForm({
        password: '',
    });

    async function submit() {
        $form.post('/password/confirm', {
            onFinish: () => $form.reset('password'),
        });
    }
</script>

<svelte:head>
    <title>Confirm Password</title>
</svelte:head>

<GuestLayout title="Confirm your password">
    <p class="text-sm text-gray-600 mb-6">
        This is a secure area. Please confirm your password before continuing.
    </p>

    <form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-6">
        <TextInput
            id="password"
            name="password"
            type="password"
            bind:value={$form.password}
            placeholder="Password"
            required
            error={$form.errors.password}
        >
            <span slot="label">Password</span>
        </TextInput>

        <Button as="button" type="submit" variant="primary" disabled={$form.processing} class="w-full justify-center">
            {#if $form.processing}
                Confirming...
            {:else}
                Confirm
            {/if}
        </Button>
    </form>
</GuestLayout>
