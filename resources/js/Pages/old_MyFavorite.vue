<template>
  <Layout>
    <template v-slot:title>My Favorites</template>

    <div class="flex justify-end border border-gray-300 py-3 pr-2 bg-gray-100 mt-7 mb-9">
      <div class="space-x-4">
        <select>
          <option value="option1">Filter one</option>
          <option value="option2">Option 2</option>
          <option value="option3">Option 3</option>
        </select>
        <select>
          <option value="option1">Filter two</option>
          <option value="option2">Option 2</option>
          <option value="option3">Option 3</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-6 gap-4">
      <PostCard v-for="post in posts" :posts="posts" :key="post.id" :post="post" />
    </div>
  </Layout>
</template>

<script>
import Layout from "../Layout/Layout.vue";
import PostCard from "../Components/PostCard.vue";

export default {
  components: {
    Layout,
    PostCard,
  },
  props: {
    user: Object,
    posts: Array,
  },
  setup(props) {},
};
</script>
