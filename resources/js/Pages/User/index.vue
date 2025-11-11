<template>
    <div class="flex w-full h-screen overflow-hidden">
        <!-- Sidebar -->
        <Sidebar
            :userRole="userRole"
            :userInformation="userInformation"
            @selectPage="(p) => navigate(p)"
            :currentPage="activePage"
        />

        <!-- Main content -->
        <div class="flex flex-col w-[85%] overflow-y-auto">
            <Header
                :userInformation="userInformation"
            />

            <Home
                v-if="activePage === 'userDashboard'"
                :blogsURL="blogsURL"
            />

            <Spots
                v-if="activePage === 'spots'"
                @navigate="navigate"
                :allSpots="allSpots"
            />

            <ExploreSpots
                v-if="activePage === 'exploreSpots'"
                :spot="selectedSpot"
                :agencies="agencies"
                :activePackages="activePackages"
                @navigate="navigate"
            />

            <TourAgenciesPanel
                v-if="activePage === 'tourAgencies'"
                :agencies="agencies"
                @navigate="navigate"
            />

            <ExploreTourAgencies
                v-if="activePage === 'exploreTourAgencies'"
                :agency="selectedAgency"
                @navigate="navigate"
            />

            <Favorites
                v-if="activePage === 'favorites'"
                :saveSpots="saveSpots"
                @navigate="navigate"
            />

            <UserSettings v-if="activePage === 'settings'" />
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";

    import Sidebar from "@/Components/Sidebar.vue";
    import Header from "@/Components/User/Header.vue";

    import Home from "./userDashboard.vue";
    import Spots from "./spots.vue";
    import ExploreSpots from "./ExploreSpot.vue";
    import TourAgenciesPanel from "./tourAgencies.vue";
    import ExploreTourAgencies from "./exploreTourAgencies.vue";
    import Favorites from "./favorites.vue";
    import UserSettings from "./settings.vue";

    const props = defineProps({
        userInformation: {
            type: Object,
            required: true,
        },
        allSpots: {
            type: Array,
            required: true,
        },
        agencies: {
            type: Array,
            required: true,
        },
        activePackages: {
            type: Array,
            required: true,
        },
        blogsURL: {
            type: Array,
            required: true,
        },
        saveSpots: {
            type: Array,
            required: true,
        },
    });

    const userRole = computed(() => props.userInformation.role);
    const activePage = ref("userDashboard");

    const selectedSpot = ref(null);
    const selectedAgency = ref(null);

    const navigate = (page, payload = null) => {
        switch (page) {
            case "exploreSpots":
                selectedSpot.value = payload;
                break;
            case "exploreTourAgencies":
                selectedAgency.value = payload;
                break;
            case "spots":
                selectedSpot.value = null;
                break;
            case "tourAgencies":
                selectedAgency.value = null;
                break;
        }
        activePage.value = page;
    };
</script>

