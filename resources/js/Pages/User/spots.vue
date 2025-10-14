<template>
  <div class="spots-body">
    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-content">
        <h1 class="hero-title">Ali na sa Surigao!</h1>
        <p class="hero-subtitle">Discover the most beautiful spots in Surigao del Norte</p>
      </div>
      <div class="wave-decoration">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="#ffffff"></path>
          <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="#ffffff"></path>
          <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="#ffffff"></path>
        </svg>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="filter-container">
        <div class="search-wrapper">
          <i class="material-icons search-icon">search</i>
          <input
            type="text"
            placeholder="Search for beaches, parks, waterfalls..."
            v-model="searchQuery"
            class="search-bar"
          />
        </div>

        <div class="category-wrapper">
          <i class="material-icons category-icon">category</i>
          <select v-model="selectedCategory" class="category-dropdown">
            <option value="">All Categories</option>
            <option value="beach">🏖️ Beaches</option>
            <option value="park">🌳 Parks</option>
            <option value="museum">🏛️ Museums</option>
            <option value="waterfall">💧 Waterfalls</option>
            <option value="mountain">⛰️ Mountains</option>
            <option value="island">🏝️ Islands</option>
          </select>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="quick-stats">
        <div class="stat-badge">
          <i class="material-icons">explore</i>
          <span>{{ filteredSpots.length }} Spots</span>
        </div>
        <div class="stat-badge">
          <i class="material-icons">star</i>
          <span>Top Rated</span>
        </div>
        <div class="stat-badge">
          <i class="material-icons">trending_up</i>
          <span>Trending</span>
        </div>
      </div>
    </div>

    <!-- Spots Grid -->
    <div class="spots-container">
      <div class="spots-grid" v-if="filteredSpots.length > 0">
        <div 
          v-for="spot in filteredSpots" 
          :key="spot.id" 
          class="spot-card"
          @click="goToExplore(spot)"
        >
          <div class="spot-image-wrapper">
            <img :src="spot.image" :alt="spot.name" class="spot-image" />
            <div class="spot-overlay">
              <button class="explore-btn">
                <i class="material-icons">visibility</i>
                <span>Explore Now</span>
              </button>
            </div>
            <div class="spot-badge">{{ getCategoryLabel(spot.category) }}</div>
          </div>
          <div class="spot-info">
            <h3>{{ spot.name }}</h3>
            <div class="spot-meta">
              <div class="rating">
                <i class="material-icons">star</i>
                <span>{{ spot.rating || '4.5' }}</span>
              </div>
              <div class="location">
                <i class="material-icons">place</i>
                <span>{{ spot.location || 'Surigao' }}</span>
              </div>
            </div>
            <div class="spot-actions">
              <button class="action-icon bookmark">
                <i class="material-icons">bookmark_border</i>
              </button>
              <button class="action-icon share">
                <i class="material-icons">share</i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="empty-state">
        <i class="material-icons">search_off</i>
        <h3>No spots found</h3>
        <p>Try adjusting your search or filter to find what you're looking for</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const emit = defineEmits(["exploreSpots"]);

const searchQuery = ref("");
const selectedCategory = ref("");

// Sample spots data
const spots = ref([
  {
    id: 1,
    name: "Surigao City Boulevard",
    image: "/images/ilovesurigao.jpg",
    category: "park",
    rating: 4.7,
    location: "Surigao City"
  },
  {
    id: 2,
    name: "Mabua Pebble Beach",
    image: "/images/ilovesurigao.jpg",
    category: "beach",
    rating: 4.8,
    location: "Surigao City"
  },
  {
    id: 3,
    name: "Luneta Park",
    image: "/images/ilovesurigao.jpg",
    category: "park",
    rating: 4.5,
    location: "Surigao City"
  },
  {
    id: 4,
    name: "Bayay Surigaonon Museum",
    image: "/images/museum.jpg",
    category: "museum",
    rating: 4.6,
    location: "Surigao City"
  },
  {
    id: 5,
    name: "Magkawas Falls",
    image: "/images/waterfall.jpg",
    category: "waterfall",
    rating: 4.9,
    location: "Gigaquit"
  },
  {
    id: 6,
    name: "Naked Island",
    image: "/images/island.jpg",
    category: "island",
    rating: 5.0,
    location: "General Luna"
  },
]);

// Filtering logic
const filteredSpots = computed(() => {
  return spots.value.filter((spot) => {
    const matchesSearch = spot.name
      .toLowerCase()
      .includes(searchQuery.value.toLowerCase());
    const matchesCategory =
      !selectedCategory.value || spot.category === selectedCategory.value;
    return matchesSearch && matchesCategory;
  });
});

