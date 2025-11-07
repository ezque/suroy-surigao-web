<template>
    <div class="reviewsAndFeedbacks-body">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <h1 class="page-title">Suroy Surigao Reviews</h1>
                <p class="page-subtitle">Manage tourist feedback and reviews for Surigao attractions</p>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card stat-card-blue">
                <div class="stat-content">
                    <div>
                        <p class="stat-label">Total Reviews</p>
                        <p class="stat-value">{{ totalReviews }}</p>
                        <p class="stat-trend">From {{ totalTourists }} tourists</p>
                    </div>
                    <div class="stat-icon stat-icon-blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-green">
                <div class="stat-content">
                    <div>
                        <p class="stat-label">Average Rating</p>
                        <p class="stat-value">{{ averageRating }}/5</p>
                        <p class="stat-trend positive">Excellent</p>
                    </div>
                    <div class="stat-icon stat-icon-green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tourism Spots Grid -->
        <div class="spots-section">
            <div class="spots-header">
                <h3 class="spots-title">Surigao Tourism Spots</h3>
                <span class="spots-count">{{ tourismSpots.length }} locations</span>
            </div>
            
            <div class="spots-grid">
                <div
                    v-for="spot in tourismSpots"
                    :key="spot.id"
                    class="spot-card"
                    :class="{ 'spot-card-active': selectedSpot === spot.id }"
                    @click="selectSpot(spot.id)"
                >
                    <div class="spot-image" :style="{ backgroundImage: `url(${spot.image})` }">
                        <div class="spot-overlay"></div>
                        <div class="spot-icon" :style="{ backgroundColor: spot.color }">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path :d="spot.icon"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="spot-content">
                        <h4 class="spot-name">{{ spot.name }}</h4>
                        <p class="spot-location">{{ spot.location }}</p>
                        
                        <div class="spot-stats">
                            <div class="spot-rating">
                                <div class="rating-stars">
                                    <svg
                                        v-for="i in 5"
                                        :key="i"
                                        class="star-icon"
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        :fill="i <= spot.rating ? '#fbbf24' : 'none'"
                                        :stroke="i <= spot.rating ? '#fbbf24' : '#d1d5db'"
                                        stroke-width="2"
                                    >
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                                <span class="rating-value">{{ spot.rating }}</span>
                            </div>
                            <div class="spot-reviews">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                                {{ getSpotReviewCount(spot.id) }} reviews
                            </div>
                        </div>
                        
                        <div class="spot-status" :class="`status-${getSpotStatus(spot.id)}`">
                            {{ getSpotStatusText(spot.id) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div v-if="selectedSpot" class="reviews-section">
            <div class="reviews-header">
                <div class="reviews-title-section">
                    <h2 class="reviews-title">
                        {{ selectedSpotData.name }} Reviews
                        <span class="reviews-count">({{ filteredReviews.length }})</span>
                    </h2>
                    <p class="reviews-subtitle">{{ selectedSpotData.location }}</p>
                    <div class="spot-overall-rating">
                        <div class="overall-rating-stars">
                            <svg
                                v-for="i in 5"
                                :key="i"
                                class="star-icon"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                :fill="i <= selectedSpotData.rating ? '#fbbf24' : 'none'"
                                :stroke="i <= selectedSpotData.rating ? '#fbbf24' : '#d1d5db'"
                                stroke-width="2"
                            >
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        </div>
                        <span class="overall-rating-value">{{ selectedSpotData.rating }} average rating</span>
                    </div>
                </div>
                <button @click="selectedSpot = null" class="btn btn-outline">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Back to Spots
                </button>
            </div>

            <!-- Reviews Display -->
            <div class="w-full flex flex-col gap-6">
                <!-- Reviews List -->
                <div class="bg-white rounded-xl p-5 shadow-md">
                    <h2 class="text-2xl font-bold text-[#1a3c5a] mb-5">Customer Reviews</h2>

                    <div v-if="filteredReviews.length > 0" class="flex flex-col gap-5">
                        <div
                            v-for="review in filteredReviews"
                            :key="review.id"
                            class="border-b border-slate-200 pb-5 last:border-b-0"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-[#1a3c5a]">
                                        {{ review.author }}
                                    </h3>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div class="rating-small">
                                            <svg
                                                v-for="i in 5"
                                                :key="i"
                                                class="star-icon-small"
                                                width="16"
                                                height="16"
                                                viewBox="0 0 24 24"
                                                :fill="i <= review.rating ? '#fbbf24' : 'none'"
                                                :stroke="i <= review.rating ? '#fbbf24' : '#d1d5db'"
                                                stroke-width="2"
                                            >
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-slate-500">{{ review.date }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs text-slate-500">{{ review.location }}</span>
                                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">
                                            {{ review.source }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Reply Button -->
                                <button 
                                    v-if="review.status === 'pending'"
                                    @click="handleReply(review.id)"
                                    class="bg-[#1a3c5a] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#2a4f6f] transition-all duration-300 text-sm"
                                >
                                    Reply
                                </button>
                                
                                <!-- Response Status -->
                                <div v-else class="flex items-center gap-2 bg-green-100 text-green-800 px-3 py-2 rounded-lg">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    <span class="text-sm font-medium">Replied</span>
                                </div>
                            </div>

                            <p class="text-slate-600 mb-4">{{ review.message }}</p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span
                                    v-for="tag in review.tags"
                                    :key="tag"
                                    class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full"
                                >
                                    {{ tag }}
                                </span>
                            </div>

                            <!-- Admin Response -->
                            <div v-if="review.status === 'replied' && review.response" class="bg-slate-50 rounded-lg p-4 mt-3">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        SS
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-800">Suroy Surigao Admin</h4>
                                        <span class="text-xs text-slate-500">Responded {{ review.responseDate }}</span>
                                    </div>
                                </div>
                                <p class="text-slate-700 text-sm">{{ review.response }}</p>
                            </div>

                            <!-- Reply Form -->
                            <div v-if="selectedReview === review.id && review.status === 'pending'" class="bg-slate-50 rounded-lg p-4 mt-4">
                                <h4 class="text-lg font-semibold text-slate-800 mb-2">Respond to {{ review.author }}</h4>
                                <p class="text-slate-600 text-sm mb-4">Your response will be visible to the tourist and help improve their experience</p>
                                
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        SS
                                    </div>
                                    <div class="flex-1">
                                        <textarea
                                            v-model="replyText"
                                            placeholder="Write a warm and helpful response to this tourist's feedback..."
                                            class="w-full h-32 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-400 outline-none resize-none"
                                            rows="4"
                                        ></textarea>
                                        <div class="flex justify-end gap-3 mt-3">
                                            <button 
                                                @click="handleCloseReply"
                                                class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-all duration-300"
                                            >
                                                Cancel
                                            </button>
                                            <button 
                                                @click="handleSubmitReply(review.id)" 
                                                class="px-4 py-2 bg-[#1a3c5a] text-white font-semibold rounded-lg hover:bg-[#2a4f6f] transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                                :disabled="!replyText.trim()"
                                            >
                                                Send Response
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center text-slate-500 py-8">
                        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-slate-400 mb-2">No reviews yet</h3>
                        <p class="text-slate-500">
                            This location doesn't have any reviews yet. Reviews will appear here once tourists start sharing their experiences.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Reactive data
const selectedReview = ref(null);
const replyText = ref('');
const selectedSpot = ref(null);

// Tourism Spots Data with images
const tourismSpots = ref([
    {
        id: 1,
        name: "Surigao City Hall",
        location: "Surigao City Proper",
        image: "https://images.unsplash.com/photo-1565895405221-6d5113b9d0c9?w=400&h=300&fit=crop",
        icon: "M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4",
        color: "#3b82f6",
        rating: 4.2
    },
    {
        id: 2,
        name: "Mabua Beach",
        location: "Mabua, Surigao City",
        image: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=300&fit=crop",
        icon: "M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z",
        color: "#10b981",
        rating: 4.8
    },
    {
        id: 3,
        name: "Luneta Park",
        location: "Surigao City Boulevard",
        image: "https://images.unsplash.com/photo-1579033462043-0f11a7862f7d?w=400&h=300&fit=crop",
        icon: "M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21H3Z",
        color: "#8b5cf6",
        rating: 4.3
    },
    {
        id: 4,
        name: "Surigao Provincial Capitol",
        location: "Capitol Road, Surigao City",
        image: "https://images.unsplash.com/photo-1513584684374-8bab748fbf90?w=400&h=300&fit=crop",
        icon: "M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z",
        color: "#f59e0b",
        rating: 4.1
    },
    {
        id: 5,
        name: "Day-asan Floating Village",
        location: "Day-asan, Surigao City",
        image: "https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=300&fit=crop",
        icon: "M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z",
        color: "#06b6d4",
        rating: 4.7
    },
    {
        id: 6,
        name: "Silop Cave",
        location: "Silop, Surigao City",
        image: "https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&h=300&fit=crop",
        icon: "M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9 9a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z",
        color: "#ef4444",
        rating: 4.4
    },
    {
        id: 7,
        name: "Bucas Grande Island",
        location: "Socorro, Surigao del Norte",
        image: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=300&fit=crop",
        icon: "M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c-.317-.159-.69-.159-1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z",
        color: "#84cc16",
        rating: 4.9
    },
    {
        id: 8,
        name: "Lianga Beach",
        location: "Lianga, Surigao del Sur",
        image: "https://images.unsplash.com/photo-1519046904884-53103b34b206?w=400&h=300&fit=crop",
        icon: "M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z",
        color: "#f97316",
        rating: 4.6
    }
]);

// Comprehensive Mock Reviews Data
const reviews = ref([
    // Mabua Beach Reviews (ID: 2)
    {
        id: 1,
        spotId: 2,
        author: "Maria Santos",
        avatar: "MS",
        avatarColor: "#3b82f6",
        location: "Manila, Philippines",
        message: "Mabua Beach is absolutely stunning! The unique pebble stones create such a beautiful natural music when the waves crash. Perfect spot for sunset watching and photography. The local vendors are very friendly and the fresh coconut juice is a must-try!",
        date: "2 days ago",
        rating: 5,
        status: "pending",
        tags: ["Beach", "Sunset", "Photography", "Local Food"],
        source: "Facebook"
    },
    {
        id: 2,
        spotId: 2,
        author: "James Wilson",
        avatar: "JW",
        avatarColor: "#10b981",
        location: "Sydney, Australia",
        message: "As an Australian used to sandy beaches, Mabua's pebble beach was a unique experience! The stones are smooth and colorful. Great for swimming and the water is crystal clear. Highly recommended for tourists looking for something different.",
        date: "1 week ago",
        rating: 4,
        status: "replied",
        tags: ["Unique", "Swimming", "Clear Water"],
        source: "TripAdvisor",
        response: "Thank you for your wonderful feedback, James! We're delighted you enjoyed the unique pebble beach experience. We continuously work with local authorities to maintain the cleanliness and natural beauty of Mabua Beach.",
        responseDate: "5 days ago"
    },
    {
        id: 3,
        spotId: 2,
        author: "Sarah Chen",
        avatar: "SC",
        avatarColor: "#8b5cf6",
        location: "Singapore",
        message: "The pebble stones are amazing! Loved collecting the smooth, colorful stones as souvenirs. The beach is well-maintained and there are plenty of shaded areas. Perfect for family picnics and relaxation.",
        date: "3 days ago",
        rating: 5,
        status: "pending",
        tags: ["Family", "Relaxation", "Souvenirs"],
        source: "Google"
    },

    // Surigao City Hall Reviews (ID: 1)
    {
        id: 4,
        spotId: 1,
        author: "Carlos Rodriguez",
        avatar: "CR",
        avatarColor: "#06b6d4",
        location: "Madrid, Spain",
        message: "Beautiful historical building! The architecture reflects Spanish colonial influence. The staff were helpful when I needed directions. Well-maintained gardens around the building make it photogenic.",
        date: "2 weeks ago",
        rating: 4,
        status: "replied",
        tags: ["Architecture", "Historical", "Photogenic"],
        source: "Instagram",
        response: "Thank you for visiting Surigao City Hall, Carlos! We're proud of our historical building and its Spanish colonial heritage. We're glad our staff could assist you. Come visit us again soon!",
        responseDate: "1 week ago"
    },
    {
        id: 5,
        spotId: 1,
        author: "Lisa Thompson",
        avatar: "LT",
        avatarColor: "#ef4444",
        location: "London, UK",
        message: "Impressive government building with rich history. The free guided tour was informative. Learned about Surigao's political history and saw some historical artifacts. Worth a quick visit when in the city.",
        date: "5 days ago",
        rating: 4,
        status: "pending",
        tags: ["Government", "History", "Free Tour"],
        source: "Booking.com"
    },

    // Luneta Park Reviews (ID: 3)
    {
        id: 6,
        spotId: 3,
        author: "David Martinez",
        avatar: "DM",
        avatarColor: "#f59e0b",
        location: "California, USA",
        message: "Perfect place for evening walks! The sea breeze is refreshing and the city lights create a romantic atmosphere. Well-maintained pathways and plenty of benches. Great for jogging in the morning too!",
        date: "1 week ago",
        rating: 5,
        status: "replied",
        tags: ["Walking", "Evening", "Romantic", "Jogging"],
        source: "Facebook",
        response: "We're happy you enjoyed your evening walks at Luneta Park, David! The park is indeed a favorite spot for both locals and tourists. We maintain the area daily to ensure everyone's safety and comfort.",
        responseDate: "4 days ago"
    },
    {
        id: 7,
        spotId: 3,
        author: "Anna Kim",
        avatar: "AK",
        avatarColor: "#8b5cf6",
        location: "Seoul, South Korea",
        message: "Beautiful park with amazing sunset views over the sea. The children's playground is well-equipped and safe. Local food stalls nearby offer delicious snacks. Perfect for family outings.",
        date: "3 days ago",
        rating: 4,
        status: "pending",
        tags: ["Family", "Sunset", "Playground", "Local Food"],
        source: "Google"
    },

    // Day-asan Floating Village Reviews (ID: 5)
    {
        id: 8,
        spotId: 5,
        author: "Mohammed Ali",
        avatar: "MA",
        avatarColor: "#06b6d4",
        location: "Dubai, UAE",
        message: "Absolutely magical experience! The floating village is a testament to human adaptability. The stilt houses are fascinating and the community is very welcoming. Boat tour was reasonably priced and informative.",
        date: "2 weeks ago",
        rating: 5,
        status: "replied",
        tags: ["Cultural", "Community", "Boat Tour", "Unique"],
        source: "TripAdvisor",
        response: "Thank you for your kind words, Mohammed! The Day-asan community takes great pride in their unique way of life. We're glad you enjoyed the boat tour and experienced the warm hospitality of our floating village residents.",
        responseDate: "1 week ago"
    },
    {
        id: 9,
        spotId: 5,
        author: "Sophie Williams",
        avatar: "SW",
        avatarColor: "#ec4899",
        location: "London, UK",
        message: "Unique cultural experience! The children swimming around the houses and the daily life on water is fascinating. Bring cash as there are no ATMs nearby. The seafood lunch we had with a local family was unforgettable!",
        date: "6 days ago",
        rating: 5,
        status: "pending",
        tags: ["Cultural", "Seafood", "Local Experience", "Photography"],
        source: "Instagram"
    },

    // Bucas Grande Island Reviews (ID: 7)
    {
        id: 10,
        spotId: 7,
        author: "Kenji Tanaka",
        avatar: "KT",
        avatarColor: "#84cc16",
        location: "Tokyo, Japan",
        message: "Paradise on earth! The jellyfish sanctuary is incredible - swimming with stingless jellyfish was a once-in-a-lifetime experience. Crystal clear lagoons and hidden caves make this place magical. Island hopping tour is a must!",
        date: "3 weeks ago",
        rating: 5,
        status: "replied",
        tags: ["Jellyfish", "Lagoons", "Island Hopping", "Caves"],
        source: "Facebook",
        response: "Arigato gozaimasu, Kenji-san! We're thrilled you enjoyed the jellyfish sanctuary and our beautiful lagoons. Bucas Grande is indeed a natural wonder. We hope to welcome you back to explore more of our hidden gems!",
        responseDate: "2 weeks ago"
    },
    {
        id: 11,
        spotId: 7,
        author: "Rachel Green",
        avatar: "RG",
        avatarColor: "#f97316",
        location: "New York, USA",
        message: "Absolutely breathtaking! The Sohoton Cove is like something from a fantasy movie. The jellyfish were amazing and our guide was very knowledgeable. The boat ride through narrow passages was thrilling. Worth every penny!",
        date: "4 days ago",
        rating: 5,
        status: "pending",
        tags: ["Adventure", "Jellyfish", "Boat Ride", "Nature"],
        source: "Google"
    },

    // Silop Cave Reviews (ID: 6)
    {
        id: 12,
        spotId: 6,
        author: "Miguel Santos",
        avatar: "MS",
        avatarColor: "#ef4444",
        location: "Cebu, Philippines",
        message: "Adventure-filled cave exploration! The rock formations are spectacular and the guides are very safety-conscious. Bring proper shoes and be prepared to get wet. The underground river inside is refreshing.",
        date: "1 week ago",
        rating: 4,
        status: "replied",
        tags: ["Adventure", "Cave", "Rock Formations", "Underground River"],
        source: "TripAdvisor",
        response: "Salamat for exploring Silop Cave, Miguel! We're glad you enjoyed the adventure. Safety is our priority, and we ensure all our guides are trained in cave exploration and first aid. Hope to see you again for more adventures!",
        responseDate: "3 days ago"
    },

    // Lianga Beach Reviews (ID: 8)
    {
        id: 13,
        spotId: 8,
        author: "Robert Brown",
        avatar: "RB",
        avatarColor: "#f97316",
        location: "Melbourne, Australia",
        message: "Long stretch of white sand beach with perfect waves for surfing! Less crowded than other tourist spots. Local surf instructors are available and very patient with beginners. Beach huts for rent are affordable and comfortable.",
        date: "5 days ago",
        rating: 4,
        status: "pending",
        tags: ["Surfing", "White Sand", "Beach Huts", "Less Crowded"],
        source: "Instagram"
    },
    {
        id: 14,
        spotId: 8,
        author: "Jessica Lee",
        avatar: "JL",
        avatarColor: "#8b5cf6",
        location: "Toronto, Canada",
        message: "Perfect escape from the city! The beach is pristine and the local community is very welcoming. Enjoyed fresh seafood at the beachfront restaurants. Sunsets here are absolutely magical. Will definitely return!",
        date: "2 weeks ago",
        rating: 5,
        status: "replied",
        tags: ["Pristine", "Seafood", "Sunsets", "Relaxing"],
        source: "Google",
        response: "Thank you for choosing Lianga Beach, Jessica! We're delighted you enjoyed our pristine shores and magical sunsets. Our local restaurants take pride in serving fresh seafood. We look forward to your next visit!",
        responseDate: "1 week ago"
    }
]);

// Computed properties
const selectedSpotData = computed(() => {
    return tourismSpots.value.find(spot => spot.id === selectedSpot.value) || {};
});

const filteredReviews = computed(() => {
    if (!selectedSpot.value) return [];
    return reviews.value.filter(review => review.spotId === selectedSpot.value);
});

const totalReviews = computed(() => reviews.value.length);

const totalTourists = computed(() => {
    const uniqueTourists = new Set(reviews.value.map(review => review.author));
    return uniqueTourists.size;
});

const averageRating = computed(() => {
    const total = reviews.value.reduce((sum, review) => sum + review.rating, 0);
    return (total / reviews.value.length).toFixed(1);
});

// Methods
const selectSpot = (spotId) => {
    selectedSpot.value = spotId;
    selectedReview.value = null;
    // Scroll to reviews section
    setTimeout(() => {
        const reviewsSection = document.querySelector('.reviews-section');
        if (reviewsSection) {
            reviewsSection.scrollIntoView({ behavior: 'smooth' });
        }
    }, 100);
};

const getSpotReviewCount = (spotId) => {
    return reviews.value.filter(review => review.spotId === spotId).length;
};

const getSpotStatus = (spotId) => {
    return getSpotReviewCount(spotId) > 0 ? 'active' : 'inactive';
};

const getSpotStatusText = (spotId) => {
    const count = getSpotReviewCount(spotId);
    if (count === 0) return 'No Reviews';
    if (count === 1) return '1 Review';
    return `${count} Reviews`;
};

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
        const reviewIndex = reviews.value.findIndex(r => r.id === reviewId);
        if (reviewIndex !== -1) {
            reviews.value[reviewIndex].status = 'replied';
            reviews.value[reviewIndex].response = replyText.value;
            reviews.value[reviewIndex].responseDate = 'just now';
        }
        selectedReview.value = null;
        replyText.value = '';
    }
};

onMounted(() => {
    // Component mounted
});
</script>

<style scoped>
/* Main Container */
.reviewsAndFeedbacks-body {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    padding: 24px;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    min-height: 100vh;
}

/* Header Section */
.header-section {
    margin-bottom: 32px;
    padding: 24px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    text-align: center;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    font-size: 16px;
    color: #64748b;
    margin: 0;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

.stat-card-blue {
    background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
    color: #1e40af;
}

.stat-card-green {
    background: linear-gradient(135deg, #dcfce7 0%, #f0fdf4 100%);
    color: #166534;
}

.stat-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    opacity: 0.9;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 4px;
}

.stat-trend {
    font-size: 12px;
    font-weight: 500;
}

.stat-trend.positive {
    color: #059669;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.6);
}

.stat-icon-blue { background: rgba(59, 130, 246, 0.1); }
.stat-icon-green { background: rgba(16, 185, 129, 0.1); }

/* Spots Section */
.spots-section {
    margin-bottom: 32px;
    padding: 24px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.spots-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.spots-title {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.spots-count {
    font-size: 14px;
    color: #64748b;
    background: #f8fafc;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
}

/* Spots Grid */
.spots-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.spot-card {
    border: 2px solid #f1f5f9;
    border-radius: 16px;
    overflow: hidden;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.spot-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
    border-color: #e2e8f0;
}

.spot-card-active {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.spot-image {
    position: relative;
    height: 160px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.spot-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.3));
}

.spot-icon {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.spot-content {
    padding: 20px;
}

.spot-name {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.spot-location {
    font-size: 14px;
    color: #64748b;
    margin: 0 0 16px 0;
    font-weight: 500;
}

.spot-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.spot-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.rating-stars {
    display: flex;
    gap: 1px;
}

.rating-value {
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
}

.spot-reviews {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

.spot-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #f3f4f6;
    color: #6b7280;
}

/* Reviews Section */
.reviews-section {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.reviews-header {
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.reviews-title-section {
    flex: 1;
}

.reviews-title {
    font-size: 20px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.reviews-count {
    font-size: 16px;
    color: #64748b;
    font-weight: 400;
}

.reviews-subtitle {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

.spot-overall-rating {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 8px;
}

.overall-rating-stars {
    display: flex;
    gap: 2px;
}

.overall-rating-value {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
}

/* Review Styles */
.rating-small {
    display: flex;
    gap: 1px;
}

.star-icon-small {
    flex-shrink: 0;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-outline {
    background: transparent;
    color: #6b7280;
    border: 1px solid #d1d5db;
}

.btn-outline:hover {
    background: #f8fafc;
    color: #374151;
}

/* Scrollbar Styling */
.reviewsAndFeedbacks-body::-webkit-scrollbar {
    width: 8px;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .reviewsAndFeedbacks-body {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .spots-grid {
        grid-template-columns: 1fr;
    }
    
    .reviews-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .review-header {
        flex-direction: column;
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .reviewer-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .review-tags {
        justify-content: center;
    }
}
</style>