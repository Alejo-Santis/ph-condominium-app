<script>
    import { useForm, page } from '@inertiajs/svelte';
    import TextInput from '../../../Components/TextInput.svelte';
    import Button from '../../../Components/Button.svelte';
    import Alert from '../../../Components/Alert.svelte';

    const form = useForm({
        name: $page.props.auth.user.name || '',
        email: $page.props.auth.user.email || '',
    });

    let recentlySuccessful = $state(false);

    async function submit() {
        $form.patch('/profile', {
            onSuccess: () => {
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
        <Alert type="success" message="Profile information updated successfully." />
    {/if}

    <TextInput
        id="name"
        name="name"
        type="text"
        bind:value={$form.name}
        required
        error={$form.errors.name}
    >
        <span slot="label">Name</span>
    </TextInput>

    <TextInput
        id="email"
        name="email"
        type="email"
        bind:value={$form.email}
        required
        error={$form.errors.email}
    >
        <span slot="label">Email</span>
    </TextInput>

    <div class="flex gap-4">
        <Button
            variant="primary"
            disabled={!$form.isDirty || $form.processing}
        >
            {#if $form.processing}
                Saving...
            {:else}
                Save Changes
            {/if}
        </Button>

        {#if $form.isDirty}
            <Button
                variant="secondary"
                type="button"
                onclick={() => $form.reset()}
            >
                Cancel
            </Button>
        {/if}
    </div>
</form>
