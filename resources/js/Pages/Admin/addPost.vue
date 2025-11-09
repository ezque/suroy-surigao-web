<template>
    <div class="w-full h-full bg-gradient-to-b from-cyan-50 to-white p-5 overflow-y-auto">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-teal-900">Add New Post</h2>
            <button
                @click="goBackToManagePosts"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors"
            >
                <i class="material-icons text-lg">arrow_back</i> Back to Manage Posts
            </button>
        </div>

        <!-- Form Container -->
        <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md p-6">
            <form @submit.prevent="submitPost" class="space-y-5">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-teal-900 font-medium mb-1.5">Title</label>
                    <input
                        type="text"
                        id="title"
                        v-model="post.title"
                        placeholder="Enter Post Title"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-base focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-600 transition"
                    />
                </div>

                <!-- URL -->
                <div>
                    <label for="url" class="block text-teal-900 font-medium mb-1.5">URL</label>
                    <input
                        type="url"
                        id="url"
                        v-model="post.url"
                        placeholder="Enter Post URL"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-base focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-600 transition"
                    />
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="bg-teal-700 hover:bg-teal-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 font-medium transition-colors"
                    >
                        <i class="material-icons text-lg">save</i> Save Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import axios from 'axios';

    const emit = defineEmits(['selectPage']);

    const post = ref({
        title: '',
        url: '',
    });

    const submitPost = async () => {
        try {
            const token = localStorage.getItem('authToken');

            const response = await axios.post(
                '/add-blog',
                post.value,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json',
                    },
                    withCredentials: true,
                }
            );

            console.log('Post added:', response.data);
            alert('Post successfully added!');

            post.value = { title: '', url: '' };
            goBackToManagePosts();
        } catch (error) {
            console.error('Error submitting post:', error.response?.data || error);
            alert('Failed to add post. Please try again.');
        }
    };

    const goBackToManagePosts = () => {
        emit('selectPage', 'managePost');
    };
</script>
