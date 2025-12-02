<template>
  <div class="h-full w-full bg-gray-50 p-6">
    <div class="mb-8 flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-800">My Reservations</h2>
        <p class="text-sm text-gray-500">Track your upcoming and past trips</p>
      </div>
    </div>

    <div v-if="reservations.length > 0" class="space-y-4">
      <div
        v-for="booking in reservations"
        :key="booking.id"
        class="group relative flex overflow-hidden rounded-xl bg-white shadow-sm transition-all hover:shadow-md border border-gray-100"
      >
        <div
          class="w-2"
          :class="{
            'bg-yellow-400': booking.status === 'pending',
            'bg-green-500': booking.status === 'confirmed',
            'bg-red-500': booking.status === 'cancelled',
            'bg-gray-400': booking.status === 'completed'
          }"
        ></div>

        <div class="flex flex-1 flex-col gap-4 p-5 md:flex-row md:items-center">
          
          <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
             <img 
               v-if="booking.image" 
               :src="booking.image" 
               class="h-full w-full object-cover" 
               alt="Tour image"
             />
             <div v-else class="flex h-full w-full items-center justify-center text-gray-300">
               <span class="material-icons text-3xl">image</span>
             </div>
          </div>

          <div class="flex-1 space-y-1">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="text-lg font-bold text-gray-800">{{ booking.tourName }}</h3>
                <p class="text-sm font-medium text-[#009FB7]">{{ booking.agencyName }}</p>
              </div>
              <span class="text-lg font-bold text-gray-900">₱{{ booking.totalPrice.toLocaleString() }}</span>
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-2">
              <div class="flex items-center gap-1">
                <span class="material-icons text-base">event</span>
                {{ formatDate(booking.date) }}
              </div>
              <div class="flex items-center gap-1">
                <span class="material-icons text-base">group</span>
                {{ booking.pax }} Pax
              </div>
            </div>
          </div>

          <div class="flex flex-row items-center justify-between gap-4 border-t border-gray-100 pt-4 md:flex-col md:items-end md:border-t-0 md:pt-0">
            <span
              class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
              :class="{
                'bg-yellow-100 text-yellow-800': booking.status === 'pending',
                'bg-green-100 text-green-800': booking.status === 'confirmed',
                'bg-red-100 text-red-800': booking.status === 'cancelled',
                'bg-gray-100 text-gray-800': booking.status === 'completed'
              }"
            >
              {{ booking.status }}
            </span>

            <button class="text-sm font-semibold text-[#009FB7] hover:text-[#007A8A] hover:underline">
              View Details
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="flex h-[60vh] flex-col items-center justify-center text-center">
      <div class="mb-4 rounded-full bg-gray-100 p-6">
        <span class="material-icons text-6xl text-gray-300">event_busy</span>
      </div>
      <h3 class="text-xl font-semibold text-gray-800">No reservations yet</h3>
      <p class="mt-2 text-gray-500">Looks like you haven't booked any trips yet.</p>
      <button 
        @click="$emit('navigate', 'spots')"
        class="mt-6 rounded-lg bg-[#009FB7] px-6 py-2.5 font-semibold text-white transition-all hover:bg-[#007A8A] shadow-lg shadow-cyan-500/30"
      >
        Explore Spots
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  userInformation: Object
})

defineEmits(['navigate'])

// Helper to format date
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('en-US', options)
}

// DUMMY DATA: Replace this with props or an API call later
const reservations = ref([
  {
    id: 1,
    tourName: 'Island Hopping Adventure',
    agencyName: 'Surigao Travels Co.',
    date: '2023-11-15',
    pax: 2,
    totalPrice: 3500,
    status: 'confirmed',
    image: '/images/spots/britania.jpg' // Ensure you have images or remove this
  },
  {
    id: 2,
    tourName: 'Enchanted River Tour',
    agencyName: 'Nature Seekers',
    date: '2023-12-01',
    pax: 5,
    totalPrice: 7500,
    status: 'pending',
    image: null
  },
  {
    id: 3,
    tourName: 'Sohoton Cove Expedition',
    agencyName: 'Surigao Travels Co.',
    date: '2023-09-10',
    pax: 1,
    totalPrice: 1800,
    status: 'completed',
    image: null
  }
])
</script>