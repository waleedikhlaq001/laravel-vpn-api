<template>
  <Layout>

    <template v-slot:title>Overview</template>

    <div class="grid grid-cols-12 gap-4">
      <div class="col-span-12 md:col-span-6">
        <h2>Latest Records</h2>
        <div class="grid grid-cols-1 gap-4">
          <PostCard v-for="post in posts" :posts="posts" :key="post.id" :post="post" />
        </div>
      </div>
      <div class="col-span-12 md:col-span-3">
        <h2>Popular Records</h2>
        <span class="text-xs">Records with the most views in x days</span>
        <!-- Add your popular record items here -->
      </div>
      <div class="col-span-12 md:col-span-3">
        <div class="flex justify-center space-x-4 text-sm">
          <div class="relative">
            <div class="cursor-pointer mr-3">
              <a href="" class="btn-link">7 days</a>
            </div>
            <div class="absolute top-0 right-0 h-full border-l border-gray-500"></div>
          </div>
          <div class="relative">
            <div class="cursor-pointer mr-3">
              <a href="" class="btn-link">30 days</a>
            </div>
            <div class="absolute top-0 right-0 h-full border-l border-gray-500"></div>
          </div>
          <div>
            <a href="" class="btn-link">90 days</a>
          </div>
        </div>
      </div>
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