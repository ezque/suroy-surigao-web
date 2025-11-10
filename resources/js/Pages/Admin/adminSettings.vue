<template>
  <div class="max-w-2xl mx-auto mt-24 p-10 sm:p-12 bg-gradient-to-br from-white via-white to-teal-50 rounded-3xl shadow-2xl space-y-8 border border-gray-100">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 border-b-2 border-[#007A8C] pb-4">
      Change Password
    </h2>

    <form @submit.prevent="updatePassword" class="space-y-6 sm:space-y-7">
      <!-- Current Password -->
      <div>
        <label class="block text-gray-700 font-medium mb-2">Current Password</label>
        <input
          type="password"
          v-model="form.current_password"
          class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base sm:text-lg"
          placeholder="Enter current password"
        />
        <p v-if="errors.current_password" class="text-red-500 text-sm mt-1">
          {{ errors.current_password }}
        </p>
      </div>

      <!-- New Password -->
      <div>
        <label class="block text-gray-700 font-medium mb-2">New Password</label>
        <input
          type="password"
          v-model="form.new_password"
          class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base sm:text-lg"
          placeholder="Enter new password"
        />
        <p v-if="errors.new_password" class="text-red-500 text-sm mt-1">
          {{ errors.new_password }}
        </p>
      </div>

      <!-- Confirm New Password -->
      <div>
        <label class="block text-gray-700 font-medium mb-2">Confirm New Password</label>
        <input
          type="password"
          v-model="form.new_password_confirmation"
          class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009FB7] focus:border-[#009FB7] transition-all text-base sm:text-lg"
          placeholder="Confirm new password"
        />
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="w-full bg-gradient-to-r from-[#007A8C] to-[#009FB7] text-white font-semibold py-3.5 rounded-lg shadow-md hover:shadow-xl hover:opacity-90 transition-all duration-300 flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed text-base sm:text-lg"
        :disabled="loading"
      >
        <span
          v-if="loading"
          class="inline-block w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"
        ></span>
        {{ loading ? "Updating..." : "Update Password" }}
      </button>
    </form>

    <!-- Success / Error Messages -->
    <p
      v-if="successMessage"
      class="text-green-600 font-medium mt-5 text-center bg-green-50 py-3 rounded-lg text-base sm:text-lg"
    >
      ✅ {{ successMessage }}
    </p>
    <p
      v-if="errorMessage"
      class="text-red-600 font-medium mt-5 text-center bg-red-50 py-3 rounded-lg text-base sm:text-lg"
    >
      ❌ {{ errorMessage }}
    </p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const form = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
});

const errors = ref({});
const successMessage = ref("");
const errorMessage = ref("");
const loading = ref(false);

const updatePassword = async () => {
  errors.value = {};
  successMessage.value = "";
  errorMessage.value = "";
  loading.value = true;

  try {
    const response = await axios.put("/user-password", form.value);
    successMessage.value = response.data.message || "Password updated successfully!";
    form.value = { current_password: "", new_password: "", new_password_confirmation: "" };
  } catch (err) {
    if (err.response && err.response.status === 422) {
      errors.value = err.response.data.errors || {};
      errorMessage.value = err.response.data.message || "Validation error.";
    } else {
      errorMessage.value = "Something went wrong. Please try again.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
input:focus {
  transition: all 0.3s ease;
}
</style>
