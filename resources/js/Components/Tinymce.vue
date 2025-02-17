<template>
    <editor v-model="editorValue" :init="init"></editor>
</template>

<script setup>
import { reactive, ref, watch, toRefs } from "vue";

import tinymce from "tinymce/tinymce.js";
// import 'tinymce/models/dom'; (TinyMCE 6)

import "tinymce/skins/ui/oxide/skin.css";
import "tinymce/themes/silver";

// Icon
import "tinymce/icons/default";

import "tinymce/plugins/emoticons";
import "tinymce/plugins/emoticons/js/emojis.js";
import "tinymce/plugins/table";
import "tinymce/plugins/quickbars";
import "tinymce/plugins/lists";


//import "tinymce-i18n/langs5/zh_TW.js";

// TinyMCE-Vue
import Editor from "@tinymce/tinymce-vue";

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    plugins: {
        type: [String, Array],
        default: "quickbars emoticons lists",
    },
    toolbar: {
        type: [String, Array],
        default:
            " bold italic underline strikethrough | forecolor backcolor | bullist numlist | blockquote | undo redo | axupimgs | removeformat | table | emoticons",
    },
});

const emit = defineEmits(["update:modelValue"]);

const init = reactive({
    height: 400,
    menubar: false,
    content_css: false,
    skin: false,
    plugins: props.plugins,
    toolbar: props.toolbar,
    quickbars_insert_toolbar: false,
    quickbars_selection_toolbar: 'bold italic | forecolor backcolor | blockquote',
    content_style: "body { font-family: Arial; }",
    branding: false,
});

const { modelValue } = toRefs(props);
const editorValue = ref(modelValue.value);

watch(modelValue, (newValue) => {
    editorValue.value = newValue;
});

watch(editorValue, (newValue) => {
    emit("update:modelValue", newValue);
});
</script>
