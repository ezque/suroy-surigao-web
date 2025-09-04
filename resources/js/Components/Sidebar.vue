<template>
<div class="sidebar-container">
  <div class="side-label-container">
    <h2 v-if="userRole === '1'">Admin</h2>
    <h2 v-else-if="userRole === '2'">Agency</h2>
  </div>

  <div class="sidebar-button-container">
    <!-- Dashboard -->
    <button @click="$emit('selectPage', 'dashboard')">
      <span class="material-icons-outlined">home</span>
      Dashboard
    </button>

    <!-- Agencies -->
    <button @click="$emit('selectPage', 'agency')">
      <span class="material-icons-outlined">business</span>
      Agencies
    </button>

    <!-- Spots -->
    <button @click="$emit('selectPage', 'spots')">
      <span class="material-icons-outlined">place</span>
      Spots
    </button>

    <!-- Manage Posts -->
    <button @click="$emit('selectPage', 'posts')">
      <span class="material-icons-outlined">article</span>
      Manage Posts
    </button>

    <!-- Manage Users -->
    <button @click="$emit('selectPage', 'users')">
      <span class="material-icons-outlined">group</span>
      Manage Users
    </button>

    <!-- Reviews & Feedbacks -->
    <button @click="$emit('selectPage', 'reviews')">
      <span class="material-icons-outlined">rate_review</span>
      Reviews & Feedbacks
    </button>

    <!-- Settings -->
    <button @click="$emit('selectPage', 'settings')">
      <span class="material-icons-outlined">settings</span>
      Settings
    </button>
  </div>
</div>

</template>
<script setup>

    const props = defineProps({
        userRole: String
    })
    const emit = defineEmits(["selectPage"])

</script>
<style scoped>
    .sidebar-container {
        width: 15%;
        height: 100%;
        background-color: rgb(249, 249, 250);
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .side-label-container {
        width: 100%;
    }
    .side-label-container h2 {
        font-size: 1.5em;
        font-weight: 700;
        margin-top: 10px;
        margin-left: 10px;
        text-transform: uppercase;
    }
    .sidebar-button-container {
        width: 85%;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding-left: 10px;
        padding-right: 10px;
    }
    .sidebar-button-container button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        background-color: rgb(246, 249, 252);
        cursor: pointer;
        text-transform: capitalize;

    }
    .sidebar-button-container button span {
        color: #464255;
        margin-right: 20px;
        margin-left: 10px;
    }

</style>
