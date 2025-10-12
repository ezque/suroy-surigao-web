<template>
  <div class="favorites-body">
    <h2 class="title">My Favorite Spots ❤️</h2>

    <div v-if="favorites.length" class="favorites-grid">
      <div
        v-for="spot in favorites"
        :key="spot.id"
        class="favorite-card"
      >
        <img :src="spot.image" :alt="spot.name" class="spot-image" />
        <div class="spot-info">
          <h3>{{ spot.name }}</h3>
          <p class="spot-category">{{ spot.category }}</p>
          <div class="spot-actions">
            <button class="explore-btn">Explore</button>
            <button class="remove-btn" @click="removeFavorite(spot.id)">
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="no-favorites">
      <p>No favorite spots yet. 🌴</p>
      <small>Go explore and add some!</small>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

// Sample favorite spots data
const favorites = ref([
  {
    id: 1,
    name: "Mabua Pebble Beach",
    image: "/images/mabua-beach.jpg",
    category: "Beach",
  },
  {
    id: 2,
    name: "Surigao City Boulevard",
    image: "/images/surigao-boulevard.jpg",
    category: "Park",
  },
]);

// Remove favorite
const removeFavorite = (id) => {
  favorites.value = favorites.value.filter((spot) => spot.id !== id);
};
</script>

<style scoped>
.favorites-body {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  padding: 30px;
  background: linear-gradient(to bottom, #b3e5fc, #e0f7fa);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.title {
  font-family: "Poppins", sans-serif;
  font-weight: 600;
  font-size: 1.8rem;
  color: #1d4f6c;
  margin-bottom: 25px;
}

/* Grid Layout */
.favorites-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 25px;
  width: 100%;
  max-width: 1000px;
}

/* Favorite Card */
.favorite-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: transform 0.2s ease;
}
.favorite-card:hover {
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
  margin: 8px 0;
  color: #333;
  font-size: 1.1rem;
}
.spot-category {
  color: #777;
  font-size: 0.9rem;
}

/* Buttons */
.spot-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 10px;
}

.explore-btn {
  background: #1d4f6c;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 7px 16px;
  cursor: pointer;
  transition: background 0.2s ease;
}
.explore-btn:hover {
  background: #156080;
}

.remove-btn {
  background: #ff4d4d;
  color: white;
  border: none;
  border-radius: 20px;
  padding: 7px 16px;
  cursor: pointer;
  transition: background 0.2s ease;
}
.remove-btn:hover {
  background: #d93636;
}

/* No favorites */
.no-favorites {
  margin-top: 50px;
  text-align: center;
  color: #333;
}
.no-favorites small {
  color: #777;
}
</style>
