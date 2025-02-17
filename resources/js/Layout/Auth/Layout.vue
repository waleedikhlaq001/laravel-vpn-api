<template>

  <div class="flex flex-col bg-gray-50 dark:bg-gray-900 items-center py-10 min-h-screen w-full">

    <div class="sm:w-3/4 lg:w-1/3 w-full">
      <main class="overflow-y-auto px-6 py-4 rounded-lg border bg-white dark:bg-gray-700 dark:border-gray-600">
        <Header />
        <div class="mt-5">
          <div class="">
            <slot></slot>
          </div>
        </div>
      </main>

    <div class="mt-6">
      <Footer />
    </div>
    </div>

  </div>

</template>

<script>
import Header from "./Header.vue";
import Footer from "../Footer.vue";

export default {
  components: {
    Header,
    Footer,
  },
};
</script>