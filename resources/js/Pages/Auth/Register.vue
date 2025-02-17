<template>
    <Layout>
        <form @submit.prevent="form.post('/registersubmit')">
            <h2>{{ $t("common.login") }}</h2>
            <div
                v-if="Object.keys(form.errors).length > 0"
                class="text-red-500"
            >
                {{ $t("form.errors") }}
            </div>

            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.email") }}
                </label>
                <div class="w-full flex flex-col">
                    <input v-model="form.email" type="email" required />
                    <div v-if="form.errors.email" class="text-red-400">
                        {{ form.errors.email }}
                    </div>
                </div>
            </div>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.password") }}
                </label>
                <div class="w-full flex flex-col">
                    <input type="password" v-model="form.password" required />
                    <div v-if="form.errors.password" class="text-red-400">
                        {{ form.errors.password }}
                    </div>
                </div>
            </div>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.passwordConfirm") }}
                </label>
                <div class="w-full flex flex-col">
                    <input
                        type="password"
                        v-model="form.password_confirmation"
                        required
                    />
                </div>
            </div>
            <h2 class="mt-12">{{ $t("register.profile") }}</h2>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.firstname") }}
                </label>
                <div class="w-full flex flex-col">
                    <input type="text" v-model="form.fname" required />
                    <div v-if="form.errors.fname" class="text-red-400">
                        {{ form.errors.fname }}
                    </div>
                </div>
            </div>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.lastname") }}
                </label>
                <div class="w-full flex flex-col">
                    <input type="text" v-model="form.lname" required />
                    <div v-if="form.errors.lname" class="text-red-400">
                        {{ form.errors.lname }}
                    </div>
                </div>
            </div>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.phone") }}
                </label>
                <div class="w-full flex flex-col">
                    <input type="tel" v-model="form.tel" required />
                    <div v-if="form.errors.tel" class="text-red-400">
                        {{ form.errors.tel }}
                    </div>
                </div>
            </div>
            <div class="flex mb-4 mt-4">
                <label class="w-48 mr-10 self-center">
                    {{ $t("common.jobTitle")
                    }}<span class="text-gray-500 text-sm">{{
                        $t("form.optionalField")
                    }}</span>
                </label>
                <div class="w-full flex flex-col">
                    <input v-model="form.jobTitle" type="text" />
                </div>
            </div>
            <h2 class="mt-12">{{ $t("common.billingMode") }}</h2>
            <p
                class="mb-3 mt-2 bg-green-50 text-green-900 border-green-200 dark:border-green-800 border px-2.5 py-1.5 rounded dark:bg-green-900 dark:text-green-300"
            >
                Price: 59 CHF / Year
            </p>
            <div class="flex mb-4 items-center justify-between gap-4">
                <label
                    class="pl-2 cursor-pointer border bg-gray-50 dark:bg-gray-800 dark:border-gray-600 rounded px-4 py-3 flex flex-1 items-center text-center"
                >
                    <input
                        type="radio"
                        name="subscription"
                        id="sub-individual"
                        class="mr-3"
                        v-model="form.subscription"
                        value="paidbyme"
                        required
                    />
                    {{ $t("common.payMyself") }}
                </label>
                <label
                    class="pl-2 cursor-pointer border bg-gray-50 dark:bg-gray-800 dark:border-gray-600 rounded px-4 py-3 flex flex-1 items-center"
                    for="sub-group"
                >
                    <input
                        type="radio"
                        name="subscription"
                        id="sub-group"
                        class="mr-3"
                        v-model="form.subscription"
                        value="paidbygroup"
                        required
                    />
                    {{ $t("common.payByGroup") }}
                </label>
            </div>
            <div class="mb-6" v-if="form.subscription === 'paidbyme'">
                <label>{{ $t("common.motivation") }}</label>
                <div class="text-sm text-gray-600 float-right">
                    30 days free trial
                </div>
                <textarea
                    rows="3"
                    cols="40"
                    class="mt-3"
                    v-model="form.motivation"
                ></textarea>
                <span class="text-xs"
                    >{{ $t("register.motivationHint") }}
                </span>
            </div>
            <div class="mb-6" v-if="form.subscription === 'paidbygroup'">
                <div class="mb-3">{{ $t("common.selectGroup") }}</div>

                <table class="w-full">
                    <thead>
                        <tr>
                            <th class=""></th>
                            <th
                                class="py-2 px-4 border-b text-left border-l border-t border-r text-sm"
                            >
                                {{ $t("common.group") }}
                            </th>
                            <th
                                class="py-2 px-4 border-b text-left border-l border-t border-r text-sm"
                            >
                                {{ $t("common.description") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="group in groups" :key="group.id">
                            <td>
                                <input
                                    type="radio"
                                    name="group"
                                    v-model="form.group"
                                    :value="group.id"
                                    :id="'group-' + group.id"
                                />
                            </td>
                            <td class="py-2 px-4 border-b border-l">
                                <label
                                    class="font-bold text-sm"
                                    :for="'group-' + group.id"
                                    >{{ group.name }}</label
                                >
                            </td>
                            <td
                                class="py-2 px-4 border-b border-l border-r text-sm"
                            >
                                {{ group.description }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h2 class="mt-12">{{ $t("register.nextSteps") }}</h2>
            <div class="flex mb-6">
                <ul class="list-decimal ml-4">
                    <li>{{ $t("register.step1") }}</li>
                    <li>
                        {{ $t("register.step2") }}
                    </li>
                    <li>
                        {{ $t("register.step3") }}
                    </li>
                </ul>
            </div>

            <div class="flex mb-6 items-center">
                <input
                    v-model="form.accept_terms"
                    type="checkbox"
                    value="accepted"
                    id="cga"
                    required
                />
                <label class="w-[31rem] pl-2 self-center" for="cga">
                    {{ $t("register.acceptConditions") }}
                </label>
                <div v-if="form.errors.accept_terms" class="text-red-400">
                    {{ form.errors.accept_terms }}
                </div>
            </div>
            <div class="flex items-center justify-end mb-3">
                <button
                    :disabled="form.processing"
                    type="submit"
                    class="btn-primary disabled:opacity-75"
                >
                    {{ $t("register.submit") }}
                </button>
            </div>
        </form>
    </Layout>
</template>

<script>
// import { useForm } from "@inertiajs/vue3";
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, inject } from "vue";
import Layout from "../../Layout/Auth/Layout.vue";

export default {
    components: {
        Layout,
    },
    props: {
        groups: Array,
    },
    setup() {
        const form = useForm({
            email: "",
            password: "",
            password_confirmation: "",
            fname: "",
            lname: "",
            tel: "",
            jobTitle: "",
            subscription: "",
            motivation: "",
            group: "",
            accept_terms: "",
        });

        return { form };
    },

    mounted() {
        // Add an event listener to the 'beforeunload' event when the component is mounted
        window.addEventListener("beforeunload", this.confirmLeave);
    },
    beforeDestroy() {
        // Remove the event listener when the component is about to be destroyed
        window.removeEventListener("beforeunload", this.confirmLeave);
    },
    methods: {
        confirmLeave(event) {
            // This function will be called when the user tries to leave the page
            // You can customize the message based on your requirements
            event.returnValue =
                "You have unsaved changes. Are you sure you want to leave?";
        },
    },
};
</script>
