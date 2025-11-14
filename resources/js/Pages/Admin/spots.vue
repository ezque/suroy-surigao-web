<template>
    <div
        class="w-full h-full flex flex-col p-6 overflow-y-auto bg-gradient-to-br from-[#E8F4F8] to-white"
    >
        <!-- Page Header -->
        <div
            class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 pb-4 border-b border-gray-200 space-y-3 sm:space-y-0"
        >
            <h2 class="text-2xl font-semibold text-gray-800">Spots Management</h2>
            <button
                class="flex items-center justify-center gap-2 bg-[#007A8C] text-white font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-[#009FB7] hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300"
                @click="$emit('selectPage', 'addSpots')"
            >
                <i class="fas fa-plus"></i>
                Add New Spot
            </button>
        </div>

        <!-- Search Bar -->
        <div class="mb-6 w-full flex justify-start">
            <input
                type="text"
                v-model="searchQuery"
                placeholder="Search spots by name or location..."
                class="bg-gray-200 px-4 py-2 rounded-lg border border-gray-300 w-80 max-w-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
        </div>

        <!-- Spots Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <AdminSpotCard
                v-for="(spot, index) in filteredSpots"
                :key="spot.id"
                :spot="spot"
                @edit="editSpot"
                @view="viewSpot"
                @delete="deleteSpot"
            />

            <!-- No Results -->
            <div
                v-if="filteredSpots.length === 0"
                class="col-span-full text-center text-gray-500 text-base mt-5"
            >
                No spots found.
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import AdminSpotCard from './AdminSpotCard.vue';

const emit = defineEmits(['selectPage']);
const props = defineProps({
    allSpots: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const filteredSpots = computed(() => {
    return props.allSpots.filter(
        (spot) =>
            (spot.spot_name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (spot.location || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

function editSpot(spot) {
    emit('selectPage', { page: 'addSpots', spot });
}

function viewSpot(spot) {
    emit('selectPage', { page: 'spotDetails', spot });
}

    async function deleteSpot(spot) {
        if (!confirm(`Are you sure you want to delete "${spot.spot_name}"?`)) return;

        try {
            await axios.delete(`/delete-spot/${spot.id}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('authToken')}`
                }
            });
            const index = props.allSpots.findIndex(s => s.id === spot.id);
            if (index !== -1) props.allSpots.splice(index, 1);

            alert('Spot deleted successfully!');
        } catch (error) {
            console.error(error);
            alert('Failed to delete spot.');
        }
    }
</script>

