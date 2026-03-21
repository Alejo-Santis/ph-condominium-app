<script>
    let { message = '' } = $props();
</script>

{#if message}
    <p class="text-sm text-red-600 font-medium mt-1">{message}</p>
{/if}
