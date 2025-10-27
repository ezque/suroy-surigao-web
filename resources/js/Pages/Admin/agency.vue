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
              <span class="status" :class="getStatus(user.status).toLowerCase()">
                {{ getStatus(user.status) }}
              </span>
                    </td>
                    <td class="action-buttons">
                        <button class="edit-btn" @click="openAgency(user)">
                            <i class="material-icons">visibility</i>
                        </button>
                        <button class="delete-btn" @click="toggleAgencyStatus(user)">
                            <i class="material-icons">
                                {{ user.status == 1 ? "block" : "check_circle" }}
                            </i>
                        </button>
                    </td>
                </tr>

                <tr v-if="filteredAgencies.length === 0">
                    <td colspan="6" class="no-data">No agencies found.</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Popup -->
        <div v-if="selectedAgency" class="popup-overlay" @click.self="closePopup">
            <div class="popup-card">
                <div class="popup-header">
                    <h3>Agency Details</h3>
                    <button class="close-icon" @click="closePopup">
                        <i class="material-icons">close</i>
                    </button>
                </div>

                <div class="popup-content">
                    <div class="detail-row">
                        <span class="detail-label">Agency Name:</span>
                        <span class="detail-value">
              {{ selectedAgency.agency?.agency_name }}
            </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location:</span>
                        <span class="detail-value">
              {{ selectedAgency.agency?.location_address }}
            </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Contact:</span>
                        <span class="detail-value">
              {{ selectedAgency.agency?.contact_number }}
            </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="status" :class="getStatus(selectedAgency.status).toLowerCase()">
              {{ getStatus(selectedAgency.status) }}
            </span>
                    </div>
                </div>

                <div class="popup-actions">
                    <button class="close-btn" @click="closePopup">Close</button>
                    <button
                        class="delete-btn"
                        @click="toggleAgencyStatus(selectedAgency)"
                    >
                        {{ selectedAgency.status == 1 ? "Block Agency" : "Unblock Agency" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import axios from "axios";

    const props = defineProps({
        agencyDetails: Array,
    });

    const emit = defineEmits(["selectPage"]);

    const searchQuery = ref("");
    const selectedAgency = ref(null);

    const getStatus = (status) => {
        switch (status) {
            case "1":
            case 1:
                return "Active";
            case "2":
            case 2:
                return "Blocked";
            case "0":
            case 0:
                return "Pending";
            default:
                return "Unknown";
        }
    };

    function openAgency(user) {
        selectedAgency.value = user;
    }

    function closePopup() {
        selectedAgency.value = null;
    }

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

    function toggleAgencyStatus(user) {
        const newStatus = user.status == 1 ? 2 : 1;
        const action = newStatus === 2 ? "blocked" : "unblocked";

        axios
            .post("/update-user-status", {
                id: user.id,
                status: newStatus,
            })
            .then((response) => {
                console.log(response.data.message);

                const agencyIndex = props.agencyDetails.findIndex((u) => u.id === user.id);
                if (agencyIndex !== -1) {
                    props.agencyDetails[agencyIndex].status = newStatus;
                }

                if (selectedAgency.value && selectedAgency.value.id === user.id) {
                    selectedAgency.value.status = newStatus;
                }

                alert(`Agency has been ${action} successfully.`);
            })
            .catch((error) => {
                console.error(error.response?.data || error);
                alert("Failed to update agency status.");
            });
    }
</script>

<style scoped>
    .manage-agency-body {
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
        padding: 20px;
        overflow-y: auto;
    }
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
    .status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: black;
        display: inline-block;
    }
    .status.active {
        background-color: #4caf50;
    }
    .status.blocked {
        background-color: #9e9e9e;
    }
    .status.pending {
        background-color: #ff9800;
    }
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
    .popup-content {
        padding: 25px;
    }
    .popup-actions {
        display: flex;
        gap: 10px;
        padding: 20px 25px;
        background: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }
    .close-btn {
        background-color: #6c757d;
        color: white;
        border: none;
        flex: 1;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
    }
    .close-icon {
        cursor: pointer;
    }
    .delete-btn {
        flex: 1;
    }
</style>
