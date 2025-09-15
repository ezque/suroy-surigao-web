<template>
    <div class="manage-user-body">
        <button
            class="add-user"
        >
            <i class="material-icons-outlined">add</i>
            Add User
        </button>

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
                    <tr v-for="(user, index) in 15" :key="index">
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
    </div>
</template>

<script setup>


</script>



<style scoped>
.manage-user-body {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
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
    max-height: 80%;
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
td:nth-child(2), td:nth-child(3) {
    width: 20%;
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
</style>
