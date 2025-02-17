<template>
    <Layout>
        <form @submit.prevent="form.post('/loginsubmit')">
<h2>Laravel</h2>
            <div v-if="form.errors.email" class="text-red-400 mt-3">
                {{ form.errors.email }}
            </div>
            <div v-if="form.errors.message" class="text-red-400 mt-4">
                {{ form.errors.message }}
            </div>

            <div>
                <div class="flex mb-4 mt-4">
                    <label class="w-40 mr-4 self-center" for="username">
                        {{ $t("common.email") }}
                    </label>
                    <div class="w-full flex flex-col">
                        <input class=" " id="username" v-model="form.email" type="email" name="email" required />
                    </div>
                </div>
                <div class="flex mb-6">
                    <label class="w-40 mr-4 self-center"  for="password">
                        {{ $t("common.password") }}
                    </label>
                    <div class="w-full flex flex-col">
                        <input id="password" v-model="form.password" name="password" type="password" required />
                        <div v-if="form.errors.password">
                            {{ form.errors.password[0] }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="btn-primary">Login</button>
                </div>
                <div class="absolute bottom-0 left-0 w-full border-b border-gray-300"></div>
            </div>
            <inertia-link href="/register" class="btn-link">{{ $t("login.registerNewAccount") }}</inertia-link>
            <div class="mt-3">
                <a href="/recover" class="btn-link">{{ $t("login.recoverPassword") }}</a>
            </div>
        </form>
    </Layout>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Auth/Layout.vue";
import { ref, onMounted } from "vue";

export default {
    components: {
        Layout,
    },
    setup() {
        const form = useForm({
            email: "",
            password: "",
        });

        const submitForm = () => {
            form.post("/loginsubmit")
                .then(() => {
                    // Redirect to the desired page after successful login
                    window.location.href = "/profile";
                })
                .catch((errors) => {
                    // Handle the error response, if necessary
                    console.error(errors);
                });
        };

        return {
            form,
            submitForm,
        };
    },
};
</script>
