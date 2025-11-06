<template>
    <div class="w-full flex flex-col gap-6">
        <!-- Write a review -->
        <div class="bg-[#f0f9ff] rounded-xl p-5 shadow-md">
            <h2 class="text-2xl font-bold text-[#1a3c5a] mb-3">Write a Review</h2>

            <textarea
                v-model="newReview"
                placeholder="Share your experience about this spot..."
                class="w-full h-32 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-sky-400 outline-none resize-none"
            ></textarea>

            <button
                @click="submitReview"
                class="mt-3 bg-[#1a3c5a] text-white font-semibold py-2 px-5 rounded-lg hover:bg-[#2a4f6f] transition-all duration-300"
            >
                Submit Review
            </button>
        </div>

        <!-- Display reviews -->
        <div class="bg-white rounded-xl p-5 shadow-md">
            <h2 class="text-2xl font-bold text-[#1a3c5a] mb-5">Customer Reviews</h2>

            <div v-if="reviews.length > 0" class="flex flex-col gap-5">
                <div
                    v-for="(review, index) in reviews"
                    :key="index"
                    class="border-b border-slate-200 pb-3"
                >
                    <h3 class="text-lg font-semibold text-[#1a3c5a]">
                        {{ formatName(review.user) }}
                    </h3>
                    <p class="text-slate-600">{{ review.review }}</p>
                    <span class="text-xs text-slate-400">
                        {{ new Date(review.created_at).toLocaleString() }}
                    </span>
                </div>
            </div>

            <div v-else class="text-center text-slate-500">
                No reviews yet. Be the first to share your experience!
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue'
    import axios from 'axios'

    const props = defineProps({
        spotId: Number
    })

    const reviews = ref([])
    const newReview = ref('')

    // ✅ Fetch reviews from backend
    const getReviews = async () => {
        try {
            const res = await axios.get(`/spots/${props.spotId}/reviews`)
            reviews.value = res.data
        } catch (err) {
            console.error('Error fetching reviews:', err)
        }
    }

    // ✅ Submit new review
    const submitReview = async () => {
        if (!newReview.value.trim()) {
            alert('Please write a review before submitting.')
            return
        }

        try {
            const res = await axios.post(`/spots/${props.spotId}/reviews`, {
                review: newReview.value
            })

            // Add new review to top of list
            reviews.value.unshift(res.data.review)
            newReview.value = ''
            alert('Review submitted successfully!')
        } catch (err) {
            console.error('Error submitting review:', err)
            alert('Failed to submit review.')
        }
    }

    // ✅ Format full name for display
    const formatName = (user) => {
        if (!user) return 'Anonymous'
        if (user.user_info) {
            return `${user.user_info.firstName} ${user.user_info.lastName}`
        }
        return 'Anonymous'
    }

    onMounted(() => {
        getReviews()
    })
</script>
