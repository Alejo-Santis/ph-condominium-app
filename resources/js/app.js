import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/svelte';
import { mount } from 'svelte';

const appName = import.meta.env.VITE_APP_NAME || 'Portal PH';

createInertiaApp({
    title: (title) => title ? `${title} — ${appName}` : appName,

    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },

    setup({ el, App, props }) {
        mount(App, { target: el, props });
    },
});
