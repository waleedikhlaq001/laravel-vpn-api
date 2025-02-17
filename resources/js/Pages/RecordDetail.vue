<template>
  <Layout>
    <template v-slot:title>{{ post.translations.title}}</template>
    <p class="font-normal mt-4 mb-2">
      {{ post.subject }}
    </p>
    <div class="flex space-x-10 mt-6 mb-6">
      <span class="text-xs"><i class="fas fa-user mr-2"></i>{{ post.user.fname }}
        {{ post.user.lname }}</span>
      <span class="text-xs"><i class="fas fa-tag mr-2"></i>
        <template v-if="post.visibility === 'private'">
          Only Me
        </template>
        <template v-else v-for="group in post.groups">
          {{ group.name }},
        </template>
      </span>
    </div>
    <p class="text-xs mb-2 md:h-32" v-html="post.translations.content"></p>
  </Layout>
</template>
<script>
import Layout from "../Layout/Layout.vue";

export default {
  components: {
    Layout,
  },
  props: {
    user: Object,
    post: Object,
  },
  setup(props) {},
};
</script>
