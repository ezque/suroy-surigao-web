<template>
    <div class="manageReservation-body">
        <div class="dashboard-header">
            <div class="header-left">
                <h1 class="dashboard-title">Reservation Management</h1>
                <p class="dashboard-subtitle">Manage and track all tour package reservations</p>
            </div>
            <div class="header-right">
                <div class="quick-stats">
                    <div class="stat-card">
                        <div class="stat-icon icon-primary"><span class="material-icons">event</span></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ stats.totalReservations }}</div>
                            <div class="stat-label">Total Reservations</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-warning"><span class="material-icons">hourglass_top</span></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ stats.pending }}</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-success"><span class="material-icons">check_circle</span></div>
                        <div class="stat-info">
                            <div class="stat-value">{{ stats.confirmed }}</div>
                            <div class="stat-label">Confirmed</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-revenue"><span class="material-icons">monetization_on</span></div>
                        <div class="stat-info">
                            <div class="stat-value">₱{{ stats.revenue.toLocaleString() }}</div>
                            <div class="stat-label">Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content">
            <div class="controls-section">
                <div class="search-filter">
                    <div class="search-box">
                        <span class="material-icons search-icon">search</span>
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Search reservations..." 
                            class="search-input"
                        >
                    </div>
                    <div class="filter-group">
                        <select v-model="statusFilter" class="filter-select">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="completed">Completed</option>
                        </select>
                        <select v-model="packageFilter" class="filter-select">
                            <option value="all">All Packages</option>
                            <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                {{ pkg.title }}
                            </option>
                        </select>
                        <input 
                            v-model="dateFilter" 
                            type="date" 
                            class="date-filter"
                        >
                    </div>
                </div>
                <div class="action-buttons">
                    <button class="btn btn-primary" @click="exportReservations">
                        <span class="material-icons">assessment</span> Export Report
                    </button>
                    <button class="btn btn-secondary" @click="refreshData">
                        <span class="material-icons">refresh</span> Refresh
                    </button>
                </div>
            </div>

            <div class="reservations-table-container">
                <div class="table-header">
                    <h3>Reservations</h3>
                    <div class="table-actions">
                        <button 
                            class="btn btn-sm" 
                            :class="{ 'active': viewMode === 'table' }"
                            @click="viewMode = 'table'"
                        >
                            <span class="material-icons">list_alt</span> Table
                        </button>
                        <button 
                            class="btn btn-sm" 
                            :class="{ 'active': viewMode === 'card' }"
                            @click="viewMode = 'card'"
                        >
                            <span class="material-icons">grid_view</span> Cards
                        </button>
                    </div>
                </div>

                <div v-if="viewMode === 'table'" class="table-view">
                    <table class="reservations-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" v-model="selectAll" @change="toggleSelectAll">
                                </th>
                                <th @click="sortReservations('id')">
                                    ID 
                                    <span class="sort-icon" :class="{ active: sortBy === 'id' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('customerName')">
                                    Customer
                                    <span class="sort-icon" :class="{ active: sortBy === 'customerName' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('packageTitle')">
                                    Package
                                    <span class="sort-icon" :class="{ active: sortBy === 'packageTitle' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('date')">
                                    Tour Date
                                    <span class="sort-icon" :class="{ active: sortBy === 'date' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('pax')">
                                    Pax
                                    <span class="sort-icon" :class="{ active: sortBy === 'pax' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('amount')">
                                    Amount
                                    <span class="sort-icon" :class="{ active: sortBy === 'amount' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th @click="sortReservations('status')">
                                    Status
                                    <span class="sort-icon" :class="{ active: sortBy === 'status' }">
                                        <span class="material-icons">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                    </span>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="reservation in filteredReservations" :key="reservation.id" 
                                :class="`status-${reservation.status}`">
                                <td>
                                    <input type="checkbox" v-model="selectedReservations" :value="reservation.id">
                                </td>
                                <td class="reservation-id">#{{ reservation.id }}</td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-name">{{ reservation.customerName }}</div>
                                        <div class="customer-contact">{{ reservation.contact }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="package-info">
                                        <div class="package-title">{{ reservation.packageTitle }}</div>
                                        <div class="package-date">{{ formatDate(reservation.date) }}</div>
                                    </div>
                                </td>
                                <td>{{ formatDate(reservation.tourDate) }}</td>
                                <td>
                                    <span class="pax-badge">{{ reservation.pax }} persons</span>
                                </td>
                                <td class="amount">₱{{ reservation.amount.toLocaleString() }}</td>
                                <td>
                                    <span class="status-badge" :class="reservation.status">
                                        {{ getStatusText(reservation.status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" @click="viewReservation(reservation)" title="View Details">
                                            <span class="material-icons">visibility</span>
                                        </button>
                                        <button class="btn-icon" @click="editReservation(reservation)" title="Edit">
                                            <span class="material-icons">edit</span>
                                        </button>
                                        <div class="dropdown">
                                            <button class="btn-icon" title="More Actions"><span class="material-icons">more_vert</span></button>
                                            <div class="dropdown-menu">
                                                <button @click="confirmReservation(reservation)" v-if="reservation.status === 'pending'">
                                                    <span class="material-icons">check</span> Confirm
                                                </button>
                                                <button @click="cancelReservation(reservation)" v-if="reservation.status !== 'cancelled'">
                                                    <span class="material-icons">cancel</span> Cancel
                                                </button>
                                                <button @click="sendReminder(reservation)" v-if="reservation.status === 'confirmed'">
                                                    <span class="material-icons">notifications</span> Send Reminder
                                                </button>
                                                <button @click="deleteReservation(reservation)" class="danger">
                                                    <span class="material-icons">delete</span> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="card-view">
                     <div class="reservations-grid">
                        <div v-for="reservation in filteredReservations" :key="reservation.id" 
                                class="reservation-card" :class="`status-${reservation.status}`">
                            <div class="card-header">
                                <div class="reservation-meta">
                                    <span class="reservation-id">#{{ reservation.id }}</span>
                                    <span class="status-badge" :class="reservation.status">
                                        {{ getStatusText(reservation.status) }}
                                    </span>
                                </div>
                                <input type="checkbox" v-model="selectedReservations" :value="reservation.id">
                            </div>
                            
                            <div class="card-body">
                                <div class="customer-section">
                                    <h4>{{ reservation.customerName }}</h4>
                                    <p class="customer-contact">{{ reservation.contact }}</p>
                                </div>
                                
                                <div class="package-section">
                                    <h5>{{ reservation.packageTitle }}</h5>
                                    <p class="tour-date"><span class="material-icons">event</span> {{ formatDate(reservation.tourDate) }}</p>
                                    <p class="pax-info"><span class="material-icons">group</span> {{ reservation.pax }} persons</p>
                                </div>
                                
                                <div class="payment-section">
                                    <div class="amount">₱{{ reservation.amount.toLocaleString() }}</div>
                                    <div class="payment-status" :class="reservation.paymentStatus">
                                        {{ reservation.paymentStatus }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button class="btn btn-sm" @click="viewReservation(reservation)">View</button>
                                <button class="btn btn-sm" @click="editReservation(reservation)">Edit</button>
                                <div class="dropdown">
                                    <button class="btn btn-sm"><span class="material-icons">more_vert</span></button>
                                    <div class="dropdown-menu">
                                        <button @click="confirmReservation(reservation)" v-if="reservation.status === 'pending'">
                                            Confirm
                                        </button>
                                        <button @click="cancelReservation(reservation)" v-if="reservation.status !== 'cancelled'">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pagination">
                    <button class="btn btn-sm" :disabled="currentPage === 1" @click="currentPage--">
                        <span class="material-icons">arrow_back</span> Previous
                    </button>
                    <span class="page-info">
                        Page {{ currentPage }} of {{ totalPages }}
                    </span>
                    <button class="btn btn-sm" :disabled="currentPage === totalPages" @click="currentPage++">
                        Next <span class="material-icons">arrow_forward</span>
                    </button>
                </div>
            </div>

            <div v-if="selectedReservations.length > 0" class="bulk-actions">
                <div class="bulk-info">
                    {{ selectedReservations.length }} reservation(s) selected
                </div>
                <div class="bulk-buttons">
                    <button class="btn btn-sm" @click="bulkConfirm">Confirm Selected</button>
                    <button class="btn btn-sm btn-danger" @click="bulkCancel">Cancel Selected</button>
                    <button class="btn btn-sm" @click="bulkExport">Export Selected</button>
                    <button class="btn btn-sm" @click="clearSelection">Clear</button>
                </div>
            </div>
        </div>

        <div v-if="selectedReservation" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <div style="padding: 20px;">
                    <h2>Reservation Details</h2>
                    <p>Details for {{ selectedReservation.id }}</p>
                    <button @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Reactive data
const searchQuery = ref('');
const statusFilter = ref('all');
const packageFilter = ref('all');
const dateFilter = ref('');
const viewMode = ref('table');
const sortBy = ref('id');
const sortOrder = ref('desc');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const selectedReservations = ref([]);
const selectAll = ref(false);
const selectedReservation = ref(null);

// Sample data - replace with actual API calls
const reservations = ref([
    {
        id: 'RES001',
        customerName: 'Maria Santos',
        contact: 'maria.s@email.com | 09171234567',
        packageTitle: 'Island Hopping Adventure',
        packageId: 'PKG001',
        tourDate: '2024-12-15',
        pax: 4,
        amount: 10000,
        status: 'pending',
        paymentStatus: 'pending',
        createdAt: '2024-11-20',
        specialRequests: 'Vegetarian meal required',
        emergencyContact: 'Juan Santos - 09178889999'
    },
    {
        id: 'RES002',
        customerName: 'John Cruz',
        contact: 'john.c@email.com | 09172345678',
        packageTitle: 'Mountain Eco Hike',
        packageId: 'PKG002',
        tourDate: '2024-12-18',
        pax: 2,
        amount: 3600,
        status: 'confirmed',
        paymentStatus: 'paid',
        createdAt: '2024-11-19',
        specialRequests: '',
        emergencyContact: 'Ana Cruz - 09177778888'
    },
    // Add more sample data as needed
]);

const packages = ref([
    { id: 'PKG001', title: 'Island Hopping Adventure' },
    { id: 'PKG002', title: 'Mountain Eco Hike' },
    { id: 'PKG003', title: 'Siargao Surf & Nature Tour' },
]);

// Computed properties
const stats = computed(() => {
    const total = reservations.value.length;
    const pending = reservations.value.filter(r => r.status === 'pending').length;
    const confirmed = reservations.value.filter(r => r.status === 'confirmed').length;
    const revenue = reservations.value
        .filter(r => r.status === 'confirmed' || r.status === 'completed')
        .reduce((sum, r) => sum + r.amount, 0);
    
    return { totalReservations: total, pending, confirmed, revenue };
});

const filteredReservations = computed(() => {
    let filtered = reservations.value;

    // Apply filters
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(r => 
            r.customerName.toLowerCase().includes(query) ||
            r.contact.toLowerCase().includes(query) ||
            r.packageTitle.toLowerCase().includes(query) ||
            r.id.toLowerCase().includes(query)
        );
    }

    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(r => r.status === statusFilter.value);
    }

    if (packageFilter.value !== 'all') {
        filtered = filtered.filter(r => r.packageId === packageFilter.value);
    }

    if (dateFilter.value) {
        filtered = filtered.filter(r => r.tourDate === dateFilter.value);
    }

    // Apply sorting
    filtered.sort((a, b) => {
        let aValue = a[sortBy.value];
        let bValue = b[sortBy.value];
        
        if (sortBy.value === 'date' || sortBy.value === 'tourDate') {
            aValue = new Date(aValue);
            bValue = new Date(bValue);
        }
        
        if (sortOrder.value === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });

    return filtered;
});

const totalPages = computed(() => 
    Math.ceil(filteredReservations.value.length / itemsPerPage.value)
);

const paginatedReservations = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredReservations.value.slice(start, end);
});

// Methods
const sortReservations = (column) => {
    if (sortBy.value === column) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = column;
        sortOrder.value = 'asc';
    }
};

const toggleSelectAll = () => {
    if (selectAll.value) {
        selectedReservations.value = paginatedReservations.value.map(r => r.id);
    } else {
        selectedReservations.value = [];
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const getStatusText = (status) => {
    const statusMap = {
        pending: 'Pending',
        confirmed: 'Confirmed',
        cancelled: 'Cancelled',
        completed: 'Completed'
    };
    return statusMap[status] || status;
};

// Reservation actions
const viewReservation = (reservation) => {
    selectedReservation.value = reservation;
};

const editReservation = (reservation) => {
    // Implement edit functionality
    console.log('Edit reservation:', reservation);
    alert(`Editing ${reservation.id}`);
};

const confirmReservation = (reservation) => {
    reservation.status = 'confirmed';
    // Add API call here
};

const cancelReservation = (reservation) => {
    if (confirm('Are you sure you want to cancel this reservation?')) {
        reservation.status = 'cancelled';
        // Add API call here
    }
};

const deleteReservation = (reservation) => {
    if (confirm('Are you sure you want to delete this reservation?')) {
        reservations.value = reservations.value.filter(r => r.id !== reservation.id);
        // Add API call here
    }
};

const sendReminder = (reservation) => {
    // Implement reminder functionality
    console.log('Send reminder for:', reservation);
    alert(`Reminder sent for ${reservation.id}`);
};

const closeModal = () => {
    selectedReservation.value = null;
};

const handleReservationUpdate = (updatedReservation) => {
    const index = reservations.value.findIndex(r => r.id === updatedReservation.id);
    if (index !== -1) {
        reservations.value[index] = updatedReservation;
    }
    closeModal();
};

// Bulk actions
const bulkConfirm = () => {
    selectedReservations.value.forEach(id => {
        const reservation = reservations.value.find(r => r.id === id);
        if (reservation && reservation.status === 'pending') {
            reservation.status = 'confirmed';
        }
    });
    clearSelection();
};

const bulkCancel = () => {
    if (confirm('Are you sure you want to cancel all selected reservations?')) {
        selectedReservations.value.forEach(id => {
            const reservation = reservations.value.find(r => r.id === id);
            if (reservation) {
                reservation.status = 'cancelled';
            }
        });
        clearSelection();
    }
};

const bulkExport = () => {
    // Implement export functionality
    console.log('Export selected:', selectedReservations.value);
};

const clearSelection = () => {
    selectedReservations.value = [];
    selectAll.value = false;
};

const exportReservations = () => {
    // Implement export functionality
    console.log('Export all reservations');
};

const refreshData = () => {
    // Implement refresh functionality
    console.log('Refresh data');
    alert('Data refreshed!');
};

onMounted(() => {
    // Fetch initial data from API
});
</script>

<style scoped>
/* Recommended additions for Material Icons */
.material-icons {
    vertical-align: middle;
    margin-bottom: 2px;
}
.btn .material-icons, .btn-sm .material-icons {
    font-size: 1.2em; 
}
.search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
}
.stat-icon .material-icons {
    font-size: 2.5rem;
}
.icon-primary { color: #4299e1; }
.icon-warning { color: #dd6b20; }
.icon-success { color: #38a169; }
.icon-revenue { color: #805ad5; }
.sort-icon .material-icons {
    font-size: 1rem;
    vertical-align: sub;
}
.dropdown-menu button .material-icons {
    font-size: 1.1rem;
    margin-right: 8px;
}
.package-section p .material-icons {
    font-size: 1rem;
    margin-right: 4px;
}

/* Base Styles */
.manageReservation-body {
    width: 100%;
    height: 100vh;
    background: #f8fafc;
    overflow-y: auto;
    padding: 20px;
    box-sizing: border-box;
}

/* Header Styles */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
}
.header-left h1 {
    font-size: 2rem;
    color: #1a365d;
    margin: 0 0 8px 0;
    font-weight: 700;
}
.header-left p {
    color: #718096;
    margin: 0;
    font-size: 1.1rem;
}
.quick-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 150px;
}
.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 4px;
}
.stat-label {
    color: #718096;
    font-size: 0.9rem;
}

/* Controls Section */
.controls-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}
.search-filter {
    display: flex;
    gap: 15px;
    align-items: center;
    flex: 1;
}
.search-box {
    position: relative;
    min-width: 300px;
}
.search-input {
    width: 100%;
    padding: 10px 12px 10px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
}
.filter-group {
    display: flex;
    gap: 10px;
    align-items: center;
}
.filter-select, .date-filter {
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.9rem;
    background: white;
}
.action-buttons {
    display: flex;
    gap: 10px;
}

/* Button Styles */
.btn {
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-primary {
    background: #4299e1;
    color: white;
}
.btn-primary:hover {
    background: #3182ce;
}
.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}
.btn-secondary:hover {
    background: #cbd5e0;
}
.btn-sm {
    padding: 8px 12px;
    font-size: 0.8rem;
}
.btn-danger {
    background: #f56565;
    color: white;
}
.btn-danger:hover {
    background: #e53e3e;
}
.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 50%;
    transition: background 0.2s ease;
}
.btn-icon:hover {
    background: #edf2f7;
}

/* Table Styles */
.reservations-table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}
.table-header {
    padding: 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.table-header h3 {
    margin: 0;
    color: #2d3748;
}
.table-actions {
    display: flex;
    gap: 8px;
}
.table-actions .btn.active {
    background: #4299e1;
    color: white;
}
.reservations-table {
    width: 100%;
    border-collapse: collapse;
}
.reservations-table th {
    background: #f7fafc;
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    user-select: none;
}
.reservations-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
}
.reservations-table tbody tr:hover {
    background: #f7fafc;
}
.sort-icon {
    opacity: 0.5;
}
.sort-icon.active {
    opacity: 1;
}

