import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import i18n, { loadTranslations } from '@shared-saas/i18n.js';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';
const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
const sharedSaasPages = import.meta.glob('@shared-saas/Pages/**/*.vue', { eager: true });

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const sharedPagePath = Object.keys(sharedSaasPages)
            .find((path) => path.endsWith(`/Pages/${name}.vue`) || path.endsWith(`${name}.vue`));

        return pages[`./Pages/${name}.vue`]
            || sharedSaasPages[sharedPagePath];
    },
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
