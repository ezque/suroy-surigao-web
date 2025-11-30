<template>
    <div
        class="min-h-screen p-8 bg-gradient-to-br from-[#f8fafc] via-[#f1f5f9] to-[#e2e8f0] animate-[fadeIn_0.5s_ease-out]"
    >
        <!-- Header -->
        <header class="flex flex-wrap justify-between items-end mb-10 border-b border-[#e2e8f0] pb-6">
            <div class="flex-1">
                <h1
                    class="capitalize text-[2.5rem] font-extrabold mb-2 bg-gradient-to-tr from-[#1e293b] to-[#3b82f6] bg-clip-text text-transparent"
                >
                    discover tour agencies
                </h1>
                <p class="capitalize text-lg text-[#64748b] m-0 max-w-[520px]">
                    find the perfect adventure with trusted local experts in surigao
                </p>
            </div>
        </header>

        <!-- Search & Filter -->
        <section class="flex flex-wrap items-center gap-5 mb-8">
            <div class="relative flex-1 min-w-[320px]">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search Agencies"
                    class="w-full pl-12 pr-12 py-3 rounded-xl border-2 border-[#e2e8f0] text-base bg-white shadow-sm transition-all duration-300 focus:ring-2 focus:ring-[#3b82f6]/30 focus:border-[#3b82f6] outline-none"
                />
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#94a3b8]">🔍</span>
                <button
                    v-if="searchQuery"
                    @click="clearSearch"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569]"
                >
                    ✕
                </button>
            </div>

        </section>


        <!-- Loading -->
        <div v-if="loading" class="text-center py-20">
            <div class="w-10 h-10 border-4 border-[#e2e8f0] border-t-[#3b82f6] rounded-full animate-spin mx-auto mb-5"></div>
            <p class="text-[#64748b]">Finding the best agencies for you...</p>
        </div>

        <!-- Agencies -->
        <UserAgency
            v-if="!selectedAgency"
            :agencies="filteredAgencies"
            :loading="loading"
            :searchQuery="searchQuery"
            :isLiked="isLiked"
            :toggleLike="toggleLike"
            :startingPrice="startingPrice"
            @selectAgency="openAgencyDetails"
        />
        <!-- Packages -->
        <AgencyPackage
            v-if="selectedAgency"
            :agency="selectedAgency"
            :isLiked="isLiked"
            :toggleLike="toggleLike"
            :userInformation="userInformation"
            @goBack="closeAgencyDetails"
        />

        <!-- Empty -->
        <div v-if="!loading && filteredAgencies.length === 0" class="text-center py-20">
            <p class="text-[#94a3b8] text-lg">No agencies found for this spot.</p>
        </div>
    </div>
</template>


<script setup>
    import { ref, computed, onMounted, watch } from "vue";
    import axios from "axios";
    import UserAgency from "./UserAgency.vue";
    import AgencyPackage from "./AgencyPackage.vue";

    const props = defineProps({
        activePackages: Array,
        spotId: Number,
        userInformation: Object,
    });

    const agencies = ref([]);
    const searchQuery = ref("");
    const sortBy = ref("name");
    const showFilters = ref(false);
    const loading = ref(false);
    const priceRange = ref([0, 10000]);
    const likedAgencies = ref([]);
    const selectedAgency = ref(null);
    const isLiked = (agencyID) => likedAgencies.value.includes(agencyID);

    const clearSearch = () => {
        searchQuery.value = "";
    };

    const openAgencyDetails = (agency) => {
        selectedAgency.value = agency;
        console.log("Selected agency:", agency);
    }
    const closeAgencyDetails = () => {
        selectedAgency.value = null;
    }

    const fetchAgencies = async () => {
        if (!props.spotId) return;
        loading.value = true;
        try {
            const response = await axios.get(`/agencies-by-spot/${props.spotId}`);
            agencies.value = response.data;

        } catch (error) {
            console.error(error);
        } finally {
            loading.value = false;
        }
    };

    const fetchLikedAgencies = async () => {
        try {
            const response = await axios.get("/check-if-agency-is-liked");
            likedAgencies.value = response.data;
        } catch (error) {
            console.error(error);
        }
    };

    const toggleLike = async (agency) => {
        try {
            const response = await axios.post("/like-unlike-agency", { agencyID: agency.id });
            if (response.data.status === "liked") {
                likedAgencies.value.push(agency.id);
            } else {
                likedAgencies.value = likedAgencies.value.filter(id => id !== agency.id);
            }
        } catch (error) {
            console.error(error);
        }
    };

    const startingPrice = (agency) => {
        if (!agency.packages || agency.packages.length === 0) return 0;
        const availablePackages = agency.packages.filter(pkg => pkg.available_slot > 0);
        if (availablePackages.length === 0) return 0;
        return Math.min(...availablePackages.map(pkg => Number(pkg.price)));
    };

    const filteredAgencies = computed(() => {
        if (!searchQuery.value) {
            console.log("Filtered (no search):", agencies.value);
            return agencies.value;
        }

        const result = agencies.value.filter(a =>
            (a.agency?.agency_name?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                a.agency?.description?.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
        console.log("Filtered (with search):", result);
        return result;
    });


    watch(() => props.spotId, fetchAgencies);
    onMounted(() => {
        fetchAgencies();
        fetchLikedAgencies();
    });
</script>
