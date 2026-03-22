<script>
    import AppLayout from '../../Layouts/AppLayout.svelte';
    import Button from '../../Components/Button.svelte';

    let { persons = [] } = $props();

    const items = persons.data ?? [];

    const avatarColors = [
        'linear-gradient(135deg,#e0e7ff,#c7d2fe)', // indigo
        'linear-gradient(135deg,#d1fae5,#a7f3d0)', // emerald
        'linear-gradient(135deg,#fef3c7,#fde68a)', // amber
        'linear-gradient(135deg,#fce7f3,#fbcfe8)', // pink
        'linear-gradient(135deg,#dbeafe,#bfdbfe)', // blue
    ];

    function avatarColor(name) {
        let sum = 0;
        for (const c of (name ?? '')) sum += c.charCodeAt(0);
        return avatarColors[sum % avatarColors.length];
    }

    function avatarText(name) {
        return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
    }
</script>

<svelte:head>
    <title>Personas — Portal PH</title>
</svelte:head>

<AppLayout>
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Personas</h1>
            <p class="text-sm text-slate-500 mt-1">Propietarios, arrendatarios y residentes</p>
        </div>
        <Button href="/persons/create" as="a" variant="primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva persona
        </Button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        {#if items.length > 0}
            <div class="px-5 py-3.5 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{persons.total ?? items.length} personas</span>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:1px solid #f1f5f9">
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Persona</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Documento</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Contacto</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Unidades</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold text-slate-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    {#each items as person}
                        {@const fullName = `${person.first_name} ${person.last_name}`}
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 text-slate-700 font-bold text-xs"
                                         style="background:{avatarColor(fullName)}">
                                        {avatarText(fullName)}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-none">{fullName}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{person.email ?? '—'}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono text-xs text-slate-600 bg-slate-50 border border-slate-200 px-2 py-0.5 rounded">
                                    {person.document_number}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500">{person.phone ?? '—'}</td>
                            <td class="px-5 py-3.5">
                                {#if person.units && person.units.length > 0}
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        {person.units.length}
                                    </span>
                                {:else}
                                    <span class="text-slate-300 text-xs">Sin unidades</span>
                                {/if}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href={`/persons/${person.uuid}`} title="Ver detalle"
                                       class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href={`/persons/${person.uuid}/edit`} title="Editar"
                                       class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    {/each}
                </tbody>
            </table>
            {#if persons.total}
                <div class="px-5 py-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">Mostrando {persons.from}–{persons.to} de {persons.total}</span>
                </div>
            {/if}
        {:else}
            <div class="py-20 text-center">
                <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-700">No hay personas registradas</p>
                <p class="text-xs text-slate-400 mt-1 mb-5">Registra el primer propietario o residente</p>
                <Button href="/persons/create" as="a" variant="primary">Nueva persona</Button>
            </div>
        {/if}
    </div>
</AppLayout>
