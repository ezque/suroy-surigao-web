<template>
    <div class="manage-user-body">
        <div class="user-table-container">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in 5" :key="index">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                        <td>
                            <button class="action-btn">View</button>
                            <button class="action-btn delete">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            class="users-all-information-card"
            v-if="selectedUser"
        >
            <h3>User Information</h3>
            <div class="user-info-content">
                <p><strong>ID:</strong> {{ selectedUser.id }}</p>
                <p><strong>Full Name:</strong> {{ getFullName(selectedUser) }}</p>
                <p><strong>Email:</strong> {{ selectedUser.email }}</p>
                <p><strong>Role:</strong> {{ selectedUser.role == 3 ? 'User' : selectedUser.role }}</p>
                <p><strong>Phone Number:</strong> {{ selectedUser.user_info.phone_num }}</p>
                <p><strong>Status: </strong>
                    <span :class="{
                        'status-badge': true,
                        'status-active': userStatus(selectedUser) === 'Active',
                        'status-inactive': userStatus(selectedUser) === 'Inactive',
                        'status-pending': userStatus(selectedUser) === 'Pending',
                        'status-unknown': userStatus(selectedUser) === 'Unknown'
                    }">
                        {{ userStatus(selectedUser) }}
                    </span>
                </p>
            </div>
            <button class="close-btn" @click="closeCard">Close</button>
            <button class="close-btn" @click="closeCard">Block</button>
        </div>
    </div>
</template>

<script setup>


</script>

<style scoped>
.manage-user-body {
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 24px;
    background-color: #f8fafc;
    box-sizing: border-box;
}

.add-user {
    margin-bottom: 15px;
    padding: 10px 16px;
    border-radius: 6px;
    border: 1px solid #4a90e2;
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
}

.user-table-container {
    width: 90%;
    overflow-x: auto;
    background-color: white;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: center;
    border: 1px solid black;
}

td {
    color: #374151;
}

tr:hover {
    background-color: #f9fafb;
    transition: background-color 0.25s ease;
}

/* STATUS BADGES */
.status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

th {
    background-color: #f0f0f0;
    font-weight: bold;
}

.action-btn {
    padding: 5px 5px;
    width: 100px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 0.85rem;
    margin-right: 5px;
    color: white;
    background-color: #4a90e2;
}

.action-btn.delete {
    background-color: #d32f2f;
}
.action-btn.delete:hover {
    background-color: #b71c1c;
    transform: scale(1.05);
}
</style>

