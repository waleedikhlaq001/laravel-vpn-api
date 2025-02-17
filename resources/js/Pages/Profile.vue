<template>
 <Layout>
        <template v-slot:title>My Profile</template>
        <div class="relative pb-10 mb-3">
          <div class="flex flex-col md:flex-row">
            <form class="md:flex" @submit.prevent="form.post('/profile-update')">
              <div class="flex flex-col w-full md:w-[40rem] md:mr-10">
                <h4 class="mt-3 text-xl">General info</h4>
                <div class="flex mb-6 mt-4">
                  <label class="w-[12rem] mr-4 self-center">
                    Firstname
                  </label>
                  <div class="w-[16rem] flex flex-col">
                    <input type="text" v-model="form.fname" required />
                    <div v-if="form.errors.fname" class="text-red-400">
                      {{ form.errors.fname }}
                    </div>
                  </div>
                </div>
                <div class="flex mb-6">
                  <label class="w-[12rem] mr-4 self-center">
                    Lastname
                  </label>
                  <div class="w-[16rem] flex flex-col">
                    <input type="text" v-model="form.lname" required />
                    <div v-if="form.errors.lname" class="text-red-400">
                      {{ form.errors.lname }}
                    </div>
                  </div>
                </div>

                <div class="flex mb-6">
                  <label class="w-[12rem] mr-4 self-center">
                    Email
                  </label>
                  <div class="w-[16rem] flex flex-col">
                    <input v-model="form.email" type="text" required />
                    <div v-if="form.errors.email" class="text-red-400">
                      {{ form.errors.email }}
                    </div>
                  </div>
                </div>
                <div class="flex mb-6">
                  <label class="w-[12rem] mr-4 self-center">
                    Password
                  </label>
                  <div class="w-[16rem] flex flex-col">
                    <input type="password" v-model="form.password" required />
                    <div v-if="form.errors.password" class="text-red-400">
                      {{ form.errors.password }}
                    </div>
                  </div>
                </div>
                <div class="flex mb-6">
                  <label class="w-[12rem] mr-4 self-center">
                    Phone Number
                  </label>
                  <div class="w-[16rem] flex flex-col">
                    <input type="tel" placeholder="076 123 45 67" v-model="form.tel" required />
                    <div v-if="form.errors.tel" class="text-red-400">
                      {{ form.errors.tel }}
                    </div>
                  </div>
                </div>
                <div class="flex mb-6">
                  <label class="w-[12rem] mr-4">
                    Job Title (optional)
                  </label>
                  <div class="flex flex-col justify-start">
                    <input class="w-[16rem]" v-model="form.job_title" type="text" />
                    <button type="submit" class="btn-primary w-[16rem] mt-6">
                      Save
                    </button>
                  </div>
                </div>
              </div>
              <div class="flex flex-col text-center mt-6 md:mt-0">
                <h4 class="mt-3 text-xl mb-4 text-left">Profile</h4>
                <div class="max-w-[10rem] mx-auto">
                  <img v-if="form.profileImage" :src="form.profileImage" alt="Profile Image" class="mb-4 rounded" />
                  <img v-else src="/images/profile.png" alt="Profile Image" class="mb-4 rounded" />
                  <input type="file" ref="fileInput" id="profileImg" accept="image/*" class="hidden" @change="handleFileChange" />
                  <label class="btn-secondary mt-6" for="profileImg" @click="openFileInput">Edit</label>
                </div>
              </div>
            </form>
          </div>
          <h2 class="text-md font-semibold mt-10 mb-5">Subscription</h2>
          <div class="flex items-center mb-2">
            <input class="w-[1rem]" type="radio" id="subscription_indi" name="subscription" :checked="user.role == 'individual'" required />
            <label class="w-[15rem] ml-2" for="subscription_indi">
              Paid by myself
            </label>
          </div>
          <div class="flex items-center mb-5">
            <input class="w-[1rem]" type="radio" id="subscription_group" name="subscription" :checked="user.role == 'member'" required />
            <label class="w-[36rem] ml-2" for="subscription_group">
              Paid by group:
              <span v-if="groups.length > 0">{{
                                groups[0].name
                            }}</span>
            </label>
          </div>
          <div class="flex items-center mb-5">
            <p>
              Next payment due:
              <span class="font-bold">31.12.2023</span>
            </p>
          </div>
          <button class="btn-secondary">Pay invoice</button>
          <h2 class="text-md font-semibold mt-10 mb-5">
            Notifications
          </h2>
          <div class="flex items-center mb-2">
            <input class="w-[1rem]" type="checkbox" required />
            <label class="w-[20rem] ml-2">
              Receive eKarto activity summaries
            </label>
            <select>
              <option>Frequency:Weekly</option>
            </select>
          </div>
          <div class="flex items-center mb-5">
            <input class="w-[1rem]" type="checkbox" required />
            <label class="w-[36rem] ml-2">
              Receive alerts when new comments/edits are added to
              my records
            </label>
          </div>
        </div>
</Layout>
</template>

<script>
import Layout from "../Layout/Layout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
export default {
  props: {
    user: Object,
    groups: Object,
  },
  components: {
    Layout,
  },
  setup(props) {
    const form = useForm({
      email: props.user.email,
      password: props.user.password,
      password_confirmation: "",
      fname: props.user.fname,
      lname: props.user.lname,
      tel: props.user.tel,
      job_title: props.user.job_title,
      avatar: props.user.avatar,
      profileImage: props.user.avatar,
    });

    const fileInputRef = ref(null);

    const openFileInput = () => {
      fileInputRef.value.click();
    };

    const handleFileChange = (event) => {
      const file = event.target.files[0];
      form.avatar = file; // Update the selected image file in the form data
      const reader = new FileReader();

      reader.onload = (e) => {
        form.profileImage = e.target.result;
      };

      reader.readAsDataURL(file);
    };

    return {
      form,
      fileInputRef,
      openFileInput,
      handleFileChange,
    };
  },
};
</script>
