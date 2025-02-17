<template>
    <Layout>
        <template v-slot:title>Manage my groups</template>
        <div
            class="mb-4 mt-6 bg-blue-100 dark:bg-blue-600 dark:border-blue-500 border border-blue-300 rounded p-3"
        >
            <label class="w-[9rem] mr-4 self-center font-bold text-sm">
                Group selected:
            </label>
            <select v-model="selectedGroup">
                <option
                    v-for="(group, index) in groups"
                    :key="index"
                    :value="group"
                >
                    {{ group.name }}
                </option>
            </select>
        </div>
        <div class="relative flex flex-col">
            <div class="py-5 flex-auto">
                <div class="tab-content tab-space">
                    <div v-if="selectedGroupData">
                        <h2 class="text-xl mb-2">Group settings</h2>
                        <h5 class="text-xs font-bold">General</h5>
                        <div class="flex mb-4 mt-4">
                            <label class="w-[9rem] mr-4 self-center text-xs">
                                Name of group
                            </label>
                            <input
                                class="w-64"
                                type="text"
                                v-model="groupName"
                                required
                            />
                        </div>
                        <div class="flex mb-9 mt-4">
                            <label class="w-[5rem] mr-4 self-center text-xs">
                                Description
                            </label>
                            <select class="me-3">
                                <option>EN</option>
                            </select>
                            <input
                                class="w-64"
                                v-model="groupDescription"
                                type="text"
                                required
                            />
                        </div>

                        <h4 class="text-sm font-bold mt-4">
                            Content visibility
                        </h4>
                        <div class="flex mb-2 mt-3">
                            <input
                                class="w-[1rem]"
                                name="content"
                                type="radio"
                                value="private"
                                id="visibilityPrivate"
                                v-model="content_visibility"
                                :checked="selectedGroupData.type === 'private'"
                                required
                            />
                            <label
                                class="w-[30rem] mr-4 pl-2 self-center text-xs"
                                for="visibilityPrivate"
                            >
                                <i class="fas fa-eye"></i> Private: Only members
                                of this group can create and read content.
                            </label>
                        </div>
                        <div class="flex mb-9">
                            <input
                                class="w-[1rem] self-center"
                                name="content"
                                type="radio"
                                id="visibilityPublic"
                                value="public"
                                v-model="content_visibility"
                                :checked="selectedGroupData.type === 'public'"
                                required
                            />
                            <label
                                class="w-[36rem] mr-4 pl-2 self-center text-xs"
                                for="visibilityPublic"
                            >
                                <i class="fas fa-lock"></i> Public: All eKarto
                                users can &nbsp;
                                <select v-model="readPermission">
                                    <option value="readwrite">
                                        Read & Edit
                                    </option>
                                    <option value="read">Read only</option>
                                </select>
                            </label>
                        </div>
                        <button @click="updateInfo()" class="btn-primary">
                            Save
                        </button>
                        <div class="flex flex-col mb-8 mt-10">
                            <h2 class="text-xl font-normal">
                                Subscription and billing
                            </h2>
                            <p class="text-xs mt-4 mb-1">
                                Your current subscription allows a maximum of:
                                <b>50 members</b> supported by this group &nbsp;
                                <button class="btn-secondary">
                                    Request subscription change
                                </button>
                            </p>
                            <p class="text-xs mb-1 mt-1">
                                Your group currently handles billing for:
                                <b>15 members</b>
                            </p>
                            <p class="text-xs mb-1 mt-1">
                                Payment method:
                                <b
                                    >E-mail invoice at the beginning of the
                                    year</b
                                >
                            </p>
                        </div>
                        <div v-if="selectedGroupData.type == 'private'">
                            <h4 class="text-sm font-bold">
                                Private group sharing
                            </h4>
                            <div class="mt-3 text-xs mb-5">
                                This private group is shared in full with the
                                following private group(s). Members of these
                                groups have full read access to all content:
                            </div>
                            <div class="flex mb-3 w-[25rem]">
                                <Multiselect
                                    mode="tags"
                                    @deselect="removeShare"
                                    v-model="selectedGroupIds"
                                    :searchable="true"
                                    placeholder="Select a group"
                                    :options="combinedGroups"
                                    ref="multiselect"
                                />
                            </div>
                            <a
                                href=""
                                class="btn-primary"
                                @click.prevent="shareGroup(selectedGroupData)"
                                >Save</a
                            >
                            <h2>
                                {{ selectedGroupData.pending_users.length }}
                                Membership applications
                            </h2>
                            <div class="mb-2 mt-3 text-xs mb-5">
                                Any eKarto user can request to join your group.
                                You are free to accept or refuse this request.
                            </div>
                        </div>
                        <table
                            class="min-w-full big-table"
                            v-if="selectedGroupData.pending_users.length > 0"
                        >
                            <thead>
                                <tr>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>E-mail</th>
                                    <th>Member since</th>
                                    <th>Billing</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="pendingusers in selectedGroupData.pending_users"
                                    :key="pendingusers.id"
                                >
                                    <td>
                                        <span class="font-bold text-sm">{{
                                            selectedGroupData.name
                                        }}</span>
                                    </td>
                                    <td>
                                        {{ pendingusers.fname }}
                                    </td>
                                    <td>
                                        {{ pendingusers.email }}
                                    </td>
                                    <td>Pierre Crotio, Marlène Petiboulot</td>
                                    <td>
                                        <select>
                                            <option>By this group</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button
                                            class="btn-primary"
                                            @click="
                                                acceptApplication(pendingusers)
                                            "
                                        >
                                            Accept
                                        </button>
                                        <button
                                            @click="
                                                removeUser(
                                                    pendingusers,
                                                    'pending'
                                                )
                                            "
                                            class="btn-secondary ms-2"
                                        >
                                            Refuse
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex mt-10 items-center">
                            <h2 class="text-xl">
                                {{ combinedUsers.length }}
                                Group members
                            </h2>
                            <input
                                type="text"
                                v-model="searchTerm"
                                placeholder="Search"
                                @input="searchUsers()"
                                class="ml-10 w-1/5"
                            />
                        </div>
                        <div class="mb-2 mt-3 text-xs mb-5">
                            Members can
                            <span class="font-bold"> read and write</span>
                            content for your group. You can also manage their
                            <span class="font-bold"> billing</span>. The maximum
                            number of members allowed in your group is defined
                            by your subscription.
                        </div>
                        <table
                            class="min-w-full big-table"
                            v-if="combinedUsers.length > 0"
                        >
                            <thead>
                                <tr>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>E-mail</th>
                                    <th>Member since</th>
                                    <th>Billing</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="activeusers in combinedUsers"
                                    :key="activeusers.id"
                                >
                                    <td>
                                        <span class="font-bold text-sm">{{
                                            activeusers.lname
                                        }}</span>
                                    </td>
                                    <td>
                                        {{ activeusers.fname }}
                                    </td>
                                    <td>
                                        {{ activeusers.email }}
                                    </td>
                                    <td>
                                        {{
                                            formatDateString(
                                                activeusers.created_at
                                            )
                                        }}
                                    </td>
                                    <td>
                                        <select>
                                            <option>By this group</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select
                                            v-if="activeusers.pivot"
                                            v-model="activeusers.pivot.role"
                                            @change="
                                                updateUserRole(
                                                    activeusers.pivot
                                                )
                                            "
                                        >
                                            <option value="leader">
                                                Group Admin
                                            </option>
                                            <option value="member">
                                                Member
                                            </option>
                                        </select>
                                        <select
                                            v-model="
                                                selectedRoles[activeusers.id]
                                            "
                                            v-else
                                        >
                                            <option value="leader">
                                                Group Admin
                                            </option>
                                            <option value="member">
                                                Member
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <button
                                            v-if="
                                                isUserInFilteredUsers(
                                                    activeusers
                                                )
                                            "
                                            class="md:w-30 btn-secondary"
                                            @click="
                                                removeUser(
                                                    activeusers,
                                                    'member'
                                                )
                                            "
                                        >
                                            Remove from group
                                        </button>

                                        <button
                                            class="btn-primary"
                                            v-else
                                            @click="addUser(activeusers)"
                                        >
                                            Add User
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script>
import Layout from "../Layout/Layout.vue";
import axios from "axios";
import { reactive, ref, watch, onMounted, computed } from "vue";
import Multiselect from "@vueform/multiselect";