// Get category label
const getCategoryLabel = (category) => {
  const labels = {
    beach: "🏖️ Beach",
    park: "🌳 Park",
    museum: "🏛️ Museum",
    waterfall: "💧 Waterfall",
    mountain: "⛰️ Mountain",
    island: "🏝️ Island"
  };
  return labels[category] || category;
};

// Navigate to explore
const goToExplore = (spot) => {
  emit("exploreSpots", spot);
};
</script>

<style scoped>
.spots-body {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  display: flex;
  flex-direction: column;
}

/* ===== HERO SECTION ===== */
.hero-section {
  position: relative;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  padding: 80px 30px 120px;
  text-align: center;
  color: white;
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
  background-size: cover;
  opacity: 0.3;
}

.hero-content {
  position: relative;
  z-index: 1;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  margin: 0 0 15px 0;
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
  animation: fadeInUp 0.8s ease-out;
}

.hero-subtitle {
  font-size: 1.3rem;
  font-weight: 300;
  opacity: 0.95;
  max-width: 600px;
  margin: 0 auto;
  animation: fadeInUp 1s ease-out;
}

.wave-decoration {
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  overflow: hidden;
  line-height: 0;
}

.wave-decoration svg {
  position: relative;
  display: block;
  width: calc(100% + 1.3px);
  height: 80px;
}

/* ===== FILTER SECTION ===== */
.filter-section {
  background: white;
  padding: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 10;
}

.filter-container {
  max-width: 1200px;
  margin: 0 auto 20px;
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.search-wrapper,
.category-wrapper {
  position: relative;
  flex: 1;
  min-width: 250px;
}

.search-icon,
.category-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #667eea;
  font-size: 24px;
  pointer-events: none;
}

.search-bar,
.category-dropdown {
  width: 100%;
  padding: 14px 20px 14px 50px;
  border: 2px solid #e8e8e8;
  border-radius: 12px;
  font-size: 1rem;
  outline: none;
  transition: all 0.3s ease;
  background: #f8f9fa;
}

.search-bar:focus,
.category-dropdown:focus {
  border-color: #667eea;
  background: white;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.category-dropdown {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 15px center;
  background-size: 20px;
  cursor: pointer;
}

/* Quick Stats */
.quick-stats {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.stat-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 10px 20px;
  border-radius: 25px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  font-size: 0.9rem;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  transition: transform 0.2s ease;
}

.stat-badge:hover {
  transform: translateY(-2px);
}

.stat-badge i {
  font-size: 20px;
}

/* ===== SPOTS CONTAINER ===== */
.spots-container {
  flex: 1;
  padding: 40px 30px;
  background: #f8f9fa;
}

.spots-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

/* ===== SPOT CARD ===== */
.spot-card {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  cursor: pointer;
}

.spot-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
}

.spot-image-wrapper {
  position: relative;
  overflow: hidden;
  height: 220px;
}

.spot-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease;
}

.spot-card:hover .spot-image {
  transform: scale(1.1);
}

.spot-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.spot-card:hover .spot-overlay {
  opacity: 1;
}

.explore-btn {
  background: white;
  color: #667eea;
  border: none;
  padding: 12px 24px;
  border-radius: 25px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transform: translateY(10px);
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.spot-card:hover .explore-btn {
  transform: translateY(0);
}

.explore-btn:hover {
  background: #667eea;
  color: white;
}

.spot-badge {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(255, 255, 255, 0.95);
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.spot-info {
  padding: 20px;
}

.spot-info h3 {
  margin: 0 0 12px 0;
  color: #333;
  font-size: 1.3rem;
  font-weight: 700;
}

.spot-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  color: #666;
  font-size: 0.9rem;
}

.rating,
.location {
  display: flex;
  align-items: center;
  gap: 5px;
}

.rating i {
  color: #ffc107;
  font-size: 18px;
}

.location i {
  color: #667eea;
  font-size: 18px;
}

.spot-actions {
  display: flex;
  gap: 10px;
  padding-top: 15px;
  border-top: 1px solid #f0f0f0;
}

.action-icon {
  flex: 1;
  background: #f8f9fa;
  border: none;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #666;
}

.action-icon:hover {
  background: #667eea;
  color: white;
  transform: translateY(-2px);
}

/* ===== EMPTY STATE ===== */
.empty-state {
  text-align: center;
  padding: 80px 20px;
  color: #999;
}

.empty-state i {
  font-size: 80px;
  color: #ddd;
  margin-bottom: 20px;
}

.empty-state h3 {
  font-size: 1.8rem;
  color: #666;
  margin: 0 0 10px 0;
}

.empty-state p {
  font-size: 1.1rem;
  color: #999;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1.1rem;
  }

  .filter-container {
    flex-direction: column;
  }

  .spots-grid {
    grid-template-columns: 1fr;
  }

  .quick-stats {
    justify-content: center;
  }
}
</style>