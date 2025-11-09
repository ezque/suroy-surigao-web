<template>
    <div class="max-w-md mx-auto p-6 bg-white rounded-2xl shadow-lg space-y-6">
        <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>

        <form @submit.prevent="updatePassword" class="space-y-4">
            <!-- Current Password -->
            <div>
                <label class="block text-gray-700 mb-1">Current Password</label>
                <input
                    type="password"
                    v-model="form.current_password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter current password"
                />
                <p v-if="errors.current_password" class="text-red-500 text-sm mt-1">{{ errors.current_password }}</p>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-gray-700 mb-1">New Password</label>
                <input
                    type="password"
                    v-model="form.new_password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Enter new password"
                />
                <p v-if="errors.new_password" class="text-red-500 text-sm mt-1">{{ errors.new_password }}</p>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label class="block text-gray-700 mb-1">Confirm New Password</label>
                <input
                    type="password"
                    v-model="form.new_password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Confirm new password"
                />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-purple-700 to-blue-500 text-white font-semibold py-2 rounded-lg shadow-md hover:translate-y-[-1px] hover:shadow-lg transition-all duration-300"
                :disabled="loading"
            >
                {{ loading ? "Updating..." : "Update Password" }}
            </button>
        </form>

        <!-- Success / Error Messages -->
        <p v-if="successMessage" class="text-green-600 font-medium mt-2">{{ successMessage }}</p>
        <p v-if="errorMessage" class="text-red-600 font-medium mt-2">{{ errorMessage }}</p>
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
            successMessage.value = response.data.message;
            form.value.current_password = "";
            form.value.new_password = "";
            form.value.new_password_confirmation = "";
        } catch (err) {
            if (err.response && err.response.status === 422) {
                errors.value = err.response.data.errors || {};
                errorMessage.value = err.response.data.message || "Validation error";
            } else {
                errorMessage.value = "Something went wrong. Try again.";
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
