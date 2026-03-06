require('./bootstrap');

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import i18n, { loadTranslations } from './i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || '';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        // 加载翻译数据
        loadTranslations().then(() => {
            const VueApp = createApp({ render: () => h(app, props) });
            
            // 使用插件和混入
            VueApp.use(plugin)
                .use(i18n)
                .mixin({ methods: { route } })
                .mount(el);
        });
    }
});

InertiaProgress.init({ color: '#439288' });
