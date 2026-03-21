<script>
    let { name = '', id = '', checked = $bindable(false), disabled = false, error = '', children } = $props();
</script>

<div class="flex items-center gap-2">
    <input
        type="checkbox"
        {id}
        {name}
        bind:checked
        {disabled}
        class="w-4 h-4 border-gray-300 rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
    />
    {#if children}
        <label for={id} class="text-sm font-medium text-slate-700 cursor-pointer">
            {@render children()}
        </label>
    {/if}
    {#if error}
        <p class="text-sm text-red-600 ml-auto">{error}</p>
    {/if}
</div>
