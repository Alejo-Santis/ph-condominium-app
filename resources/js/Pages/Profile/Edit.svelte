<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.svelte';
    import UpdatePasswordForm from './Partials/UpdatePasswordForm.svelte';
    import DeleteUserForm from './Partials/DeleteUserForm.svelte';
</script>

<svelte:head>
    <title>Profile</title>
</svelte:head>

<AppLayout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Profile</h1>
            <p class="text-gray-600 mt-2">Manage your account settings and preferences</p>
        </div>

        <div class="space-y-6">
            <!-- Update Profile -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                </div>
                <UpdateProfileInformationForm />
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Update Password</h2>
                </div>
                <UpdatePasswordForm />
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Delete Account</h2>
                </div>
                <DeleteUserForm />
            </div>
        </div>
    </div>
</AppLayout>
