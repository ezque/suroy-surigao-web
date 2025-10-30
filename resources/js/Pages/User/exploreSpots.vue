<template>
    <div class="exploreSpots-body" v-if="spot">
        <div class="spot-banner">
            <img
                v-if="spot.images && spot.images.length > 0"
                :src="`/storage/${spot.images[0].spot_image}`"
                :alt="spot.spot_name"
                class="spot-banner-img"
            />
            <div class="banner-overlay">
                <button class="back-btn" @click="$emit('back')">
                    <span class="back-icon">←</span> Back to Explore
                </button>
                <div class="banner-actions">
                    <button class="share-btn" @click="shareSpot">
                        <span class="share-icon">📤</span> Share
                    </button>
                    <button
                        class="favorite-btn"
                        :class="{ favorited: isFavorited }"
                        @click="toggleFavorite"
                    >
                        <span class="heart-icon">{{ isFavorited ? "❤️" : "🤍" }}</span>
                        {{ isFavorited ? "Favorited" : "Add to Favorites" }}
                    </button>
                </div>
            </div>
        </div>

        <div class="spot-content">
            <div class="spot-header">
                <div class="spot-title-section">
                    <h1 class="spot-title">{{ spot.name }}</h1>
                    <div class="spot-location">
                        <span class="location-icon">📍</span>
                        {{ spot.location }}
                    </div>
                </div>
                <div class="spot-rating">
                    <div class="rating-stars">
                        <span
                            v-for="n in 5"
                            :key="n"
                            class="star"
                            :class="{ filled: n <= spot.rating }"
                        >
                          {{ n <= spot.rating ? "⭐" : "☆" }}
                        </span>
                    </div>
                    <div class="rating-value">{{ spot.rating }}/5</div>
                    <div class="review-count">({{ spot.reviewCount }} reviews)</div>
                </div>
            </div>

            <!-- ✅ Tabs -->
            <div class="spot-tabs">
                <button
                    class="tab-btn"
                    :class="{ active: activeTab === 'overview' }"
                    @click="activeTab = 'overview'"
                >
                    📖 Overview
                </button>

                <button
                    class="tab-btn"
                    :class="{ active: activeTab === 'tourAgencies' }"
                    @click="activeTab = 'tourAgencies'"
                >
                    🏢 Tour Agencies
                </button>

                <button
                    class="tab-btn"
                    :class="{ active: activeTab === 'reviews' }"
                    @click="activeTab = 'reviews'"
                >
                    💬 Reviews
                </button>
            </div>

            <div class="tab-content">
                <!-- ✅ Overview -->
                <div v-if="activeTab === 'overview'" class="tab-panel overview-panel">
                    <div class="description-section">
                        <h3>About This Place</h3>
                        <p class="spot-description">{{ spot.description }}</p>

                        <div class="highlights-grid">
                            <div
                                class="highlight-card"
                                v-for="highlight in spot.highlights"
                                :key="highlight"
                            >
                                ✅ {{ highlight }}
                            </div>
                        </div>
                    </div>

                    <div class="details-grid">
                        <div class="detail-section">
                            <h4>📍 How to Get There</h4>
                            <p>{{ spot.howToGetThere }}</p>
                        </div>
                        <div class="detail-section">
                            <h4>⏰ Operating Hours</h4>
                            <p>{{ spot.operatingHours }}</p>
                        </div>
                        <div class="detail-section">
                            <h4>🎒 What to Bring</h4>
                            <div class="items-list">
                <span
                    class="item-tag"
                    v-for="item in spot.whatToBring"
                    :key="item"
                >{{ item }}</span
                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Tour Agencies Tab -->
                <div v-if="activeTab === 'tourAgencies'" class="tab-panel agencies-panel">
                    <TourAgencies :spotId="spot.id" @viewAgency="openAgencyDetails" />
                    <ExploreTourAgencies
                        v-if="selectedAgency"
                        :agency="selectedAgency"
                        @close="selectedAgency = null"
                    />
                </div>

                <!-- ✅ Reviews -->
                <div v-if="activeTab === 'reviews'" class="tab-panel reviews-panel">
                    <div class="reviews-header">
                        <div class="reviews-summary">
                            <div class="overall-rating">
                                <div class="rating-big">{{ spot.rating }}</div>
                                <div class="rating-stars">
                  <span
                      v-for="n in 5"
                      :key="n"
                      class="star"
                      :class="{ filled: n <= spot.rating }"
                  >
                    {{ n <= spot.rating ? "⭐" : "☆" }}
                  </span>
                                </div>
                                <div class="rating-text">
                                    Based on {{ spot.reviewCount }} reviews
                                </div>
                            </div>
                        </div>
                        <button class="add-review-btn" @click="showReviewForm = true">
                            ✍️ Write a Review
                        </button>
                    </div>

                    <div class="reviews-list">
                        <div
                            class="review-card"
                            v-for="review in spot.reviews"
                            :key="review.id"
                        >
                            <div class="reviewer-info">
                                <div class="reviewer-avatar">{{ review.user.charAt(0) }}</div>
                                <div class="reviewer-details">
                                    <div class="reviewer-name">{{ review.user }}</div>
                                    <div class="review-date">{{ review.date }}</div>
                                </div>
                            </div>
                            <div class="review-rating">
                <span
                    v-for="n in 5"
                    :key="n"
                    class="star"
                    :class="{ filled: n <= review.rating }"
                >
                  {{ n <= review.rating ? "⭐" : "☆" }}
                </span>
                            </div>
                            <p class="review-content">{{ review.content }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Map -->
            <div class="map-section">
                <h3>📍 Location Map</h3>
                <div class="map-container">
                    <iframe
                        v-if="mapUrl"
                        :src="mapUrl"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
                <button class="directions-btn" @click="getDirections">
                    🚗 Get Directions
                </button>
            </div>
        </div>

        <!-- ✅ Review Modal -->
        <div
            v-if="showReviewForm"
            class="modal-overlay"
            @click="showReviewForm = false"
        >
            <div class="modal-content" @click.stop>
                <h3>Write a Review</h3>
                <form @submit.prevent="submitReview">
                    <div class="rating-input">
                        <label>Your Rating:</label>
                        <div class="star-rating">
                  <span
                      v-for="n in 5"
                      :key="n"
                      class="star-selectable"
                      :class="{ selected: n <= newReview.rating }"
                      @click="newReview.rating = n"
                  >
                    ⭐
                  </span>
                        </div>
                    </div>
                    <textarea
                        v-model="newReview.content"
                        placeholder="Share your experience..."
                        rows="5"
                    ></textarea>
                    <div class="modal-actions">
                        <button type="button" @click="showReviewForm = false">Cancel</button>
                        <button type="submit">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ✅ Not Found -->
    <div v-else class="not-found">
        <div class="not-found-content">
            <h2>📍 Spot Not Found</h2>
            <p>The tourist spot you're looking for doesn't exist or has been removed.</p>
            <button class="back-btn" @click="$emit('back')">← Back to Explore</button>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive, onMounted, computed } from "vue";
    import TourAgencies from "./tourAgencies.vue";
    import ExploreTourAgencies from "./exploreTourAgencies.vue";

    const props = defineProps({
        spot: Object,
    });


    const emit = defineEmits(["back", "navigate"]);

    const activeTab = ref("overview");
    const selectedAgency = ref(null);
    const isFavorited = ref(false);
    const showReviewForm = ref(false);
    const newReview = reactive({ rating: 0, content: "" });

    const spot = props.spot || {};
    console.log(spot.id);

    const mapUrl = computed(() => {
        if (!spot || !spot.location) return "";
        const query = encodeURIComponent(`${spot.location}`);
        return `https://maps.google.com/maps?q=${query}&t=&z=15&ie=UTF8&iwloc=&output=embed`;
    });

    const openAgencyDetails = (agency) => {
        selectedAgency.value = agency;
    };

    const navigate = (page, payload = null) => {
        emit("navigate", { page, payload });
    };

    const checkIfFavorited = () => {
        const favorites = JSON.parse(localStorage.getItem("favorites")) || [];
        isFavorited.value = favorites.some((fav) => fav.id === spot.id);
    };

    const toggleFavorite = () => {
        let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
        if (isFavorited.value) {
            favorites = favorites.filter((fav) => fav.id !== spot.id);
            isFavorited.value = false;
        } else {
            favorites.push(spot);
            isFavorited.value = true;
        }
        localStorage.setItem("favorites", JSON.stringify(favorites));
    };

    const shareSpot = () => {
        if (navigator.share) {
            navigator.share({
                title: spot.name,
                text: `Check out ${spot.name} in Surigao!`,
                url: window.location.href,
            });
        } else {
            navigator.clipboard.writeText(window.location.href);
            alert("Link copied to clipboard!");
        }
    };

    const getDirections = () => {
        if (spot && spot.location) {
            const query = encodeURIComponent(`${spot.name}, ${spot.location}`);
            const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${query}`;
            window.open(mapsUrl, "_blank");
        }
    };

    const submitReview = () => {
        console.log("New review:", newReview);
        showReviewForm.value = false;
        newReview.rating = 0;
        newReview.content = "";
        alert("Thank you for your review!");
    };

    onMounted(() => checkIfFavorited());
</script>


<style scoped>
.exploreSpots-body {
  width: 100%;
  height: 100vh;
  overflow-y: auto;
  background: linear-gradient(135deg, #e0f7fa 0%, #b3e5fc 50%, #81d4fa 100%);
}

/* Banner Styles */
.spot-banner {
  position: relative;
  width: 100%;
  height: 400px;
  overflow: hidden;
}

.spot-banner-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.banner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 20px;
}

.back-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 25px;
  padding: 10px 20px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.back-btn:hover {
  background: white;
  transform: translateX(-5px);
}

.banner-actions {
  display: flex;
  gap: 10px;
}

.share-btn, .favorite-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 25px;
  padding: 10px 20px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.favorite-btn.favorited {
  background: #ff4757;
  color: white;
}

.share-btn:hover, .favorite-btn:hover {
  background: white;
  transform: translateY(-2px);
}

/* Main Content */
.spot-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px 20px;
}

/* Spot Header */
.spot-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 30px;
}

.spot-title {
  font-size: 2.5rem;
  color: #1a3c5a;
  margin-bottom: 10px;
  font-weight: 800;
}

.spot-location {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #4a6572;
  font-size: 1.1rem;
}

.spot-rating {
  text-align: right;
}

.rating-stars {
  display: flex;
  gap: 2px;
  margin-bottom: 5px;
}

.star {
  font-size: 1.2rem;
}

.rating-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1a3c5a;
}

.review-count {
  color: #4a6572;
  font-size: 0.9rem;
}

/* Stats Grid */
.spot-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-item {
  background: white;
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-5px);
}

.stat-icon {
  font-size: 2rem;
}

.stat-value {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1a3c5a;
  margin-bottom: 5px;
}

.stat-label {
  color: #4a6572;
  font-size: 0.9rem;
}

/* Tabs */
.spot-tabs {
  display: flex;
  gap: 5px;
  margin-bottom: 30px;
  background: white;
  border-radius: 15px;
  padding: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.tab-btn {
  flex: 1;
  background: transparent;
  border: none;
  border-radius: 10px;
  padding: 15px 20px;
  cursor: pointer;
  font-weight: 600;
  color: #4a6572;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.tab-btn.active {
  background: #1a3c5a;
  color: white;
}

.tab-btn:hover:not(.active) {
  background: #e3f2fd;
}

/* Tab Content */
.tab-content {
  background: white;
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.tab-panel h3 {
  color: #1a3c5a;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

/* Overview Panel */
.spot-description {
  font-size: 1.1rem;
  line-height: 1.7;
  color: #37474f;
  margin-bottom: 25px;
}

.highlights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px;
  margin-bottom: 30px;
}

.highlight-card {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px;
  background: #f8fdff;
  border-radius: 10px;
  border-left: 4px solid #1a3c5a;
}

.details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 25px;
}

.detail-section h4 {
  color: #1a3c5a;
  margin-bottom: 10px;
  font-size: 1.1rem;
}

.items-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.item-tag {
  background: #e3f2fd;
  color: #1a3c5a;
  padding: 5px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
}

/* Gallery Panel */
.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 15px;
}

.gallery-item {
  aspect-ratio: 1;
  border-radius: 10px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.gallery-item:hover {
  transform: scale(1.05);
}

.gallery-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Services Panel */
.services-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.service-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8fdff;
  border-radius: 15px;
  padding: 25px;
  border: 1px solid #e1f5fe;
}

.service-info h4 {
  color: #1a3c5a;
  margin-bottom: 8px;
  font-size: 1.2rem;
}

.service-description {
  color: #546e7a;
  margin-bottom: 15px;
}

.service-features {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.feature-tag {
  background: #e1f5fe;
  color: #0277bd;
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
}

.service-pricing {
  text-align: center;
}

.price {
  font-size: 1.8rem;
  font-weight: 700;
  color: #1a3c5a;
  margin-bottom: 5px;
}

.price-note {
  color: #546e7a;
  font-size: 0.9rem;
  margin-bottom: 15px;
}

.book-btn {
  background: #ff6f00;
  color: white;
  border: none;
  border-radius: 25px;
  padding: 12px 25px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.book-btn:hover {
  background: #ff8f00;
  transform: translateY(-2px);
}

/* Reviews Panel */
.reviews-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #e3f2fd;
}

.overall-rating {
  text-align: center;
}

.rating-big {
  font-size: 3rem;
  font-weight: 700;
  color: #1a3c5a;
  margin-bottom: 5px;
}

.rating-text {
  color: #546e7a;
  font-size: 0.9rem;
}

.add-review-btn {
  background: #1a3c5a;
  color: white;
  border: none;
  border-radius: 25px;
  padding: 12px 25px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.add-review-btn:hover {
  background: #2c5282;
  transform: translateY(-2px);
}

.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.review-card {
  background: #f8fdff;
  border-radius: 15px;
  padding: 20px;
  border: 1px solid #e1f5fe;
}

.reviewer-info {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 10px;
}

.reviewer-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #1a3c5a;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
}

.reviewer-name {
  font-weight: 600;
  color: #1a3c5a;
}

.review-date {
  color: #78909c;
  font-size: 0.9rem;
}

.review-content {
  color: #37474f;
  line-height: 1.6;
  margin-top: 10px;
}

/* Tips Panel */
.tips-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.tip-card {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 20px;
  background: #f8fdff;
  border-radius: 15px;
  border-left: 4px solid #ff6f00;
}

.tip-icon {
  font-size: 2rem;
}

.tip-content h4 {
  color: #1a3c5a;
  margin-bottom: 8px;
  font-size: 1.1rem;
}

.tip-content p {
  color: #546e7a;
  line-height: 1.5;
}

/* ✨ UPDATED: Map Section Styles */
.map-section {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  text-align: center; /* Center align section content */
}

.map-section h3 {
  color: #1a3c5a;
  margin-bottom: 20px;
}

.map-container {
  border-radius: 15px;
  overflow: hidden;
  background: #e3f2fd; /* Fallback color while map loads */
  height: 400px; /* Adjust height as needed */
  width: 100%;
  margin-bottom: 20px; /* Space between map and button */
}

.map-container iframe {
  width: 100%;
  height: 100%;
  border: 0;
}

.directions-btn {
  background: #1a3c5a;
  color: white;
  border: none;
  border-radius: 25px;
  padding: 12px 25px;
  cursor: pointer;
  font-weight: 600;
  display: inline-block; /* Allows margin auto to work if needed */
  transition: all 0.3s ease;
}

.directions-btn:hover {
  background: #2c5282;
  transform: translateY(-2px);
}

/* Modal Styles */
.modal-overlay, .lightbox-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 20px;
  padding: 30px;
  width: 90%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-content h3 {
  color: #1a3c5a;
  margin-bottom: 20px;
}

.rating-input {
  margin-bottom: 20px;
}

.star-rating {
  display: flex;
  gap: 5px;
  margin-top: 10px;
}

.star-selectable {
  font-size: 2rem;
  cursor: pointer;
  opacity: 0.3;
  transition: all 0.2s ease;
}

.star-selectable.selected {
  opacity: 1;
}

.modal-content textarea {
  width: 100%;
  border: 2px solid #e3f2fd;
  border-radius: 10px;
  padding: 15px;
  font-family: inherit;
  resize: vertical;
  margin-bottom: 20px;
}

.modal-actions {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
}

.modal-actions button {
  padding: 10px 20px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.modal-actions button:first-child {
  background: #e3f2fd;
  color: #1a3c5a;
}

.modal-actions button:last-child {
  background: #1a3c5a;
  color: white;
}

.modal-actions button:hover {
  transform: translateY(-2px);
}

/* Lightbox Styles */
.lightbox-content {
  position: relative;
  max-width: 90%;
  max-height: 90%;
}

.lightbox-content img {
  max-width: 100%;
  max-height: 80vh;
  border-radius: 10px;
}

.lightbox-close {
  position: absolute;
  top: -40px;
  right: 0;
  background: none;
  border: none;
  color: white;
  font-size: 2rem;
  cursor: pointer;
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.8);
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-nav:hover {
  background: white;
}

.lightbox-nav.prev {
  left: -70px;
}

.lightbox-nav.next {
  right: -70px;
}

/* Not Found */
.not-found {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100vh;
  background: linear-gradient(135deg, #e0f7fa 0%, #b3e5fc 50%, #81d4fa 100%);
}

.not-found-content {
  text-align: center;
  background: white;
  padding: 50px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.not-found-content h2 {
  color: #1a3c5a;
  margin-bottom: 15px;
  font-size: 2rem;
}

.not-found-content p {
  color: #546e7a;
  margin-bottom: 25px;
  font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .spot-header {
    flex-direction: column;
    gap: 20px;
  }

  .spot-title {
    font-size: 2rem;
  }

  .spot-tabs {
    flex-direction: column;
  }

  .service-card {
    flex-direction: column;
    gap: 20px;
    text-align: center;
  }

  .reviews-header {
    flex-direction: column;
    gap: 20px;
    text-align: center;
  }

  .details-grid {
    grid-template-columns: 1fr;
  }

  .lightbox-nav.prev {
    left: 10px;
  }

  .lightbox-nav.next {
    right: 10px;
  }
}

@media (max-width: 480px) {
  .spot-content {
    padding: 20px 15px;
  }

  .spot-title {
    font-size: 1.8rem;
  }

  .spot-stats {
    grid-template-columns: 1fr;
  }

  .tab-content {
    padding: 20px;
  }

  .banner-overlay {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .banner-actions {
    width: 100%;
    justify-content: space-between;
  }
}
</style>
