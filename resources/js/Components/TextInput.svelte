<script>
    let {
        id = '',
        type = 'text',
        name = '',
        value = $bindable(''),
        placeholder = '',
        error = '',
        required = false,
        disabled = false,
        clearable = false,
        ...rest
    } = $props();

    function handleClear() {
        value = '';
    }
</script>

<div class="flex flex-col space-y-1.5">
    {#if $$slots.label}
        <label for={id} class="text-sm font-medium text-slate-700">
            <slot name="label" />
            {#if required}
                <span class="text-red-500 ml-0.5">*</span>
            {/if}
        </label>
    {/if}

    <div class="relative">
        <input
            type={type}
            {id}
            {name}
            bind:value
            {placeholder}
            {required}
            {disabled}
            class="w-full px-3.5 py-2.5 border text-sm rounded-lg text-slate-900 placeholder-slate-400
                focus:outline-none focus:ring-2 transition duration-150
                {error
                    ? 'border-red-300 focus:border-red-400 focus:ring-red-200'
                    : 'border-slate-300 focus:border-indigo-400 focus:ring-indigo-100'}
                disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed"
            {...rest}
        />
        {#if clearable && value}
            <button
                type="button"
                onclick={handleClear}
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition"
            >
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        {/if}
    </div>

    {#if error}
        <p class="text-xs text-red-600 font-medium">{error}</p>
    {/if}
</div>
