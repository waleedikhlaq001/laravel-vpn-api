<template>
    <header
        class="z-10 w-full flex flex-wrap h-16 bg-white border-b border-gray-200  dark:bg-gray-900 dark:border-gray-700 px-4 md:justify-between items-center"
    >
        <div
            class="flex w-full md:w-auto justify-between md:justify-start items-center"
        >
            <span class="text-4xl font-bold text-[#6186ca]">
                <inertia-link href="/"
                    ><img src="/images/logo.png" alt="eKarto"
                /></inertia-link>
            </span>
            <!-- Mobile Menu Button -->
            <button
                @click="toggleMobileMenu"
                class="block md:hidden text-gray-500 hover:text-gray-900"
            >
                <i class="fas fa-bars"></i>
            </button>
            <!-- Search bar (Desktop) -->
            <div class="hidden md:block w-full md:w-64 ml-10 relative">
                <input placeholder="Search" type="search" class="!ps-10" />
                <div
                    class="absolute inset-y-0 left-3 pr-3 flex items-center pointer-events-none"
                >
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>
        <!-- Desktop Menu Links -->
        <!-- {{ groupleader }} -->
        <div class="hidden md:flex md:space-x-4 md:ml-6">
            <!-- Header links -->
            <inertia-link
                href="/new-record"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-circle-plus"></i>
                <span class="text-sm pt-1">Create a record</span>
            </inertia-link>
            <inertia-link
                href="/add-group"
                v-if="user.role == 'admin'"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-circle-plus"></i>
                <span class="text-sm pt-1">Create a group</span>
            </inertia-link>
            <inertia-link
                v-if="user.role == 'admin'"
                href="/users-list"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center relative"
            >
                <i class="fas fa-user-group"></i>

                <span class="text-sm pt-1">Manage users</span>
            </inertia-link>
            <inertia-link
                v-if="groupleader.length > 0 || user.role == 'admin'"
                href="/manage-group"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center relative"
            >
                <i class="fas fa-layer-group"></i>

                <span class="text-sm pt-1">Manage groups</span>
            </inertia-link>
            <inertia-link
                href="/profile"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <img
                    v-if="user.avatar"
                    :src="user.avatar"
                    alt="Profile Image"
                    class="max-w-[1rem] h-[1rem] rounded-full"
                />
                <i class="fas fa-circle-user" v-else></i>
                <span class="text-sm pt-1">Profile</span>
            </inertia-link>
            <inertia-link
                href="/stop-impersonating"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
                v-if="isImpersonating"
                @click="stopImpersonation"
            >
                <i class="fas fa-power-off"></i>
                <span class="text-sm pt-1">Stop Impersonating</span>
            </inertia-link>
            <a
                href="/logout"
                v-if="!isImpersonating"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-power-off"></i>
                <span class="text-sm pt-1">Logout</span>
            </a>
        </div>
        <!-- Mobile Menu -->
        <div
            v-show="isMobileMenuOpen"
            class="w-full md:hidden flex flex-col space-y-2 bg-white p-4 z-40"
        >
            <inertia-link
                href="/new-record"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-circle-plus"></i>
                <span class="text-sm">Create a record</span>
            </inertia-link>
            <inertia-link
                href="/add-group"
                v-if="user.role == 'admin'"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-circle-plus"></i>
                <span class="text-sm pt-1">Create a group</span>
            </inertia-link>
            <inertia-link
                v-if="user.role == 'admin'"
                href="/users-list"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center relative"
            >
                <i class="fas fa-user-group"></i>

                <span class="text-sm pt-1">Manage users</span>
            </inertia-link>
            <inertia-link
                v-if="user.role == 'leader' || user.role == 'admin'"
                href="/manage-group"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-user-group"></i>
                <span class="text-sm">Manage my groups</span>
            </inertia-link>
            <inertia-link
                href="/profile"
                class="text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-circle-user"></i>
                <span class="text-sm">Profile</span>
            </inertia-link>

            <inertia-link
                href="/stop-impersonating"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
                v-if="isImpersonating"
                @click="stopImpersonation"
            >
                <i class="fas fa-power-off"></i>
                <span class="text-sm pt-1">Stop Impersonating</span>
            </inertia-link>
            <a
                href="/logout"
                v-if="!isImpersonating"
                class="ml-4 text-gray-900 hover:!text-blue-600 dark:text-gray-300 flex flex-col items-center"
            >
                <i class="fas fa-power-off"></i>
                <span class="text-sm pt-1">Logout</span>
            </a>
        </div>
    </header>
</template>

<script>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export default {
    setup() {
        const { user, groupleader } = usePage().props;
        const userCount = ref(0);

        const isImpersonating = ref(
            localStorage.getItem("isImpersonating") === "true"
        );

        // Computed property
        const displayStatus = computed(() => {
            return isImpersonating.value
                ? "Impersonating"
                : "Not Impersonating";
        });
        const isMobileMenuOpen = ref(false);
        const toggleMobileMenu = () => {
            isMobileMenuOpen.value = !isMobileMenuOpen.value;
        };
        const stopImpersonation = () => {
            isImpersonating.value = false;
            localStorage.setItem("isImpersonating", "false");
            // Perform any additional actions needed when stopping impersonation
        };

        // onMounted(async () => {
        //     if (user.role === "admin") {
        //         const response = await fetch("/api/get-user-count");
        //         const data = await response.json();
        //         userCount.value = data.count; // Assuming your API returns an object with a 'count' property
        //     }
        // });

        return {
            isMobileMenuOpen,
            userCount,
            user,
            isImpersonating,
            displayStatus,
            stopImpersonation,
            toggleMobileMenu,
            groupleader,
        };
    },
};
</script>
