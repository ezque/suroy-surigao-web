<template>
  <div class="exploreSpots-body" v-if="spot">
    <!-- Banner Image -->
    <div class="spot-banner">
      <img :src="spot.image" :alt="spot.name" class="spot-banner-img" />
    </div>

    <!-- Spot Title -->
    <div class="spot-header">
      <h2 class="spot-title">{{ spot.name }}</h2>
    </div>

    <!-- Tabs -->
    <div class="spot-tabs">
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'overview' }"
        @click="activeTab = 'overview'"
      >
        Overview
      </button>
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'services' }"
        @click="activeTab = 'services'"
      >
        Service/Agencies Options
      </button>
      <button
        class="tab-btn"
        :class="{ active: activeTab === 'reviews' }"
        @click="activeTab = 'reviews'"
      >
        Reviews
      </button>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
      <div v-if="activeTab === 'overview'" class="tab-panel">
        <p>
          Mabua Pebble Beach is a unique destination near Surigao City, known
          for its shoreline of smooth, white, and gray pebbles instead of sand.
        </p>
      </div>

      <div v-if="activeTab === 'services'" class="tab-panel">
        <p>List of agencies offering tours will appear here.</p>
      </div>

      <div v-if="activeTab === 'reviews'" class="tab-panel">
        <p>Reviews from travelers will be displayed here.</p>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="spot-actions">
      <button class="favorite-btn" @click="addToFavorites(spot)">
        ❤️ Add to Favorites
      </button>
      <button class="back-btn" @click="$emit('back')">← Back</button>
    </div>
  </div>

  <div v-else class="not-found">
    <h2>Spot not found</h2>
    <button class="back-btn" @click="$emit('back')">Go Back</button>
  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
  spot: Object,
});

const activeTab = ref("overview");

const addToFavorites = (spot) => {
  let favorites = JSON.parse(localStorage.getItem("favorites")) || [];
  if (!favorites.some((fav) => fav.id === spot.id)) {
    favorites.push(spot);
    localStorage.setItem("favorites", JSON.stringify(favorites));
    alert(`${spot.name} added to favorites!`);
  } else {
    alert("This spot is already in your favorites.");
  }
};
</script>

<style scoped>
.exploreSpots-body {
  width: 100%;
  height: 100%;
  padding: 20px;
  overflow-y: auto;
  background: linear-gradient(to bottom, #b3e5fc, #e0f7fa);
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Banner Image */
.spot-banner {
  width: 100%;
  max-width: 800px;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
.spot-banner-img {
  width: 100%;
  height: 250px;
  object-fit: cover;
}

/* Title */
.spot-header {
  text-align: left;
  width: 100%;
  max-width: 800px;
  margin-top: 10px;
}
.spot-title {
  font-size: 1.6rem;
  color: #1d4f6c;
  margin-bottom: 10px;
}

/* Tabs */
.spot-tabs {
  display: flex;
  gap: 10px;
  justify-content: flex-start;
  width: 100%;
  max-width: 800px;
  margin-bottom: 15px;
}
.tab-btn {
  background: #dfe6e9;
  border: none;
  border-radius: 10px;
  padding: 8px 15px;
  font-weight: 500;
  cursor: pointer;
  color: #333;
  transition: all 0.2s ease;
}
.tab-btn.active {
  background: #1d4f6c;
  color: white;
}

/* Content */
.tab-content {
  background: #468da0;
  width: 100%;
  max-width: 800px;
  border-radius: 10px;
  padding: 15px 20px;
  color: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.tab-panel p {
  line-height: 1.6;
  font-size: 1rem;
}

/* Buttons */
.spot-actions {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}
.favorite-btn,
.back-btn {
  background: #1d4f6c;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  cursor: pointer;
  transition: background 0.2s ease;
}
.favorite-btn:hover,
.back-btn:hover {
  background: #156080;
}

/* Not Found */
.not-found {
  text-align: center;
  padding: 50px;
}
</style>
