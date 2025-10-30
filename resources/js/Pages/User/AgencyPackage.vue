<template>
    <div class="container mx-auto p-6 min-h-screen bg-gradient-to-br from-[#f9fbfd] to-[#e8f1f9] animate-[fadeInUp_0.6s_ease-out]">

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <button
                @click="$emit('goBack')"
                class="flex items-center gap-2 bg-gradient-to-r from-[#3b82f6] to-[#2563eb] text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-blue-400 transition-all duration-300"
            >
                ← Back to Agencies
            </button>

            <div class="flex gap-3">
                <button class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md transition-all">
                    📤 Share
                </button>
                <button
                    @click.stop="props.toggleLike(props.agency)"
                    class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg bg-white shadow-sm hover:shadow-md transition-all"
                >
                    <span>{{ props.isLiked(props.agency.id) ? '❤️' : '🤍' }}</span>
                    {{ props.isLiked(props.agency.id) ? 'Favorites' : 'Favorite' }}
                </button>
            </div>
        </div>

        <!-- Agency Hero -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-10 hover:shadow-2xl transition-all duration-300">
            <div class="relative h-[300px]">
                <img src="/public/images/logo/logo.png" alt="Agency image" class="w-full h-full object-cover"/>
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2 shadow-md">
                    <div class="flex gap-1 text-yellow-400">
                        <span v-for="n in 5" :key="n">⭐</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">0/5</span>
                </div>
            </div>

            <div class="p-8">
                <h1 class="text-4xl font-extrabold mb-4 text-gray-900">{{ props.agency.agency?.agency_name }}</h1>
                <p class="text-gray-600 mb-6">{{ props.agency.agency?.description || 'Professional and adventurous agency.' }}</p>

                <div class="flex flex-wrap gap-6 text-gray-500">
                    <div class="flex items-center gap-2">📅 Est. {{ established }}</div>
                    <div class="flex items-center gap-2">🚌 100+ Tours</div>
                    <div class="flex items-center gap-2">💬 Response: 20</div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl flex items-center gap-4 shadow hover:scale-105 transition-transform">
                <span class="text-3xl">🏢</span>
                <div>
                    <div class="text-2xl font-bold text-gray-800">0</div>
                    <div class="text-gray-500 text-sm">Tour Packages</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl flex items-center gap-4 shadow hover:scale-105 transition-transform">
                <span class="text-3xl">⭐</span>
                <div>
                    <div class="text-2xl font-bold text-gray-800">N/A</div>
                    <div class="text-gray-500 text-sm">Rating</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl flex items-center gap-4 shadow hover:scale-105 transition-transform">
                <span class="text-3xl">👥</span>
                <div>
                    <div class="text-2xl font-bold text-gray-800">500+</div>
                    <div class="text-gray-500 text-sm">Customers</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl flex items-center gap-4 shadow hover:scale-105 transition-transform">
                <span class="text-3xl">📍</span>
                <div>
                    <div class="text-2xl font-bold text-gray-800">Surigao</div>
                    <div class="text-gray-500 text-sm">Location</div>
                </div>
            </div>
        </div>

        <!-- Packages Section -->
        <div>
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h2 class="text-3xl font-bold text-gray-900">Available Tour Packages</h2>

                <!-- Filter Buttons -->
                <div class="flex gap-3">
                    <button
                        class="px-6 py-2 rounded-full font-semibold border-2 transition-all duration-300"
                        :class="filter === 'all' ? 'bg-[#3498db] text-white border-[#3498db]' : 'bg-white border-gray-300 hover:border-[#3498db] hover:text-[#3498db]'"
                        @click="filter = 'all'"
                    >All Packages</button>

                    <button
                        class="px-6 py-2 rounded-full font-semibold border-2 transition-all duration-300"
                        :class="filter === 'available' ? 'bg-[#3498db] text-white border-[#3498db]' : 'bg-white border-gray-300 hover:border-[#3498db] hover:text-[#3498db]'"
                        @click="filter = 'available'"
                    >Available</button>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="pkg in filteredPackages" :key="pkg.id"
                     :class="[
                'bg-white rounded-2xl p-6 shadow-md transition-all duration-300',
                pkg.available_slot === 0 ? 'opacity-70 border border-gray-200' : 'hover:-translate-y-1 hover:shadow-xl'
              ]"
                >
                    <!-- Package Info (Header, Description, Quick Info, Schedule, Destinations, Inclusions/Exclusions) -->
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ pkg.package_name }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium"
                              :class="pkg.available_slot > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
              {{ pkg.available_slot > 0 ? `${pkg.available_slot} slots left` : 'Fully Booked' }}
            </span>
                    </div>

                    <p class="text-gray-600 mb-4">{{ pkg.description }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-2">💰 ₱{{ pkg.price.toLocaleString() }}/person</div>
                        <div class="flex items-center gap-2">👥 Up to {{ pkg.capacity }} persons</div>
                        <div class="flex items-center gap-2">🕒 {{ pkg.duration }}</div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">🕒 Tour Schedule</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 text-sm font-semibold">Start</span>
                                <p class="text-gray-700">{{ formatDate(pkg.start_date) }} at {{ formatTime(pkg.start_time) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 text-sm font-semibold">End</span>
                                <p class="text-gray-700">{{ formatDate(pkg.end_date) }} at {{ formatTime(pkg.end_time) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">📍 Pick-up Point</h4>
                        <p class="text-gray-600">{{ pkg.pickup_point }}</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">🏖️ Destinations</h4>
                        <div class="flex flex-wrap gap-2">
              <span
                  v-for="destinationId in JSON.parse(pkg.tour_destination)"
                  :key="destinationId"
                  class="px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-700"
              >
                {{ getSpotName(destinationId) }}
              </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div>
                            <h4 class="font-semibold text-green-600 mb-2 flex items-center gap-2">✅ Inclusions</h4>
                            <p>{{ pkg.inclusions }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-red-600 mb-2 flex items-center gap-2">❌ Exclusions</h4>
                            <p>{{ pkg.exclusions }}</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-end">
                        <button
                            :class="[
                'px-6 py-3 rounded-xl font-semibold text-white transition-all flex items-center gap-2',
                pkg.available_slot === 0
                  ? 'bg-gray-400 cursor-not-allowed'
                  : 'bg-gradient-to-br from-green-400 to-green-600 hover:from-green-600 hover:to-green-700 hover:-translate-y-1 hover:shadow-lg'
              ]"
                            :disabled="pkg.available_slot === 0"
                            @click="openReservationForm(pkg)"
                        >
              <span v-if="pkg.available_slot > 0" class="flex items-center gap-2">
                💬 Inquire / Reserve Now
                <span class="bg-white/20 px-2 py-1 rounded text-sm">₱{{ pkg.price.toLocaleString() }}</span>
              </span>
                            <span v-else class="flex items-center gap-2">🔒 Fully Booked</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Packages Message -->
            <div v-if="filteredPackages.length === 0" class="text-center p-16 bg-white rounded-2xl shadow-xl mt-6">
                <div class="text-5xl mb-5">📭</div>
                <h3 class="text-gray-900 text-xl font-semibold mb-2.5">No Packages Available</h3>
                <p class="text-gray-500 text-sm">There are no {{ filter === 'available' ? 'available' : '' }} packages at the moment.</p>
            </div>
        </div>

        <!-- Reservation Modal -->
        <div v-if="showReservationForm" class="fixed inset-0 bg-black/70 flex items-center justify-center z-[1000] p-5 animate-fadeIn">
            <div class="bg-white rounded-[20px] max-w-[800px] w-full max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.3)] animate-slideUp relative">
                <ReservePackage :package="selectedPackage" @close="showReservationForm = false"/>
            </div>
        </div>

    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from 'vue';
    import axios from "axios";
    import ReservePackage from "./reservePackage.vue";

    const props = defineProps({
        agency: Object,
        isLiked: Function,
        toggleLike: Function,
    });

    const emit = defineEmits(["goBack"]);

    const filter = ref('all');
    const established = new Date(props.agency.created_at).getFullYear();
    const spots = ref([]);

    const showReservationForm = ref(false);
    const selectedPackage = ref(null);

    const filteredPackages = computed(() => {
        if (!props.agency.packages) return [];

        if (filter.value === 'available') {
            return props.agency.packages.filter(pkg => pkg.status === '1');
        }
        return props.agency.packages;
    });

    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    };

    const formatTime = (timeString) => {
        if (!timeString) return '';
        const date = new Date(`1970-01-01T${timeString}`);
        return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit', hour12: true });
    };

    const getSpotName = (id) => {
        const spot = spots.value.find(s => s.id === id);
        return spot ? spot.spot_name : 'Unknown';
    };

    const openReservationForm = (pkg) => {
        selectedPackage.value = pkg;
        showReservationForm.value = true;
    };

    onMounted(async () => {
        const response = await axios.get('/get-spots');
        spots.value = response.data;
    });
</script>
