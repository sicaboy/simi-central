import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import i18n, { loadTranslations } from './i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';
const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => pages[`./Pages/${name}.vue`],
    progress: { color: '#ab8949' },
    setup({ el, App, props, plugin }) {
        loadTranslations().then(() => {
            const VueApp = createApp({ render: () => h(App, props) });

            VueApp.use(plugin)
                .use(i18n)
                .mixin({ methods: { route } })
                .mount(el);
        });
    }
});
