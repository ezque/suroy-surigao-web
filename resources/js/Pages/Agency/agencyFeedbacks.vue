<template>
    <div class="min-h-screen overflow-y-auto bg-slate-50 p-6 md:p-8">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-slate-800">Customer Reviews & Feedback</h1>
            <p class="mt-1 text-slate-600">Read and respond to feedback from your customers.</p>
        </div>

        <!-- Search Section -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by customer name or message..."
                    class="w-full rounded-xl border border-slate-300 bg-white py-3 pl-10 pr-4 text-sm shadow-sm transition-all focus:border-blue-400 focus:outline-none focus:ring-4 focus:ring-blue-400/20"
                />
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
            <!-- Total Reviews -->
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-green-50 p-6 shadow-sm">
                <div>
                    <p class="text-sm font-medium text-green-800">Total Reviews</p>
                    <p class="text-4xl font-bold text-green-900">{{ stats.totalReviews }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-200 text-green-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
            </div>

            <!-- Response Rate -->
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-purple-50 p-6 shadow-sm">
                <div>
                    <p class="text-sm font-medium text-purple-800">Response Rate</p>
                    <p class="text-4xl font-bold text-purple-900">{{ stats.responseRate }}%</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-200 text-purple-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-amber-50 p-6 shadow-sm">
                <div>
                    <p class="text-sm font-medium text-amber-800">Average Rating</p>
                    <p class="text-4xl font-bold text-amber-900">{{ stats.averageRating }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-200 text-amber-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Reviews Container -->
        <div class="space-y-4">
            <div
                v-for="review in filteredReviews"
                :key="review.id"
                class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition-all"
                :class="{ 'border-blue-400 shadow-lg': selectedReview === review.id }"
            >
                <!-- Review Content -->
                <div class="p-6">
                    <div class="flex gap-4">
                        <!-- Avatar -->
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-purple-400 text-sm font-semibold text-white">
                            {{ review.avatar }}
                        </div>

                        <!-- Review Info -->
                        <div class="flex-1">
                            <div class="mb-2 flex flex-wrap items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ review.author }}</h3>
                                    <p class="text-sm text-gray-500">{{ review.date }}</p>
                                </div>
                                <div class="flex gap-1">
                                    <svg
                                        v-for="i in 5"
                                        :key="i"
                                        class="h-4 w-4"
                                        :fill="i <= review.rating ? '#fbbf24' : 'none'"
                                        :stroke="i <= review.rating ? '#fbbf24' : '#d1d5db'"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                            </div>

                            <p class="mb-4 text-gray-700 leading-relaxed">{{ review.message }}</p>

                            <!-- Action Buttons or Status -->
                            <div v-if="review.status === 'pending'" class="flex gap-3">
                                <button @click="handleReply(review.id)" class="flex items-center gap-2 rounded-full bg-blue-500 px-6 py-2 text-sm font-medium text-white transition hover:bg-blue-600">
                                    Reply
                                </button>
                                <button @click="handleHide(review.id)" class="flex items-center gap-2 rounded-full bg-gray-200 px-6 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300">
                                    Hide
                                </button>
                            </div>

                            <div v-if="review.status === 'replied'" class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-800">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Replied
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reply Section -->
                <div v-if="selectedReview === review.id" class="border-t border-gray-200 bg-gray-50 p-6">
                    <div class="flex gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-pink-500 to-purple-400 text-sm font-semibold text-white">
                            A
                        </div>
                        <div class="flex-1">
              <textarea
                  v-model="replyText"
                  placeholder="Write your response..."
                  rows="3"
                  class="w-full resize-y rounded-lg border border-gray-300 p-3 text-sm focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400/20"
              ></textarea>
                            <div class="mt-3 flex justify-end gap-3">
                                <button @click="handleCloseReply" class="flex items-center gap-2 rounded-full bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-300">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Cancel
                                </button>
                                <button @click="handleSubmitReply(review.id)" class="flex items-center gap-2 rounded-full bg-blue-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <line x1="22" y1="2" x2="11" y2="13"></line>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                    </svg>
                                    Send Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';

    const searchQuery = ref('');
    const selectedReview = ref(null);
    const replyText = ref('');

    const reviews = ref([
        {
            id: 1,
            author: "John Anderson",
            avatar: "JA",
            message: "The store is providing the facilities are on range, and the front view unforgettable!",
            date: "2 days ago",
            rating: 5,
            status: "pending"
        },
        {
            id: 2,
            author: "Sarah Mitchell",
            avatar: "SM",
            message: "The area is breathing! The facilities are on range, and the front tool were unforgettable!",
            date: "3 days ago",
            rating: 4,
            status: "pending"
        },
        {
            id: 3,
            author: "Michael Chen",
            avatar: "MC",
            message: "An absolutely fantastic experience from start to finish. Highly recommended!",
            date: "5 days ago",
            rating: 5,
            status: "replied"
        },
        {
            id: 4,
            author: "Emily Rodriguez",
            avatar: "ER",
            message: "Good tour, but the bus was a bit crowded. The guide was very knowledgeable.",
            date: "1 week ago",
            rating: 3,
            status: "pending"
        },
        {
            id: 5,
            author: "David Lee",
            avatar: "DL",
            message: "Our family had a wonderful time. Thank you for the great memories!",
            date: "2 weeks ago",
            rating: 5,
            status: "replied"
        }
    ]);

    const filteredReviews = computed(() => {
        if (!searchQuery.value) return reviews.value;
        const query = searchQuery.value.toLowerCase();
        return reviews.value.filter(
            (review) =>
                review.author.toLowerCase().includes(query) ||
                review.message.toLowerCase().includes(query)
        );
    });

    const stats = computed(() => {
        const total = reviews.value.length;
        const replied = reviews.value.filter((r) => r.status === 'replied').length;
        const responseRate = total > 0 ? Math.round((replied / total) * 100) : 0;
        const totalRating = reviews.value.reduce((sum, r) => sum + r.rating, 0);
        const averageRating = total > 0 ? (totalRating / total).toFixed(1) : 'N/A';

        return { totalReviews: total, responseRate, averageRating };
    });

    const handleReply = (reviewId) => {
        selectedReview.value = reviewId;
        replyText.value = '';
    };

    const handleCloseReply = () => {
        selectedReview.value = null;
        replyText.value = '';
    };

    const handleSubmitReply = (reviewId) => {
        if (replyText.value.trim()) {
            const reviewIndex = reviews.value.findIndex((r) => r.id === reviewId);
            if (reviewIndex !== -1) {
                reviews.value[reviewIndex].status = 'replied';
            }
            selectedReview.value = null;
            replyText.value = '';
        }
    };

    const handleHide = (reviewId) => {
        reviews.value = reviews.value.filter((review) => review.id !== reviewId);
    };
</script>
