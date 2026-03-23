<script>
    import AppLayout from '@/Layouts/AppLayout.svelte';
    import { useForm } from '@inertiajs/svelte';

    let { person, existingUser } = $props();

    const form = useForm({ email: person.email ?? '' });

    function submit() {
        $form.post(`/persons/${person.uuid}/invite`);
    }
</script>

<svelte:head><title>Invitar residente — Portal PH</title></svelte:head>

<AppLayout>
    <div class="max-w-xl mx-auto">

        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="/persons/{person.uuid}"
               class="p-2 rounded-lg hover:bg-white border border-transparent hover:border-slate-200 text-slate-400 hover:text-slate-600 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Invitar al portal</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    {person.first_name} {person.last_name}
                </p>
            </div>
        </div>

        <!-- Existing user warning -->
        {#if existingUser}
            <div class="mb-6 flex items-start gap-3 rounded-xl border p-4
                {existingUser.is_active ? 'bg-blue-50 border-blue-200' : 'bg-amber-50 border-amber-200'}">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {existingUser.is_active ? 'text-blue-500' : 'text-amber-500'}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold {existingUser.is_active ? 'text-blue-800' : 'text-amber-800'}">
                        {existingUser.is_active ? 'Este residente ya tiene acceso' : 'Cuenta existente desactivada'}
                    </p>
                    <p class="text-xs mt-0.5 {existingUser.is_active ? 'text-blue-600' : 'text-amber-600'}">
                        Usuario registrado con {existingUser.email}
                    </p>
                </div>
            </div>
        {/if}

        <!-- Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <!-- Person info -->
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-indigo-700 font-bold text-lg flex-shrink-0"
                     style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe)">
                    {person.first_name.charAt(0)}{person.last_name.charAt(0)}
                </div>
                <div>
                    <p class="font-semibold text-slate-800">{person.first_name} {person.last_name}</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {person.document_type?.toUpperCase()} {person.document_number}
                    </p>
                </div>
            </div>

            <!-- Form -->
            <div class="px-6 py-6">
                <p class="text-sm text-slate-600 mb-5">
                    Se enviará un correo con un enlace para que el residente cree su contraseña y acceda al <strong>Portal PH</strong>.
                </p>

                <form onsubmit={(e) => { e.preventDefault(); submit(); }}>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">
                            Correo electrónico de acceso
                        </label>
                        <input type="email"
                               bind:value={$form.email}
                               placeholder="correo@ejemplo.com"
                               class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition bg-slate-50
                                      {$form.errors.email ? 'border-red-300' : 'border-slate-200'}"/>
                        {#if $form.errors.email}
                            <p class="text-xs text-red-500 mt-1">{$form.errors.email}</p>
                        {/if}
                        <p class="text-xs text-slate-400 mt-1.5">
                            Puede ser diferente al correo registrado en su perfil de persona.
                        </p>
                    </div>

                    <button type="submit"
                            disabled={$form.processing || !!existingUser}
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        {#if $form.processing}
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            Enviando...
                        {:else}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Enviar invitación
                        {/if}
                    </button>
                </form>
            </div>
        </div>

        <!-- Info box -->
        <div class="mt-5 bg-slate-50 rounded-xl border border-slate-200 px-5 py-4">
            <p class="text-xs font-semibold text-slate-600 mb-2">¿Qué pasará?</p>
            <ul class="space-y-1.5 text-xs text-slate-500">
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                    Se crea una cuenta con rol <strong>residente</strong> vinculada a esta persona.
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                    El residente recibe un email con un enlace para crear su contraseña (válido 60 minutos).
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0 mt-1.5"></span>
                    Al ingresar, verá solo sus propios cobros y podrá pagar con Wompi.
                </li>
            </ul>
        </div>

    </div>
</AppLayout>
