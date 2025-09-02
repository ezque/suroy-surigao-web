<template>
    <div class="agency-main-body">
        <button class="add-agency">
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
                <tr>
                    <td>1</td>
                    <td>Sunset Travel Tours</td>
                    <td>Manila, PH</td>
                    <td>+63 912 345 6789</td>
                    <td>Active</td>
                    <td>
                        <button class="action-btn">View</button>
                        <button class="action-btn delete">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Island Adventures</td>
                    <td>Cebu, PH</td>
                    <td>+63 987 654 3210</td>
                    <td>Inactive</td>
                    <td>
                        <button class="action-btn">View</button>
                        <button class="action-btn delete">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>GlowHub</td>
                    <td>Davao, PH</td>
                    <td>+63 923 456 7890</td>
                    <td>Active</td>
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
// Static rows for now; can replace with v-for later
</script>

<style scoped>
.agency-main-body {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}

.add-agency {
    margin-bottom: 15px;
    padding: 10px 16px;
    border-radius: 6px;
    border: 1px solid #4a90e2;
    background-color: #4a90e2;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.agency-table-container {
    width: 100%;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ccc;
}

th {
    background-color: #f0f0f0;
    font-weight: bold;
}

.action-btn {
    padding: 5px 10px;
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
