<template>
    <div class="w-full h-full bg-gradient-to-b from-cyan-50 to-white p-4 md:p-6 overflow-y-auto">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold text-teal-900">Manage Posts</h1>
            <button
                @click="goToAddPost"
                class="inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-600 text-white font-medium px-4 py-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
            >
                <i class="material-icons text-lg">add</i>
                Add New Post
            </button>
        </header>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Loading / Empty State -->
            <div v-if="!allBlogs || allBlogs.length === 0" class="p-12 text-center">
                <i class="material-icons text-6xl text-gray-300 mb-4">article</i>
                <p class="text-lg text-gray-600 mb-4">No posts yet.</p>
                <button
                    @click="goToAddPost"
                    class="text-teal-600 hover:text-teal-700 font-medium underline"
                >
                    Create your first post
                </button>
            </div>

            <!-- Table -->
            <div v-else class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-teal-700 text-white sticky top-0">
                    <tr>
                        <th class="px-4 py-3 font-semibold">ID</th>
                        <th class="px-4 py-3 font-semibold">Title</th>
                        <th class="px-4 py-3 font-semibold">URL</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <!-- Skeleton rows while loading (optional) -->
                    <tr v-if="isLoading" v-for="n in 4" :key="n" class="animate-pulse">
                        <td class="px-4 py-4"><div class="h-4 bg-gray-200 rounded"></div></td>
                        <td class="px-4 py-4"><div class="h-4 bg-gray-200 rounded w-48"></div></td>
                        <td class="px-4 py-4"><div class="h-4 bg-gray-200 rounded w-32"></div></td>
                        <td class="px-4 py-4"><div class="h-6 w-16 bg-gray-200 rounded-full"></div></td>
                        <td class="px-4 py-4"><div class="h-8 w-20 bg-gray-200 rounded"></div></td>
                    </tr>

                    <!-- Real rows -->
                    <tr
                        v-for="post in allBlogs"
                        :key="post.id"
                        class="hover:bg-teal-50 transition-colors"
                    >
                        <td class="px-4 py-4 text-sm font-medium text-gray-800">{{ post.id }}</td>
                        <td class="px-4 py-4 font-medium text-gray-900">{{ post.title }}</td>
                        <td class="px-4 py-4">
                            <a
                                :href="post.url"
                                target="_blank"
                                rel="noopener"
                                class="text-teal-600 hover:underline break-all text-sm"
                            >
                                {{ post.url }}
                            </a>
                        </td>
                        <td class="px-4 py-4">
                <span
                    :class="{
                    'bg-emerald-100 text-emerald-800': post.status === 'Published',
                    'bg-amber-100 text-amber-800': post.status === 'Draft',
                    'bg-gray-100 text-gray-800': !post.status,
                  }"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                >
                  {{ post.status || '—' }}
                </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Edit -->
                                <button
                                    @click="editPost(post)"
                                    class="p-2 text-white bg-yellow-500 hover:bg-yellow-600 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2"
                                    aria-label="Edit post"
                                >
                                    <i class="material-icons text-sm">edit</i>
                                </button>

                                <!-- Delete -->
                                <button
                                    @click="deletePost(post.id)"
                                    class="p-2 text-white bg-red-500 hover:bg-red-600 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2"
                                    aria-label="Delete post"
                                >
                                    <i class="material-icons text-sm">delete</i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div
                    v-if="showEditModal"
                    class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
                >
                    <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-xl">
                        <h2 class="text-xl font-bold mb-4 text-teal-800">
                            Edit Blog Post
                        </h2>

                        <!-- Title -->
                        <label class="block text-sm font-medium mb-1">Title</label>
                        <input
                            v-model="editForm.title"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg p-2 mb-3"
                        />

                        <!-- URL -->
                        <label class="block text-sm font-medium mb-1">URL</label>
                        <input
                            v-model="editForm.url"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg p-2 mb-3"
                        />

                        <!-- Buttons -->
                        <div class="flex justify-end gap-2">
                            <button
                                @click="closeEditModal"
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg"
                            >
                                Cancel
                            </button>

                            <button
                                @click="updatePost"
                                class="px-4 py-2 bg-teal-700 text-white hover:bg-teal-600 rounded-lg"
                            >
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { computed, ref } from 'vue';
    import axios from "axios";
    // Props
    const props = defineProps({
        allBlogs: { type: Array, default: () => [] },
        isLoading: { type: Boolean, default: false },
    });

    // Emits
    const emit = defineEmits(['selectPage']);
    const showEditModal = ref(false);
    const goToAddPost = () => emit('selectPage', 'addPost');

    const editPost = (post) => {
        editForm.value = { ...post };
        showEditModal.value = true;
    };
    const editForm = ref({
        id: null,
        title: "",
        url: "",
    });
    const closeEditModal = () => {
        showEditModal.value = false;
    };
    const updatePost = async () => {
        try {
            await axios.put(`/update-blog/${editForm.value.id}`, editForm.value);

            // Update table instantly
            const index = props.allBlogs.findIndex(
                (b) => b.id === editForm.value.id
            );
            if (index !== -1) {
                props.allBlogs[index] = { ...editForm.value };
            }

            alert("Post updated successfully!");
            closeEditModal();
        } catch (error) {
            console.error(error);
            alert("Failed to update post.");
        }
    };

    const deletePost = async (id) => {
        if (!confirm('Are you sure you want to delete this post?')) {
            return;
        }

        try {
            await axios.delete(`/delete-blog/${id}`,{
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            // Remove deleted blog from the table
            props.allBlogs.splice(
                props.allBlogs.findIndex(blog => blog.id === id),
                1
            );

            alert("Blog deleted successfully!");
        } catch (error) {
            console.error(error);
            alert("Failed to delete blog.");
        }
    };
</script>
