<template>
  <Layout>
    <template v-slot:title>Join a group</template>
    <p class="text-xs mt-2">
      You are automatically subscribed to
      <span class="font-bold">public</span> groups whose content
      is accessible to all eKarto members.
    </p>
    <p class="text-xs mb-3">
      For the <span class="font-bold">private</span> groups below,
      a membership request must be sent to the group
      administrators, who will decide whether or not to accept
      you.
    </p>
    <table class="min-w-full big-table">
      <thead>
        <tr>
          <th>
            Group
          </th>
          <th>
            Description
          </th>
          <th>
            Type
          </th>
          <th>
            Admins
          </th>
          <th>
            Request to join
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="group in groups" :key="group.id">
          <td>
            <span class="font-bold text-sm">{{
              group.name
            }}</span>
          </td>
          <td>
            {{ group.description }}
          </td>
          <td>
            {{ group.type }}
          </td>
          <td>
            {{ group.guser.fname }} {{ group.guser.lname }}
          </td>
          <td>
            <button class="btn-primary" @click="removeGroup(group)">
              Apply
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </Layout>
</template>
<script>
import axios from "axios";
import Layout from "../Layout/Layout.vue";

export default {
  components: {
    Layout,
  },
  props: {
    user: Object,
    groups: Array,
  },
  setup(props) {
    const removeGroup = (userGroup) => {
      if (props.groups) {
        const groups = props.groups;
        if (Array.isArray(groups)) {
          const indexToRemove = groups.findIndex(
            (group) => group.id === userGroup.id
          );

          if (indexToRemove !== -1) {
            // Use splice to remove the user from the array
            groups.splice(indexToRemove, 1);
          }
          axios
            .get(`/applygroup/${userGroup.id}`)
            .then((response) => { })
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
    };
    return {
      removeGroup,
    };
  },
};
</script>
