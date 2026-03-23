<script>
    import PortalLayout from '@/Layouts/PortalLayout.svelte';
</script>

<PortalLayout>
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-slate-800 mb-2">Cuenta sin perfil asociado</h1>
        <p class="text-slate-500 text-sm max-w-sm">
            Tu usuario aún no tiene un perfil de residente vinculado. Contacta al administrador del conjunto para que asocie tu cuenta.
        </p>
    </div>
</PortalLayout>
