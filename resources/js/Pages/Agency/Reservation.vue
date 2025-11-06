<template>
    <div class="min-h-screen bg-gray-50 p-5">
        <!-- Header and Stats Cards -->
        <div class="flex flex-col md:flex-row justify-between items-start mb-8 pb-5 border-b border-gray-200">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Reservation Management</h1>
                <p class="text-gray-500 mt-1">Manage and track all tour package reservations</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 md:mt-0 w-full md:w-auto">
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4 min-w-[140px]">
                    <div class="text-blue-500">
                        <span class="material-icons text-5xl">event</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">{{ totalReservations }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Total Reservations</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4 min-w-[140px]">
                    <div class="text-orange-500">
                        <span class="material-icons text-5xl">hourglass_top</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">{{ totalPending }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Pending</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4 min-w-[140px]">
                    <div class="text-green-600">
                        <span class="material-icons text-5xl">check_circle</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">{{ totalConfirmed }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Confirmed</div>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center gap-4 min-w-[140px]">
                    <div class="text-purple-600">
                        <span class="material-icons text-5xl">monetization_on</span>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-gray-800">₱{{ totalRevenue.toLocaleString() }}</div>
                        <div class="text-xs text-gray-400 uppercase tracking-wide">Revenue</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="bg-white p-5 rounded-xl shadow mb-5 flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-5">
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <div class="relative min-w-[250px]">
                    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search reservations..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                    >
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Tour Date</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Pax</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr v-for="reservation in paginatedReservations" :key="reservation.id" class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ reservation.id }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ reservation.full_name }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ reservation.package?.package_name }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ reservation.package?.start_date }} - {{ reservation.package?.end_date }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">{{ reservation.package?.capacity }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-700">₱{{ reservation.package?.price.toLocaleString() }}</td>
                            <td class="px-4 py-3">
                                <span
                                  :class="{
                                      'bg-yellow-100 text-yellow-800': reservation.status === 'pending',
                                      'bg-green-100 text-green-800': reservation.status === 'confirmed',
                                      'bg-red-100 text-red-800': reservation.status === 'rejected',
                                      'bg-gray-100 text-gray-600': !reservation.status
                                  }"
                                  class="px-3 py-1 rounded-full text-xs font-semibold"
                                >
                                    {{ getStatusText(reservation.status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-sm font-medium text-gray-700">
                                <button @click="openModal(reservation)" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-sm">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                <button @click="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 cursor-pointer">
                    <span class="material-icons">close</span>
                </button>
                <h2 class="text-xl font-bold text-gray-800 mb-4">Reservation Details</h2>
                <div class="space-y-2">
                    <p><span class="font-semibold">ID:</span> {{ selectedReservation.id }}</p>
                    <p><span class="font-semibold">Customer:</span> {{ selectedReservation.full_name }}</p>
                    <p><span class="font-semibold">Package:</span> {{ selectedReservation.package?.package_name }}</p>
                    <p><span class="font-semibold">Tour Date:</span> {{ selectedReservation.package?.start_date }} - {{ selectedReservation.package?.end_date }}</p>
                    <p><span class="font-semibold">Pax:</span> {{ selectedReservation.package?.capacity }}</p>
                    <p><span class="font-semibold">Amount:</span> ₱{{ selectedReservation.package?.price.toLocaleString() }}</p>
                    <p><span class="font-semibold">Status:</span> {{ getStatusText(selectedReservation.status) }}</p>

                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button
                        @click="updateReservation(selectedReservation, 'rejected')"
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                        Reject
                    </button>
                    <button
                        @click="updateReservation(selectedReservation, 'confirmed')"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
                        Accept
                    </button>
                </div>
            </div>
        </div>

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
</template>

<script setup>
    import { ref, computed } from 'vue';
    import axios from 'axios';

    const props = defineProps({
        allReservations: Array,
        totalReservations: Number,
    });

    const searchQuery = ref('');
    const showModal = ref(false);
    const selectedReservation = ref({});

    // 💡 Pagination states
    const currentPage = ref(1);
    const itemsPerPage = 5;

    // 💡 Filtered reservations (search)
    const filteredReservations = computed(() => {
        if (!searchQuery.value) return props.allReservations;
        return props.allReservations.filter(r =>
            r.full_name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    });

    // 💡 Paginated reservations (only 5 per page)
    const paginatedReservations = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        return filteredReservations.value.slice(start, end);
    });

    // 💡 Calculate total pages
    const totalPages = computed(() =>
        Math.ceil(filteredReservations.value.length / itemsPerPage)
    );

    const totalPending = computed(() => props.allReservations.filter(r => r.status === 'pending').length);
    const totalConfirmed = computed(() => props.allReservations.filter(r => r.status === 'confirmed').length);
    const totalRevenue = computed(() => 200);

    const getStatusText = (status) => {
        switch (status) {
            case 'confirmed':
                return 'Active';
            case 'rejected':
                return 'Rejected';
            case 'pending':
                return 'Pending';
            default:
                return 'Unknown';
        }
    };

    const openModal = (reservation) => {
        selectedReservation.value = reservation;
        showModal.value = true;
    };

    const closeModal = () => {
        showModal.value = false;
    };

    const updateReservation = async (reservation, status) => {
        try {
            const response = await axios.post(`/update-reservation/${reservation.id}`, {
                status: status,
            });

            reservation.status = status;

            closeModal();

            alert(`Reservation has been ${status}.`);
        } catch (error) {
            console.error('Error updating reservation:', error);
            if (error.response?.status === 401) {
                alert('You are not authenticated. Please log in again.');
            } else if (error.response?.status === 403) {
                alert('You are not authorized to perform this action.');
            } else {
                alert('Failed to update reservation. Please try again.');
            }
        }
    };
</script>

