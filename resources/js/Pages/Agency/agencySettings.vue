<template>
  <div
    class="max-w-3xl mx-auto mt-12 p-6 bg-gradient-to-br from-white via-white to-teal-50 rounded-3xl shadow-2xl border border-gray-100 mb-12
         max-h-[90vh] overflow-y-auto"
  >
    <!-- Header -->
    <h2 class="text-3xl sm:text-4xl font-bold mb-10 text-gray-800 border-b-2 border-[#007A8C] pb-4">
      Account Settings
    </h2>

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-10">
      <button @click="activeTab = 'profile'" :class="tabClass('profile')">Profile</button>
      <button @click="activeTab = 'password'" :class="tabClass('password')">Password</button>
    </div>

    <!-- Profile Tab -->
    <div v-if="activeTab === 'profile'" class="space-y-8">
      <form
        @submit.prevent="updateProfile"
        class="bg-white p-10 rounded-2xl shadow-lg space-y-8 border border-gray-100"
      >
        <!-- Image Upload -->
        <div class="flex flex-col items-center gap-5">
          <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center shadow-md">
            <img v-if="previewImage" :src="previewImage" class="w-full h-full object-cover" />
            <span v-else class="text-gray-400 material-icons text-6xl">person</span>
          </div>
          <input type="file" @change="handleImageChange" accept="image/*" class="text-sm text-gray-600" />
        </div>

        <!-- Form Fields -->
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <label class="block text-gray-700 font-medium mb-2">Agency Name</label>
            <input
              v-model="form.agency_name"
              type="text"
              class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
              placeholder="Enter agency name"
            />
          </div>

          <div>
            <label class="block text-gray-700 font-medium mb-2">Phone Number</label>
            <input
              v-model="form.contact_number"
              type="tel"
              class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
              placeholder="Enter contact number"
            />
          </div>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Location Address</label>
          <input
            v-model="form.location_address"
            type="text"
            class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
            placeholder="Enter location"
          />
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
            placeholder="Describe your agency"
          ></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-6">
          <button
            type="submit"
            :disabled="loading"
            class="px-8 py-3 bg-gradient-to-r from-[#007A8C] to-[#009FB7] text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:opacity-90 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span
              v-if="loading"
              class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></span>
            {{ loading ? 'Updating...' : 'Update Profile' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Password Tab -->
    <div
      v-if="activeTab === 'password'"
      class="bg-white p-10 rounded-2xl shadow-lg space-y-6 border border-gray-100"
    >
      <h3 class="text-2xl font-semibold text-gray-800 mb-6 border-b-2 border-[#007A8C] pb-3">
        Change Password
      </h3>

      <div class="space-y-5">
        <div>
          <label class="block text-gray-700 font-medium mb-2">Current Password</label>
          <input
            v-model="passwordForm.current_password"
            type="password"
            class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
            placeholder="Enter current password"
          />
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">New Password</label>
          <input
            v-model="passwordForm.new_password"
            type="password"
            class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
            placeholder="Enter new password"
          />
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
          <input
            v-model="passwordForm.new_password_confirmation"
            type="password"
            class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base"
            placeholder="Confirm new password"
          />
        </div>

        <div class="pt-4">
          <button
            @click.prevent="updatePassword"
            :disabled="loadingPassword"
            class="px-8 py-3 bg-gradient-to-r from-[#007A8C] to-[#009FB7] text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:opacity-90 transition-all duration-300 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span
              v-if="loadingPassword"
              class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
            ></span>
            {{ loadingPassword ? 'Updating...' : 'Update Password' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const activeTab = ref("profile");
const form = ref({
  agency_name: "",
  description: "",
  location_address: "",
  contact_number: "",
});
const imageFile = ref(null);
const previewImage = ref(null);
const loading = ref(false);

const passwordForm = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});
const loadingPassword = ref(false);

const tabClass = (tab) => [
  "px-8 py-3 text-base font-semibold transition-all duration-300 border-b-4",
  activeTab.value === tab
    ? "border-[#007A8C] text-[#007A8C]"
    : "border-transparent text-gray-500 hover:text-[#009FB7] hover:border-[#009FB7]",
];

// Fetch agency info
const getUserInfo = async () => {
  try {
    const response = await axios.get("/agency-information");
    const data = response.data.agency;
    if (data) {
      form.value = {
        agency_name: data.agency_name || "",
        description: data.description || "",
        location_address: data.location_address || "",
        contact_number: data.contact_number || "",
      };
      if (data.image_path) previewImage.value = `/storage/${data.image_path}`;
    }
  } catch (error) {
    console.error(error);
  }
};

// Handle image selection
const handleImageChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    imageFile.value = file;
    previewImage.value = URL.createObjectURL(file);
  }
};

// Update profile
const updateProfile = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    Object.entries(form.value).forEach(([key, value]) => formData.append(key, value));
    if (imageFile.value) formData.append("image_path", imageFile.value);

    const response = await axios.post("/agency-information", formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    alert("Profile updated successfully!");
    if (response.data.user_info.image_path) {
      previewImage.value = `/storage/${response.data.user_info.image_path}`;
    }
  } catch (error) {
    console.error(error);
    alert("Failed to update profile.");
  } finally {
    loading.value = false;
  }
};

// Update password
const updatePassword = async () => {
  loadingPassword.value = true;
  try {
    await axios.put("/user-password", passwordForm.value);
    alert("Password updated successfully!");
    passwordForm.value = {
      current_password: "",
      new_password: "",
      new_password_confirmation: "",
    };
  } catch (error) {
    console.error(error);
    alert(error.response?.data?.message || "Failed to update password.");
  } finally {
    loadingPassword.value = false;
  }
};

onMounted(getUserInfo);
</script>
