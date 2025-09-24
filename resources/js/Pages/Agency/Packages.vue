<template>
  <div class="packages-body">
    <!-- Top controls -->
    <div class="top-controls">
      <button
          class="add-package"
          @click="$emit('selectPage', 'agencyAddPackage')"
      >
        <i class="material-icons-outlined">add</i>
        Add Package / Tour
      </button>

      <!-- Search bar -->
      <input
        type="text"
        placeholder="Search tours or packages..."
        class="search-bar"
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
          <!-- Example rows -->
          <tr>
            <td>1</td>
            <td>Island Hopping</td>
            <td>Daily</td>
            <td><span class="status active">Active</span></td>
            <td>20</td>
            <td>
              <button class="action-btn view">View</button>
              <button class="action-btn delete">🗑 Delete</button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Mountain Trek</td>
            <td>Weekends</td>
            <td><span class="status pending">Pending</span></td>
            <td>15</td>
            <td>
              <button class="action-btn view">View</button>
              <button class="action-btn delete">🗑 Delete</button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>River Cruise</td>
            <td>Monthly</td>
            <td><span class="status inactive">Inactive</span></td>
            <td>40</td>
            <td>
              <button class="action-btn view">View</button>
              <button class="action-btn delete">🗑 Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script setup>
    const emit = defineEmits(["selectPage"]);
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

/* Add Package Button */
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

/* Table container */
.packages-table-container {
  width: 90%;
  overflow-x: auto;
  max-height: 80%;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Table styles */
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

/* Top controls (button + search bar) */
.top-controls {
  width: 90%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

/* Search bar styling */
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
</style>
