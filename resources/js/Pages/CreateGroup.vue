<template>
    <Layout>
        <template v-slot:title>Create new group</template>
        <form @submit.prevent="form.post('/create-group')">
            <div class="relative flex flex-col">
                <div class="py-5 flex-auto">
                    <div class="flex mb-4 mt-4">
                        <label class="w-[9rem] mr-4 self-center text-xs">
                            Name of group
                        </label>
                        <input
                            class="w-64"
                            type="text"
                            v-model="form.groupName"
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
                            v-model="form.groupDescription"
                            type="text"
                            required
                        />
                    </div>

                    <!-- <h4 class="text-sm font-bold mt-4">Content visibility</h4>
                    <div class="flex mb-2 mt-3">
                        <input
                            class="w-[1rem]"
                            name="content"
                            type="radio"
                            value="private"
                            v-model="form.content_visibility"
                            required
                        />
                        <label class="w-[30rem] mr-4 pl-2 self-center text-xs">
                            <i class="fas fa-eye"></i> Private: Only members of
                            this group can create and read content.
                        </label>
                    </div>
                    <div class="flex mb-9">
                        <input
                            class="w-[1rem] self-center"
                            name="content"
                            type="radio"
                            value="public"
                            v-model="form.content_visibility"
                            required
                        />
                        <label class="w-[36rem] mr-4 pl-2 self-center text-xs">
                            <i class="fas fa-lock"></i> Public: All eKarto users
                            can &nbsp;
                            <select v-model="form.readPermission">
                                <option value="readwrite">Read & Edit</option>
                                <option value="read">Read only</option>
                            </select>
                        </label>
                    </div> -->
                    <button type="submit" class="btn-primary">Add</button>
                </div>
            </div>
        </form>
    </Layout>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Layout from "../Layout/Layout.vue";

export default {
    components: {
        Layout,
    },
    props: {
        leaders: Array,
    },
    setup(props) {
        const form = useForm({
            groupName: "",
            readPermission: "readwrite",
            groupDescription: "",
            content_visibility: "",
        });

        return { form };
    },
};
</script>
