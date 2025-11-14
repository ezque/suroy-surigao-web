<template>
    <div class="min-h-screen bg-gray-50 p-6 lg:p-10">

        <div class="flex flex-col lg:flex-row justify-between items-start mb-8 pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Tour Reservation Manager ✈️</h1>
                <p class="text-gray-500 mt-1">Manage and track all tour package reservations efficiently</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 lg:mt-0 w-full md:w-auto">
                <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 border border-gray-100 min-w-[140px]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-50 rounded-full">
                            <span class="material-icons text-2xl text-indigo-600">event</span>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ totalReservations }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wide mt-0.5">Total</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 border border-gray-100 min-w-[140px]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-yellow-50 rounded-full">
                            <span class="material-icons text-2xl text-yellow-600">hourglass_top</span>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ totalPending }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wide mt-0.5">Pending</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 border border-gray-100 min-w-[140px]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-50 rounded-full">
                            <span class="material-icons text-2xl text-green-600">check_circle</span>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ totalConfirmed }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wide mt-0.5">Confirmed</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 border border-gray-100 min-w-[140px]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gray-100 rounded-full">
                            <span class="material-icons text-2xl text-gray-600">flight_takeoff</span>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-800">{{ totalCompleted }}</div>
                            <div class="text-xs text-gray-400 uppercase tracking-wide mt-0.5">Completed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-lg mb-6 flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-5 border border-gray-100">
            <div class="flex flex-col sm:flex-row gap-4 flex-1">
                <div class="relative min-w-[300px]">
                    <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search by customer name..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition duration-150"
                    />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tour Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pax</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr v-for="reservation in paginatedReservations" :key="reservation.id" class="hover:bg-indigo-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ reservation.id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ reservation.full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ reservation.package?.package_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ reservation.package?.start_date }} - {{ reservation.package?.end_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ reservation.package?.capacity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">₱{{ reservation.package?.price.toLocaleString() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                :class="{
                                'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-300': reservation.status === 'pending',
                                'bg-green-100 text-green-700 ring-1 ring-green-300': reservation.status === 'confirmed',
                                'bg-red-100 text-red-700 ring-1 ring-red-300': reservation.status === 'rejected' || reservation.status === 'cancelled',
                                'bg-gray-100 text-gray-600 ring-1 ring-gray-300': reservation.status === 'completed' || !reservation.status
                              }"
                                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                            >
                              {{ getStatusText(reservation.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button
                                @click="openModal(reservation)"
                                class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition duration-150 text-sm font-medium shadow-md hover:shadow-lg"
                            >
                                Details
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black/40 p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-7 relative animate-slide-in border border-gray-100">
                <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 transition cursor-pointer p-1 rounded-full bg-gray-50">
                    <span class="material-icons">close</span>
                </button>
                <h2 class="text-2xl font-bold text-gray-800 mb-5 border-b pb-3">Reservation #{{ selectedReservation.id }}</h2>
                <div class="space-y-4 text-gray-700">
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Customer Name:</span> <span class="text-right font-medium">{{ selectedReservation.full_name }}</span></p>
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Phone Number:</span> <span class="text-right">{{ selectedReservation.phone_number }}</span></p>
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Package:</span> <span class="text-right font-medium text-indigo-600">{{ selectedReservation.package?.package_name }}</span></p>
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Tour Date:</span> <span class="text-right">{{ selectedReservation.package?.start_date }} - {{ selectedReservation.package?.end_date }}</span></p>
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Total Pax:</span> <span class="text-right">{{ selectedReservation.package?.capacity }}</span></p>
                    <p class="flex justify-between items-center border-t pt-3"><span class="font-semibold text-gray-800 text-lg">Total Amount:</span> <span class="text-right text-lg font-bold text-green-600">₱{{ selectedReservation?.total_amount.toLocaleString() }}</span></p>
                    <p class="flex justify-between items-center"><span class="font-semibold text-gray-600">Current Status:</span>
                        <span
                            :class="{
                            'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-300': selectedReservation.status === 'pending',
                            'bg-green-100 text-green-700 ring-1 ring-green-300': selectedReservation.status === 'confirmed',
                            'bg-red-100 text-red-700 ring-1 ring-red-300': selectedReservation.status === 'rejected' || selectedReservation.status === 'cancelled',
                            'bg-gray-100 text-gray-600 ring-1 ring-gray-300': selectedReservation.status === 'completed'
                          }"
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
                        >
                          {{ getStatusText(selectedReservation.status) }}
                        </span>
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                    <template v-if="selectedReservation.status === 'pending'">
                        <button
                            @click="updateReservation(selectedReservation, 'rejected')"
                            class="px-5 py-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition shadow-md hover:shadow-lg font-medium"
                        >
                            Reject
                        </button>
                        <button
                            @click="updateReservation(selectedReservation, 'confirmed')"
                            class="px-5 py-2 bg-green-500 text-white rounded-xl hover:bg-green-600 transition shadow-md hover:shadow-lg font-medium"
                        >
                            Accept
                        </button>
                    </template>
                    <template v-else-if="selectedReservation.status === 'confirmed'">
                        <button
                            @click="updateReservation(selectedReservation, 'cancelled')"
                            class="px-5 py-2 bg-yellow-500 text-white rounded-xl hover:bg-yellow-600 transition shadow-md hover:shadow-lg font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            @click="updateReservation(selectedReservation, 'completed')"
                            class="px-5 py-2 bg-indigo-500 text-white rounded-xl hover:bg-indigo-600 transition shadow-md hover:shadow-lg font-medium"
                        >
                            Complete Trip
                        </button>
                    </template>
                    <button
                        v-else-if="selectedReservation.status === 'rejected' || selectedReservation.status === 'completed' || selectedReservation.status === 'cancelled'"
                        @click="closeModal"
                        class="px-5 py-2 bg-gray-300 text-gray-800 rounded-xl hover:bg-gray-400 transition font-medium"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-center items-center gap-4 p-5 mt-6 border-t border-gray-200 bg-white rounded-xl shadow-lg">
            <button
                @click="currentPage--"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl flex items-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 transition font-medium"
            >
                <span class="material-icons text-base">arrow_back_ios</span> Previous
            </button>
            <span class="text-sm font-medium text-gray-600">Page **{{ currentPage }}** of **{{ totalPages }}**</span>
            <button
                @click="currentPage++"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl flex items-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-200 transition font-medium"
            >
                Next <span class="material-icons text-base">arrow_forward_ios</span>
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

const currentPage = ref(1);
const itemsPerPage = 5;

const filteredReservations = computed(() => {
    if (!searchQuery.value) return props.allReservations;
    return props.allReservations.filter(r =>
        r.full_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const paginatedReservations = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredReservations.value.slice(start, end);
});

const totalPages = computed(() =>
    Math.ceil(filteredReservations.value.length / itemsPerPage)
);

const totalPending = computed(() => props.allReservations.filter(r => r.status === 'pending').length);
const totalConfirmed = computed(() => props.allReservations.filter(r => r.status === 'confirmed').length);
const totalCompleted = computed(() => props.allReservations.filter(r => r.status === 'completed').length);
const getStatusText = (status) => {
    switch (status) {
        case 'confirmed': return 'Active';
        case 'rejected': return 'Rejected';
        case 'pending': return 'Pending';
        case 'completed': return 'Complete';
        case 'cancelled': return 'Cancelled';
        default: return 'Unknown';
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
        await axios.post(`/update-reservation/${reservation.id}`, { status });
        reservation.status = status;
        closeModal();
        alert(`Reservation has been ${status}.`);
    } catch (error) {
        console.error(error);
        alert('Failed to update reservation. Please try again.');
    }
};
</script>

<style scoped>
/* ANIMATION REMAINS UNCHANGED */
@keyframes slide-in {
    0% { transform: translateY(-20px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}
.animate-slide-in {
    animation: slide-in 0.3s ease forwards;
}
</style>
