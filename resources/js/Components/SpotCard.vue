<template>
    <div
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
                    @click.stop="$emit('explore', spot)"
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
</template>

<script setup>
    const props = defineProps({
        spot: {
            type: Object,
            required: true,
        },
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
</script>
