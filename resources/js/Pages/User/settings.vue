<template>
  <div class="p-8 max-w-2xl mx-auto bg-gradient-to-br from-white via-white to-teal-50 rounded-2xl shadow-xl">
    <h2 class="text-3xl font-bold mb-8 text-gray-800 border-b-2 border-teal-600 pb-2">Account Settings</h2>

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-8">
      <button @click="activeTab = 'profile'" :class="tabClass('profile')">Profile</button>
      <button @click="activeTab = 'password'" :class="tabClass('password')">Password</button>
    </div>

    <!-- Profile Tab Content -->
    <div v-if="activeTab === 'profile'" class="space-y-6">
      <form @submit.prevent="updateProfile" class="bg-white p-8 rounded-2xl shadow-md space-y-6 border border-gray-100">
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
            <input
              v-model="form.firstName"
              type="text"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
            <input
              v-model="form.lastName"
              type="text"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all"
            />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
          <input
            v-model="form.phone_num"
            type="tel"
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
          <select
            v-model="form.gender"
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all"
          >
            <option value="" disabled>Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
            <option value="prefer-not-to-say">Prefer not to say</option>
          </select>
        </div>

        <div class="flex justify-end pt-4">
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2.5 bg-gradient-to-r from-[#007A8C] to-[#009FB7] text-white font-medium rounded-lg shadow-md hover:shadow-lg hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-2"
          >
            <span
              v-if="loading"
              class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></span>
            {{ loading ? "Updating..." : "Update Profile" }}
          </button>
        </div>
      </form>
    </div>

    <!-- Password Tab Content -->
    <div v-if="activeTab === 'password'" class="bg-white p-8 rounded-2xl shadow-md border border-gray-100 space-y-5">
      <h3 class="text-xl font-semibold text-gray-800 mb-6">Change Password</h3>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
        <input
          v-model="passwordForm.current_password"
          type="password"
          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7]"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
        <input
          v-model="passwordForm.new_password"
          type="password"
          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7]"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
        <input
          v-model="passwordForm.new_password_confirmation"
          type="password"
          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7]"
        />
      </div>

      <div class="pt-4 flex justify-end">
        <button
          @click.prevent="updatePassword"
          :disabled="loadingPassword"
          class="px-6 py-2.5 bg-gradient-to-r from-[#007A8C] to-[#009FB7] text-white font-medium rounded-lg shadow-md hover:shadow-lg hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-2"
        >
          <span
            v-if="loadingPassword"
            class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"
          ></span>
          {{ loadingPassword ? "Updating..." : "Update Password" }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import axios from "axios";

  const activeTab = ref("profile");
  const form = ref({ firstName: "", lastName: "", phone_num: "", gender: "" });
  const loading = ref(false);

  const passwordForm = ref({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
  });
  const loadingPassword = ref(false);

  const tabClass = (tab) => [
    "px-6 py-3 font-medium text-sm transition-colors duration-200 border-b-2 -mb-px focus:outline-none",
    activeTab.value === tab
      ? "border-[#007A8C] text-[#007A8C]"
      : "border-transparent text-gray-500 hover:text-[#009FB7] hover:border-[#009FB7]",
  ];

  const getUserInfo = async () => {
    try {
      const response = await axios.get("/user-information");
      const data = response.data.user_info;
      if (data)
        form.value = {
          firstName: data.firstName || "",
          lastName: data.lastName || "",
          phone_num: data.phone_num || "",
          gender: data.gender || "",
        };
    } catch (error) {
      console.error(error);
    }
  };

  const updateProfile = async () => {
    loading.value = true;
    try {
      await axios.put("/user-information", form.value);
      alert("✅ Profile updated successfully!");
    } catch (error) {
      console.error(error);
      alert("❌ Failed to update profile.");
    } finally {
      loading.value = false;
    }
  };

  const updatePassword = async () => {
    loadingPassword.value = true;
    try {
      await axios.put("/user-password", passwordForm.value);
      alert("✅ Password updated successfully!");
      passwordForm.value = {
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
      };
    } catch (error) {
      console.error(error);
      alert(error.response?.data?.message || "❌ Failed to update password.");
    } finally {
      loadingPassword.value = false;
    }
  };

  onMounted(getUserInfo);
</script>
