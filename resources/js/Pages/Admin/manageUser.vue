    <template>
    <div class="manage-user-body">
        <div class="header-actions">
        <button class="add-user">
            <i class="material-icons-outlined">add</i>
            Add User
        </button>

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
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td>John Doe</td>
                <td>john.doe@example.com</td>
                <td>Admin</td>
                <td><span class="status active">Active</span></td>
                <td>
                <button class="action-btn view">View</button>
                <button class="action-btn delete">🗑 Delete</button>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jane Smith</td>
                <td>jane.smith@example.com</td>
                <td>User</td>
                <td><span class="status inactive">Inactive</span></td>
                <td>
                <button class="action-btn view">View</button>
                <button class="action-btn delete">🗑 Delete</button>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td>Michael Johnson</td>
                <td>michael.j@example.com</td>
                <td>Moderator</td>
                <td><span class="status active">Active</span></td>
                <td>
                <button class="action-btn view">View</button>
                <button class="action-btn delete">🗑 Delete</button>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td>Emily Davis</td>
                <td>emily.davis@example.com</td>
                <td>User</td>
                <td><span class="status pending">Pending</span></td>
                <td>
                <button class="action-btn view">View</button>
                <button class="action-btn delete">🗑 Delete</button>
                </td>
            </tr>
            <tr>
                <td>5</td>
                <td>Chris Wilson</td>
                <td>chris.wilson@example.com</td>
                <td>User</td>
                <td><span class="status active">Active</span></td>
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
    import { ref } from "vue";

    const searchQuery = ref("");
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

    /* Flex row for button + search */
    .header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 90%;
    margin-bottom: 15px;
    }

    .add-user {
    padding: 10px 16px;
    border-radius: 6px;
    border: none;
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 180px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: 0.3s;
    }
    .add-user:hover {
    opacity: 0.9;
    transform: scale(1.05);
    }

    /* Search bar same as agency */
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
    </style>
