import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import i18n, { loadTranslations } from './i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';
const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => pages[`./Pages/${name}.vue`],
    setup({ el, app, props, plugin }) {
        loadTranslations().then(() => {
            const VueApp = createApp({ render: () => h(app, props) });

            VueApp.use(plugin)
                .use(i18n)
                .mixin({ methods: { route } })
                .mount(el);
        });
    }
});

InertiaProgress.init({ color: '#ab8949' });
