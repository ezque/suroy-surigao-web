<template>
    <div class="reviewsAndFeedbacks-body">
        <!-- Search Bar -->
        <div class="search-section">
            <div class="search-bar">
                <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search reviews..."
                    class="search-input"
                />
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card stat-card-green">
                <div class="stat-content">
                    <div>
                        <p class="stat-label">Total Reviews</p>
                        <p class="stat-value">5</p>
                    </div>
                    <div class="stat-icon stat-icon-green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-purple">
                <div class="stat-content">
                    <div>
                        <p class="stat-label">Response Rate</p>
                        <p class="stat-value">94%</p>
                    </div>
                    <div class="stat-icon stat-icon-purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card stat-card-orange">
                <div class="stat-content">
                    <div>
                        <p class="stat-label">Average Rating</p>
                        <p class="stat-value">300</p>
                    </div>
                    <div class="stat-icon stat-icon-orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="reviews-container">
            <div
                v-for="review in reviews"
                :key="review.id"
                class="review-card"
                :class="{ 'review-card-active': selectedReview === review.id }"
            >
                <div class="review-content">
                    <div class="review-header">
                        <div class="avatar">{{ review.avatar }}</div>
                        <div class="review-info">
                            <div class="review-top">
                                <div>
                                    <h3 class="review-author">{{ review.author }}</h3>
                                    <p class="review-date">{{ review.date }}</p>
                                </div>
                                <div class="rating">
                                    <svg
                                        v-for="i in review.rating"
                                        :key="i"
                                        class="star-icon"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="#fbbf24"
                                        stroke="#fbbf24"
                                        stroke-width="2"
                                    >
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                    </svg>
                                </div>
                            </div>
                            <p class="review-message">{{ review.message }}</p>
                            
                            <div v-if="review.status === 'pending'" class="action-buttons">
                                <button @click="handleReply(review.id)" class="btn btn-reply">
                                    Reply
                                </button>
                                <button @click="handleHide(review.id)" class="btn btn-hide">
                                    Hide
                                </button>
                            </div>

                            <div v-if="review.status === 'replied'" class="status-badge">
                                <span class="status-dot"></span>
                                Replied
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                <div v-if="selectedReview === review.id" class="reply-section">
                    <div class="reply-form">
                        <div class="reply-avatar">S</div>
                        <div class="reply-input-container">
                            <textarea
                                v-model="replyText"
                                placeholder="Write your response..."
                                class="reply-textarea"
                                rows="3"
                            ></textarea>
                            <div class="reply-actions">
                                <button @click="handleCloseReply" class="btn btn-cancel">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Cancel
                                </button>
                                <button @click="handleSubmitReply(review.id)" class="btn btn-send">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
import { ref } from 'vue';

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
        message: "The area is breathing! The facilities are on range, and the front tool were unforgettable!",
        date: "5 days ago",
        rating: 5,
        status: "replied"
    },
     {
        id: 3,
        author: "Michael Chen",
        avatar: "MC",
        message: "The area is breathing! The facilities are on range, and the front tool were unforgettable!",
        date: "5 days ago",
        rating: 5,
        status: "replied"
    },
     {
        id: 3,
        author: "Michael Chen",
        avatar: "MC",
        message: "The area is breathing! The facilities are on range, and the front tool were unforgettable!",
        date: "5 days ago",
        rating: 5,
        status: "replied"
    },
     {
        id: 3,
        author: "Michael Chen",
        avatar: "MC",
        message: "The area is breathing! The facilities are on range, and the front tool were unforgettable!",
        date: "5 days ago",
        rating: 5,
        status: "replied"
    }
    
]);

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
        }
        selectedReview.value = null;
        replyText.value = '';
    }
};

const handleHide = (reviewId) => {
    reviews.value = reviews.value.filter(review => review.id !== reviewId);
};
</script>

<style scoped>
.reviewsAndFeedbacks-body {
    width: 100%;
    height: 100%;
    overflow-y: auto;
    padding: 24px;
    background: linear-gradient(to bottom right, #e0f2fe, #bfdbfe);
}

/* Search Section */
.search-section {
    margin-bottom: 24px;
}

.search-bar {
    position: relative;
    max-width: 448px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 14px;
    color: #9ca3af;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 40px;
    border-radius: 12px;
    border: 2px solid white;
    background: white;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.search-input:focus {
    border-color: #60a5fa;
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
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-card-green {
    background: linear-gradient(to bottom right, #86efac, #4ade80);
}

.stat-card-purple {
    background: linear-gradient(to bottom right, #d8b4fe, #c084fc);
}

.stat-card-orange {
    background: linear-gradient(to bottom right, #fcd34d, #fb923c);
}

.stat-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.stat-label {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
}

.stat-card-green .stat-label {
    color: #166534;
}

.stat-card-purple .stat-label {
    color: #6b21a8;
}

.stat-card-orange .stat-label {
    color: #9a3412;
}

.stat-value {
    font-size: 36px;
    font-weight: bold;
}

.stat-card-green .stat-value {
    color: #14532d;
}

.stat-card-purple .stat-value {
    color: #581c87;
}

.stat-card-orange .stat-value {
    color: #7c2d12;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon-green {
    background: rgba(255, 255, 255, 0.3);
    color: #14532d;
}

.stat-icon-purple {
    background: rgba(255, 255, 255, 0.3);
    color: #581c87;
}

.stat-icon-orange {
    background: rgba(255, 255, 255, 0.3);
    color: #7c2d12;
}

/* Reviews Container */
.reviews-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.review-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s;
}

.review-card-active {
    box-shadow: 0 0 0 2px #60a5fa;
}

.review-content {
    padding: 24px;
}

.review-header {
    display: flex;
    gap: 16px;
}

.avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(to bottom right, #60a5fa, #a78bfa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.review-info {
    flex: 1;
}

.review-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.review-author {
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 4px 0;
}

.review-date {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.rating {
    display: flex;
    gap: 4px;
}

.star-icon {
    flex-shrink: 0;
}

.review-message {
    color: #374151;
    margin: 0 0 16px 0;
    line-height: 1.5;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.btn {
    padding: 8px 24px;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-reply {
    background: #2dd4bf;
    color: white;
}

.btn-reply:hover {
    background: #14b8a6;
}

.btn-hide {
    background: #9ca3af;
    color: white;
}

.btn-hide:hover {
    background: #6b7280;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 9999px;
    font-size: 14px;
    font-weight: 500;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
}

/* Reply Section */
.reply-section {
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
    padding: 24px;
}

.reply-form {
    display: flex;
    gap: 16px;
}

.reply-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(to bottom right, #ec4899, #a78bfa);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.reply-input-container {
    flex: 1;
}

.reply-textarea {
    width: 100%;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    font-family: inherit;
    resize: none;
    outline: none;
    transition: border-color 0.2s;
}

.reply-textarea:focus {
    border-color: #60a5fa;
}

.reply-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 12px;
}

.btn-cancel {
    background: #e5e7eb;
    color: #374151;
}

.btn-cancel:hover {
    background: #d1d5db;
}

.btn-send {
    background: #3b82f6;
    color: white;
}

.btn-send:hover {
    background: #2563eb;
}

/* Scrollbar Styling */
.reviewsAndFeedbacks-body::-webkit-scrollbar {
    width: 8px;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-track {
    background: transparent;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.reviewsAndFeedbacks-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>