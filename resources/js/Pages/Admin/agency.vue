<template>
  <div class="manage-agency-body">
    <!-- Header Section -->
    <div class="header-section">
      <h2>Manage Agencies</h2>
      <button class="add-btn" @click="$emit('selectPage', 'agencyAddForm')">
        <i class="material-icons">add</i> Add Agency
      </button>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search agencies by name or location..."
        class="search-bar"
      />
    </div>

    <!-- Table -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Agency Name</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredAgencies" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.agency?.agency_name }}</td>
            <td>{{ user.agency?.location_address }}</td>
            <td>{{ user.agency?.contact_number }}</td>
            <td>
              <span
                class="status"
                :class="{
                  active: user.status === '1',
                  inactive: user.status === '2',
                  pending: user.status === '0'
                }"
              >
                {{ 
                  user.status === '1' ? 'Active' : 
                  user.status === '2' ? 'Inactive' : 'Pending' 
                }}
              </span>
            </td>
            <td class="action-buttons">
              <button class="edit-btn">
                <i class="material-icons">visibility</i>
              </button>
              <button class="delete-btn">
                <i class="material-icons">delete</i>
              </button>
            </td>
          </tr>

          <tr v-if="filteredAgencies.length === 0">
            <td colspan="6" class="no-data">No agencies found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  agencyDetails: Array,
});

const emit = defineEmits(["selectPage"]);

// search state
const searchQuery = ref("");

// computed filter
const filteredAgencies = computed(() => {
  if (!searchQuery.value) return props.agencyDetails;
  return props.agencyDetails.filter((user) => {
    const name = user.agency?.agency_name?.toLowerCase() || "";
    const location = user.agency?.location_address?.toLowerCase() || "";
    return (
      name.includes(searchQuery.value.toLowerCase()) ||
      location.includes(searchQuery.value.toLowerCase())
    );
  });
});
</script>

<style scoped>
.manage-agency-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
  padding: 20px;
  overflow-y: auto;
}

/* ===== HEADER SECTION ===== */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header-section h2 {
  color: #004C5E;
  font-weight: 700;
}

.add-btn {
  background-color: #007A8C;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  text-decoration: none;
  transition: 0.2s;
}

.add-btn:hover {
  background-color: #009FB7;
}

/* ===== SEARCH SECTION ===== */
.search-section {
  margin-bottom: 20px;
  display: flex;
  justify-content: flex-end;
}

.search-bar {
  width: 300px;
  padding: 10px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  transition: 0.3s;
}

.search-bar:focus {
  outline: none;
  border-color: #007A8C;
  box-shadow: 0 0 5px rgba(0, 122, 140, 0.3);
}

/* ===== TABLE ===== */
.table-container {
  width: 100%;
  background: white;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background-color: #007A8C;
  color: white;
}

th, td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tbody tr:hover {
  background-color: #f2f9fa;
}

.no-data {
  text-align: center;
  color: #999;
  font-style: italic;
}

/* ===== STATUS STYLES ===== */
.status {
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
  display: inline-block;
}

.status.active {
  background-color: #4caf50;
}

.status.inactive {
  background-color: #9e9e9e;
}

.status.pending {
  background-color: #ff9800;
}

/* ===== ACTION BUTTONS ===== */
.action-buttons {
  display: flex;
  gap: 8px;
}

.edit-btn,
.delete-btn {
  border: none;
  cursor: pointer;
  border-radius: 5px;
  padding: 6px;
  transition: 0.2s;
}

.edit-btn {
  background-color: #2196f3;
  color: white;
}

.edit-btn:hover {
  background-color: #1976d2;
}

.delete-btn {
  background-color: #E74C3C;
  color: white;
}

.delete-btn:hover {
  background-color: #C0392B;
}
</style>