<template>
  <div class="user-body-container">
    <Sidebar :userRole="userRole" @selectPage="selectActivePage" />

    <div class="user-main-body">
      <Header :userInformation="userInformation" @logout="handleLogout" />

      <!-- Home -->
      <Home v-if="activePage === 'userDashboard'" />

      <!-- Spots -->
      <div v-if="activePage === 'spots'">
        <Spots @exploreSpots="openExploreSpots" />
      </div>

      <!-- Explore Spots -->
      <div v-else-if="activePage === 'exploreSpots'">
        <ExploreSpots :spot="selectedSpot" @back="goBackToSpots" />
      </div>

      <!-- Tour Agencies -->
      <div v-else-if="activePage === 'tourAgencies'">
        <TourAgenciesPanel :agencies="agencies" @view-agency="openExploreTourAgencies" />
      </div>

      <!-- Explore Tour Agency Packages -->
      <div v-else-if="activePage === 'exploreTourAgencies'">
        <ExploreTourAgencies :agency="selectedAgency" @back="goBackToTourAgencies" />
      </div>

      <!-- Other Pages -->
      <Favorites v-if="activePage === 'favorites'" />
      <Messages v-if="activePage === 'messages'" />
      <UserSettings v-if="activePage === 'settings'" />
      <AddPackage v-if="activePage === 'AddPackage'" />
      <Reserve v-if="activePage === 'Reserve'" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

// Components
import Sidebar from "../../Components/Sidebar.vue";
import Header from "@/Components/User/Header.vue";
import Home from "./userDashboard.vue";
import Spots from "./spots.vue";
import ExploreSpots from "./exploreSpots.vue";
import TourAgenciesPanel from "./tourAgencies.vue";
import ExploreTourAgencies from "./exploreTourAgencies.vue";
import Favorites from "./favorites.vue";
import Messages from "./messages.vue";
import UserSettings from "./settings.vue";
import AddPackage from "../../Components/Forms/addPackage.vue";
import Reserve from "../../Components/Forms/reserve.vue";

const props = defineProps({
  userInformation: {
    type: Object,
    required: true,
  },
});

const userRole = computed(() => props.userInformation.role);
const activePage = ref("AddPackage");

// Navigation states
const selectedSpot = ref(null);
const selectedAgency = ref(null);

// Sample Tour Agencies
const agencies = ref([
  {
    id: 1,
    name: "Surigao Eco Tours",
    location: "Surigao City",
    shortDesc: "Eco-friendly island hopping and mountain adventures.",
    packages: [
      {
        id: 101,
        title: "Island Hopping Adventure",
        shortDesc: "Explore 3 scenic islands in one day.",
        price: 2500,
        capacity: 15,
        availableSlots: 8,
        days: 1,
        nights: 0,
        startDate: "2025-11-10",
        startTime: "7:00 AM",
        endDate: "2025-11-10",
        endTime: "6:00 PM",
        pickUpPoint: "Surigao Boulevard",
        destinations: ["Naked Island", "Daku Island", "Guyam Island"],
        inclusions: ["Lunch", "Boat", "Guide", "Entrance Fees"],
        exclusions: ["Snorkeling Gear", "Drinks"],
      },
    ],
  },
  {
    id: 2,
    name: "Adventure Surigao Travel Co.",
    location: "Dapa, Siargao",
    shortDesc: "Surf and nature combo packages around Siargao.",
    packages: [
      {
        id: 102,
        title: "Siargao Surf & Nature Tour",
        shortDesc: "2 days of island life and surf adventure.",
        price: 4800,
        capacity: 10,
        availableSlots: 4,
        days: 2,
        nights: 1,
        startDate: "2025-12-05",
        startTime: "6:00 AM",
        endDate: "2025-12-06",
        endTime: "5:00 PM",
        pickUpPoint: "Dapa Port",
        destinations: ["Cloud 9", "Magpupungko", "Sugba Lagoon"],
        inclusions: ["Accommodation", "Breakfast", "Transport"],
        exclusions: ["Personal Expenses", "Dinner"],
      },
    ],
  },
]);

// Navigation handlers
const selectActivePage = (page) => (activePage.value = page);

const openExploreSpots = (spot) => {
  selectedSpot.value = spot;
  activePage.value = "exploreSpots";
};

const openExploreTourAgencies = (agency) => {
  selectedAgency.value = agency;
  activePage.value = "exploreTourAgencies";
};

const goBackToSpots = () => (activePage.value = "spots");
const goBackToTourAgencies = () => (activePage.value = "tourAgencies");

const handleLogout = () => console.log("Logging out...");
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
