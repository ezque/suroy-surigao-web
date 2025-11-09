<template>
    <div class="p-6 space-y-6">
        <!-- Back Button -->
        <button
            @click="goBack"
            class="flex items-center gap-2 text-gray-700 font-medium bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-lg transition-colors duration-200"
        >
            <i class="fas fa-arrow-left"></i>
            Back
        </button>

        <!-- Spot Info -->
        <div>
            <h2 class="text-2xl font-bold mb-2">{{ spot.spot_name }}</h2>
            <p class="text-gray-600">{{ spot.location }}</p>
            <div class="flex items-center mt-1">
                <div class="flex mr-2">
                    <template v-for="(star, index) in 5" :key="index">
                        <!-- Full Star -->
                        <svg
                            v-if="averageRating >= index + 1"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            class="w-5 h-5 text-yellow-400"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.378 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.379-2.455a1 1 0 00-1.175 0l-3.379 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.288-3.966z"
                            />
                        </svg>

                        <!-- Half Star -->
                        <svg
                            v-else-if="averageRating > index && averageRating < index + 1"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            fill="currentColor"
                            class="w-5 h-5 text-yellow-400"
                        >
                            <defs>
                                <linearGradient id="half">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="transparent" />
                                </linearGradient>
                            </defs>
                            <path
                                fill="url(#half)"
                                d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                            />
                        </svg>

                        <!-- Empty Star -->
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            stroke="currentColor"
                            class="w-5 h-5 text-gray-300"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.378 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.379-2.455a1 1 0 00-1.175 0l-3.379 2.455c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.288-3.966z"
                            />
                        </svg>
                    </template>
                </div>
                <p class="text-gray-700 text-sm">
                    {{ averageRating }} ({{ spot.reviews_count || 0 }} reviews)
                </p>
            </div>
        </div>

        <!-- ✅ Spot Images Slideshow -->
        <div class="relative w-full h-64 rounded-lg overflow-hidden mt-4">
            <transition name="fade" mode="out-in">
                <img
                    :key="currentImageIndex"
                    :src="`/storage/${spot.images[currentImageIndex].spot_image}`"
                    class="w-full h-full object-cover rounded-lg"
                />
            </transition>

            <!-- Optional Dots -->
            <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex gap-2">
                <span
                    v-for="(image, index) in spot.images"
                    :key="index"
                    class="w-3 h-3 rounded-full cursor-pointer transition-all duration-300"
                    :class="{
                        'bg-yellow-400 scale-110': index === currentImageIndex,
                        'bg-gray-300': index !== currentImageIndex
                    }"
                    @click="currentImageIndex = index"
                ></span>
            </div>
        </div>

        <!-- Reviews Section -->
        <div v-if="spot.reviews && spot.reviews.length > 0">
            <h3 class="text-xl font-semibold mb-4">Reviews</h3>
            <div class="space-y-4">
                <div
                    v-for="(review, index) in spot.reviews"
                    :key="index"
                    class="p-4 bg-gray-50 rounded-lg shadow-sm"
                >
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold capitalize">
                            {{ review.user?.user_info?.lastName || 'Anonymous' }}
                        </span>
                        <span class="text-xs text-gray-400">{{ formatDate(review.created_at) }}</span>
                    </div>
                    <p class="text-gray-700">{{ review.review }}</p>
                </div>
            </div>
        </div>
        <div v-else class="text-gray-500">No reviews yet.</div>
    </div>
</template>

<script setup>
    import { computed, ref, onMounted, onBeforeUnmount } from "vue";

    const emit = defineEmits(["back"]);

    const props = defineProps({
        spot: {
            type: Object,
            required: true,
        },
    });

    // ✅ Rating logic
    const averageRating = computed(() => {
        const rating = parseFloat(props.spot.average_rating);
        return isNaN(rating) ? 0 : rating;
    });

    // ✅ Format date
    const formatDate = (dateString) => {
        if (!dateString) return "";
        const options = { year: "numeric", month: "short", day: "numeric" };
        return new Date(dateString).toLocaleDateString(undefined, options);
    };

    // ✅ Back button
    const goBack = () => emit("back");

    // ✅ Slideshow logic
    const currentImageIndex = ref(0);
    let interval = null;

    onMounted(() => {
        if (props.spot.images && props.spot.images.length > 1) {
            interval = setInterval(() => {
                currentImageIndex.value =
                    (currentImageIndex.value + 1) % props.spot.images.length;
            }, 5000); // changes every 5 seconds
        }
    });

    onBeforeUnmount(() => {
        if (interval) clearInterval(interval);
    });
</script>

<style scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.8s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>
