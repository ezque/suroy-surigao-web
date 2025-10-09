<template>
  <div class="spots-body">
    <!-- Top Bar -->
    <div class="top-bar">
      <input
        type="text"
        placeholder="Search spots..."
        v-model="searchQuery"
        class="search-bar"
      />

      <select v-model="selectedCategory" class="category-dropdown">
        <option value="">All Categories</option>
        <option value="beach">Beaches</option>
        <option value="park">Parks</option>
        <option value="museum">Museums</option>
        <option value="waterfall">Waterfalls</option>
      </select>
    </div>

    <!-- Page Title -->
    <h2 class="page-title">Ali na sa Surigao!</h2>

    <!-- Spots Grid -->
    <div class="spots-grid">
      <div v-for="spot in filteredSpots" :key="spot.id" class="spot-card">
        <img :src="spot.image" :alt="spot.name" class="spot-image" />
        <div class="spot-info">
          <h3>{{ spot.name }}</h3>
          <button class="explore-btn" @click="goToExplore(spot)">
            Explore
          </button>
        </div>
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
    image: "/images/surigao-boulevard.jpg",
    category: "park",
  },
  {
    id: 2,
    name: "Mabua Pebble Beach",
    image: "/images/mabua-beach.jpg",
    category: "beach",
  },
  {
    id: 3,
    name: "Luneta Park",
    image: "/images/luneta-park.jpg",
    category: "park",
  },
  {
    id: 4,
    name: "Bayay Surigaonon Museum",
    image: "/images/museum.jpg",
    category: "museum",
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

// Instead of router navigation, emit event to parent
const goToExplore = (spot) => {
  emit("exploreSpots", spot);
};
</script>

<style scoped>
.spots-body {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  padding: 30px;
  background: linear-gradient(to bottom, #b3e5fc, #e0f7fa);
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Top Bar */
.top-bar {
  width: 100%;
  max-width: 900px;
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
}
.search-bar {
  width: 60%;
  padding: 10px 15px;
  border-radius: 8px;
  border: none;
  font-size: 1em;
  outline: none;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.category-dropdown {
  width: 35%;
  padding: 10px 15px;
  border-radius: 8px;
  border: none;
  font-size: 1em;
  background: white;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Title */
.page-title {
  font-family: "Brush Script MT", cursive;
  font-size: 2rem;
  color: #1d4f6c;
  margin-bottom: 20px;
}

/* Grid */
.spots-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 25px;
  width: 100%;
  max-width: 1000px;
}

/* Spot Card */
.spot-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: transform 0.2s ease;
}
.spot-card:hover {
  transform: translateY(-5px);
}
.spot-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
}
.spot-info {
  padding: 15px;
  text-align: center;
}
.spot-info h3 {
  margin: 10px 0;
  color: #333;
  font-size: 1.1rem;
}
.explore-btn {
  background: #1d4f6c;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 8px 18px;
  cursor: pointer;
  transition: background 0.2s ease;
}
.explore-btn:hover {
  background: #156080;
}

@media (max-width: 768px) {
  .top-bar {
    flex-direction: column;
    gap: 10px;
  }

  .search-bar,
  .category-dropdown {
    width: 100%;
  }
}
</style>
