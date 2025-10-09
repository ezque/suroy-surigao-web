<template>
  <div class="user-body-container">
    <Sidebar
      :userRole="userRole"
      @selectPage="selectActivePage"
    />

    <div class="user-main-body">
      <Header />

      <Home v-if="activePage === 'userDashboard'" />
      <Spots v-if="activePage === 'spots'" />
      <Favorites v-if="activePage === 'favorites'" />
      <Messages v-if="activePage === 'messages'" />
      <UserSettings v-if="activePage === 'settings'" />
    </div>
  </div>
</template>

<script setup>
import Sidebar from "../../Components/Sidebar.vue";
import Header from "../../Components/Header.vue";
import Home from "./userDashboard.vue";
import Spots from "./spots.vue";
import Favorites from "./favorites.vue";
import Messages from "./messages.vue";
import UserSettings from "./settings.vue"; // create this file if you haven’t yet

import { ref, computed } from "vue";

const props = defineProps({
  userInformation: {
    type: Object,
  },
});

const userRole = computed(() => props.userInformation.role);
console.log(userRole.value);

const activePage = ref("userDashboard");

const selectActivePage = (pageName) => {
  activePage.value = pageName;
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
