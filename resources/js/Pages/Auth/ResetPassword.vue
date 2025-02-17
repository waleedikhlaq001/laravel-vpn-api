<template>
  <Layout>
      <form @submit.prevent="form.post('/reset-password')">


          <div class="flex mb-4 mt-4">
            <label class="w-48 mr-4 self-center"> {{ $t("common.email") }}
            </label>
            <div class="w-full flex flex-col">
              <input class="input-text" v-model="form.email" type="text" required />
              <div v-if="form.errors.email" class="text-red-400">
                {{ form.errors.email }}
              </div>
            </div>
          </div>
          <div class="flex mb-4 mt-4">
            <label class="w-48 mr-4 self-center"> {{ $t("common.password") }}
            </label>
            <div class="w-full flex flex-col">
              <input class="input-text" type="password" v-model="form.password" required />
              <div v-if="form.errors.password" class="text-red-400">
                {{ form.errors.password }}
              </div>
            </div>
          </div>
          <div class="flex mb-4 mt-4">
            <label class="w-48 mr-4 self-center"> {{ $t("login.reset.passwordConfirm") }}</label>
            <div class="w-full flex flex-col">
              <input class="input-text" type="password" v-model="form.password_confirmation" required />
            </div>
          </div>
          <div class="flex items-center justify-end">
            <button type="submit" class="btn-primary">
              {{ $t("login.reset.reset") }}
            </button>
          </div>
      </form>
</Layout>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Auth/Layout.vue";

export default {
  components: {
    Layout,
  },
  props: {
    token: String,
  },
  setup(props) {
    const form = useForm({
      email: "",
      password: "",
      password_confirmation: "",
      token: props.token,
    });

    return {
      form,
    };
  },
};
</script>
