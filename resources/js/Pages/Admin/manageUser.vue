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
                <tr
                    v-for="(user, index) in allUsers"
                    :key="user.id || index"
                >
                    <td>{{ user.id }}</td>
                    <td>{{ getFullName(user) }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role == 3 ? 'User' : user.role }}</td>
                    <td>
                        <span :class="{
                            'status-badge': true,
                            'status-active': userStatus(user) === 'Active',
                            'status-inactive': userStatus(user) === 'Inactive',
                            'status-pending': userStatus(user) === 'Pending',
                            'status-unknown': userStatus(user) === 'Unknown'
                        }">
                            {{ userStatus(user) }}
                        </span>
                    </td>
                    <td>
                        <button class="action-btn view-btn" @click="viewUser(user)">View</button>
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
    import { ref } from "vue";

    const props = defineProps({
        allUsers: Array,
    });

    const selectedUser = ref(null);

    const getFullName = (user) => {
        return user.user_info
            ? `${user.user_info.firstName} ${user.user_info.lastName}`
            : "";
    };

    const userStatus = (user) => {
        if (user.status === "1") {
            return "Active";
        } else if (user.status === "2") {
            return "Inactive";
        } else if (user.status === "3") {
            return "Pending";
        }
        return "Unknown";
    };
    const userGender = (user) => {
        if (user.userInfo.gender === "1") {
            return "Male";
        } else if (user.userInfo.gender === "2") {
            return "Female";
        }
        return "Unknown";
    };

    const viewUser = (user) => {
        selectedUser.value = user;
    };

    const closeCard = () => {
        selectedUser.value = null;
    };
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
    font-family: "Inter", "Segoe UI", sans-serif;
}

.user-table-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}

th,
td {
    padding: 14px 18px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

th {
    background-color: #2563eb;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.status-active {
    background-color: #dcfce7;
    color: #166534;
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

.status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.status-unknown {
    background-color: #e5e7eb;
    color: #374151;
}

/* ACTION BUTTONS */
.action-btn {
    padding: 7px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    margin-right: 8px;
    font-weight: 500;
    transition: all 0.25s ease;
}

.view-btn {
    background-color: #2563eb;
    color: #ffffff;
}

.view-btn:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
}

.delete-btn {
    background-color: #dc2626;
    color: #ffffff;
}

.delete-btn:hover {
    background-color: #b91c1c;
    transform: translateY(-2px);
}

/* MODAL OVERLAY */
.users-all-information-card {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 360px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    padding: 24px;
    z-index: 1001;
    animation: fadeInScale 0.3s ease;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

.users-all-information-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 20px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 8px;
}

.user-info-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.user-info-content p {
    font-size: 0.9rem;
    color: #374151;
    margin: 0;
}

.user-info-content strong {
    color: #111827;
}

/* CLOSE BUTTON */
.close-btn {
    width: 100%;
    padding: 10px 16px;
    background-color: #dc2626;
    color: #ffffff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 20px;
    transition: background-color 0.25s ease, transform 0.2s ease;
}

.close-btn:hover {
    background-color: #b91c1c;
    transform: translateY(-2px);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .user-table-container {
        width: 100%;
    }

    .users-all-information-card {
        width: 90%;
    }

    th,
    td {
        padding: 10px;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    th,
    td {
        padding: 8px;
        font-size: 0.75rem;
    }

    .action-btn {
        padding: 5px 10px;
        font-size: 0.75rem;
    }
}
</style>

