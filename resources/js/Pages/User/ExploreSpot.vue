<template>
    <div
        class="w-full h-full overflow-y-auto [background:linear-gradient(135deg,#e0f7fa_0%,#b3e5fc_50%,#81d4fa_100%)]"
    >
        <!-- header/banner -->
        <div
            class="w-full h-[500px] relative flex items-center justify-center bg-gradient-to-b from-sky-100 via-sky-200 to-sky-300 overflow-hidden rounded-xl shadow-lg"
        >
            <img
                v-if="spot.images && spot.images.length > 0"
                :src="`/storage/${spot.images[currentImage].spot_image}`"
                :alt="spot.spot_name"
                class="w-full h-auto object-cover rounded-xl shadow-lg transition-all duration-700"
            />
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/20 to-black/50 flex justify-between items-start p-[20px]"
            >
                <button
                    class="bg-white/90 rounded-[25px] px-5 py-2.5 flex items-center gap-2.5 font-semibold cursor-pointer transition-all duration-300 hover:bg-white hover:-translate-x-1"
                    @click="backToSpots"
                >
                    <span>←</span>
                    Back to Explore
                </button>
                <button
                    @click="toggleFavorite"
                    :class="[
                        'border-none rounded-[25px] px-5 py-2.5 flex items-center gap-2.5 font-semibold cursor-pointer transition-all duration-300',
                        isSaved === '1' ? 'bg-red-500 text-white' : 'bg-white/90 text-black',
                        'hover:bg-white hover:-translate-y-0.5'
                    ]"
                >
                    <span>{{ isSaved === '1' ? '❤️' : '🤍' }}</span>
                    {{ isSaved === '1' ? 'Favorite' : 'Add to Favorites' }}
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-[1200px] mx-auto px-5 py-7.5 flex flex-col">
            <div class="flex justify-between items-start mb-7.5">
                <!-- Header -->
                <div>
                    <h1 class="capitalize text-5xl text-[#1a3c5a] mb-2.5 font-extrabold">
                        {{ spot.spot_name || "Name Not Found" }}
                    </h1>
                    <div class="flex items-center gap-2 text-slate-600 text-[1.1rem]">
                        <span>📍</span>
                        <h5 class="capitalize text-slate-600 text-[1.1rem]">
                            {{ spot.location || "Location Not Found" }}
                        </h5>
                    </div>
                </div>

                <!-- Rating -->
                <div class="text-left flex flex-col justify-end items-end">
                    <div class="flex g-[2px] mb-[5px] flex-row">
                        <span
                            v-for="n in 5"
                            :key="n"
                            class="text-[1.2rem]"
                            :class="{ filled: n <= spot.rating }"
                        >
                          {{ n <= spot.rating ? "⭐" : "☆" }}
                        </span>
                    </div>
                    <div class="text-[1.5rem] font-bold text-[#1a3c5a]">
                        {{ spot.rating || 0 }}/5
                    </div>
                    <div class="text-[#4a6572] text-[0.9rem]">
                        ({{ spot.reviewCount || 0 }} reviews)
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-[5px] mb-[30px] bg-white rounded-[15px] p-[10px] shadow-[0_4px_15px_rgba(0,0,0,0.1)]">
                <!-- Overview Tab -->
                <button
                    class="flex-1 rounded-[10px] px-5 py-3 flex items-center justify-center gap-2 text-[#4a6572] font-semibold transition-all duration-300 cursor-pointer"
                    :class="activeTab === 'overview'
                        ? 'bg-[#1a3c5a] text-white'
                        : 'bg-transparent hover:bg-[#e3f2fd]'"
                    @click="changeTab('overview')"
                >
                    📖 Overview
                </button>

                <!-- Tour Agencies Tab -->
                <button
                    class="flex-1 rounded-[10px] px-5 py-3 flex items-center justify-center gap-2 text-[#4a6572] font-semibold transition-all duration-300 cursor-pointer"
                    :class="activeTab === 'spotAgencies'
                        ? 'bg-[#1a3c5a] text-white'
                        : 'bg-transparent hover:bg-[#e3f2fd]'"
                    @click="changeTab('spotAgencies')"
                >
                    🏢 Tour Agencies
                </button>

                <!-- Reviews Tab -->
                <button
                    class="flex-1 rounded-[10px] px-5 py-3 flex items-center justify-center gap-2 text-[#4a6572] font-semibold transition-all duration-300 cursor-pointer"
                    :class="activeTab === 'reviews'
                        ? 'bg-[#1a3c5a] text-white'
                        : 'bg-transparent hover:bg-[#e3f2fd]'"
                    @click="changeTab('reviews')"
                >
                    💬 Reviews
                </button>
            </div>

            <!-- Tabs Content -->
            <div class="bg-white rounded-[20px] p-[30px] mb-[30px] shadow-[0_4px_20px_rgba(0,0,0,0.1)]">
                <Overview
                    v-if="activeTab === 'overview'"
                    :spot="spot"
                />
                <SpotAgencies
                    v-if="activeTab === 'spotAgencies'"
                    :agencies="agencies"
                    :activePackages="activePackages"
                    :spotId="spot.id"
                />
                <div v-if="activeTab === 'reviews'">
                    <!-- You can later replace this with your actual Reviews component -->
                    <p class="text-gray-600 text-center text-lg">
                        Reviews section coming soon...
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted } from "vue";
    import axios from "axios";

    import Overview from "./Overview.vue";
    import SpotAgencies from "./SpotAgencies.vue";

    const props = defineProps({
        spot: Object,
        agencies: Array,
        activePackages: Array,
    });

    const emit = defineEmits(["navigate"]);

    const currentImage = ref(0);
    let interval = null;
    const isSaved = ref("2");
    const activeTab = ref("overview");

    const checkIfSpotSaved = async () => {
        try {
            const response = await axios.get(`/check-saved-unsave-spot/${props.spot.id}`);
            isSaved.value = response.data.is_saved;
        } catch (error) {
            console.error(error);
        }
    };

    onMounted(() => {
        checkIfSpotSaved();

        if (props.spot.images && props.spot.images.length > 1) {
            interval = setInterval(() => {
                currentImage.value = (currentImage.value + 1) % props.spot.images.length;
            }, 4000);
        }
    });

    onUnmounted(() => {
        if (interval) clearInterval(interval);
    });

    const backToSpots = () => {
        emit("navigate", "spots", null);
    };

    const changeTab = (tabName) => {
        activeTab.value = tabName;
    };

    const toggleFavorite = async () => {
        try {
            const response = await axios.post("/saved-unsave-spot", {
                spot_id: props.spot.id,
            });
            isSaved.value = response.data.is_saved;
        } catch (error) {
            console.error(error);
        }
    };
</script>
