<template>
    <Layout>
        <template v-slot:title>Users List</template>
        <h2>New Users to validate</h2>
        <table class="min-w-full mt-5 big-table">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Subscription</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="user in unpaidusers" :key="user.id">
                    <td>
                        {{ user.lname }}
                    </td>
                    <td>
                        {{ user.fname }}
                    </td>
                    <td>
                        <a :href="'mailto:' + user.email"> {{ user.email }}</a>
                    </td>
                    <td v-if="user.subscription == 'paidbyme'">By the user</td>
                    <td v-else>
                        By group
                        <template v-for="group in user.privateg">
                            ( {{ group.name }} ),
                        </template>
                    </td>
                    <td>
                        <button
                            :href="``"
                            class="btn-primary ml-4"
                            @click="acceptReject(user, 'paid')"
                        >
                            Accept
                        </button>
                        <button
                            :href="``"
                            class="btn-secondary ml-4"
                            @click="acceptReject(user, 'reject')"
                        >
                            Reject
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <h2 class="mt-12">All Users</h2>
        <table class="min-w-full mt-5 big-table">
            <thead>
                <tr>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>

                    <th>User Role</th>
                    <th>User Groups</th>
                    <th>Group Manager</th>
                    <th>Group Shared</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="user in allusers" :key="user.id">
                    <td>
                        {{ user.lname }}
                    </td>
                    <td>
                        {{ user.fname }}
                    </td>
                    <td>
                        <a :href="'mailto:' + user.email"> {{ user.email }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                        <template v-if="user.status == 'active'">
                            Active
                        </template>
                        <template v-else>Inactive </template>
                    </td>
                    <td class="flex">
                        <inertia-link
                            :href="`/impersonate/${user.id}`"
                            class="btn-primary"
                            @click="startImpersonation()"
                            >Impersonate
                        </inertia-link>
                        <button
                            v-if="user.status == 'active'"
                            :href="``"
                            class="btn-secondary ml-4"
                            @click="changeStatus(user)"
                        >
                            Disable
                        </button>
                        <button
                            v-else
                            :href="``"
                            class="btn-secondary ml-4"
                            @click="changeStatus(user)"
                        >
                            Enable
                        </button>
                    </td>
                    <td>
                        {{ user.role }}
                    </td>
                    <td>
                        <template v-for="group in user.privateg">
                            {{ group.name }},
                        </template>
                    </td>
                    <td>
                        <template v-for="group in user.leadergroups">
                            {{ group.name }},
                        </template>
                    </td>
                    <td>
                        <template v-for="group in user.shared_groups">
                            {{ group }},
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>
    </Layout>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia-vue3";
import Layout from "../Layout/Layout.vue";

export default {
    components: {
        Layout,
    },
    props: {
        user: Object,
        post: Object,
        allusers: Array,
        unpaidusers: Array,
    },
    setup(props) {
        // Reactive variables
        const isImpersonating = ref(
            localStorage.getItem("isImpersonating") === "true"
        );

        // Method to start impersonation
        const startImpersonation = async () => {
            isImpersonating.value = true;
            localStorage.setItem("isImpersonating", "true");
        };
        const changeStatus = async (user) => {
            let status = "";
            if (user.status == "active") {
                user.status = "pending";
                status = user.status;
            } else {
                user.status = "active"; // or however you format this date in your backend
                status = user.status;
            }
            console.log(status);
            const data = {
                id: user.id,
                status: status,
            };
            axios
                .post("/change-user-status", data)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((error) => {});
        };

        const acceptReject = (user, status) => {
            if (status == "paid") {
                user.payement_status = "payed";
                user.status = "active";
                const indexToRemove = props.unpaidusers.findIndex(
                    (u) => u.id === user.id
                );

                if (indexToRemove !== -1) {
                    props.unpaidusers.splice(indexToRemove, 1);
                    props.allusers.push(user);
                }
            } else {
            }
            const data = {
                id: user.id,
                status: status,
            };
            axios
                .post("/change-user-paid-status", data)
                .then((response) => {
                    console.log(response.data);
                })
                .catch((error) => {});
        };

        return {
            isImpersonating,
            startImpersonation,
            changeStatus,
            acceptReject,
        };
    },
};
</script>
