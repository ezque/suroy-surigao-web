<template>
    <div class="admin-spots-body">
        <div class="page-label">
            <h2>Spots Management</h2>
            <button class="add-spots-btn" @click="$emit('selectPage', 'addSpots')">
                <i class="fas fa-plus"></i> Add New Spot
            </button>
        </div>

        <!-- 🔍 Search Bar -->
        <div class="search-bar">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search spots by name or location..."
            />
        </div>

        <div class="spots-grid scrollable">
            <div
                class="spot-card"
                v-for="(spot, index) in filteredSpots"
                :key="spot.id"
            >
                <!-- Spot Image -->
                <div class="spot-image">
                    <img
                        v-if="spot.images && spot.images.length > 0"
                        :src="`/storage/${spot.images[0].spot_image}`"
                        :alt="spot.spot_name"
                    />
                    <img
                        v-else
                        src="/public/images/logo/logo.png"
                        alt="No Image Available"
                    />

                    <!-- Spot Status -->
                    <div
                        class="spot-status"
                        :class="spot.status === '1' ? 'status-active' : 'status-inactive'"
                    >
                        {{ spot.status === '1' ? "Active" : "Inactive" }}
                    </div>
                </div>

                <!-- Spot Info -->
                <div class="spot-info">
                    <h3 class="spot-name">{{ spot.spot_name }}</h3>
                    <div class="spot-location">
                        <i class="fas fa-map-marker-alt"></i> {{ spot.location }}
                    </div>
                    <div class="spot-actions">
                        <button class="edit-btn" @click="editSpot(spot)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="filteredSpots.length === 0" class="no-results">
                No spots found.
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue"

    const emit = defineEmits(["selectPage"])

    const props = defineProps({
        allSpots: {
            type: Array,
            default: () => [],
        },
    })

    const searchQuery = ref("")

    const filteredSpots = computed(() => {
        return props.allSpots.filter((spot) =>
            (spot.spot_name || "").toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (spot.location || "").toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    })

    function editSpot(spot) {
        emit("selectPage", { page: "addSpots", spot })
    }
</script>

<style scoped>
.admin-spots-body {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  padding: 25px;
  overflow-y: auto;
  background: linear-gradient(135deg, #E8F4F8 0%, #FFFFFF 100%);
}

.page-label {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 25px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eaeaea;
}

.page-label h2 {
  font-size: 1.8rem;
  color: #2c3e50;
  font-weight: 600;
}

.add-spots-btn {
  background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(106, 17, 203, 0.25);
}

.add-spots-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(106, 17, 203, 0.35);
}

/* 🔍 Search bar styles */
.search-bar {
  margin-bottom: 20px;
  width: 100%;
  display: flex;
  justify-content: flex-start;
}

.search-bar input {
  background-color: #e0e0e0;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #ccc;
  width: 300px;
  max-width: 100%;
  font-size: 1rem;
}

.spots-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.spot-card {
  background: rgb(112, 198, 245);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
}

.spot-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.spot-image {
  height: 160px;
  overflow: hidden;
  position: relative;
}

.spot-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.spot-card:hover .spot-image img {
  transform: scale(1.05);
}

.spot-status {
  position: absolute;
  top: 12px;
  right: 12px;
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-active {
  background: #4cd964;
  color: white;
}

.status-inactive {
  background: #ff9500;
  color: white;
}

.spot-info {
  padding: 15px;
}

.spot-name {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 8px;
  color: #2c3e50;
}

.spot-location {
  display: flex;
  align-items: center;
  color: #f9fcfc;
  font-size: 0.9rem;
  margin-bottom: 12px;
}

.spot-location i {
  margin-right: 5px;
}

.spot-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid #f1f1f1;
}

.edit-btn {
  background: #f5f7fa;
  border: none;
  padding: 8px 15px;
  border-radius: 6px;
  color: #3498db;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 5px;
}

.edit-btn:hover {
  background: #3498db;
  color: white;
}

.no-results {
  grid-column: span 4;
  text-align: center;
  font-size: 1rem;
  color: #7f8c8d;
  margin-top: 20px;
}

/* Responsive grid */
@media (max-width: 1024px) {
  .spots-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .spots-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .page-label {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .add-spots-btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .spots-grid {
    grid-template-columns: 1fr;
  }
}
</style>
