<template>
    <div
        class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 ease-out cursor-pointer group border border-gray-100"
    >
        <!-- Spot Image -->
        <div class="relative overflow-hidden bg-gray-50">
            <img
                v-if="spot.images && spot.images.length > 0"
                :src="`/storage/${spot.images[0].spot_image}`"
                :alt="spot.spot_name"
                class="w-full h-52 object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <img
                v-else
                src="/public/images/logo/logo.png"
                alt="No Image Available"
                class="w-full h-52 object-contain bg-gray-50 p-6 transition-transform duration-700 group-hover:scale-110"
            />

            <!-- Status Badge -->
            <div
                class="absolute top-3 left-3 px-3 py-1.5 rounded-full text-white text-xs font-bold tracking-wider shadow-lg flex items-center gap-1"
                :class="spot.status === 'active' ? 'bg-emerald-500' : 'bg-rose-500'"
            >
                <i class="fas" :class="spot.status === 'active' ? 'fa-check-circle' : 'fa-times-circle'"></i>
                {{ spot.status === 'active' ? 'Active' : 'Inactive' }}
            </div>
        </div>

        <!-- Spot Info -->
        <div class="p-5 space-y-4">
            <!-- Spot Name -->
            <h3
                class="text-xl font-bold text-gray-900 line-clamp-1 group-hover:text-blue-600 transition-colors duration-200"
            >
                {{ spot.spot_name }}
            </h3>

            <!-- Rating + Review Count -->
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center gap-1">
                    <!-- Star Ratings -->
                    <div class="flex">
                        <template v-for="n in 5" :key="n">
                            <i
                                v-if="n <= fullStars"
                                class="material-icons text-yellow-400 text-base"
                            >star</i>
                            <i
                                v-else-if="n === fullStars + 1 && hasHalfStar"
                                class="material-icons text-yellow-400 text-base"
                            >star_half</i>
                            <i
                                v-else
                                class="material-icons text-gray-300 text-base"
                            >star</i>
                        </template>
                    </div>
                    <span class="ml-1.5 font-semibold text-gray-800">
                        {{ averageRating.toFixed(1) }}
                    </span>
                </div>

                <!-- Review Count -->
                <div class="flex items-center gap-1 text-gray-500">
                    <i class="fas fa-comment-dots text-sm"></i>
                    <span class="font-medium">{{ spot.reviews_count || 0 }} reviews</span>
                </div>
            </div>

            <!-- Location -->
            <div class="flex items-center gap-2 text-gray-600 text-sm">
                <i class="fas fa-map-marker-alt text-blue-500 text-base"></i>
                <span class="line-clamp-1">{{ spot.location || 'Unknown location' }}</span>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 pt-2">
                <button
                    @click="$emit('edit', spot)"
                    class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-lg flex-1 group cursor-pointer"
                >
                    <i class="fas fa-edit text-xs"></i>
                    Edit
                </button>

                <button
                    @click="$emit('view', spot)"
                    class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-lg flex-1 group cursor-pointer"
                >
                    <i class="fas fa-eye text-xs"></i>
                    View
                </button>

                <button
                    @click="$emit('delete', spot)"
                    class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-lg flex-1 group cursor-pointer"
                >
                    <i class="fas fa-trash text-xs"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    spot: {
        type: Object,
        required: true
    }
});

// Compute average rating safely
const averageRating = computed(() => {
    const rating = parseFloat(props.spot.average_rating);
    return isNaN(rating) ? 0 : rating;
});

// Determine full and half stars
const fullStars = computed(() => Math.floor(averageRating.value));
const hasHalfStar = computed(() => averageRating.value - fullStars.value >= 0.5);
</script>

<style scoped>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>
