<template>
    <div class="flex flex-col w-full h-full overflow-y-auto bg-gradient-to-br from-[#00b4db] to-[#0083b0]">
        <!-- Hero Section -->
        <div class="relative text-center text-white overflow-hidden bg-gradient-to-br from-[#1ef5e2] to-[#f1a8a8] py-20 px-6">
            <div class="relative z-10">
                <h1 class="text-5xl font-extrabold mb-4 drop-shadow-lg animate-fadeInUp">Ali na sa Surigao!</h1>
                <p class="max-w-xl mx-auto text-lg font-light opacity-95 animate-fadeInUp delay-100">
                    Discover the most beautiful spots in Surigao del Norte
                </p>
            </div>

            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
                <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="block w-[calc(100%+1.3px)] h-20">
                    <path
                        opacity=".25"
                        fill="#ffffff"
                        d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    ></path>
                    <path
                        opacity=".5"
                        fill="#ffffff"
                        d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    ></path>
                    <path
                        fill="#ffffff"
                        d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                    ></path>
                </svg>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="sticky top-0 z-10 bg-white p-6 shadow-md">
            <div class="max-w-6xl mx-auto mb-5 flex flex-wrap gap-5">
                <!-- Search -->
                <div class="relative flex-1 min-w-[250px]">
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-[#00b4db] text-xl">search</i>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Search for beaches, parks, waterfalls..."
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl py-3 pl-12 pr-4 text-base outline-none focus:border-[#00b4db] focus:bg-white focus:shadow-md transition"
                    />
                </div>

                <!-- Category -->
                <div class="relative flex-1 min-w-[250px]">
                    <i class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-[#00b4db] text-xl">category</i>
                    <select
                        v-model="selectedCategory"
                        class="w-full bg-gray-50 border-2 border-gray-200 rounded-xl py-3 pl-12 pr-10 text-base outline-none focus:border-[#00b4db] focus:bg-white focus:shadow-md transition appearance-none cursor-pointer"
                    >
                        <option value="">All Categories</option>
                        <option value="beach">🏖️ Beaches</option>
                        <option value="park">🌳 Parks</option>
                        <option value="museum">🏛️ Museums</option>
                        <option value="waterfall">💧 Waterfalls</option>
                        <option value="mountain">⛰️ Mountains</option>
                        <option value="island">🏝️ Islands</option>
                    </select>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="max-w-6xl mx-auto flex flex-wrap gap-3">
                <div class="flex items-center gap-2 px-4 py-2 text-white font-semibold text-sm rounded-full shadow-md bg-gradient-to-r from-[#00b4db] to-[#0083b0] hover:-translate-y-0.5 transition">
                    <i class="material-icons text-lg">explore</i>
                    <span>{{ filteredSpots.length }} Spots</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 text-white font-semibold text-sm rounded-full shadow-md bg-gradient-to-r from-[#00b4db] to-[#0083b0] hover:-translate-y-0.5 transition">
                    <i class="material-icons text-lg">star</i>
                    <span>Top Rated</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 text-white font-semibold text-sm rounded-full shadow-md bg-gradient-to-r from-[#00b4db] to-[#0083b0] hover:-translate-y-0.5 transition">
                    <i class="material-icons text-lg">trending_up</i>
                    <span>Trending</span>
                </div>
            </div>
        </div>

        <!-- Spots Grid -->
        <div class="flex-1 bg-gray-100 p-10">
            <div v-if="filteredSpots.length > 0" class="grid grid-cols-[repeat(auto-fill,minmax(320px,1fr))] gap-8 max-w-6xl mx-auto">
                <div
                    v-for="spot in filteredSpots"
                    :key="spot.spot_id"
                    class="bg-white rounded-2xl shadow-lg overflow-hidden transition-transform duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer"
                >
                    <div class="relative h-56 overflow-hidden">
                        <img
                            v-if="spot.images && spot.images.length > 0"
                            :src="`/storage/${spot.images[0].spot_image}`"
                            :alt="spot.spot_name"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-transparent to-black/80 opacity-0 hover:opacity-100 transition"
                        >
                            <button
                                @click.stop="goToExplore(spot)"
                                class="flex items-center gap-2 bg-white text-[#00b4db] font-semibold px-5 py-2.5 rounded-full shadow-md hover:bg-[#00b4db] hover:text-white transition"
                            >
                                <i class="material-icons">visibility</i>
                                <span>Explore Now</span>
                            </button>
                        </div>
                        <div
                            class="absolute top-3 right-3 bg-white/90 px-3 py-1.5 rounded-full text-sm font-semibold shadow-md"
                        >
                            {{ getCategoryLabel(spot.category) }}
                        </div>
                    </div>

                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">{{ spot.spot_name }}</h3>
                        <div class="flex justify-between text-gray-500 text-sm mb-3">
                            <div class="flex items-center gap-1">
                                <i class="material-icons text-yellow-400 text-lg">star</i>
                                <span>{{ spot.rating || 'No Rating' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="material-icons text-[#00b4db] text-lg">place</i>
                                <span>{{ spot.location || 'Location not Found' }}</span>
                            </div>
                        </div>
                        <div class="flex gap-3 pt-3 border-t border-gray-200">
                            <button class="flex-1 bg-gray-50 rounded-lg py-2 text-gray-500 hover:bg-[#00b4db] hover:text-white transition">
                                <i class="material-icons">bookmark_border</i>
                            </button>
                            <button class="flex-1 bg-gray-50 rounded-lg py-2 text-gray-500 hover:bg-[#00b4db] hover:text-white transition">
                                <i class="material-icons">share</i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-gray-500 py-20">
                <i class="material-icons text-8xl text-gray-300 mb-5">search_off</i>
                <h3 class="text-2xl font-semibold mb-2">No spots found</h3>
                <p class="text-gray-400">Try adjusting your search or filter to find what you're looking for</p>
            </div>
        </div>
    </div>
</template>


<script setup>
    import { ref, computed } from "vue";

    const emit = defineEmits(["navigate"]);

    const props = defineProps({
        allSpots: {
            type: Array,
            required: true,
        },
    });

    const searchQuery = ref("");
    const selectedCategory = ref("");

    const filteredSpots = computed(() => {
        return props.allSpots.filter((spot) => {
            const matchesSearch =
                (spot.spot_name || "")
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase()) ||
                (spot.location || "")
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase());

            const matchesCategory =
                !selectedCategory.value || spot.category === selectedCategory.value;

            return matchesSearch && matchesCategory;
        });
    });

    const getCategoryLabel = (category) => {
        const labels = {
            beach: "🏖️ Beach",
            park: "🌳 Park",
            museum: "🏛️ Museum",
            waterfall: "💧 Waterfall",
            mountain: "⛰️ Mountain",
            island: "🏝️ Island",
        };
        return labels[category] || category;
    };
    const goToExplore = (spot) => {
        emit("navigate", "exploreSpots", spot);
    };
</script>
