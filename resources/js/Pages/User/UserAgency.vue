<template>
    <section v-if="!loading && props.agencies.length" class="animate-[fadeInUp_0.5s_ease-out]">
        
        <div v-if="topAgency" class="mb-10">
            <div class="relative overflow-hidden rounded-3xl bg-white shadow-xl ring-1 ring-gray-100 md:flex">
                
                <div class="absolute top-4 left-4 z-10 flex items-center gap-1 rounded-full bg-yellow-400 px-3 py-1 text-xs font-bold uppercase tracking-wider text-yellow-900 shadow-sm">
                    <i class="material-icons text-sm">emoji_events</i>
                    Traveler's Top Choice
                </div>

                <div class="md:w-1/2 lg:w-2/5 relative h-64 md:h-auto">
                    <img
                        :src="topAgency.agency?.image_path ? `/storage/${topAgency.agency.image_path}` : '/images/logo/logo.png'"
                        class="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
                        alt="Top Agency"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent md:bg-gradient-to-r md:from-transparent md:to-white/10"></div>
                </div>

                <div class="flex flex-col justify-center p-8 md:w-1/2 lg:w-3/5">
                    <div class="flex items-center gap-2 mb-2">
                         <h2 class="text-3xl font-extrabold text-gray-800">{{ topAgency.agency?.agency_name }}</h2>
                         <i class="material-icons text-blue-500 verified-icon" title="Verified">verified</i>
                    </div>
                   
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center gap-1 rounded-lg bg-yellow-50 px-2 py-1">
                            <span class="text-lg font-bold text-yellow-700">{{ Number(topAgency.agency?.average_rating).toFixed(1) }}</span>
                            <i class="material-icons text-yellow-400 text-lg">star</i>
                        </div>
                        <span class="text-sm text-gray-500">{{ topAgency.packages.length }} Active Packages</span>
                    </div>

                    <p class="mb-6 text-gray-600 leading-relaxed">
                        {{ topAgency.agency?.description || "Experience the best tours with our highest-rated agency partner. Validated by travelers for excellent service." }}
                    </p>

                    <div class="flex gap-3">
                        <button
                            @click="$emit('selectAgency', topAgency)"
                            class="rounded-xl bg-[#009FB7] px-8 py-3 font-bold text-white shadow-lg shadow-cyan-500/30 transition-all hover:bg-[#007A8A] hover:-translate-y-0.5"
                        >
                            View Exclusive Deals
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-8 grid-cols-[repeat(auto-fill,minmax(360px,1fr))]">
            <article
                v-for="agency in otherAgencies"
                :key="agency.id"
                class="group bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.05)]
                hover:shadow-[0_8px_30px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-1"
            >
                <img
                    :src="agency.agency?.image_path ? `/storage/${agency.agency.image_path}` : '/images/logo/logo.png'"
                    class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105"
                />

                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold">{{ agency.agency?.agency_name }}</h3>

                        <div class="flex cursor-pointer mt-2">
                            <p>{{ Number(agency.agency?.average_rating).toFixed(1) }}</p>
                            <template v-for="n in 5" :key="n">
                                <i
                                    class="material-icons text-lg"
                                    :class="agencyRatings[agency.id] >= n ? 'text-yellow-400' : 'text-gray-300'"
                                    @click="rateAgency(agency.id, n)"
                                >
                                    star
                                </i>
                            </template>
                        </div>
                    </div>

                    <p class="text-sm mb-4 line-clamp-2">
                        {{ agency.agency?.description || "No description available." }}
                    </p>

                    <div class="mb-4">
                        <div class="flex justify-between mb-2 text-sm font-semibold">
                            <span>Available Packages</span>
                            <span class="text-green-600">{{ agency.available_package }} available</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="pkg in agency.packages.slice(0, 3)"
                                :key="pkg.id"
                                class="px-2 py-1 rounded-md text-xs font-medium"
                                :class="pkg.available_slot === 0 ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600'"
                            >
                                {{ pkg.package_name }}
                            </span>

                            <span
                                v-if="agency.packages.length > 3"
                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-medium"
                            >
                                +{{ agency.packages.length - 3 }} more
                            </span>
                        </div>
                    </div>

                    <button
                        @click="$emit('selectAgency', agency)"
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white py-3 rounded-xl font-semibold"
                    >
                        View Details →
                    </button>
                </div>
            </article>
        </div>
    </section>
</template>

<script setup>
    import { ref, onMounted, watch, computed } from "vue";
    import axios from "axios";

    const props = defineProps({
        agencies: Array,
        loading: Boolean,
    });

    const emit = defineEmits(["selectAgency"]);

    const agencyRatings = ref({});

    /* --- COMPUTED PROPERTIES FOR TOP PICK --- */
    
    // Sort agencies by rating (highest first) and pick the top one
    const topAgency = computed(() => {
        if (!props.agencies || props.agencies.length === 0) return null;
        
        return [...props.agencies].sort((a, b) => {
            const ratingA = parseFloat(a.agency?.average_rating || 0);
            const ratingB = parseFloat(b.agency?.average_rating || 0);
            return ratingB - ratingA; // Descending order
        })[0];
    });

    // Return the rest of the agencies (excluding the top one) so it doesn't repeat
    // OR: You can just return 'props.agencies' if you want the top one to appear in the grid too.
    // I will return ALL agencies in the grid as well, as usually users still expect to find it in the list.
    // If you want to hide it from the list, change this return.
    const otherAgencies = computed(() => {
        return props.agencies; 
        // If you want to remove the top agency from the bottom list, use:
        // return props.agencies.filter(a => a.id !== topAgency.value?.id);
    });

    /* --- EXISTING LOGIC --- */

    const fetchRatings = async () => {
        try {
            for (const agency of props.agencies) {
                const res = await axios.get(`/agency/${agency.id}/rating`);
                agencyRatings.value[agency.id] = res.data ? res.data.rating : 0;
            }
        } catch (error) {
            console.error("Error fetching ratings:", error);
        }
    };

    const rateAgency = async (agencyId, rating) => {
        try {
            await axios.post(`/rate-agency/${agencyId}`, { rating });
            agencyRatings.value[agencyId] = rating; // update instantly
        } catch (error) {
            console.error("Rating failed:", error);
        }
    };

    watch(
        () => props.agencies,
        (agencies) => {
            if (agencies && agencies.length > 0) {
                fetchRatings();
            }
        },
        { immediate: true }
    );
</script>