<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../../Components/TextInput.svelte';
    import Button from '../../../Components/Button.svelte';
    import Alert from '../../../Components/Alert.svelte';

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    let recentlySuccessful = $state(false);

    async function submit() {
        $form.put('/password', {
            onSuccess: () => {
                $form.reset();
                recentlySuccessful = true;
                setTimeout(() => {
                    recentlySuccessful = false;
                }, 2000);
            },
        });
    }
</script>

<form onsubmit={(e) => { e.preventDefault(); submit(); }} class="space-y-6">
    {#if recentlySuccessful}
        <Alert type="success" message="Password updated successfully." />
    {/if}

    <TextInput
        id="current_password"
        name="current_password"
        type="password"
        bind:value={$form.current_password}
        placeholder="Current password"
        required
        error={$form.errors.current_password}
    >
        <span slot="label">Current Password</span>
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
        <span slot="label">New Password</span>
    </TextInput>

    <TextInput
        id="password_confirmation"
        name="password_confirmation"
        type="password"
        bind:value={$form.password_confirmation}
        placeholder="Confirm new password"
        required
        error={$form.errors.password_confirmation}
    >
        <span slot="label">Confirm Password</span>
    </TextInput>

    <Button variant="primary" disabled={$form.processing}>
        {#if $form.processing}
            Updating...
        {:else}
            Update Password
        {/if}
    </Button>
</form>
