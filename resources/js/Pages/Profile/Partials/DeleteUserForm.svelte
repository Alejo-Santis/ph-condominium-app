<script>
    import { useForm } from '@inertiajs/svelte';
    import TextInput from '../../../Components/TextInput.svelte';
    import Button from '../../../Components/Button.svelte';
    import Modal from '../../../Components/Modal.svelte';
    import Alert from '../../../Components/Alert.svelte';
    import { notify } from '../../../utils/notify.js';

    const form = useForm({
        password: '',
    });

    let confirmingUserDeletion = $state(false);

    async function confirmUserDeletion() {
        confirmingUserDeletion = true;
    }

    async function deleteUser() {
        await $form.delete('/profile', {
            onSuccess: () => {
                confirmingUserDeletion = false;
                notify.success('Account deleted successfully.', 'Account Deleted');
            },
            onError: () => {
                notify.error('An error occurred while deleting your account.', 'Error');
            },
        });
    }
</script>

<Alert
    type="danger"
    title="Delete Account"
    message="When you delete your account, there is no going back. Please be certain."
/>

<div class="mt-6">
    <Button
        variant="danger"
        onclick={confirmUserDeletion}
    >
        Delete Account
    </Button>
</div>

<Modal
    isOpen={confirmingUserDeletion}
    title="Delete Account"
    onClose={() => (confirmingUserDeletion = false)}
>
    <div class="space-y-4">
        <p class="text-sm text-gray-600">
            Are you sure you want to delete your account? Once your account is deleted,
            there is no going back. Please enter your password to confirm you would like
            to permanently delete your account.
        </p>

        <form onsubmit={(e) => { e.preventDefault(); deleteUser(); }} class="space-y-6">
            <TextInput
                id="password"
                name="password"
                type="password"
                bind:value={$form.password}
                placeholder="Password"
                required
                error={$form.errors.password}
                autofocus
            >
                <span slot="label">Password</span>
            </TextInput>

            <div class="flex gap-3 justify-end">
                <Button
                    variant="secondary"
                    type="button"
                    onclick={() => (confirmingUserDeletion = false)}
                >
                    Cancel
                </Button>
                <Button
                    variant="danger"
                    disabled={$form.processing}
                    type="submit"
                >
                    {#if $form.processing}
                        Deleting...
                    {:else}
                        Delete Account
                    {/if}
                </Button>
            </div>
        </form>
    </div>
</Modal>
