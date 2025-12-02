<template>
    <div class="container mx-auto p-6 min-h-screen bg-gradient-to-br from-[#f9fbfd] to-[#e8f1f9] animate-[fadeInUp_0.6s_ease-out]">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <button
                @click="$emit('goBack')"
                class="flex items-center gap-2 bg-gradient-to-r from-[#3b82f6] to-[#2563eb] text-white font-semibold px-5 py-3 rounded-xl shadow-lg hover:-translate-y-1 hover:shadow-blue-400 transition-all duration-300"
            >
                ← Back to Agencies
            </button>
        </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-10 hover:shadow-2xl transition-all duration-300">
            <div class="relative h-[300px]">
                <img :src="agency.agency?.image_path
                    ? `/storage/${agency.agency.image_path}`
                    : '/images/logo/logo.png'"
                     alt="Agency image"
                     class="w-full h-full object-cover"
                />
                 <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            </div>

            <div class="p-8 relative">
                <h1 class="text-4xl font-extrabold mb-4 text-gray-900">{{ props.agency.agency?.agency_name }}</h1>
                <p class="text-gray-600 mb-6">{{ props.agency.agency?.description || 'Professional and adventurous agency.' }}</p>
            </div>
        </div>


        <div>
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ filter === 'popular' ? '🔥 Popular Packages' : 'Available Tour Packages' }}
                </h2>

                <div class="flex gap-3 flex-wrap justify-center">
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

                    <button
                        class="px-6 py-2 rounded-full font-semibold border-2 transition-all duration-300 flex items-center gap-2"
                        :class="filter === 'popular' ? 'bg-orange-500 text-white border-orange-500' : 'bg-white border-gray-300 hover:border-orange-500 hover:text-orange-500'"
                        @click="filter = 'popular'"
                    >
                        <span v-if="filter !== 'popular'">🔥</span> Popular Packages
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div v-for="pkg in filteredPackages" :key="pkg.id"
                     :class="[
                        'bg-white rounded-2xl p-6 shadow-md transition-all duration-300 relative overflow-hidden',
                        pkg.available_slot === 0 ? 'opacity-70 border border-gray-200' : 'hover:-translate-y-1 hover:shadow-xl'
                    ]"
                >
                    <div v-if="filter === 'popular'" class="absolute top-0 right-0 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-bl-xl z-10">
                        HOT PICK
                    </div>

                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 pr-8">{{ pkg.package_name }}</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap"
                              :class="pkg.available_slot > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                        >
                            {{ pkg.available_slot > 0 ? `${pkg.available_slot} slots left` : 'Fully Booked' }}
                        </span>
                    </div>

                    <p class="text-gray-600 mb-4">{{ pkg.description }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 bg-gray-50 p-4 rounded-xl">
                        <div class="flex items-center gap-2 font-semibold text-blue-600">₱{{ pkg.price.toLocaleString() }}</div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">👥 {{ pkg.capacity }} pax</div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">🕒 {{ pkg.duration }}</div>
                    </div>

                    <div class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider font-bold">Start</span>
                                <p class="text-gray-700 text-sm font-medium">{{ formatDate(pkg.start_date) }} <span class="text-gray-400">|</span> {{ formatTime(pkg.start_time) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs uppercase tracking-wider font-bold">End</span>
                                <p class="text-gray-700 text-sm font-medium">{{ formatDate(pkg.end_date) }} <span class="text-gray-400">|</span> {{ formatTime(pkg.end_time) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2 text-sm">📍 Pick-up Point</h4>
                        <p class="text-gray-600 text-sm">{{ pkg.pickup_point }}</p>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center gap-2 text-sm">🏖️ Destinations</h4>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="destinationId in JSON.parse(pkg.tour_destination)"
                                :key="destinationId"
                                class="px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100"
                            >
                                {{ getSpotName(destinationId) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50/50 p-3 rounded-lg border border-green-100">
                            <h4 class="font-bold text-green-700 mb-1 text-sm flex items-center gap-2">✅ Inclusions</h4>
                            <p class="text-gray-600 text-xs leading-relaxed">{{ pkg.inclusions }}</p>
                        </div>
                        <div class="bg-red-50/50 p-3 rounded-lg border border-red-100">
                            <h4 class="font-bold text-red-700 mb-1 text-sm flex items-center gap-2">❌ Exclusions</h4>
                            <p class="text-gray-600 text-xs leading-relaxed">{{ pkg.exclusions }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-auto">
                        <button
                            :class="[
                                'w-full py-3 rounded-xl font-bold text-white transition-all shadow-md',
                                pkg.available_slot === 0
                                ? 'bg-gray-400 cursor-not-allowed shadow-none'
                                : 'bg-gradient-to-r from-[#009FB7] to-[#007A8A] hover:shadow-lg hover:-translate-y-0.5'
                            ]"
                            :disabled="pkg.available_slot === 0"
                            @click="openReservationForm(pkg)"
                        >
                            <span v-if="pkg.available_slot > 0" class="flex items-center justify-center gap-2">
                                Reserve Slot
                            </span>
                            <span v-else class="flex items-center justify-center gap-2">
                                🔒 Fully Booked
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="filteredPackages.length === 0" class="text-center p-16 bg-white rounded-2xl shadow-xl mt-6 border border-dashed border-gray-300">
                <div class="text-6xl mb-5 grayscale opacity-50">🏝️</div>
                <h3 class="text-gray-900 text-xl font-semibold mb-2.5">No Packages Found</h3>
                <p class="text-gray-500 text-sm">
                    {{ filter === 'popular' ? 'No trending packages at the moment.' : 'No packages match your filter.' }}
                </p>
                <button @click="filter = 'all'" class="mt-4 text-[#3498db] font-semibold hover:underline">View all packages</button>
            </div>
        </div>

        <div v-if="showReservationForm" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-[1000] p-5 animate-fadeIn">
            <div class="bg-white rounded-[20px] max-w-[800px] w-full max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_rgba(0,0,0,0.3)] animate-slideUp relative">
                <ReservePackage
                    :package="selectedPackage"
                    :userInformation="userInformation"
                    @close="showReservationForm = false"
                />
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
        userInformation: Object,
    });

    const emit = defineEmits(["goBack"]);

    const filter = ref('all');
    const established = new Date(props.agency.created_at).getFullYear();
    const spots = ref([]);

    const showReservationForm = ref(false);
    const selectedPackage = ref(null);

    const filteredPackages = computed(() => {
        if (!props.agency.packages) return [];

        let result = [...props.agency.packages]; // Create a copy to avoid mutating prop

        // Available Filter
        if (filter.value === 'available') {
            return result.filter(pkg => pkg.status === '1' && pkg.available_slot > 0);
        }

        // Popular Filter
        // Logic: Show available packages, sorted by lowest slots remaining (High Demand)
        if (filter.value === 'popular') {
            return result
                .filter(pkg => pkg.status === '1' && pkg.available_slot > 0)
                .sort((a, b) => a.available_slot - b.available_slot); // Ascending: fewer slots = more popular
        }

        return result;
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