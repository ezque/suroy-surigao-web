<template>
    <section v-if="!loading && props.agencies.length" class="animate-[fadeInUp_0.5s_ease-out]">
        <div class="grid gap-8 grid-cols-[repeat(auto-fill,minmax(360px,1fr))]">
            <article
                v-for="agency in props.agencies"
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

                        <!-- USER RATING ONLY -->
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

                    <p class="text-sm mb-4">
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
    import { ref, onMounted, watch } from "vue";
    import axios from "axios";

    const props = defineProps({
        agencies: Array,
        loading: Boolean,
    });

    const emit = defineEmits(["selectAgency"]);

    const agencyRatings = ref({});

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
