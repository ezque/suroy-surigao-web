<template>
    <div class="packages-body">
        <div class="header-section">
            <div class="header-text">
                <h2>Manage Tour Packages</h2>
                <p>Add, edit, and manage your tour packages.</p>
            </div>
            <button class="add-btn" @click="handleAddPackageClick">
                <i class="material-icons">add</i>
                <span>Add New Package</span>
            </button>
        </div>

        <div class="controls-wrapper">
            <div class="search-bar-wrapper">
                <i class="material-icons">search</i>
                <input
                    type="text"
                    v-model="search"
                    placeholder="Search packages by name..."
                />
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Package / Tour Name</th>
                    <th>Available Slot</th>
                    <th>Status</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody v-if="filteredPackages.length > 0">
                <tr v-for="pkg in filteredPackages" :key="pkg.id">
                    <td class="package-name-cell">
                        <span class="package-name">{{ pkg.package_name }}</span>
                        <span class="package-id">ID: {{ pkg.id }}</span>
                    </td>
                    <td>{{ pkg.available_slot }}</td>
                    <td>
                <span
                    class="status"
                    :class="pkg.status == 1 ? 'active' : 'inactive'"
                >
                    {{ pkg.status == 1 ? 'Active' : 'Inactive' }}
                </span>
                    </td>
                    <td>{{ pkg.capacity }} people</td>
                    <td class="action-buttons">
                        <button
                            class="icon-btn view-btn"
                            title="View Details"
                            @click="handleViewPackageClick(pkg)"
                        >
                            <i class="material-icons">visibility</i>
                        </button>
                        <button
                            class="icon-btn edit-btn"
                            @click="handleEditPackageClick(pkg)"
                            title="Edit Package"
                        >
                            <i class="material-icons">edit</i>
                        </button>
                        <button
                            class="icon-btn delete-btn"
                            @click="deletePackage(pkg.id)"
                            title="Delete Package"
                        >
                            <i class="material-icons">delete</i>
                        </button>
                    </td>
                </tr>
                </tbody>
                <tbody v-else>
                <tr class="empty-row">
                    <td colspan="5">
                        <i class="material-icons">search_off</i>
                        <p>No packages found</p>
                        <span>Try adjusting your search query.</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showForm" class="modal-overlay" @click="handleCancel">
            <div class="modal-content" @click.stop>
                <button class="modal-close-btn" @click="handleCancel">
                    <i class="material-icons">close</i>
                </button>
                <AddPackages
                    :packageData="editingPackage"
                    @submit="handleFormSubmit"
                    @cancel="handleCancel"
                    :allSpots="allSpots"
                />
            </div>
        </div>
        <div v-if="showViewBox" class="modal-overlay" @click="closeViewBox">
            <div class="view-box" @click.stop>
                <button class="modal-close-btn" @click="closeViewBox">
                    <i class="material-icons">close</i>
                </button>
                <h3>{{ viewingPackage.package_name }}</h3>
                <p><strong>ID:</strong> {{ viewingPackage.id }}</p>
                <p><strong>Status:</strong> {{ viewingPackage.status == 1 ? 'Active' : 'Inactive' }}</p>
                <p><strong>Available Slot:</strong> {{ viewingPackage.available_slot }}</p>
                <p><strong>Capacity:</strong> {{ viewingPackage.capacity }} people</p>
                <p><strong>Description:</strong></p>
                <p>{{ viewingPackage.description }}</p>
                <p><strong>Duration:</strong> {{ viewingPackage.duration }}</p>
                <p><strong>Available On:</strong> {{ viewingPackage.available_On }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import AddPackages from "./addPackages.vue";
    import axios from "axios";

    const props = defineProps({
        usePageNavigation: Boolean,
        allSpots: Array,
        allPackages: Array, // comes from Laravel controller
    });

    const emit = defineEmits(["selectPage"]);

    const showForm = ref(false);
    const search = ref("");
    const editingPackage = ref(null);
    const viewingPackage = ref({});
    const showViewBox = ref(false);

    // --- Modal and Form Handling ---
    const handleAddPackageClick = () => {
        if (props.usePageNavigation) {
            emit("selectPage", "agencyAddPackage");
        } else {
            editingPackage.value = null; // Ensure we are in "add" mode
            showForm.value = true;
        }
    };

    const handleEditPackageClick = (pkg) => {
        // Create a copy to avoid mutating the original object directly
        editingPackage.value = { ...pkg };
        showForm.value = true;
    };

    const handleFormSubmit = (formData) => {
        if (editingPackage.value) {
            // TODO: call API for update instead of just frontend change
            const index = props.allPackages.findIndex(
                (p) => p.id === editingPackage.value.id
            );
            if (index !== -1) {
                props.allPackages[index] = { ...editingPackage.value, ...formData };
            }
        } else {
            // TODO: call API for store instead of just frontend push
            console.log("Saving new package...", formData);
        }
        showForm.value = false;
        editingPackage.value = null;
    };

    const handleCancel = () => {
        showForm.value = false;
        editingPackage.value = null;
    };

    // --- Package Actions ---
    const deletePackage = async (id) => {
        if (confirm("Are you sure you want to delete this package? This action cannot be undone.")) {
            try {
                // Retrieve token (assuming stored in localStorage)
                const token = localStorage.getItem("access_token"); // or from AsyncStorage if in React Native

                const response = await axios.delete(`/packages/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: "application/json",
                    },
                });

                if (response.data.success) {
                    alert("Package deleted successfully!");
                    // Remove from list on frontend
                    const index = props.allPackages.findIndex(p => p.id === id);
                    if (index !== -1) props.allPackages.splice(index, 1);
                } else {
                    alert(response.data.message || "Failed to delete package.");
                }

            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || "An error occurred while deleting the package.");
            }
        }
    };


    // --- Computed Properties ---
    const filteredPackages = computed(() =>
        props.allPackages.filter((p) =>
            p.package_name.toLowerCase().includes(search.value.toLowerCase())
        )
    );

    const handleViewPackageClick = (pkg) => {
        viewingPackage.value = { ...pkg };
        showViewBox.value = true;
    };
    const closeViewBox = () => {
        showViewBox.value = false;
        viewingPackage.value = {};
    };
</script>


<style scoped>
    .packages-body {
      width: 100%;
      height: 100%;
      background-color: #f8fafc;
      padding: 24px 32px;
      overflow-y: auto;
    }

    /* ===== HEADER ===== */
    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .header-text h2 {
      color: #1e293b;
      font-weight: 700;
      font-size: 1.75rem;
      margin: 0;
    }
    .header-text p {
        color: #64748b;
        margin-top: 4px;
    }
    .add-btn {
      background-color: #007A8C;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      font-size: 0.9rem;
      transition: background-color 0.2s ease, box-shadow 0.2s ease;
    }
    .add-btn:hover {
      background-color: #009FB7;
      box-shadow: 0 4px 12px rgba(0, 122, 140, 0.2);
    }

    /* ===== CONTROLS (SEARCH) ===== */
    .controls-wrapper {
        margin-bottom: 24px;
    }
    .search-bar-wrapper {
      position: relative;
      width: 100%;
      max-width: 400px;
    }
    .search-bar-wrapper i {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      left: 12px;
      color: #94a3b8;
    }
    .search-bar-wrapper input {
      width: 100%;
      padding: 10px 16px 10px 40px;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      background-color: #ffffff;
      font-size: 1rem;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .search-bar-wrapper input::placeholder {
        color: #94a3b8;
    }
    .search-bar-wrapper input:focus {
      border-color: #007A8C;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0, 122, 140, 0.15);
    }

    /* ===== TABLE ===== */
    .table-container {
      width: 100%;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      border: 1px solid #e2e8f0;
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    /* REVERTED STYLES FOR THEAD */
    thead {
      background-color: #007A8C;
    }
    th {
      padding: 12px 16px;
      text-align: left;
      font-size: 0.8rem;
      font-weight: 600;
      color: white; /* Changed back to white */
      text-transform: uppercase;
      letter-spacing: 0.5px;
      border-bottom: 1px solid #e2e8f0;
    }
    td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
      color: #334155;
      vertical-align: middle;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    tbody tr:hover {
      background-color: #f2f9fa; /* Kept original hover color */
    }
    .package-name-cell {
        display: flex;
        flex-direction: column;
    }
    .package-name {
        font-weight: 600;
        color: #1e293b;
    }
    .package-id {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* ===== STATUS TAGS ===== */
    .status {
      padding: 4px 12px;
      border-radius: 999px;
      font-size: 0.8rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .status::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .status.active { background-color: #dcfce7; color: #166534; }
    .status.active::before { background-color: #22c55e; }
    .status.pending { background-color: #fef9c3; color: #854d0e; }
    .status.pending::before { background-color: #facc15; }
    .status.inactive { background-color: #f1f5f9; color: #475569; }
    .status.inactive::before { background-color: #64748b; }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
      display: flex;
      gap: 8px;
    }
    .icon-btn {
      border: none;
      cursor: pointer;
      border-radius: 50%;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: transparent;
      transition: background-color 0.2s;
    }
    .icon-btn i {
        font-size: 20px;
    }
    .view-btn { color: #0ea5e9; }
    .view-btn:hover { background-color: #e0f2fe; }
    .edit-btn { color: #f59e0b; }
    .edit-btn:hover { background-color: #fef9c3; }
    .delete-btn { color: #ef4444; }
    .delete-btn:hover { background-color: #fee2e2; }

    /* ===== EMPTY TABLE STATE ===== */
    .empty-row td {
        text-align: center;
        padding: 48px;
        color: #64748b;
    }
    .empty-row i {
        font-size: 48px;
        margin-bottom: 8px;
    }
    .empty-row p {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
    }
    .empty-row span {
        font-size: 0.9rem;
    }

    /* ===== MODAL ===== */
    .modal-overlay {
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
    }
    .modal-content {
      background: white;
      padding: 32px;
      border-radius: 16px;
      width: 90%;
      max-width: 850px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    .modal-close-btn {
        position: absolute;
        top: 16px;
        right: 16px;
        border: none;
        background: transparent;
        cursor: pointer;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: background-color 0.2s, color 0.2s;
    }
    .modal-close-btn:hover {
        background-color: #f1f5f9;
        color: #1e293b;
    }

    /* ===== RESPONSIVENESS ===== */
    @media (max-width: 768px) {
        .packages-body {
            padding: 16px;
        }
        .header-section {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        .add-btn span {
            display: none; /* Hide text on small screens */
        }
        .add-btn {
            padding: 10px;
            border-radius: 50%;
        }
        .add-btn i {
            margin-right: 0;
        }
    }
    .view-box {
        background: white;
        padding: 32px;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        position: relative;
    }
    .view-box h3 {
        margin-top: 0;
        margin-bottom: 16px;
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .view-box p {
        margin: 8px 0;
        color: #475569;
        line-height: 1.5;
    }

</style>
