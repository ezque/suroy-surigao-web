<template>
  <div class="manage-user-body">
    <!-- Header Section -->
    <div class="header-section">
      <h2>Manage Users</h2>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search users by name or email..."
        class="search-bar"
      />
    </div>

    <!-- Table -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredUsers" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.contact }}</td>
            <td>
              <span class="status" :class="user.status.toLowerCase()">
                {{ user.status }}
              </span>
            </td>
            <td class="action-buttons">
              <button class="edit-btn" @click="openUser(user)">
                <i class="material-icons">visibility</i>
              </button>
              <button class="delete-btn">
                <i class="material-icons">block</i>
              </button>
            </td>
          </tr>

          <tr v-if="filteredUsers.length === 0">
            <td colspan="6" class="no-data">No users found.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Popup card -->
    <div v-if="selectedUser" class="popup-overlay" @click.self="closePopup">
      <div class="popup-card">
        <div class="popup-header">
          <h3>User Details</h3>
          <button class="close-icon" @click="closePopup">
            <i class="material-icons">close</i>
          </button>
        </div>
        <div class="popup-content">
          <div class="detail-row">
            <span class="detail-label">ID:</span>
            <span class="detail-value">{{ selectedUser.id }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Name:</span>
            <span class="detail-value">{{ selectedUser.name }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">{{ selectedUser.email }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Contact:</span>
            <span class="detail-value">{{ selectedUser.contact }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span class="status" :class="selectedUser.status.toLowerCase()">
              {{ selectedUser.status }}
            </span>
          </div>
        </div>
        <div class="popup-actions">
          <button class="close-btn" @click="closePopup">Close</button>
          <button class="block-btn">Block User</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const searchQuery = ref("");

// Example users
const users = ref([
  { id: 1, name: "John Doe", email: "john.doe@example.com", contact: "09123456789", status: "Active" },
  { id: 2, name: "Jane Smith", email: "jane.smith@example.com", contact: "09987654321", status: "Inactive" },
  { id: 3, name: "Michael Johnson", email: "michael.j@example.com", contact: "09223334444", status: "Active" },
  { id: 4, name: "Emily Davis", email: "emily.davis@example.com", contact: "09445556666", status: "Pending" },
  { id: 5, name: "Chris Wilson", email: "chris.wilson@example.com", contact: "09778889999", status: "Active" },
]);

const selectedUser = ref(null);

// Computed filter for search
const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value;
  return users.value.filter((user) => {
    const name = user.name?.toLowerCase() || "";
    const email = user.email?.toLowerCase() || "";
    return (
      name.includes(searchQuery.value.toLowerCase()) ||
      email.includes(searchQuery.value.toLowerCase())
    );
  });
});

function openUser(user) {
  selectedUser.value = user;
}

function closePopup() {
  selectedUser.value = null;
}
</script>

<style scoped>
.manage-user-body {
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

/* ===== POPUP OVERLAY ===== */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  backdrop-filter: blur(4px);
}

/* ===== POPUP CARD ===== */
.popup-card {
  background: white;
  padding: 0;
  border-radius: 12px;
  width: 450px;
  max-width: 90%;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
  animation: popIn 0.3s ease-out;
}

.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  background: linear-gradient(135deg, #007A8C 0%, #009FB7 100%);
  border-radius: 12px 12px 0 0;
  color: white;
}

.popup-header h3 {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 700;
}

.close-icon {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  cursor: pointer;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: 0.2s;
}

.close-icon:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

.close-icon i {
  font-size: 20px;
}

.popup-content {
  padding: 25px;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
  border-bottom: none;
}

.detail-label {
  font-weight: 600;
  color: #555;
  font-size: 0.95rem;
}

.detail-value {
  color: #333;
  font-size: 0.95rem;
}

.popup-actions {
  display: flex;
  gap: 10px;
  padding: 20px 25px;
  background: #f8f9fa;
  border-radius: 0 0 12px 12px;
}

.close-btn,
.block-btn {
  flex: 1;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.95rem;
  transition: 0.2s;
}

.close-btn {
  background-color: #6c757d;
  color: white;
}

.close-btn:hover {
  background-color: #5a6268;
}

.block-btn {
  background-color: #E74C3C;
  color: white;
}

.block-btn:hover {
  background-color: #C0392B;
}

/* Animation */
@keyframes popIn {
  from {
    transform: scale(0.8) translateY(-20px);
    opacity: 0;
  }
  to {
    transform: scale(1) translateY(0);
    opacity: 1;
  }
}
</style>