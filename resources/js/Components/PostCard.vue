<template>
  <div class="col-span-12 md:col-span-3 shadow border border-gray-200 dark:border-gray-400 rounded-lg flex flex-col justify-between p-6" :class="post.isFavorited ? 'bg-amber-50 dark:bg-amber-900 transition-all':''">
    <div class="mb-6">
      <span class="bg-pink-100 text-pink-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-pink-400 border border-pink-400 float-right" v-if="
                        post.status == 'unpublished' &&
                        currentpage === 'MyContent'
                    ">Draft</span>
      <h2 class="font-bold text-2lg" v-html="limitedTitle(post.translations.title)"></h2>

      <p class="font-normal mb-3 text-gray-700 dark:text-gray-400">
        {{ post.subject }}
      </p>
      <p class="md:h-30 text-ellipsis overflow-hidden ..." v-html="limitedText(post.translations.content)"></p>
    </div>
    <div class="flex justify-between items-center">
      <div class="flex space-x-5">
        <span class="text-xs"><i class="fas fa-user mr-2"></i>{{ post.user.fname }}
          {{ post.user.lname }}</span>
        <span class="text-xs"><i class="fas fa-tag mr-2"></i>
          <template v-if="post.visibility === 'private'">
            Private
          </template>
          <template v-else> {{ post.groups.map(group => group.name).join(', ') }} </template>
        </span>
      </div>
      <div class="space-x-5">
        <button class="btn-link text-sm" @click="toggleFavorite(post.id, post.isFavorited)"><i :class="post.isFavorited ? 'fa-solid fa-star' : 'fa-regular fa-star'"></i>
          {{ post.isFavorited ? "Unfavorite" : "Favorite" }}
        </button>
        <inertia-link v-if="
                        currentpage === 'MyContent' ||
                        (post.groups[0].type == 'public' &&
                            post.groups[0].permissions == 'readwrite')
                    " :href="'/update-record/' + post.id" class="btn-link text-sm"><i class="fa-regular fa-pen-to-square"></i> Edit</inertia-link>
        <button v-if="currentpage === 'MyContent'" @click="removePost(post)" class="btn-link text-sm">
          <i class="fa-regular fa-trash-can"></i> Delete
        </button>
        <!-- <a href="" class="btn-link text-sm">Print</a> -->
        <inertia-link :href="'/share/' + post.id" class="btn-link text-sm"><i class="fa-regular fa-share-from-square"></i> Share</inertia-link>
        <inertia-link :href="'/record-detail/' + post.id" class="btn-link text-sm font-bold"><i class="fa-regular fa-eye"></i> Read</inertia-link>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, defineProps } from "vue";
import axios from "axios";

export default {
  props: {
    post: Object,
    posts: Array,
    currentpage: "",
  },
  setup(props) {
    const currentPage = defineProps(["currentPage"]); // Get the current page prop

    const maxLength = 400;
    const maxTitle = 100;
    const allPosts = ref(props.posts);

    const limitedTitle = (originalText) => {
      if (originalText.length <= maxTitle) {
        return originalText;
      } else {
        const truncatedText = originalText.slice(0, maxTitle);
        return truncatedText.replace(/\s\w*$/, "...");
      }
    };

    const limitedText = (originalText) => {
      if (originalText.length <= maxLength) {
        return originalText;
      } else {
        const truncatedText = originalText.slice(0, maxLength);
        return truncatedText.replace(/\s\w*$/, "...");
      }
    };

    const toggleFavorite = async (postId, isFavorited) => {
      if (isFavorited) {
        // Unfavorite the post
        try {
          await axios.delete(`/posts/${postId}/unfavorite`);
          // Update the isFavorited property locally
          const postIndex = allPosts.value.findIndex(
            (post) => post.id === postId
          );
          if (postIndex !== -1) {
            allPosts.value[postIndex].isFavorited = false;
          }
        } catch (error) {
          console.error("Error unfavorite post:", error);
        }
      } else {
        // Favorite the post
        try {
          await axios.post(`/posts/${postId}/favorite`);
          // Update the isFavorited property locally
          const postIndex = allPosts.value.findIndex(
            (post) => post.id === postId
          );
          if (postIndex !== -1) {
            allPosts.value[postIndex].isFavorited = true;
          }
        } catch (error) {
          console.error("Error favorite post:", error);
        }
      }
    };

    const removePost = (postRemove) => {
      const isConfirmed = window.confirm(
        "Are you sure you want to delete this post?"
      );
      if (isConfirmed) {
        console.log(allPosts.value);
        if (allPosts.value) {
          const posts = allPosts.value;
          if (Array.isArray(posts)) {
            const indexToRemove = posts.findIndex(
              (post) => post.id === postRemove.id
            );

            if (indexToRemove !== -1) {
              // Use splice to remove the user from the array
              posts.splice(indexToRemove, 1);
            }
            axios
              .get(`/remove-record/${postRemove.id}`)
              .then((response) => {})
              .catch((error) => {
                console.error("Error deleting user:", error);
              });
          } else {
            console.error(
              "selectedGroupData.value.pending_users is not an array."
            );
          }
        } else {
          console.error("selectedGroupData.value is undefined.");
        }
      }
    };

    return {
      limitedTitle,
      limitedText,
      toggleFavorite,
      allPosts,
      currentPage,
      removePost,
    };
  },
};
</script>
