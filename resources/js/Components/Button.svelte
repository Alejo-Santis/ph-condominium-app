<script>
    let {
        href = '#',
        method = 'get',
        as = 'button',
        type = 'submit',
        variant = 'primary',
        disabled = false,
        class: extraClass = '',
        children,
        ...rest
    } = $props();

    let buttonElement = $state(null);

    const variants = {
        primary:
            'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-medium text-white text-sm hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm',
        secondary:
            'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-slate-300 rounded-lg font-medium text-slate-700 text-sm hover:bg-slate-50 active:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm',
        danger:
            'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-red-600 border border-transparent rounded-lg font-medium text-white text-sm hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm',
        ghost:
            'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-transparent border border-slate-200 rounded-lg font-medium text-slate-600 text-sm hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed',
    };
</script>

{#if as === 'button'}
    <button
        {type}
        {disabled}
        class="{variants[variant]} {extraClass}"
        bind:this={buttonElement}
        {...rest}
    >
        {@render children?.()}
    </button>
{:else if as === 'a'}
    <a
        {href}
        class="{variants[variant]} {extraClass}"
        {...rest}
    >
        {@render children?.()}
    </a>
{/if}