/* Status Badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.pending { background: #fffaf0; color: #dd6b20; }
.status-badge.confirmed { background: #f0fff4; color: #38a169; }
.status-badge.cancelled { background: #fff5f5; color: #e53e3e; }
.status-badge.completed { background: #ebf8ff; color: #3182ce; }

/* Card View */
.card-view {
    padding: 20px;
}
.reservations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}
.reservation-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}
.reservation-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.reservation-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}
.reservation-id {
    font-weight: 600;
    color: #4a5568;
}
.card-body { margin-bottom: 15px; }
.customer-section h4 { margin: 0 0 4px 0; color: #2d3748; }
.customer-contact { color: #718096; font-size: 0.9rem; margin: 0 0 12px 0; }
.package-section h5 { margin: 0 0 8px 0; color: #4a5568; }
.tour-date, .pax-info { color: #718096; font-size: 0.9rem; margin: 4px 0; display: flex; align-items: center; }
.payment-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
}
.amount { font-weight: 700; color: #2d3748; font-size: 1.1rem; }
.payment-status { padding: 2px 6px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; }
.payment-status.paid { background: #f0fff4; color: #38a169; }
.payment-status.pending { background: #fffaf0; color: #dd6b20; }
.card-footer { display: flex; gap: 8px; justify-content: flex-end; }

/* Dropdown */
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 8px;
    min-width: 150px;
    z-index: 1000;
    display: none;
}
.dropdown:hover .dropdown-menu { display: block; }
.dropdown-menu button {
    width: 100%;
    padding: 8px 12px;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    border-radius: 4px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}
.dropdown-menu button:hover { background: #f7fafc; }
.dropdown-menu button.danger { color: #e53e3e; }
.dropdown-menu button.danger:hover { background: #fed7d7; }

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
}
.page-info { color: #718096; font-size: 0.9rem; }

/* Bulk Actions */
.bulk-actions {
    background: #ebf8ff;
    border: 1px solid #bee3f8;
    border-radius: 8px;
    padding: 15px 20px;
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.bulk-info { font-weight: 600; color: #2b6cb0; }
.bulk-buttons { display: flex; gap: 10px; }

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    border-radius: 12px;
    max-width: 90%;
    max-height: 90%;
    overflow: auto;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .quick-stats { grid-template-columns: repeat(2, 1fr); }
    .controls-section { flex-direction: column; align-items: stretch; }
    .search-filter { flex-direction: column; align-items: stretch; }
    .search-box { min-width: auto; }
}
@media (max-width: 768px) {
    .dashboard-header { flex-direction: column; gap: 20px; }
    .reservations-table { font-size: 0.8rem; }
    .reservations-table th, .reservations-table td { padding: 8px 12px; }
    .reservations-grid { grid-template-columns: 1fr; }
    .bulk-actions { flex-direction: column; gap: 10px; align-items: stretch; }
    .bulk-buttons { flex-wrap: wrap; justify-content: center; }
}
@media (max-width: 480px) {
    .manageReservation-body { padding: 10px; }
    .table-header { flex-direction: column; gap: 10px; align-items: stretch; }
    .table-actions { justify-content: center; }
}
</style>