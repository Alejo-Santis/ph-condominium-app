<script>
    let { isOpen = false, title = '', onClose = () => {} } = $props();

    function handleBackdropClick() {
        onClose();
    }

    function handleKeydown(e) {
        if (e.key === 'Escape') onClose();
    }

    function stopPropagation(e) {
        e.stopPropagation();
    }
</script>

<svelte:window onkeydown={handleKeydown} />

{#if isOpen}
    <div
        onclick={handleBackdropClick}
        onkeydown={handleKeydown}
        role="button"
        tabindex="0"
        class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50"
    >
        <div
            onclick={stopPropagation}
            onkeydown={stopPropagation}
            role="dialog"
            tabindex="-1"
            class="bg-white rounded-xl shadow-xl max-w-md w-full"
        >
            {#if title}
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-slate-900">{title}</h2>
                    <button
                        onclick={() => onClose()}
                        class="text-slate-400 hover:text-slate-600 transition p-1 rounded-md hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            {/if}
            <div class="p-6">
                <slot />
            </div>
        </div>
    </div>
{/if}
