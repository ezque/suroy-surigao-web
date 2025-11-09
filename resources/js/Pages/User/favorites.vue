<template>
    <div class="p-6 w-full mx-auto">
        <!-- Header & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-5">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Saved Spots
            </h2>

            <!-- Enhanced Search Bar -->
            <div class="relative group w-full sm:w-96">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i
                        class="material-icons text-gray-400 group-focus-within:text-[#00b4db] transition-colors duration-200"
                    >
                        search
                    </i>
                </div>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search by name or location..."
                    class="w-full pl-12 pr-5 py-3 text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm
                    focus:outline-none focus:ring-2 focus:ring-[#00b4db]/20 focus:border-[#00b4db]
                    transition-all duration-200 placeholder-gray-400
                    hover:border-gray-300 hover:shadow-md"
                />
                <!-- Optional: Clear button when typing -->
                <button
                    v-if="searchQuery"
                    @click="searchQuery = ''"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600"
                >
                    <i class="material-icons text-xl">close</i>
                </button>
            </div>
        </div>

        <!-- Spot Grid -->
        <div
            v-if="filteredSpots.length > 0"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 auto-rows-fr"
        >
            <SpotCard
                v-for="spot in filteredSpots"
                :key="spot.spot_id"
                :spot="spot"
                @explore="goToExplore"
                class="transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl
                cursor-pointer rounded-xl overflow-hidden bg-white"
            />
        </div>

        <!-- Enhanced Empty State -->
        <div v-else class="text-center py-16 px-4">
            <div class="inline-flex items-center justify-center w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200">
                <i class="material-icons text-5xl text-gray-400">location_off</i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">
                {{ searchQuery ? "No spots found" : "No saved spots yet" }}
            </h3>
            <p class="text-gray-500 max-w-md mx-auto">
                {{ searchQuery
                ? `Try searching for "${searchQuery}" in a different way.`
                : "Explore the map and save your favorite spots to see them here." }}
            </p>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import SpotCard from "../../Components/SpotCard.vue";

    const props = defineProps({
        saveSpots: {
            type: Array,
            required: true,
        },
    });

    const emit = defineEmits(["navigate"]);

    const searchQuery = ref("");

    const filteredSpots = computed(() => {
        const query = searchQuery.value.trim().toLowerCase();
        if (!query) return props.saveSpots;

        return props.saveSpots.filter((spot) => {
            const name = spot.spot_name?.toLowerCase() || "";
            const location = spot.location?.toLowerCase() || "";
            return name.includes(query) || location.includes(query);
        });
    });

    const goToExplore = (spot) => {
        emit("navigate", "exploreSpots", spot);
    };
</script>

<style scoped>
    /* Optional: Add subtle animation to cards */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .SpotCard:hover {
        animation: float 6s ease-in-out infinite;
    }
</style>