export default {
    components: {
        Layout,
        Multiselect,
    },
    props: {
        groups: Array,
        allgroups: Array,
    },
    setup(props) {
        const groups = reactive(props.groups);
        const selectedGroup = ref(props.groups[0]); // Default selected group as the first group's name
        const selectedGroupData = ref(null); // Will be updated with the data of the selected group
        const groupid = ref([]);
        const mygroupid = ref(null);
        const searchTerm = ref("");
        const groupName = ref("");
        const groupDescription = ref("");
        const content_visibility = ref("");
        const readPermission = ref("");
        const selectedRoles = ref({});
        const userRole = reactive([]);
        const searchResults = reactive([]);
        const selectedGroupIds = ref([]);

        // Watch for changes in the selected group option
        watch(selectedGroup, (newValue) => {
            // Find the selected group's data from the groups array
            selectedGroupData.value = props.groups.find(
                (group) => group.id === newValue.id
            );
            groupName.value = selectedGroupData.value.name;
            groupDescription.value = selectedGroupData.value.description;
            content_visibility.value = selectedGroupData.value.type;
            readPermission.value = selectedGroupData.value.permissions;
            selectedGroupIds.value = selectedGroupData.value.shared_with
                .filter((group) => group.type === "private")
                .map((group) => group.id);
        });
        watch(groupName, (newValue) => {
            const group = props.groups.find(
                (group) => group.id === selectedGroupData.value.id
            );
            group.name = newValue;
        });

        const filteredUsers = computed(() => {
            // Check if selectedGroupData.users is defined before filtering
            if (selectedGroupData.value.active_users) {
                return selectedGroupData.value.active_users;
            }
        });

        const combinedUsers = computed(() => {
            const allUsers = [
                ...(Array.isArray(filteredUsers.value)
                    ? filteredUsers.value
                    : []),
                ...(Array.isArray(searchResults.value)
                    ? searchResults.value
                    : []),
            ];
            return allUsers.filter((user, index) => {
                return (
                    index === allUsers.findIndex((u) => u.id === user.id) // Remove duplicates
                );
            });
        });

        const searchUsers = async () => {
            const obj = {
                search: searchTerm.value,
            };
            const response = await axios.post("/search-users", obj);
            searchResults.value = response.data.users;
            searchResults.value.forEach((user) => {
                selectedRoles.value[user.id] = user.pivot
                    ? user.pivot.role
                    : "leader";
            });
            console.log("Response:", response.data.users);
        };

        const isUserInFilteredUsers = (user) => {
            return filteredUsers.value.some(
                (filteredUser) => filteredUser.id === user.id
            );
        };

        const filtergroups = computed(() => {
            const selectedGroupId = selectedGroup.value.id;
            return props.allgroups
                .filter(
                    (group) =>
                        group.id !== selectedGroupId && group.type === "private"
                )
                .map((group) => ({ value: group.id, label: group.name }));
        });

        const combinedGroups = computed(() => {
            const selectedGroups = selectedGroupData.value.shared_with;
            console.log(selectedGroups);
            selectedGroups
                .filter((group) => group.type === "private")
                .map((group) => ({ value: group.id, label: group.name }));

            return [...selectedGroups, ...filtergroups.value];
        });

        const addUser = (user) => {
            const data = {
                user_id: user.id,
                group_id: selectedGroupData.value.id,
                role: selectedRoles.value[user.id],
                status: "active", // Assuming the default status
            };
            console.log(data);

            axios
                .post("/add-user-to-group", data)
                .then((response) => {
                    console.log(response.data);
                    selectedGroupData.value.active_users.push(response.data);
                })
                .catch((error) => {});
        };

        const shareGroup = async (groupData) => {
            if (selectedGroupIds.value.length > 0) {
                try {
                    const dataToSend = {
                        group_id: groupData.id,
                        groups: selectedGroupIds.value,
                    };
                    const response = await axios.post(
                        "/assign-group",
                        dataToSend
                    );
                    // selectedGroupIds.value = [];
                    alert("This private group is shared successfully");
                    window.location.reload();
                    console.log("Response:", response.data);
                } catch (error) {
                    console.error("Error:", error);
                }
            } else {
                alert("Please select group");
            }
        };

        const removeShare = (removedTagValue) => {
            axios
                .get(
                    `/detach-group/${removedTagValue}/${selectedGroupData.value.id}`
                )
                .then((response) => {
                    window.location.reload();
                })
                .catch((error) => {
                    console.error("Error deleting user:", error);
                });
        };

        const acceptApplication = (userToRemove) => {
            if (selectedGroupData.value) {
                const pendingUsers = selectedGroupData.value.pending_users;
                const users = selectedGroupData.value.active_users;

                if (Array.isArray(pendingUsers)) {
                    const indexToRemove = pendingUsers.findIndex(
                        (user) => user.id === userToRemove.id
                    );

                    if (indexToRemove !== -1) {
                        pendingUsers.splice(indexToRemove, 1);
                        if (Array.isArray(users)) {
                            users.push(userToRemove);
                        } else {
                            console.error(
                                "selectedGroupData.value.users is not an array."
                            );
                        }
                        // Make an API call to the backend to update status the user from the database
                        axios
                            .get(
                                `/updatestatus/${userToRemove.id}/${selectedGroupData.value.id}`
                            )
                            .then((response) => {})
                            .catch((error) => {
                                console.error("Error deleting user:", error);
                            });
                    }
                } else {
                    console.error(
                        "selectedGroupData.value.pending_users is not an array."
                    );
                }
            } else {
                console.error("selectedGroupData.value is undefined.");
            }
        };

        const removeUser = (userRemove, status) => {
            if (selectedGroupData.value) {
                if (status == "pending") {
                    const isConfirmed = window.confirm(
                        "Are you sure you want to refuse?"
                    );
                    if (isConfirmed) {
                        console.log("dasdadasd", userRemove);

                        const pendingUsers =
                            selectedGroupData.value.pending_users;
                        if (Array.isArray(pendingUsers)) {
                            const indexToRemove = pendingUsers.findIndex(
                                (user) => user.id === userRemove.id
                            );

                            if (indexToRemove !== -1) {
                                pendingUsers.splice(indexToRemove, 1);
                            }
                        }
                    }
                } else {
                    const users = selectedGroupData.value.active_users;
                    if (Array.isArray(users)) {
                        const indexToRemove = users.findIndex(
                            (user) => user.id === userRemove.id
                        );

                        if (indexToRemove !== -1) {
                            // Use splice to remove the user from the array
                            users.splice(indexToRemove, 1);
                        }
                    } else {
                        console.error(
                            "selectedGroupData.value.pending_users is not an array."
                        );
                    }
                }
                axios
                    .get(
                        `/removeuser/${userRemove.id}/${selectedGroupData.value.id}`
                    )
                    .then((response) => {})
                    .catch((error) => {
                        console.error("Error deleting user:", error);
                    });
            } else {
                console.error("selectedGroupData.value is undefined.");
            }
        };
        const updateInfo = async () => {
            try {
                const obj = {
                    id: selectedGroupData.value.id,
                    name: groupName.value,
                    description: groupDescription.value,
                    content_visibility: content_visibility.value,
                    permission: readPermission.value,
                };
                console.log(obj);
                const response = await axios.post("/group-update", obj);

                alert("Group updated successfully");
                window.location.reload();
                console.log("Response:", response.data);
            } catch (error) {
                // Handle errors if any
                console.error("Error:", error);
            }
        };

        const formatDateString = (dateString) => {
            const options = {
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
            };
            return new Date(dateString).toLocaleString(undefined, options);
        };

        const updateUserRole = async (user) => {
            console.log(user);
            const obj = {
                id: user.id,
                role: user.role,
            };
            const response = await axios.post("/update-user-role", obj);
        };

        // Set up the default value when the component is mounted
        onMounted(() => {
            selectedGroupData.value = groups.find(
                (group) => group.id === selectedGroup.value.id
            );
            groupName.value = selectedGroupData.value.name;
            groupDescription.value = selectedGroupData.value.description;
            content_visibility.value = selectedGroupData.value.type;
            readPermission.value = selectedGroupData.value.permissions;
            selectedGroupIds.value = selectedGroupData.value.shared_with
                .filter((group) => group.type === "private")
                .map((group) => group.id);
        });
        mygroupid.value = selectedGroupData;
        return {
            selectedGroup,
            groups,
            selectedGroupData,
            filtergroups,
            mygroupid,
            groupid,
            filteredUsers,
            searchTerm,
            groupName,
            readPermission,
            groupDescription,
            content_visibility,
            userRole,
            selectedRoles,
            combinedUsers,
            combinedGroups,
            selectedGroupIds,
            addUser,
            shareGroup,
            acceptApplication,
            removeUser,
            updateInfo,
            formatDateString,
            updateUserRole,
            searchUsers,
            isUserInFilteredUsers,
            removeShare,
        };
    },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
