<template>
  <Layout>
    <form @submit.prevent="submit()">
      <div v-if="!resetRequested">
        <div class="flex mb-4 mt-4">
          <label class="w-40 mr-4 self-center" for="email">
            {{ $t("common.email") }}
          </label>
          <div class="w-full flex flex-col">
            <input id="email" v-model="email" type="text" required />
          </div>
        </div>

        <div class="text-sm pb-3">
          {{ $t("login.recover.hint") }}
        </div>
        <div class="flex items-center justify-end">
          <button type="submit" class="btn-primary">
            {{ $t("login.recover.recover") }}
          </button>
        </div>
      </div>
      <div v-else>
        <p v-if="successMessage">{{ successMessage }}</p>
        <p v-if="errorMessage">{{ errorMessage }}</p>
      </div>
    </form>
    <Footer />
  </Layout>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import Layout from "../../Layout/Auth/Layout.vue";
import { ref } from "vue";

export default {
  components: {
    Layout,
  },
  setup() {
    const email = ref("");
    const resetRequested = ref(false);
    const successMessage = ref("");
    const errorMessage = ref("");

    const submit = async () => {
      try {
        const response = await fetch("/api/forgot-password", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ email: email.value }),
        });

        if (response.ok) {
          // Reset successful, show success message and hide the form
          successMessage.value = "Password reset link sent successfully";
          errorMessage.value = "";
          resetRequested.value = true;
        } else {
          // Handle the error, show an error message and keep the form visible
          errorMessage.value = "Failed to send reset link";
          successMessage.value = "";
        }
      } catch (error) {
        console.error(error);
        errorMessage.value = "An error occurred. Please try again later.";
        successMessage.value = "";
      }
    };

    return {
      email,
      resetRequested,
      successMessage,
      errorMessage,
      submit,
    };
  },
};
</script>
