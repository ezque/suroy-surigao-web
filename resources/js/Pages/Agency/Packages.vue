<template>
  <div class="packages-body">
    <!-- Top controls -->
    <div class="top-controls">
      <button class="add-package" @click="showForm = true">
        <i class="material-icons-outlined">add</i>
        Add Package / Tour
      </button>

      <!-- Search bar -->
      <input
        type="text"
        placeholder="Search tours or packages..."
        class="search-bar"
        v-model="search"
      />
    </div>

    <!-- Table -->
    <div class="packages-table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Package / Tour Name</th>
            <th>Availability</th>
            <th>Status</th>
            <th>Capacity</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="pkg in filteredPackages" :key="pkg.id">
            <td>{{ pkg.id }}</td>
            <td>{{ pkg.name }}</td>
            <td>{{ pkg.availability }}</td>
            <td>
              <span
                class="status"
                :class="{
                  active: pkg.status === 'Active',
                  pending: pkg.status === 'Pending',
                  inactive: pkg.status === 'Inactive'
                }"
              >
                {{ pkg.status }}
              </span>
            </td>
            <td>{{ pkg.capacity }}</td>
            <td>
              <button class="action-btn view">View</button>
              <button class="action-btn delete" @click="deletePackage(pkg.id)">
                🗑 Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal for Add Package -->
    <div v-if="showForm" class="modal-overlay">
      <div class="modal-content">
        <AddPackages
          @submit="handleAddPackage"
          @cancel="showForm = false"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AddPackages from './addPackages.vue'; // Import the corrected AddPackages component

const showForm = ref(false);
const search = ref('');

// Example initial data
const packages = ref([
  { id: 1, name: 'Island Hopping', availability: 'Daily', status: 'Active', capacity: 20 },
  { id: 2, name: 'Mountain Trek', availability: 'Weekends', status: 'Pending', capacity: 15 },
  { id: 3, name: 'River Cruise', availability: 'Monthly', status: 'Inactive', capacity: 40 }
]);

// Add new package
const handleAddPackage = (formData) => {
  const newId = packages.value.length + 1;
  packages.value.push({ id: newId, ...formData });
  showForm.value = false;
};

// Delete package
const deletePackage = (id) => {
  packages.value = packages.value.filter((p) => p.id !== id);
};

// Search filter
const filteredPackages = computed(() =>
  packages.value.filter((p) =>
    p.name.toLowerCase().includes(search.value.toLowerCase())
  )
);
</script>

<style scoped>
.packages-body {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  flex-direction: column;
  box-sizing: border-box;
  padding: 20px;
}

.add-package {
  margin-bottom: 15px;
  padding: 10px 16px;
  border-radius: 6px;
  border: none;
  background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
  color: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 10px;
  margin-left: 10px;
  width: 200px;
  align-self: flex-start;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  transition: 0.3s;
}
.add-package:hover {
  opacity: 0.9;
  transform: scale(1.05);
}

.packages-table-container {
  width: 90%;
  overflow-x: auto;
  max-height: 80%;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

table {
  width: 100%;
  border-collapse: collapse;
  border-radius: 8px;
  overflow: hidden;
}
th, td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #e0e0e0;
}
th {
  background-color: #2575fc;
  color: white;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
}

tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}
tbody tr:hover {
  background-color: #eef4ff;
  transition: 0.2s;
}

.status {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: bold;
  color: white;
}
.status.active { background-color: #4caf50; }
.status.inactive { background-color: #9e9e9e; }
.status.pending { background-color: #ff9800; }

.action-btn {
  padding: 6px 10px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-size: 0.85rem;
  margin-right: 5px;
  color: white;
  transition: 0.3s;
}
.action-btn.view { background-color: #2196f3; }
.action-btn.view:hover { background-color: #1976d2; transform: scale(1.05); }
.action-btn.delete { background-color: #d32f2f; }
.action-btn.delete:hover { background-color: #b71c1c; transform: scale(1.05); }

.top-controls {
  width: 90%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.search-bar {
  background-color: #e0e0e0;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  width: 250px;
  font-size: 0.9rem;
  transition: 0.3s;
}
.search-bar:focus {
  border-color: #2575fc;
  outline: none;
  box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 12px;
  width: 90%;
  max-width: 850px;
  max-height: 90%;
  overflow-y: auto;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
}
</style>