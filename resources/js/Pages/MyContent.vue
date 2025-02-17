<template>
    <Layout>
        <template v-slot:title>{{ currentpage }}</template>

        <div
            class="flex border rounded border-gray-300 py-2 px-4 bg-gray-100 dark:bg-gray-800 dark:border-gray-600 mt-7 mb-9 text-sm"
        >
            <div class="space-x-4 mr-10">
                <span
                    ><i class="fa-solid fa-display mr-2 text-gray-500"></i
                    >Display</span
                >
                <select class="text-sm">
                    <option value="option2">Author's layout</option>
                    <option value="option3">Plain text</option>
                </select>
            </div>
            <div class="space-x-4">
                <span
                    ><i class="fa-solid fa-filter mr-2 text-gray-500"></i
                    >Filters</span
                >
                <select class="text-sm" v-model="groupSearch">
                    <option value="">All groups</option>
                    <option v-for="group in user.groups" :value="group.id">
                        {{ group.name }}
                    </option>
                </select>
                <select class="text-sm" v-model="recordType">
                    <option value="">All types</option>
                    <option value="Record">Records only</option>
                    <option value="Memo">Memo only</option>
                </select>
                <input
                    type="checkbox"
                    class="ms-10"
                    id="favorite"
                    v-model="isFavorited"
                />
                <label for="favorite">Favorite only</label>
            </div>
        </div>

        <div class="grid grid-cols-6 gap-4">
            <PostCard
                v-for="post in filteredPosts"
                :key="post.id"
                :posts="posts"
                :post="post"
                :currentpage="currentpage"
            />
        </div>
    </Layout>
</template>

<script>
import { ref, computed } from "vue";
import axios from "axios";
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
        group: Object,
        currentpage: String,
    },
    setup(props) {
        const groupSearch = ref("");
        const recordType = ref("");
        const isFavorited = ref(false);
        const filteredPosts = computed(() => {
            return props.posts.filter((post) => {
                const groupFilter =
                    groupSearch.value === "" ||
                    post.groups.some(
                        (group) =>
                            group.pivot.group_id === Number(groupSearch.value)
                    );

                const typeFilter =
                    recordType.value === "" || post.type === recordType.value;

                const favoriteFilter = isFavorited.value
                    ? post.isFavorited
                    : true;

                return groupFilter && typeFilter && favoriteFilter;
            });
        });
        return {
            groupSearch,
            recordType,
            filteredPosts,
            isFavorited,
        };
    },
};
</script>
