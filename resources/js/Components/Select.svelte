<script>
    let {
        id = '',
        name = '',
        value = $bindable(''),
        options = [],
        placeholder = 'Select an option',
        disabled = false,
        error = '',
        required = false
    } = $props();
</script>

<div class="flex flex-col space-y-1.5">
    {#if $$slots.label}
        <label for={id} class="text-sm font-medium text-gray-700">
            <slot name="label" />
            {#if required}
                <span class="text-red-500 ml-1">*</span>
            {/if}
        </label>
    {/if}

    <select
        {id}
        {name}
        bind:value
        {disabled}
        {required}
        class="w-full px-4 py-2.5 border {error
            ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:border-blue-500 focus:ring-blue-500'} rounded-lg text-gray-900 focus:outline-none focus:ring-2 transition ease-in-out duration-150 disabled:bg-gray-50 disabled:text-gray-500 disabled:cursor-not-allowed"
    >
        <option value="">{placeholder}</option>
        {#each options as option}
            <option value={option.value}>{option.label}</option>
        {/each}
    </select>

    {#if error}
        <p class="text-sm text-red-600 font-medium">{error}</p>
    {/if}
</div>
