<template>
    <footer class="flex w-full h-16 p-4 py-3 justify-between items-center">
        <p class="text-sm text-gray-500 mr-10">
            {{ $t("site.description") }}
        </p>
        <div class="space-x-4 flex">
            <select v-model="selectedTheme" @change="changeTheme">
                <option value="light">{{ $t("theme.light") }}</option>
                <option value="dark">{{ $t("theme.dark") }}</option>
            </select>
            <select v-model="selectedLocale" @change="changeLocale">
                <option
                    v-for="(locale, index) in supportedLocales"
                    :key="index"
                    :value="locale"
                >
                    {{ localeNames[locale] }}
                </option>
            </select>
        </div>
    </footer>
</template>

<script>
import { ref, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import Cookies from "js-cookie";

export default {
    setup() {
        const { t, locale } = useI18n();

        const supportedLocales = ["en", "it", "de", "fr"];
        const localeNames = {
            en: "English",
            it: "Italian",
            de: "German",
            fr: "French",
        };

        const selectedTheme = ref(Cookies.get("userTheme") || "light");
        const detectedLocale = (
            navigator.language || navigator.userLanguage
        ).substring(0, 2);
        const storedLocale = Cookies.get("userLocale");
        const defaultLocale =
            storedLocale ||
            (supportedLocales.includes(detectedLocale) ? detectedLocale : "en");
        const selectedLocale = ref(defaultLocale);
        locale.value = selectedLocale.value;

        function changeTheme() {
            if (selectedTheme.value === "dark") {
                document.body.classList.add("dark");
            } else {
                document.body.classList.remove("dark");
            }
            Cookies.set("userTheme", selectedTheme.value, { expires: 365 }); // Store theme preference in cookies
        }

        function changeLocale() {
            locale.value = selectedLocale.value;
            Cookies.set("userLocale", selectedLocale.value, { expires: 365 }); // Store locale preference in cookies
        }

        onMounted(() => {
            // Set the initial theme from the cookie or default
            changeTheme();
        });

        return {
            t,
            selectedTheme,
            selectedLocale,
            supportedLocales,
            localeNames,
            changeTheme,
            changeLocale,
        };
    },
};
</script>

<style>
/* Define your light and dark mode styles here */
.dark {
    background-color: #222;
    color: white;
}
</style>
