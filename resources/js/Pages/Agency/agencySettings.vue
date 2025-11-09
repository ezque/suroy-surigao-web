<template>
    <div class="p-6 max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-8 text-gray-800">Account Settings</h2>

        <!-- Tab Navigation -->
        <div class="flex border-b border-gray-200 mb-8">
            <button @click="activeTab = 'profile'" :class="tabClass('profile')">Profile</button>
            <button @click="activeTab = 'password'" :class="tabClass('password')">Password</button>
        </div>

        <!-- Profile Tab -->
        <div v-if="activeTab === 'profile'" class="space-y-6">
            <form @submit.prevent="updateProfile" class="bg-white p-8 rounded-2xl shadow-lg space-y-6">
                <!-- Image Upload -->
                <div class="flex flex-col items-center gap-4">
                    <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                        <img v-if="previewImage" :src="previewImage" class="w-full h-full object-cover" />
                        <span v-else class="text-gray-400 material-icons text-5xl">person</span>
                    </div>
                    <input type="file" @change="handleImageChange" accept="image/*" />
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agency Name</label>
                        <input v-model="form.agency_name" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input v-model="form.contact_number" type="tel" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Address</label>
                    <input v-model="form.location_address" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea v-model="form.description" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"></textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" :disabled="loading" class="px-6 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-2">
                        <span v-if="loading" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        {{ loading ? 'Updating...' : 'Update Profile' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Tab -->
        <div v-if="activeTab === 'password'" class="bg-white p-8 rounded-2xl shadow-lg space-y-5">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Change Password</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input v-model="passwordForm.current_password" type="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input v-model="passwordForm.new_password" type="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                <input v-model="passwordForm.new_password_confirmation" type="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"/>
            </div>
            <div class="pt-4">
                <button @click.prevent="updatePassword" :disabled="loadingPassword" class="px-6 py-2.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span v-if="loadingPassword" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    {{ loadingPassword ? 'Updating...' : 'Update Password' }}
                </button>
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

    const passwordForm = ref({ current_password: "", new_password: "", new_password_confirmation: "" });
    const loadingPassword = ref(false);

    const tabClass = (tab) => [
        'px-6 py-3 font-medium text-sm transition-colors duration-200 border-b-2 -mb-px',
        activeTab.value === tab ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-700'
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
                // Use /storage/ + image_path for preview
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
            if (imageFile.value) formData.append('image_path', imageFile.value);

            const response = await axios.post("/agency-information", formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });

            alert("Profile updated successfully!");
            // Update preview after save using /storage/ + returned path
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
            passwordForm.value = { current_password: "", new_password: "", new_password_confirmation: "" };
        } catch (error) {
            console.error(error);
            alert(error.response?.data?.message || "Failed to update password.");
        } finally { loadingPassword.value = false; }
    };

    onMounted(getUserInfo);
</script>
