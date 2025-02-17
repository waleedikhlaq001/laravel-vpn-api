<template>
    <Layout>
        <template v-slot:title>
            {{ form.id ? "Update Record" : "New Record" }}
        </template>
        <form @submit.prevent="validateAndSubmitForm">
            <div class="relative pb-10 mb-3">
                <div class="flex mb-4 mt-4 mb-10">
                    <label class="w-full md:w-[12rem] mr-4 self-center">
                        Type
                    </label>
                    <select
                        class="w-full md:w-1/2"
                        v-model="form.type"
                        required
                    >
                        <option>Record</option>
                        <option>Memo</option>
                    </select>
                </div>
                <div class="flex mb-6">
                    <label class="w-full md:w-[12rem] mr-4 self-center">
                        Title
                    </label>
                    <div class="w-full md:w-1/2">
                        <input type="text" required v-model="form.title" />
                    </div>
                </div>
                <div class="flex mb-4 mt-4">
                    <label class="w-full md:w-[12rem] mr-4 self-center">
                        Article of law/main topic
                    </label>
                    <div class="flex w-full md:w-1/2">
                        <input
                            id="username"
                            type="text"
                            required
                            v-model="form.subject"
                        />
                        <button class="btn-secondary ms-2">
                            <i class="fas fa-circle-question text-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="flex mb-6">
                    <label class="w-full md:w-[12rem] mr-4"> Visibility </label>
                    <div class="w-full md:w-1/2 form-group">
                        <div class="flex mb-6 items-center">
                            <input
                                v-model="form.visibility_me"
                                :disabled="form.visibility.length > 0"
                                @change="handleOnlyMeChange"
                                type="checkbox"
                            />
                            <label class="w-[15rem] mr-4 pl-2 self-center"
                                >Only Me</label
                            >
                        </div>
                        <div class="flex">
                            <div
                                class="mb-3 flex items-center"
                                v-for="group in groupsWithAllRights"
                                :key="group.id"
                            >
                                <input
                                    :checked="
                                        form.visibility.includes(group.id)
                                    "
                                    :disabled="form.visibility_me"
                                    @change="toggleGroupVisibility(group.id)"
                                    type="checkbox"
                                />
                                <label class="w-[10rem] mr-2 pl-2 self-center">
                                    {{ group.name }}
                                    <i
                                        class="fas fa-lock"
                                        v-if="group.type == 'private'"
                                    ></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex mb-6">
                    <label class="w-full md:w-[12rem] mr-4 self-center">
                        Attachment
                    </label>
                    <div class="w-full md:w-1/2">
                        <input type="file" @change="handleFileChange" />
                    </div>
                </div>
                <div class="flex mb-6">
                    <label class="w-full md:w-[12rem] mr-4"> Content </label>
                    <div class="w-full md:w-1/2">
                        <tinycme-editor v-model="form.content"></tinycme-editor>

                        <p class="text-xs pt-2">
                            Your entry will be automatically translated into
                            other languages. A legal glossary will be used.
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-center">
                    <input
                        type="submit"
                        @click="setPublishValue(false)"
                        :value="form.publish ? 'Draft' : 'Save as draft'"
                        class="btn-secondary mr-5"
                    />
                    <input
                        type="submit"
                        @click="setPublishValue(true)"
                        :value="form.publish ? 'Publish' : 'Publish'"
                        class="btn-primary"
                    />
                </div>
            </div>
        </form>
    </Layout>
</template>

<script setup>
import TinycmeEditor from "../Components/Tinymce.vue";
import { ref, computed, onMounted, defineProps, watch } from "vue";
import Layout from "../Layout/Layout.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps(["user", "groups", "post", "postgroups", "visibile"]);
const groupsWithAllRights = computed(() => {
    if (!props.groups) return []; // Handle null or undefined props.groups
    return props.groups;
});
// Your form setup
const form = useForm({
    id: props.post.id,
    type: props.post.type ? props.post.type : "Record",
    title: props.post ? props.post.translations.title : "",
    subject: props.post.subject,
    visibility_me: props.visibile,
    visibility: props.postgroups,
    files: "",
    content: props.post ? props.post.translations.content : "",
    publish: false,
});

// Your form functions (handleFileChange, handleOnlyMeChange, setPublishValue, toggleGroupVisibility)

const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.files = file; // Update the selected image file in the form data
};

const handleOnlyMeChange = () => {
    // When "Only Me" checkbox is checked, uncheck all group checkboxes
    if (form.visibility_me.value) {
        form.visibility.value = [];
    }
};

const setPublishValue = (value) => {
    form.publish = value;
};

const toggleGroupVisibility = (groupId) => {
    const index = form.visibility.indexOf(groupId);
    if (index === -1) {
        // Group is not selected, add it to the array
        form.visibility_me = false;
        form.visibility.push(groupId);
    } else {
        // Group is selected, remove it from the array
        form.visibility.splice(index, 1);
    }
};
const validateAndSubmitForm = () => {
    if (form.visibility_me || form.visibility.length > 0) {
        if (props.post) {
            form.post("/update-record");
        } else {
            form.post("/add-record");
        }
    } else {
        // Show error message to the user, asking them to select at least one visibility option
        // You can show the error message using a toast or a custom error div in your UI
        alert("Please select at least one visibility option.");
    }
};
watch(form.content, (newValue) => {
    console.log(newValue);
});
</script>
