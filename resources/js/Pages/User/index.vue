<template>
    <div class="user-body-container">
        <Sidebar
            :userRole="userRole"
            @selectPage="(p) => navigate(p)"
        />

        <div class="user-main-body">
            <Header
                :userInformation="userInformation"
                @logout="handleLogout"
            />

            <Home
                v-if="activePage === 'userDashboard'"
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

            <Favorites v-if="activePage === 'favorites'" />
            <Messages v-if="activePage === 'messages'" />
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
    import ExploreSpots from "./ExploreSpot.vue"; //exploreSpots.vue ExploreSpot.vue
    import TourAgenciesPanel from "./tourAgencies.vue";
    import ExploreTourAgencies from "./exploreTourAgencies.vue";
    import Favorites from "./favorites.vue";
    import Messages from "./messages.vue";
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
            required: true
        }

    });

    console.log(props.savedUnsaved)

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
    const handleLogout = () => {
        console.log("Logging out...");
    };
</script>


<style scoped>
.user-body-container {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

.user-main-body {
  width: 85%;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}
</style>
