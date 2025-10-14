<template>
  <div class="favorites-body">
    <!-- Header Section -->
    <div class="favorites-header">
      <div class="header-content">
        <h1 class="title">My Favorite Spots ❤️</h1>
        <p class="subtitle">Your curated collection of Surigao's best destinations</p>
        <div class="favorites-stats">
          <div class="stat">
            <div class="stat-number">{{ favorites.length }}</div>
            <div class="stat-label">Saved Places</div>
          </div>
          <div class="stat">
            <div class="stat-number">{{ visitedCount }}</div>
            <div class="stat-label">Visited</div>
          </div>
          <div class="stat">
            <div class="stat-number">{{ categories.length }}</div>
            <div class="stat-label">Categories</div>
          </div>
        </div>
      </div>
      <div class="header-actions">
        <button class="action-btn share-all" @click="shareAllFavorites">
          <span class="btn-icon">📤</span>
          Share All
        </button>
        <button class="action-btn clear-all" @click="clearAllFavorites" v-if="favorites.length">
          <span class="btn-icon">🗑️</span>
          Clear All
        </button>
      </div>
    </div>

    <!-- Filter and Sort Controls -->
    <div class="controls-section" v-if="favorites.length">
      <div class="filter-controls">
        <div class="filter-group">
          <label>Filter by Category:</label>
          <select v-model="selectedCategory" class="filter-select">
            <option value="all">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>
        <div class="filter-group">
          <label>Sort by:</label>
          <select v-model="sortBy" class="filter-select">
            <option value="dateAdded">Date Added</option>
            <option value="name">Name</option>
            <option value="rating">Rating</option>
            <option value="distance">Distance</option>
          </select>
        </div>
      </div>
      <div class="view-controls">
        <button 
          class="view-btn" 
          :class="{ active: viewMode === 'grid' }" 
          @click="viewMode = 'grid'"
        >
          <span class="btn-icon">⏹️</span>
          Grid
        </button>
        <button 
          class="view-btn" 
          :class="{ active: viewMode === 'list' }" 
          @click="viewMode = 'list'"
        >
          <span class="btn-icon">📋</span>
          List
        </button>
      </div>
    </div>

    <!-- Favorites Grid/List -->
    <div v-if="filteredFavorites.length" class="favorites-container">
      <div :class="['favorites-display', viewMode]">
        <div
          v-for="spot in sortedFavorites"
          :key="spot.id"
          :class="['favorite-item', { visited: spot.visited }]"
        >
          <div class="item-image">
            <img :src="spot.image" :alt="spot.name" />
            <div class="image-overlay">
              <button class="quick-action favorite" @click="toggleFavorite(spot.id)">
                <span class="action-icon">{{ spot.favorited ? '❤️' : '🤍' }}</span>
              </button>
              <button class="quick-action share" @click="shareSpot(spot)">
                <span class="action-icon">📤</span>
              </button>
              <div class="spot-badges">
                <span class="badge category" :style="{ backgroundColor: getCategoryColor(spot.category) }">
                  {{ spot.category }}
                </span>
                <span v-if="spot.visited" class="badge visited">✅ Visited</span>
              </div>
            </div>
          </div>

          <div class="item-content">
            <div class="spot-header">
              <h3 class="spot-name">{{ spot.name }}</h3>
              <div class="spot-rating">
                <span class="rating-stars">
                  <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= spot.rating }">
                    {{ n <= spot.rating ? '⭐' : '☆' }}
                  </span>
                </span>
                <span class="rating-value">{{ spot.rating }}</span>
              </div>
            </div>

            <p class="spot-description">{{ spot.description }}</p>

            <div class="spot-details">
              <div class="detail">
                <span class="detail-icon">📍</span>
                <span class="detail-text">{{ spot.distance }} from city</span>
              </div>
              <div class="detail">
                <span class="detail-icon">⏱️</span>
                <span class="detail-text">{{ spot.bestTime }}</span>
              </div>
              <div class="detail" v-if="spot.entryFee">
                <span class="detail-icon">💰</span>
                <span class="detail-text">{{ spot.entryFee }}</span>
              </div>
            </div>

            <div class="spot-actions">
              <button class="btn primary" @click="exploreSpot(spot)">
                <span class="btn-icon">🔍</span>
                Explore Details
              </button>
              <button class="btn secondary" @click="toggleVisited(spot.id)">
                <span class="btn-icon">{{ spot.visited ? '✅' : '🟡' }}</span>
                {{ spot.visited ? 'Visited' : 'Mark Visited' }}
              </button>
              <button class="btn remove" @click="removeFavorite(spot.id)">
                <span class="btn-icon">🗑️</span>
                Remove
              </button>
            </div>

            <div class="spot-meta">
              <span class="meta-item">
                <span class="meta-icon">📅</span>
                Added {{ formatDate(spot.dateAdded) }}
              </span>
              <span v-if="spot.lastVisited" class="meta-item">
                <span class="meta-icon">👣</span>
                Last visited {{ formatDate(spot.lastVisited) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="empty-state">
      <div class="empty-content">
        <div class="empty-icon">🌴</div>
        <h2>No favorite spots yet</h2>
        <p>Start exploring Surigao's beautiful destinations and add them to your favorites!</p>
        <div class="empty-actions">
          <button class="btn primary" @click="exploreSpots">
            <span class="btn-icon">🔍</span>
            Explore Spots
          </button>
          <button class="btn secondary" @click="viewPopularSpots">
            <span class="btn-icon">🔥</span>
            View Popular Spots
          </button>
        </div>
        <div class="empty-tips">
          <h4>Tips for getting started:</h4>
          <ul>
            <li>Browse through different categories like beaches, mountains, and historical sites</li>
            <li>Read reviews and check ratings before adding to favorites</li>
            <li>Use the map view to discover spots near your location</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Categories Overview -->
    <div class="categories-section" v-if="favorites.length">
      <h3>Your Favorites by Category</h3>
      <div class="categories-grid">
        <div 
          v-for="category in categories" 
          :key="category" 
          class="category-card"
          @click="selectedCategory = category"
          :class="{ active: selectedCategory === category }"
        >
          <div class="category-icon">{{ getCategoryIcon(category) }}</div>
          <div class="category-info">
            <div class="category-name">{{ category }}</div>
            <div class="category-count">
              {{ getCategoryCount(category) }} spots
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="activity-section" v-if="recentActivity.length">
      <h3>Recent Activity</h3>
      <div class="activity-list">
        <div 
          v-for="activity in recentActivity" 
          :key="activity.id" 
          class="activity-item"
        >
          <div class="activity-icon">{{ activity.icon }}</div>
          <div class="activity-content">
            <p class="activity-text">{{ activity.text }}</p>
            <span class="activity-time">{{ activity.time }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";

// Reactive data
const favorites = ref([]);
const selectedCategory = ref("all");
const sortBy = ref("dateAdded");
const viewMode = ref("grid");
const recentActivity = ref([]);

// Sample favorite spots data
const sampleFavorites = [
  {
    id: 1,
    name: "Mabua Pebble Beach",
    image: "/images/mabua-beach.jpg",
    category: "Beach",
    description: "Unique pebble beach with smooth stones and crystal clear waters, perfect for relaxation and photography.",
    rating: 4.5,
    distance: "15 km",
    bestTime: "7AM - 5PM",
    entryFee: "₱50",
    dateAdded: "2024-01-15",
    visited: true,
    lastVisited: "2024-02-20",
    favorited: true
  },
  {
    id: 2,
    name: "Surigao City Boulevard",
    image: "/images/surigao-boulevard.jpg",
    category: "Park",
    description: "Scenic seaside boulevard perfect for evening walks, exercise, and watching beautiful sunsets.",
    rating: 4.2,
    distance: "2 km",
    bestTime: "5AM - 10PM",
    entryFee: "Free",
    dateAdded: "2024-01-20",
    visited: false,
    favorited: true
  },
  {
    id: 3,
    name: "Siargao Cloud 9",
    image: "/images/cloud9.jpg",
    category: "Beach",
    description: "World-renowned surfing destination with perfect waves and vibrant beach community.",
    rating: 4.8,
    distance: "45 km",
    bestTime: "6AM - 6PM",
    entryFee: "₱100",
    dateAdded: "2024-01-10",
    visited: true,
    lastVisited: "2024-03-05",
    favorited: true
  },
  {
    id: 4,
    name: "Enchanted River",
    image: "/images/enchanted-river.jpg",
    category: "Nature",
    description: "Mysterious deep blue river with crystal clear waters and rich marine life.",
    rating: 4.7,
    distance: "80 km",
    bestTime: "8AM - 5PM",
    entryFee: "₱150",
    dateAdded: "2024-02-01",
    visited: false,
    favorited: true
  }
];

// Computed properties
const categories = computed(() => {
  const cats = [...new Set(favorites.value.map(spot => spot.category))];
  return cats.sort();
});

const visitedCount = computed(() => {
  return favorites.value.filter(spot => spot.visited).length;
});

const filteredFavorites = computed(() => {
  if (selectedCategory.value === "all") {
    return favorites.value;
  }
  return favorites.value.filter(spot => spot.category === selectedCategory.value);
});

const sortedFavorites = computed(() => {
  const spots = [...filteredFavorites.value];
  
  switch (sortBy.value) {
    case "name":
      return spots.sort((a, b) => a.name.localeCompare(b.name));
    case "rating":
      return spots.sort((a, b) => b.rating - a.rating);
    case "distance":
      return spots.sort((a, b) => {
        const aDist = parseInt(a.distance);
        const bDist = parseInt(b.distance);
        return aDist - bDist;
      });
    case "dateAdded":
    default:
      return spots.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
  }
});

// Methods
const getCategoryColor = (category) => {
  const colors = {
    'Beach': '#4fc3f7',
    'Park': '#66bb6a',
    'Nature': '#9ccc65',
    'Historical': '#ffb74d',
    'Adventure': '#f06292'
  };
  return colors[category] || '#78909c';
};

const getCategoryIcon = (category) => {
  const icons = {
    'Beach': '🏖️',
    'Park': '🌳',
    'Nature': '🌿',
    'Historical': '🏛️',
    'Adventure': '🧗'
  };
  return icons[category] || '📍';
};

const getCategoryCount = (category) => {
  return favorites.value.filter(spot => spot.category === category).length;
};

const formatDate = (dateString) => {
  const options = { month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
};

const removeFavorite = (id) => {
  const spot = favorites.value.find(s => s.id === id);
  favorites.value = favorites.value.filter(spot => spot.id !== id);
  
  // Add to recent activity
  recentActivity.value.unshift({
    id: Date.now(),
    icon: '🗑️',
    text: `Removed ${spot.name} from favorites`,
    time: 'Just now'
  });
};

const toggleFavorite = (id) => {
  const spot = favorites.value.find(s => s.id === id);
  if (spot) {
    spot.favorited = !spot.favorited;
    if (!spot.favorited) {
      removeFavorite(id);
    }
  }
};

const toggleVisited = (id) => {
  const spot = favorites.value.find(s => s.id === id);
  if (spot) {
    spot.visited = !spot.visited;
    if (spot.visited) {
      spot.lastVisited = new Date().toISOString().split('T')[0];
      
      // Add to recent activity
      recentActivity.value.unshift({
        id: Date.now(),
        icon: '✅',
        text: `Marked ${spot.name} as visited`,
        time: 'Just now'
      });
    }
  }
};

const exploreSpot = (spot) => {
  // In a real app, this would navigate to the spot detail page
  alert(`Exploring ${spot.name}`);
};

const shareSpot = (spot) => {
  if (navigator.share) {
    navigator.share({
      title: spot.name,
      text: `Check out ${spot.name} in Surigao!`,
      url: window.location.href,
    });
  } else {
    navigator.clipboard.writeText(`${spot.name} - ${spot.description}`);
    alert('Spot details copied to clipboard!');
  }
};

const shareAllFavorites = () => {
  const favoriteNames = favorites.value.map(spot => spot.name).join(', ');
  if (navigator.share) {
    navigator.share({
      title: 'My Favorite Spots in Surigao',
      text: `My favorite spots: ${favoriteNames}`,
      url: window.location.href,
    });
  } else {
    navigator.clipboard.writeText(`My favorite spots in Surigao: ${favoriteNames}`);
    alert('Favorite spots list copied to clipboard!');
  }
};

const clearAllFavorites = () => {
  if (confirm('Are you sure you want to remove all favorites?')) {
    favorites.value = [];
    
    // Add to recent activity
    recentActivity.value.unshift({
      id: Date.now(),
      icon: '🗑️',
      text: 'Cleared all favorites',
      time: 'Just now'
    });
  }
};

const exploreSpots = () => {
  // Navigate to explore page
  alert('Navigating to explore spots...');
};

const viewPopularSpots = () => {
  // Navigate to popular spots
  alert('Showing popular spots...');
};

// Initialize
onMounted(() => {
  // Load from localStorage or use sample data
  const savedFavorites = localStorage.getItem('userFavorites');
  if (savedFavorites) {
    favorites.value = JSON.parse(savedFavorites);
  } else {
    favorites.value = sampleFavorites;
  }
  
  // Load recent activity
  const savedActivity = localStorage.getItem('recentActivity');
  if (savedActivity) {
    recentActivity.value = JSON.parse(savedActivity);
  }
});

// Watch for changes to save to localStorage
import { watch } from 'vue';
watch(favorites, (newFavorites) => {
  localStorage.setItem('userFavorites', JSON.stringify(newFavorites));
}, { deep: true });

watch(recentActivity, (newActivity) => {
  localStorage.setItem('recentActivity', JSON.stringify(newActivity));
}, { deep: true });
</script>

<style scoped>
.favorites-body {
  width: 100%;
  min-height: 50vh;
  background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
  padding: 20px;
  overflow-y: auto;
}

/* Header Section */
.favorites-header {
  background: white;
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

.header-content {
  flex: 1;
}

.title {
  font-size: 2.5rem;
  font-weight: 800;
  color: #1a3c5a;
  margin-bottom: 8px;
  background: linear-gradient(135deg, #1a3c5a, #2c5282);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.subtitle {
  font-size: 1.1rem;
  color: #4a6572;
  margin-bottom: 25px;
}

.favorites-stats {
  display: flex;
  gap: 30px;
}

.stat {
  text-align: center;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: #1a3c5a;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 0.9rem;
  color: #4a6572;
}

.header-actions {
  display: flex;
  gap: 10px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border: none;
  border-radius: 25px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.action-btn.share-all {
  background: #1a3c5a;
  color: white;
}

.action-btn.clear-all {
  background: #ff4757;
  color: white;
}

.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Controls Section */
.controls-section {
  background: white;
  border-radius: 15px;
  padding: 20px;
  margin-bottom: 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.filter-controls {
  display: flex;
  gap: 20px;
  align-items: center;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-group label {
  font-weight: 600;
  color: #1a3c5a;
  font-size: 0.9rem;
}

.filter-select {
  padding: 8px 15px;
  border: 2px solid #e3f2fd;
  border-radius: 10px;
  background: white;
  cursor: pointer;
  transition: border-color 0.3s ease;
}

.filter-select:focus {
  outline: none;
  border-color: #1a3c5a;
}

.view-controls {
  display: flex;
  gap: 5px;
  background: #f8fdff;
  border-radius: 12px;
  padding: 5px;
}

.view-btn {
  padding: 8px 16px;
  border: none;
  background: transparent;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: all 0.3s ease;
}

.view-btn.active {
  background: #1a3c5a;
  color: white;
}

/* Favorites Display */
.favorites-container {
  margin-bottom: 40px;
}

.favorites-display.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 25px;
}

.favorites-display.list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.favorites-display.list .favorite-item {
  display: flex;
  flex-direction: row;
}

.favorites-display.list .item-image {
  width: 200px;
  height: 150px;
  flex-shrink: 0;
}

.favorite-item {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  position: relative;
}

.favorite-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.favorite-item.visited {
  border-left: 4px solid #4caf50;
}

/* Item Image */
.item-image {
  position: relative;
  height: 200px;
  overflow: hidden;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.favorite-item:hover .item-image img {
  transform: scale(1.05);
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.7));
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 15px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.favorite-item:hover .image-overlay {
  opacity: 1;
}

.quick-action {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.quick-action:hover {
  background: white;
  transform: scale(1.1);
}

.spot-badges {
  position: absolute;
  bottom: 15px;
  right: 15px;
  display: flex;
  gap: 8px;
  flex-direction: column;
  align-items: flex-end;
}

.badge {
  padding: 4px 10px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
}

.badge.category {
  background: #1a3c5a;
}

.badge.visited {
  background: #4caf50;
}

/* Item Content */
.item-content {
  padding: 20px;
}

.spot-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 12px;
}

.spot-name {
  font-size: 1.3rem;
  font-weight: 700;
  color: #1a3c5a;
  margin: 0;
  flex: 1;
}

.spot-rating {
  display: flex;
  align-items: center;
  gap: 8px;
}

.rating-stars {
  display: flex;
  gap: 2px;
}

.star {
  font-size: 1rem;
}

.star.filled {
  color: #ffc107;
}

.rating-value {
  font-weight: 600;
  color: #1a3c5a;
}

.spot-description {
  color: #546e7a;
  line-height: 1.5;
  margin-bottom: 15px;
  font-size: 0.95rem;
}

.spot-details {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.detail {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.9rem;
  color: #4a6572;
}

.detail-icon {
  font-size: 1rem;
}

.spot-actions {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
  flex-wrap: wrap;
}

.btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border: none;
  border-radius: 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.btn.primary {
  background: #1a3c5a;
  color: white;
}

.btn.secondary {
  background: #e3f2fd;
  color: #1a3c5a;
}

.btn.remove {
  background: #ffebee;
  color: #d32f2f;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.spot-meta {
  display: flex;
  gap: 15px;
  font-size: 0.8rem;
  color: #78909c;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 4px;
}

/* Empty State */
.empty-state {
  background: white;
  border-radius: 20px;
  padding: 60px 40px;
  text-align: center;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.empty-state h2 {
  color: #1a3c5a;
  margin-bottom: 10px;
  font-size: 1.8rem;
}

.empty-state p {
  color: #546e7a;
  margin-bottom: 30px;
  font-size: 1.1rem;
}

.empty-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  margin-bottom: 40px;
}

.empty-tips {
  text-align: left;
  max-width: 500px;
  margin: 0 auto;
}

.empty-tips h4 {
  color: #1a3c5a;
  margin-bottom: 15px;
}

.empty-tips ul {
  color: #546e7a;
  line-height: 1.6;
  text-align: left;
}

.empty-tips li {
  margin-bottom: 8px;
}

/* Categories Section */
.categories-section {
  background: white;
  border-radius: 20px;
  padding: 30px;
  margin-bottom: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.categories-section h3 {
  color: #1a3c5a;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 15px;
}

.category-card {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  background: #f8fdff;
  border-radius: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.category-card:hover {
  background: #e3f2fd;
  transform: translateY(-3px);
}

.category-card.active {
  border-color: #1a3c5a;
  background: #e3f2fd;
}

.category-icon {
  font-size: 2rem;
}

.category-name {
  font-weight: 600;
  color: #1a3c5a;
  margin-bottom: 4px;
}

.category-count {
  font-size: 0.9rem;
  color: #4a6572;
}

/* Activity Section */
.activity-section {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.activity-section h3 {
  color: #1a3c5a;
  margin-bottom: 20px;
  font-size: 1.5rem;
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.activity-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: #f8fdff;
  border-radius: 12px;
  transition: background 0.3s ease;
}

.activity-item:hover {
  background: #e3f2fd;
}

.activity-icon {
  font-size: 1.5rem;
}

.activity-text {
  flex: 1;
  color: #37474f;
  margin: 0;
}

.activity-time {
  font-size: 0.8rem;
  color: #78909c;
}

/* Responsive Design */
@media (max-width: 768px) {
  .favorites-body {
    padding: 15px;
  }
  
  .favorites-header {
    flex-direction: column;
    gap: 20px;
  }
  
  .title {
    font-size: 2rem;
  }
  
  .favorites-stats {
    justify-content: space-around;
  }
  
  .controls-section {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }
  
  .filter-controls {
    flex-direction: column;
    gap: 15px;
  }
  
  .favorites-display.grid {
    grid-template-columns: 1fr;
  }
  
  .favorites-display.list .favorite-item {
    flex-direction: column;
  }
  
  .favorites-display.list .item-image {
    width: 100%;
    height: 200px;
  }
  
  .spot-header {
    flex-direction: column;
    gap: 10px;
  }
  
  .spot-actions {
    flex-direction: column;
  }
  
  .empty-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .categories-grid {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}

@media (max-width: 480px) {
  .title {
    font-size: 1.8rem;
  }
  
  .favorites-stats {
    flex-direction: column;
    gap: 15px;
  }
  
  .header-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .action-btn {
    justify-content: center;
  }
}
</style>