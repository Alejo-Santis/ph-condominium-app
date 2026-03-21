<script>
    let { variant = 'default' } = $props();

    const variants = {
        default: 'bg-slate-100 text-slate-700 ring-slate-200',
        success: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        warning: 'bg-amber-50 text-amber-700 ring-amber-200',
        danger:  'bg-red-50 text-red-700 ring-red-200',
        info:    'bg-indigo-50 text-indigo-700 ring-indigo-200',
    };
</script>

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {variants[variant]}">
    <slot />
</span>
