<template>
    <div class="min-h-screen bg-slate-50 p-5">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 pb-5 border-b-2 border-slate-200">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Reservation Management</h1>
                <p class="text-slate-600 mt-1">Manage and track all tour package reservations</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 md:mt-0 w-full md:w-auto">
                <div class="bg-white p-5 rounded-xl shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="text-blue-500"><span class="material-icons text-5xl">event</span></div>
                    <div>
                        <div class="text-xl font-bold text-slate-800">{{ stats.totalReservations }}</div>
                        <div class="text-xs text-slate-500">Total Reservations</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="text-orange-500"><span class="material-icons text-5xl">hourglass_top</span></div>
                    <div>
                        <div class="text-xl font-bold text-slate-800">{{ stats.pending }}</div>
                        <div class="text-xs text-slate-500">Pending</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="text-green-600"><span class="material-icons text-5xl">check_circle</span></div>
                    <div>
                        <div class="text-xl font-bold text-slate-800">{{ stats.confirmed }}</div>
                        <div class="text-xs text-slate-500">Confirmed</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm flex items-center gap-4 min-w-[140px]">
                    <div class="text-purple-600"><span class="material-icons text-5xl">monetization_on</span></div>
                    <div>
                        <div class="text-xl font-bold text-slate-800">₱{{ stats.revenue.toLocaleString() }}</div>
                        <div class="text-xs text-slate-500">Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="bg-white p-5 rounded-xl shadow-sm mb-5 flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-5">
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <div class="relative min-w-[250px]">
                    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search reservations..."
                        class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
                <div class="flex gap-3 flex-wrap">
                    <select v-model="statusFilter" class="px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                    <select v-model="packageFilter" class="px-3 py-2.5 border border-slate-300 rounded-lg text-sm bg-white">
                        <option value="all">All Packages</option>
                        <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">{{ pkg.title }}</option>
                    </select>
                    <input v-model="dateFilter" type="date" class="px-3 py-2.5 border border-slate-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="exportReservations" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-sm font-medium transition">
                    <span class="material-icons text-lg">assessment</span> Export Report
                </button>
                <button @click="refreshData" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-lg flex items-center gap-2 text-sm font-medium transition">
                    <span class="material-icons text-lg">refresh</span> Refresh
                </button>
            </div>
        </div>

        <!-- Table/Card View Container -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-semibold text-slate-800">Reservations</h3>
                <div class="flex gap-2">
                    <button
                        @click="viewMode = 'table'"
                        :class="{ 'bg-blue-500 text-white': viewMode === 'table', 'bg-slate-200 text-slate-700': viewMode !== 'table' }"
                        class="px-3 py-2 rounded-lg flex items-center gap-2 text-sm font-medium transition"
                    >
                        <span class="material-icons text-base">list_alt</span> Table
                    </button>
                    <button
                        @click="viewMode = 'card'"
                        :class="{ 'bg-blue-500 text-white': viewMode === 'card', 'bg-slate-200 text-slate-700': viewMode !== 'card' }"
                        class="px-3 py-2 rounded-lg flex items-center gap-2 text-sm font-medium transition"
                    >
                        <span class="material-icons text-base">grid_view</span> Cards
                    </button>
                </div>
            </div>

            <!-- Table View -->
            <div v-if="viewMode === 'table'" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" v-model="selectAll" @change="toggleSelectAll" class="rounded">
                            </th>
                            <th @click="sortReservations('id')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                ID
                                <span :class="{ 'opacity-100': sortBy === 'id', 'opacity-50': sortBy !== 'id' }" class="inline-block ml-1">
                                    <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('customerName')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Customer
                                <span :class="{ 'opacity-100': sortBy === 'customerName', 'opacity-50': sortBy !== 'customerName' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('packageTitle')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Package
                                <span :class="{ 'opacity-100': sortBy === 'packageTitle', 'opacity-50': sortBy !== 'packageTitle' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('tourDate')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Tour Date
                                <span :class="{ 'opacity-100': sortBy === 'tourDate', 'opacity-50': sortBy !== 'tourDate' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('pax')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Pax
                                <span :class="{ 'opacity-100': sortBy === 'pax', 'opacity-50': sortBy !== 'pax' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('amount')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Amount
                                <span :class="{ 'opacity-100': sortBy === 'amount', 'opacity-50': sortBy !== 'amount' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th @click="sortReservations('status')" class="px-4 py-3 text-left text-sm font-medium text-slate-600 cursor-pointer hover:bg-slate-100">
                                Status
                                <span :class="{ 'opacity-100': sortBy === 'status', 'opacity-50': sortBy !== 'status' }" class="inline-block ml-1">
                                  <span class="material-icons text-xs">{{ sortOrder === 'asc' ? 'arrow_upward' : 'arrow_downward' }}</span>
                                </span>
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <tr v-for="reservation in paginatedReservations" :key="reservation.id" class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3">
                                <input type="checkbox" v-model="selectedReservations" :value="reservation.id" class="rounded">
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-700">#{{ reservation.id }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm">
                                    <div class="font-medium text-slate-900">{{ reservation.customerName }}</div>
                                    <div class="text-slate-500 text-xs">{{ reservation.contact }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm">
                                    <div class="font-medium text-slate-900">{{ reservation.packageTitle }}</div>
                                    <div class="text-slate-500 text-xs">{{ formatDate(reservation.tourDate) }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ formatDate(reservation.tourDate) }}</td>
                            <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                              {{ reservation.pax }} persons
                            </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-900">₱{{ reservation.amount.toLocaleString() }}</td>
                                    <td class="px-4 py-3">
                            <span
                                :class="{
                                'bg-orange-100 text-orange-700': reservation.status === 'pending',
                                'bg-green-100 text-green-700': reservation.status === 'confirmed',
                                'bg-red-100 text-red-700': reservation.status === 'cancelled',
                                'bg-blue-100 text-blue-700': reservation.status === 'completed'
                              }"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase"
                            >
                              {{ getStatusText(reservation.status) }}
                            </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <button @click="viewReservation(reservation)" class="p-1.5 rounded-full hover:bg-slate-100 transition" title="View Details">
                                        <span class="material-icons text-base text-slate-600">visibility</span>
                                    </button>
                                    <button @click="editReservation(reservation)" class="p-1.5 rounded-full hover:bg-slate-100 transition" title="Edit">
                                        <span class="material-icons text-base text-slate-600">edit</span>
                                    </button>
                                    <div class="relative inline-block">
                                        <button class="p-1.5 rounded-full hover:bg-slate-100 transition" title="More Actions">
                                            <span class="material-icons text-base text-slate-600">more_vert</span>
                                        </button>
                                        <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-2 z-10 hidden group-hover:block">
                                            <button v-if="reservation.status === 'pending'" @click="confirmReservation(reservation)" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2">
                                                <span class="material-icons text-sm">check</span> Confirm
                                            </button>
                                            <button v-if="reservation.status !== 'cancelled'" @click="cancelReservation(reservation)" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2">
                                                <span class="material-icons text-sm">cancel</span> Cancel
                                            </button>
                                            <button v-if="reservation.status === 'confirmed'" @click="sendReminder(reservation)" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50 flex items-center gap-2">
                                                <span class="material-icons text-sm">notifications</span> Send Reminder
                                            </button>
                                            <button @click="deleteReservation(reservation)" class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                <span class="material-icons text-sm">delete</span> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Card View -->
            <div v-else class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div
                        v-for="reservation in paginatedReservations"
                        :key="reservation.id"
                        class="bg-white border border-slate-200 rounded-xl p-5 hover:shadow-md hover:-translate-y-0.5 transition-all"
                        :class="{
              'ring-2 ring-orange-400': reservation.status === 'pending',
              'ring-2 ring-green-400': reservation.status === 'confirmed',
              'ring-2 ring-red-400': reservation.status === 'cancelled',
              'ring-2 ring-blue-400': reservation.status === 'completed'
            }"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-700">#{{ reservation.id }}</span>
                                <span
                                    :class="{
                    'bg-orange-100 text-orange-700': reservation.status === 'pending',
                    'bg-green-100 text-green-700': reservation.status === 'confirmed',
                    'bg-red-100 text-red-700': reservation.status === 'cancelled',
                    'bg-blue-100 text-blue-700': reservation.status === 'completed'
                  }"
                                    class="text-xs font-semibold uppercase px-2.5 py-0.5 rounded-full"
                                >
                  {{ getStatusText(reservation.status) }}
                </span>
                            </div>
                            <input type="checkbox" v-model="selectedReservations" :value="reservation.id" class="rounded">
                        </div>

                        <div class="space-y-3">
                            <div>
                                <h4 class="font-semibold text-slate-900">{{ reservation.customerName }}</h4>
                                <p class="text-xs text-slate-500 mt-1">{{ reservation.contact }}</p>
                            </div>
                            <div>
                                <h5 class="font-medium text-slate-800">{{ reservation.packageTitle }}</h5>
                                <p class="text-xs text-slate-600 mt-1 flex items-center gap-1">
                                    <span class="material-icons text-sm">event</span> {{ formatDate(reservation.tourDate) }}
                                </p>
                                <p class="text-xs text-slate-600 flex items-center gap-1">
                                    <span class="material-icons text-sm">group</span> {{ reservation.pax }} persons
                                </p>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                                <div class="font-bold text-slate-900">₱{{ reservation.amount.toLocaleString() }}</div>
                                <div
                                    :class="{
                    'bg-green-100 text-green-700': reservation.paymentStatus === 'paid',
                    'bg-orange-100 text-orange-700': reservation.paymentStatus === 'pending'
                  }"
                                    class="text-xs font-semibold px-2 py-0.5 rounded"
                                >
                                    {{ reservation.paymentStatus }}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button @click="viewReservation(reservation)" class="text-xs px-3 py-1.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">View</button>
                            <button @click="editReservation(reservation)" class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Edit</button>
                            <div class="relative">
                                <button class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">
                                    <span class="material-icons text-sm">more_vert</span>
                                </button>
                                <div class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-slate-200 py-2 z-10 hidden group-hover:block">
                                    <button v-if="reservation.status === 'pending'" @click="confirmReservation(reservation)" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50">Confirm</button>
                                    <button v-if="reservation.status !== 'cancelled'" @click="cancelReservation(reservation)" class="w-full px-4 py-2 text-left text-sm hover:bg-slate-50">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-4 p-5 border-t border-slate-200">
                <button
                    @click="currentPage--"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg flex items-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-300 transition"
                >
                    <span class="material-icons text-base">arrow_back</span> Previous
                </button>
                <span class="text-sm text-slate-600">Page {{ currentPage }} of {{ totalPages }}</span>
                <button
                    @click="currentPage++"
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg flex items-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-300 transition"
                >
                    Next <span class="material-icons text-base">arrow_forward</span>
                </button>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div v-if="selectedReservations.length > 0" class="mt-5 bg-sky-50 border border-sky-200 rounded-lg p-4 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="font-semibold text-sky-800">{{ selectedReservations.length }} reservation(s) selected</div>
            <div class="flex flex-wrap gap-2">
                <button @click="bulkConfirm" class="px-3 py-1.5 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition">Confirm Selected</button>
                <button @click="bulkCancel" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition">Cancel Selected</button>
                <button @click="bulkExport" class="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-sm hover:bg-slate-300 transition">Export Selected</button>
                <button @click="clearSelection" class="px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg text-sm hover:bg-slate-300 transition">Clear</button>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="selectedReservation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeModal">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6" @click.stop>
                <h2 class="text-2xl font-bold text-slate-900 mb-4">Reservation Details</h2>
                <p class="text-slate-700 mb-6">Details for <strong>#{{ selectedReservation.id }}</strong></p>
                <div class="space-y-3 text-sm text-slate-600">
                    <p><strong>Customer:</strong> {{ selectedReservation.customerName }}</p>
                    <p><strong>Contact:</strong> {{ selectedReservation.contact }}</p>
                    <p><strong>Package:</strong> {{ selectedReservation.packageTitle }}</p>
                    <p><strong>Tour Date:</strong> {{ formatDate(selectedReservation.tourDate) }}</p>
                    <p><strong>Pax:</strong> {{ selectedReservation.pax }} persons</p>
                    <p><strong>Amount:</strong> ₱{{ selectedReservation.amount.toLocaleString() }}</p>
                    <p><strong>Status:</strong> <span :class="getStatusClass(selectedReservation.status)" class="inline-block px-2 py-1 rounded text-xs font-medium">{{ getStatusText(selectedReservation.status) }}</span></p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="closeModal" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Close</button>
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

    const props = defineProps({
        allReservations: Array,
    })

    // Sample data
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
    ]);

    const packages = ref([
        { id: 'PKG001', title: 'Island Hopping Adventure' },
        { id: 'PKG002', title: 'Mountain Eco Hike' },
        { id: 'PKG003', title: 'Siargao Surf & Nature Tour' },
    ]);

    // Computed
    const stats = computed(() => {
        const total = reservations.value.length;
        const pending = reservations.value.filter(r => r.status === 'pending').length;
        const confirmed = reservations.value.filter(r => r.status === 'confirmed').length;
        const revenue = reservations.value
            .filter(r => ['confirmed', 'completed'].includes(r.status))
            .reduce((sum, r) => sum + r.amount, 0);
        return { totalReservations: total, pending, confirmed, revenue };
    });

    const filteredReservations = computed(() => {
        let filtered = reservations.value;

        if (searchQuery.value) {
            const q = searchQuery.value.toLowerCase();
            filtered = filtered.filter(r =>
                r.customerName.toLowerCase().includes(q) ||
                r.contact.toLowerCase().includes(q) ||
                r.packageTitle.toLowerCase().includes(q) ||
                r.id.toLowerCase().includes(q)
            );
        }

        if (statusFilter.value !== 'all') filtered = filtered.filter(r => r.status === statusFilter.value);
        if (packageFilter.value !== 'all') filtered = filtered.filter(r => r.packageId === packageFilter.value);
        if (dateFilter.value) filtered = filtered.filter(r => r.tourDate === dateFilter.value);

        filtered.sort((a, b) => {
            let aVal = a[sortBy.value];
            let bVal = b[sortBy.value];
            if (['date', 'tourDate'].includes(sortBy.value)) {
                aVal = new Date(aVal); bVal = new Date(bVal);
            }
            return sortOrder.value === 'asc' ? (aVal > bVal ? 1 : -1) : (aVal < bVal ? 1 : -1);
        });

        return filtered;
    });

    const totalPages = computed(() => Math.ceil(filteredReservations.value.length / itemsPerPage.value));
    const paginatedReservations = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        return filteredReservations.value.slice(start, start + itemsPerPage.value);
    });

    // Methods
    const sortReservations = (col) => {
        if (sortBy.value === col) sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
        else { sortBy.value = col; sortOrder.value = 'asc'; }
    };

    const toggleSelectAll = () => {
        selectedReservations.value = selectAll.value ? paginatedReservations.value.map(r => r.id) : [];
    };

    const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
    const getStatusText = (s) => ({ pending: 'Pending', confirmed: 'Confirmed', cancelled: 'Cancelled', completed: 'Completed' }[s] || s);
    const getStatusClass = (s) => ({
        pending: 'bg-orange-100 text-orange-700',
        confirmed: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700',
        completed: 'bg-blue-100 text-blue-700'
    }[s] || '');

    // Actions
    const viewReservation = (r) => selectedReservation.value = r;
    const editReservation = (r) => alert(`Editing ${r.id}`);
    const confirmReservation = (r) => r.status = 'confirmed';
    const cancelReservation = (r) => { if (confirm('Cancel this reservation?')) r.status = 'cancelled'; };
    const deleteReservation = (r) => { if (confirm('Delete this reservation?')) reservations.value = reservations.value.filter(x => x.id !== r.id); };
    const sendReminder = (r) => alert(`Reminder sent for ${r.id}`);
    const closeModal = () => selectedReservation.value = null;

    // Bulk
    const bulkConfirm = () => { selectedReservations.value.forEach(id => { const r = reservations.value.find(x => x.id === id); if (r?.status === 'pending') r.status = 'confirmed'; }); clearSelection(); };
    const bulkCancel = () => { if (confirm('Cancel selected?')) { selectedReservations.value.forEach(id => { const r = reservations.value.find(x => x.id === id); if (r) r.status = 'cancelled'; }); clearSelection(); } };
    const bulkExport = () => console.log('Export:', selectedReservations.value);
    const clearSelection = () => { selectedReservations.value = []; selectAll.value = false; };
    const exportReservations = () => console.log('Export all');
    const refreshData = () => alert('Data refreshed!');

    onMounted(() => { /* Fetch data */ });
</script>
