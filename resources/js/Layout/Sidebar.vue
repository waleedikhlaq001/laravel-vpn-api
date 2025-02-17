<template>
    <button
        @click="toggleSidebar"
        class="md:hidden text-gray-500 hover:text-gray-900"
    >
        <i class="fas fa-bars"></i>
    </button>
    <aside :class="sidebarClasses" class="bg-gray-50 dark:bg-gray-900">
        <div v-show="isSidebarOpen">
            <div class="w-full md:w-64 h-full p-3 overflow-auto">
                <div class="flex flex-col mb-4">
                    <inertia-link
                        href="/"
                        :class="{
                            '!bg-blue-200 dark:!bg-blue-700 font-semibold': $page.url === '/',
                        }"
                        class="px-2 py-1 hover:bg-gray-200 hover:dark:bg-gray-700 rounded-lg mb-2"
                        >Overview</inertia-link
                    >
                    <inertia-link
                        href="/content"
                        :class="{
                            '!bg-blue-200 dark:!bg-blue-700 font-semibold':
                                $page.url === '/content',
                        }"
                        class="px-2 py-1 hover:bg-gray-200 hover:dark:bg-gray-700 rounded-lg mb-2"
                        >My content</inertia-link
                    >
                    <inertia-link
                        href="/favorites"
                        :class="{
                            '!bg-blue-200 dark:!bg-blue-700 font-semibold':
                                $page.url === '/favorites',
                        }"
                        class="px-2 py-1 hover:bg-gray-200 hover:dark:bg-gray-700 rounded-lg mb-2"
                        >My favorites</inertia-link
                    >
                </div>
                <div class="mb-2">
                    <h2 class="text-sm ms-2 mb-2 text-gray-500">My Groups</h2>
                    <div v-if="publicgroups">
                        <h3
                            class="ms-2 text-sm mb-2 text-gray-500 border-b border-gray-200  dark:border-gray-600"
                        >
                            Public
                        </h3>
                        <div
                            class="flex flex-col"
                            v-for="publicGrp in publicgroups"
                            :key="publicGrp.id"
                        >
                            <inertia-link
                                :href="'/public-content/' + publicGrp.id"
                                :class="{
                                    '!bg-blue-200 dark:!bg-blue-700 font-semibold':
                                        $page.url ===
                                        '/public-content/' + publicGrp.id,
                                }"
                                class="px-2 py-1 hover:bg-gray-200 hover:dark:bg-gray-700 rounded-lg mb-2"
                                >{{ publicGrp.name }}</inertia-link
                            >
                        </div>
                    </div>

                    <h3
                        class="ms-2 text-sm mb-2 text-gray-500 border-b border-gray-200 dark:border-gray-600"
                    >
                        Private
                    </h3>
                    <div
                        class="flex flex-col"
                        v-for="privategrp in privateArray"
                        :key="privategrp.id"
                    >
                        <inertia-link
                            :href="'/private-content/' + privategrp.id"
                            :class="{
                                '!bg-blue-200 dark:!bg-blue-700 font-semibold':
                                    $page.url ===
                                    '/private-content/' + privategrp.id,
                            }"
                            class="px-2 py-1 hover:bg-gray-200 hover:dark:bg-gray-700 rounded-lg mb-2"
                            >{{ privategrp.name }}

                            <i
                                v-if="privategrp.is_shared"
                                class="fas fa-share ml-1 text-gray-400"
                                title="This group pays for your subscription"
                            ></i>
                            <i
                                v-else
                                class="fas fa-house-user ml-1 text-gray-400"
                                title="This group pays for your subscription"
                            ></i
                        ></inertia-link>
                    </div>
                </div>
                <inertia-link href="/join-group" class="btn-link ms-2 text-sm"
                    >Join a group</inertia-link
                >
            </div>
            <div v-show="!isSidebarOpen" class="md:hidden">
                <div class="flex flex-col mb-4">
                    <inertia-link
                        href="/dashboard"
                        class="border bo px-4 py-1 hover:bg-blue-100 hover:border-blue-300 rounded"
                        >Overview</inertia-link
                    >
                    <inertia-link
                        href="/content"
                        class="border bo px-4 py-1 hover:bg-blue-100 hover:border-blue-300 rounded"
                        >My records/memo</inertia-link
                    >
                    <button
                        class="border bo px-4 py-1 hover:bg-blue-100 hover:border-blue-300 rounded"
                    >
                        My favorites {{ publicgroups }}
                    </button>
                </div>
                <ul>
                    <li v-for="group in publicgroups" :key="group.id">
                        {{ group.name }}
                    </li>
                </ul>
                <div class="mb-2">
                    <h2 class="font-bold text-lg mb-2">My Groups</h2>
                    <h3 class="font-semibold text-md mb-2">Public</h3>
                    <div
                        class="flex flex-col mb-4"
                        v-for="publicGrp in publicArray"
                        :key="publicGrp.id"
                    >
                        <inertia-link
                            :href="'/public-content/' + publicGrp.id"
                            class="border bo px-4 py-1 hover:bg-blue-100 rounded mb-2"
                            >{{ publicGrp.name }}</inertia-link
                        >
                    </div>
                    <h3 class="font-semibold text-md mb-2">
                        Private <i class="fas fa-lock"></i>
                    </h3>
                    <div
                        class="flex flex-col"
                        v-for="privategrp in privateArray"
                        :key="privategrp.id"
                    >
                        <inertia-link
                            :href="'/private-content/' + privategrp.id"
                            class="border bo px-4 py-1 hover:bg-blue-100 rounded text-left mb-2"
                            >{{ privategrp.name }}
                            <i class="fa-regular fa-house-user"></i
                        ></inertia-link>
                    </div>
                </div>
                <inertia-link href="/join-group" class="btn-link"
                    >Join a private group</inertia-link
                >
            </div>
        </div>
    </aside>
</template>

<script>
import { reactive, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export default {
    setup() {
        const { user, publicgroups, privategroups } = usePage().props;
        console.log(publicgroups);
        // Create a reactive object to store the state
        const state = reactive({
            isSidebarOpen: true,
        });

        // Define the computed property
        const sidebarClasses = computed(() => {
            return {
                hidden: !state.isSidebarOpen,
                "md:flex": state.isSidebarOpen,
            };
        });
        const privateArray = computed(() => privategroups);
        // Define the method
        const toggleSidebar = () => {
            state.isSidebarOpen = !state.isSidebarOpen;
        };

        // Return the reactive state and the computed property along with the method
        return {
            ...state,
            sidebarClasses,
            toggleSidebar,
            user,
            privateArray,
            publicgroups,
        };
    },
};
</script>
