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
                    <div class="flex gap-[2px] mb-[5px] flex-row cursor-pointer">
                        <span
                            v-for="n in 5"
                            :key="n"
                            class="text-[1.8rem] transition-all duration-200 cursor-pointer"
                            :class="{
                                'text-yellow-400': n <= (hoverRating || userRating || spot.rating),
                                'text-gray-400': n > (hoverRating || userRating || spot.rating)
                            }"
                            @mouseover="hoverRating = n"
                            @mouseleave="hoverRating = 0"
                            @click="setRating(n)"
                        >
                           ★
                        </span>
                    </div>

                    <div class="text-[1.5rem] font-bold text-[#1a3c5a]">
                        {{ Math.round(spot.rating) || 0 }}/5
                    </div>

                    <div class="text-[#4a6572] text-[0.9rem]">
                        ({{ spot.reviews_count || 0 }} reviews)
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
                    :userInformation="userInformation"
                />
                <Review
                    v-if="activeTab === 'reviews'"
                    :spotId="spot.id"
                />
            </div>
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-[#1a3c5a] mb-3">📍 Location Map</h2>
                <div class="w-full h-[400px] rounded-xl overflow-hidden shadow-md border border-gray-200">
                    <iframe
                        v-if="spot.location"
                        :src="mapUrl"
                        class="w-full h-full"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>


        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted, onUnmounted, computed } from "vue";
    import axios from "axios";

    import Overview from "./Overview.vue";
    import SpotAgencies from "./SpotAgencies.vue";
    import Review from "./Review.vue"

    const props = defineProps({
        spot: Object,
        agencies: Array,
        activePackages: Array,
        userInformation: Object,
    });

    const emit = defineEmits(["navigate"]);

    const currentImage = ref(0);
    let interval = null;
    const isSaved = ref("2");
    const activeTab = ref("overview");
    const userRating = ref(0);
    const hoverRating = ref(0);

    const setRating = async (rating) => {
        userRating.value = rating;

        try {
            const response = await axios.post(`/spots/${props.spot.id}/rate`, {
                rating: rating,
            });

            if (response.data.new_average_rating) {
                props.spot.rating = response.data.new_average_rating;
            }

            alert(`You rated ${rating} stars!`);
        } catch (error) {
            console.error("Error saving rating:", error);
            alert("Failed to submit rating. Please try again.");
        }
    };
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

    onMounted(() => {
        checkIfSpotSaved();

        if (props.spot.images && props.spot.images.length > 1) {
            interval = setInterval(() => {
                currentImage.value = (currentImage.value + 1) % props.spot.images.length;
            }, 4000);
        }
    });
    const mapUrl = computed(() => {
        if (!props.spot.location) return "";
        const encodedLocation = encodeURIComponent(props.spot.location);
        return `https://www.google.com/maps?q=${encodedLocation}&output=embed`;
    });

</script>
