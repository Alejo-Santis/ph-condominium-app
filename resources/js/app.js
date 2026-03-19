import './bootstrap';
import { createInertiaApp } from '@inertiajs/svelte';
import { mount } from 'svelte';

createInertiaApp({
    // Resuelve cada página buscando en resources/js/Pages/**/*.svelte
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true });
        return pages[`./Pages/${name}.svelte`];
    },

    // Svelte 5 usa mount() en lugar de new Component()
    setup({ el, App, props }) {
        mount(App, { target: el, props });
    },

    // Nombre de la app para el <title> del navegador
    title: (title) => `${title} — ${import.meta.env.VITE_APP_NAME ?? 'Condominio'}`,
});
