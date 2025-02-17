<template>
  <Layout>
    <template v-slot:title>Share a record</template>
    <div class="grid grid-rows-3 grid-flow-col">
      <div class="row-start-1 w-[25rem]">
        <div class="mb-4 mt-4 mb-10">
          <span class="font-bold">Title</span>:
          {{ post.title }}
        </div>
        <div class="mb-4 mt-4 mb-10">
          <span class="font-bold">Subject</span>:
          {{ post.subject }}
        </div>
        <div class="mb-4 mt-4 mb-10">
          <span class="font-bold">Content</span>:
          <span v-html="post.content"></span>
        </div>
      </div>
      <div class="row-end-3 row-span-5 ...">
        <div class="w-full max-w-24rem">
          <form @submit.prevent="form.post('/share-content')">
            <div class="mb-4 mt-4 mb-10">
              <input type="email" v-model="form.email" placeholder="Recipient's Email" required />
            </div>
            <div class="mb-4 mt-4 mb-10">
              <textarea rows="6" cols="6" v-model="form.message" placeholder="Your personal message" required></textarea>
            </div>
            <button class="btn-primary w-full">
              Submit
            </button>
          </form>
        </div>
      </div>
    </div>
  </Layout>
</template>
<script>
import { useForm } from "@inertiajs/vue3";
import Layout from "../Layout/Layout.vue";

export default {
  components: {
    Layout,
  },
  props: {
    user: Object,
    post: Object,
  },
  setup(props) {
    const form = useForm({
      email: "",
      message: "",
      id: props.post.id,
    });

    return { form };
  },
};
</script>
