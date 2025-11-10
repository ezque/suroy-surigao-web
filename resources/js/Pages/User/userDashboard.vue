<template>
    <div class="w-full h-full bg-gradient-to-b from-cyan-50 to-blue-50 p-6 md:p-8 lg:p-12 xl:p-16 overflow-y-auto flex flex-col gap-8">
        <!-- Welcome Header -->
        <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-teal-700 to-teal-900 flex items-center justify-center shadow-lg">
                    <span class="text-2xl md:text-3xl">User</span>
                </div>
                <div class="max-w-lg">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-teal-900">Welcome back, Traveler!</h1>
                    <p class="text-sm md:text-base text-gray-700 mt-1">
                        Ready to explore Surigao's hidden gems? Check out our latest recommendations below.
                    </p>
                </div>
            </div>
        </header>

        <!-- Mini Browser Section -->
        <section class="w-full max-w-6xl mx-auto">
            <div class="mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-teal-900">Live Blog Preview</h2>
                <p class="text-sm md:text-base text-gray-600 mt-1">
                    {{ hasBlogs ? 'Previewing your latest blog post.' : 'No blog posts available.' }}
                </p>
            </div>

            <!-- Browser Toolbar -->
            <div class="bg-gray-100 p-3 rounded-t-2xl border border-gray-300 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <!-- Single Tab -->
                <div class="flex gap-1">
                    <button
                        :disabled="!hasBlogs"
                        class="px-4 py-2 rounded-full font-medium transition-all"
                        :class="hasBlogs
                            ? 'bg-teal-900 text-white'
                            : 'bg-white text-gray-700 opacity-50 cursor-not-allowed'"
                    >
                        Travel Blog
                    </button>
                </div>

                <!-- Address Bar -->
                <div class="flex items-center bg-white rounded-full px-3 py-1.5 shadow-inner flex-1 max-w-md">
                    <span class="text-xs text-gray-500 mr-2">Web</span>
                    <input
                        :value="displayUrl"
                        readonly
                        class="flex-1 text-xs text-gray-700 bg-transparent outline-none truncate"
                        title="Current URL"
                    />
                    <button @click="refreshContent" class="ml-2 text-teal-700 hover:text-teal-900">
                        Refresh
                    </button>
                </div>

                <!-- Zoom Button -->
                <button
                    @click="toggleZoom"
                    class="px-3 py-1.5 bg-white rounded-full text-sm font-medium hover:bg-gray-50 transition-colors"
                >
                    Zoom {{ zoomLevel }}%
                </button>
            </div>

            <!-- Mini Browser Window -->
            <div class="bg-white rounded-b-2xl shadow-2xl overflow-hidden border-x border-b border-gray-300">
                <!-- Loading State -->
                <div v-if="isLoading" class="h-96 md:h-[550px] flex items-center justify-center bg-gray-50">
                    <div class="text-center">
                        <div class="inline-block animate-spin w-8 h-8 border-4 border-teal-600 border-t-transparent rounded-full"></div>
                        <p class="mt-3 text-gray-600">Loading preview...</p>
                    </div>
                </div>

                <!-- No Blog Posts -->
                <div v-else-if="!hasBlogs" class="h-96 md:h-[550px] flex flex-col items-center justify-center bg-yellow-50 p-6 text-center">
                    <p class="text-yellow-800 font-medium">No blog posts available</p>
                    <p class="text-sm text-yellow-700 mt-2">Add a post in the admin panel to preview it here.</p>
                </div>

                <!-- Error State -->
                <div v-else-if="hasError" class="h-96 md:h-[550px] flex flex-col items-center justify-center bg-red-50 p-6 text-center">
                    <p class="text-red-700 font-medium">Failed to load content</p>
                    <button @click="refreshContent" class="mt-3 text-teal-700 underline hover:text-teal-800">
                        Try Again
                    </button>
                </div>

                <!-- iFrame Preview -->
                <iframe
                    v-else
                    :src="blogUrl"
                    title="Your Blog Post Preview"
                    class="w-full h-96 md:h-[550px] border-0"
                    :style="{ transform: `scale(${zoomLevel / 100})`, transformOrigin: 'top left', width: zoomLevel !== 100 ? '133%' : '100%' }"
                    sandbox="allow-same-origin allow-scripts allow-popups allow-forms"
                    @load="onIframeLoad"
                    @error="hasError = true"
                ></iframe>
            </div>
        </section>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue'

// === Props from Laravel ===
const props = defineProps({
    blogsURL: {
        type: Array,
        default: () => []
    }
})

// === State ===
const isLoading = ref(true)
const hasError = ref(false)
const zoomLevel = ref(100)

// === Computed Values ===
const hasBlogs = computed(() => props.blogsURL.length > 0)
const blogUrl = computed(() => hasBlogs.value ? props.blogsURL[0] : null)
const displayUrl = computed(() => {
    if (blogUrl.value) {
        return blogUrl.value.length > 50 ? blogUrl.value.substring(0, 50) + '...' : blogUrl.value
    }
    return 'No blog post URL available'
})

// === iFrame Handlers ===
const onIframeLoad = () => {
    isLoading.value = false
    hasError.value = false
}

// === Actions ===
const refreshContent = () => {
    isLoading.value = true
    hasError.value = false
    nextTick(() => {
        const iframe = document.querySelector('iframe')
        if (iframe) {
            iframe.src = iframe.src // Force reload
        }
    })
}

const toggleZoom = () => {
    zoomLevel.value = zoomLevel.value === 100 ? 75 : zoomLevel.value === 75 ? 50 : 100
}

// === Lifecycle ===
onMounted(() => {
    setTimeout(() => {
        if (isLoading.value) isLoading.value = false
    }, 1500)
})
</script>
