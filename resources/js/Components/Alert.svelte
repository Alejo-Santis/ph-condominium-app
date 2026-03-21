<script>
    let { type = 'info', title = '', message = '', dismissible = true } = $props();
    let visible = $state(true);

    const styles = {
        info: {
            wrap: 'bg-indigo-50 border-indigo-200 text-indigo-800',
            icon: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>`,
        },
        success: {
            wrap: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            icon: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>`,
        },
        warning: {
            wrap: 'bg-amber-50 border-amber-200 text-amber-800',
            icon: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>`,
        },
        danger: {
            wrap: 'bg-red-50 border-red-200 text-red-800',
            icon: `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>`,
        },
    };

    const style = styles[type];
</script>

{#if visible}
    <div class="rounded-lg border {style.wrap} px-4 py-3">
        <div class="flex gap-3">
            <div class="flex-shrink-0 mt-0.5">
                {@html style.icon}
            </div>
            <div class="flex-1 text-sm">
                {#if title}
                    <p class="font-semibold">{title}</p>
                {/if}
                {#if message}
                    <p class="{title ? 'mt-0.5 opacity-90' : ''}">{message}</p>
                {/if}
                {#if $$slots.default}
                    <div class="{title || message ? 'mt-1' : ''}">
                        <slot />
                    </div>
                {/if}
            </div>
            {#if dismissible}
                <button
                    onclick={() => (visible = false)}
                    class="flex-shrink-0 opacity-60 hover:opacity-100 transition mt-0.5"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            {/if}
        </div>
    </div>
{/if}
