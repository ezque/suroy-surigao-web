<template>
  <div class="manage-user-body">
    <div class="header-actions">
      <!-- Search bar -->
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Search users..."
        class="search-bar"
      />
    </div>

    <div class="user-table-container">
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
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.id }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.contact }}</td>
            <td>
              <span class="status" :class="user.status.toLowerCase()">
                {{ user.status }}
              </span>
            </td>
            <td>
              <button class="action-btn view" @click="openUser(user)">View</button>
              <button class="action-btn delete">Block</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Popup card -->
    <div v-if="selectedUser" class="popup-overlay" @click.self="closePopup">
      <div class="popup-card">
        <h3>User Details</h3>
        <p><strong>ID:</strong> {{ selectedUser.id }}</p>
        <p><strong>Name:</strong> {{ selectedUser.name }}</p>
        <p><strong>Email:</strong> {{ selectedUser.email }}</p>
        <p><strong>Contact:</strong> {{ selectedUser.contact }}</p>
        <p><strong>Status:</strong> {{ selectedUser.status }}</p>
        <div class="popup-actions">
          <button class="close-btn" @click="closePopup">Close</button>
          <button class="block-btn">Block User</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";

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
  display: flex;
  align-items: center;
  flex-direction: column;
  box-sizing: border-box;
  padding: 20px;
}

.header-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 90%;
  margin-bottom: 15px;
}

.search-bar {
  padding: 10px 14px;
  border-radius: 6px;
  border: 1px solid #ccc;
  background-color: #e0e0e0;
  width: 250px;
  outline: none;
  transition: 0.3s;
}
.search-bar:focus {
  border-color: #2575fc;
  box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
}

.user-table-container {
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
td:nth-child(2), td:nth-child(3) {
  width: 20%;
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

/* Status styles */
.status {
  padding: 5px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: bold;
  color: white;
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

/* Action buttons */
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
.action-btn.view {
  background-color: #2196f3;
}
.action-btn.view:hover {
  background-color: #1976d2;
  transform: scale(1.05);
}
.action-btn.delete {
  background-color: #d32f2f;
}
.action-btn.delete:hover {
  background-color: #b71c1c;
  transform: scale(1.05);
}

/* Popup overlay */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 20;
}

/* Popup card */
.popup-card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  width: 320px;
  text-align: left;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  animation: popIn 0.2s ease-in-out;
}

.popup-card h3 {
  margin-bottom: 10px;
  color: #2575fc;
}

.popup-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}

.close-btn, .block-btn {
  padding: 8px 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: white;
}
.close-btn {
  background: #2575fc;
}
.close-btn:hover {
  background: #1b5fd3;
}
.block-btn {
  background: #d32f2f;
}
.block-btn:hover {
  background: #b71c1c;
}

/* Animation */
@keyframes popIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
