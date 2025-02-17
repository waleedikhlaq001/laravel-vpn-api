import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import '@fortawesome/fontawesome-free/css/all.css';
import { createI18n } from 'vue-i18n';

// Import your localization files here
import en from './locales/en.json';
import fr from './locales/fr.json';
import it from './locales/it.json';
import de from './locales/de.json';

const i18n = createI18n({
    legacy: false, // Vite doesn't support legacy mode
    returnEmptyString: false,
    locale: 'en', // Set the initial locale
    fallbackLocale: 'en', // Fallback locale
    messages: {
        en,
        fr,
        it,
        de
    },
});

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    async setup({ el, App, props, plugin }) {

        return createApp({ render: () => h(App, props) })
            .use(i18n) // Use the i18n plugin
            .use(plugin)
            .component('InertiaHead', Head)
            .component('InertiaLink', Link)
            .mount(el);
    },
    progress: {
        delay: 250,
        color: '#29d',
        includeCSS: true,
        showSpinner: false,
    },
});
