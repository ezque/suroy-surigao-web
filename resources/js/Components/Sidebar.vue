<template>
  <div class="sidebar-container">
    <div class="side-label-container">
      <h2 v-if="userRole === '1'">Admin</h2>
      <h2 v-else-if="userRole === '2'">Agency</h2>
    </div>

    <div class="sidebar-button-container">
      <!-- Dashboard -->
      <button @click="$emit('selectPage', 'dashboard')">
        <img src="/public/images/home.png" alt="Dashboard" class="icon-img" />
        Dashboard
      </button>

      <!-- Agencies -->
      <button @click="$emit('selectPage', 'agency')">
        <img src="/public/images/agency.png" alt="Agencies" class="icon-img" />
        Agencies
      </button>

      <button @click="$emit('selectPage', 'spots')">
        <img src="/public/images/manage-spots.png" alt="Spots" class="icon-img" />
        Manage Spots
      </button>

      <!-- Manage Posts -->
      <button @click="$emit('selectPage', 'post')">
        <img src="/public/images/manage-post.png" alt="Posts" class="icon-img" />
        Manage Post
      </button>

      <button @click="$emit('selectPage', 'manageUser')">
        <img src="/public/images/manage-users.png" alt="Users" class="icon-img" />
        Manage Users
      </button>

      <button @click="$emit('selectPage', 'reviews')">
        <img src="/public/images/manage-feedbacks.png" alt="Reviews" class="icon-img" />
        Reviews & Feedbacks
      </button>

      <button @click="$emit('selectPage', 'settings')">
        <img src="/public/images/settings.png" alt="Settings" class="icon-img" />
        Settings
      </button>

      <!-- Spacer pushes logout to bottom -->
      <div class="sidebar-spacer"></div>

      <!-- Logout button -->
      <button class="logout-btn" @click="$emit('selectPage', 'logout')">
        <img src="/public/images/exit.png" alt="Logout" class="icon-img" />
        Logout
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

/* Spacer pushes logout button to bottom */
.sidebar-spacer {
  flex-grow: 1;
}

.sidebar-button-container button {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px;
  border: none;
  background: none;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.2s;
  width: 100%;
  text-align: left;
}

.sidebar-button-container button:hover {
  background: #f0f0f0;
  border-radius: 6px;
}

.icon-img {
  width: 22px;
  height: 22px;
  object-fit: contain;
}

.logout-btn {
  color: #302322;
  font-weight: 600;
}

.logout-btn:hover {
  background: #ffecec;
}
</style>
