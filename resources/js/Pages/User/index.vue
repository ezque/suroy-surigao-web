<template>
  <div class="user-body-container">
    <Sidebar
      :userRole="userRole"
      @selectPage="selectActivePage"
    />

    <div class="user-main-body">
      <Header
        :userInformation="userInformation"
        @logout="handleLogout"
      />

      <!-- Pages -->
      <Home v-if="activePage === 'userDashboard'" />

      <!-- Spots List -->
      <div v-if="activePage === 'spots'">
        <Spots @exploreSpots="openExplore" />
      </div>

      <!-- Explore Spot Details -->
      <div v-else-if="activePage === 'exploreSpots'">
        <ExploreSpots :spot="selectedSpot" @back="goBackToSpots" />
      </div>

      <Favorites v-if="activePage === 'favorites'" />
      <Messages v-if="activePage === 'messages'" />
      <UserSettings v-if="activePage === 'settings'" />
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
import Favorites from "./favorites.vue";
import Messages from "./messages.vue";
import UserSettings from "./settings.vue";

const props = defineProps({
  userInformation: {
    type: Object,
    required: true,
  },
});

// Compute user role
const userRole = computed(() => props.userInformation.role);

// Active Page Management
const activePage = ref("userDashboard");
const selectedSpot = ref(null);

const selectActivePage = (pageName) => {
  activePage.value = pageName;
};

// When “Explore” is clicked in Spots.vue
const openExplore = (spot) => {
  selectedSpot.value = spot;
  activePage.value = "exploreSpots";
};

// Go back from ExploreSpots to Spots
const goBackToSpots = () => {
  activePage.value = "spots";
};

// Logout
const handleLogout = () => {
  console.log("Logging out...");
};
</script>

<style scoped>
.user-body-container {
  width: 100%;
  height: 100vh;
  overflow: hidden;
  display: flex;
  flex-direction: row;
}

.user-main-body {
  width: 85%;
  height: 100%;
  display: flex;
  flex-direction: column;
  background: linear-gradient(to bottom, #6ABBE4, #EEF8F9);
}
</style>
