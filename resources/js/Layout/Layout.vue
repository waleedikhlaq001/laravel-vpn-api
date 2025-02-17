<template>

  <div class="flex flex-col bg-gray-50 dark:bg-gray-900 min-h-screen">
    <Header />

    <div class="flex-1 flex flex-col sm:flex-row">
      <Sidebar />

      <div class="p-3 w-full overflow-y-auto">
        <main class="overflow-y-auto px-6 py-4 rounded-lg border bg-white  dark:bg-gray-800 dark:border-gray-700 h-full">
          <h1>
            <slot name="title">⚠️ Title not set</slot>
          </h1>
          <slot></slot>
        </main>
      </div>
    </div>

    <Footer />
  </div>

</template>

<script>
import Header from "./Header.vue";
import Sidebar from "./Sidebar.vue";
import Footer from "./Footer.vue";

export default {
  components: {
    Header,
    Sidebar,
    Footer,
  },
};
</script>