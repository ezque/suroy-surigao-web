<template>
    <div class="manage-agency-body">
        <button
            class="add-agency"
            @click="$emit('selectPage', 'agencyAddForm')"
        >
            <i class="material-icons-outlined">add</i>
            Add Agency
        </button>

        <div class="agency-table-container">
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
                    <tr v-for="user in agencyDetails" :key="user.id">
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
                        <td>
                            <button class="action-btn view">View</button>
                            <button class="action-btn delete">🗑 Delete</button>
                        </td>
                    </tr>

                    <tr v-if="agencyDetails.length === 0">
                        <td colspan="6">No agencies found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    agencyDetails: Array,
})

const emit = defineEmits(["selectPage"]);
</script>

<style scoped>
.manage-agency-body {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    box-sizing: border-box;
    padding: 20px;
}

/* Add Agency Button */
.add-agency {
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
    width: 180px;
    align-self: flex-start;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: 0.3s;
}
.add-agency:hover {
    opacity: 0.9;
    transform: scale(1.05);
}

/* Table container */
.agency-table-container {
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
</style>
