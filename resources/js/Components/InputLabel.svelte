<script>
    let { htmlFor = '', text = '', required = false } = $props();
</script>

<label for={htmlFor} class="block text-sm font-medium text-gray-700 mb-1">
    {text}
    {#if required}
        <span class="text-red-500">*</span>
    {/if}
</label>
