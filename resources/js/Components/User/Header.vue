<template>
  <header class="user-header">
    <!-- Left Section: Logo & Welcome -->
    <div class="user-header-left">
      <h1>Welcome, {{ userName }}</h1>
    </div>

    <!-- Right Section: Messages, Notifications, Profile -->
    <div class="right-controls">
      <!-- Messages -->
      <div class="icon-button">
        <img src="/public/images/message.png" alt="Messages" class="icon-img" />
        <div class="notification-badge">{{ messages }}</div>
      </div>

      <!-- Notifications -->
      <div class="icon-button" @click="toggleNotifications">
        <img src="/public/images/bell.png" alt="Notifications" class="icon-img" />
        <div class="notification-badge">{{ notifications.length }}</div>

        <!-- Notification dropdown -->
        <div v-if="showNotifications" class="notif-dropdown">
          <p v-if="notifications.length === 0" class="no-notifs">No new notifications</p>
          <ul v-else>
            <li v-for="(notif, index) in notifications" :key="index">
              <p>{{ notif.message }}</p>
              <small>{{ notif.time }}</small>
            </li>
          </ul>
        </div>
      </div>

      <!-- Profile -->
      <div class="profile-button" @click="toggleProfileDropdown">
        <div class="profile-img">{{ initials }}</div>
        <span>{{ userName }}</span>

        <!-- Dropdown -->
        <div v-if="showProfileDropdown" class="profile-dropdown">
          <div class="profile-info">
            <div class="profile-avatar">{{ initials }}</div>
            <div>
              <h4>{{ userName }}</h4>
              <p>{{ userEmail }}</p>
            </div>
          </div>
          <hr />
          <button @click="$emit('selectPage', 'settings')">Settings</button>
          <button @click="$emit('logout')">Logout</button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  userInformation: {
    type: Object,
    required: true,
  },
});

const userName = computed(() => props.userInformation?.name || "User");
const userEmail = computed(() => props.userInformation?.email || "user@email.com");
const initials = computed(() =>
  userName.value
    .split(" ")
    .map((n) => n[0])
    .join("")
    .toUpperCase()
);

// Message and Notification counts
const messages = ref(3);
const notifications = ref([
  { message: "Your booking has been confirmed!", time: "2h ago" },
  { message: "New destination added near you.", time: "5h ago" },
]);

// Dropdown toggles
const showNotifications = ref(false);
const showProfileDropdown = ref(false);

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  showProfileDropdown.value = false;
};

const toggleProfileDropdown = () => {
  showProfileDropdown.value = !showProfileDropdown.value;
  showNotifications.value = false;
};
</script>

<style scoped>
/* --- Base --- */
* {
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.user-header {
  width: 100%;
  height: 70px;
  background: linear-gradient(135deg, #213f3fb6 0%, #a3c9df52 100%);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 25px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  position: relative;
  color: white;
}

/* --- Left Section --- */
.user-header-left {
  display: flex;
  align-items: center;
  gap: 25px;
}

.user-header-left h1 {
  font-size: 1.2rem;
  font-weight: 500;
  color: #fff;
}

.logo {
  display: flex;
  align-items: center;
  color: white;
  font-weight: 700;
  font-size: 1.5rem;
}

.logo i {
  margin-right: 10px;
  font-size: 1.8rem;
}

/* --- Right Controls --- */
.right-controls {
  display: flex;
  align-items: center;
  gap: 15px;
}

/* Icon Buttons (Messages & Notifications) */
.icon-button {
  position: relative;
  background: rgba(255, 255, 255, 0.15);
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  transition: all 0.3s ease;
  cursor: pointer;
}

.icon-button:hover {
  background: rgba(255, 255, 255, 0.25);
  transform: translateY(-2px);
}

.icon-img {
  width: 22px;
  height: 22px;
  object-fit: contain;
}

.notification-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: #ff4757;
  color: white;
  font-size: 0.7rem;
  font-weight: 600;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid #6a11cb;
}

/* --- Notification Dropdown --- */
.notif-dropdown {
  position: absolute;
  right: 0;
  top: 50px;
  background: white;
  border-radius: 8px;
  width: 250px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  color: #333;
  padding: 10px 0;
  z-index: 100;
}

.notif-dropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.notif-dropdown li {
  padding: 10px 15px;
  border-bottom: 1px solid #eee;
}

.notif-dropdown li:last-child {
  border-bottom: none;
}

.notif-dropdown li p {
  margin: 0;
  font-size: 0.9rem;
}

.notif-dropdown li small {
  color: #999;
  font-size: 0.75rem;
}

.no-notifs {
  text-align: center;
  color: #888;
  font-size: 0.9rem;
  padding: 10px;
}

/* --- Profile --- */
.profile-button {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.15);
  padding: 8px 15px;
  border-radius: 25px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.profile-button:hover {
  background: rgba(255, 255, 255, 0.25);
}

.profile-img {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  margin-right: 10px;
  background: linear-gradient(45deg, #ff9a9e, #fad0c4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
}

/* --- Profile Dropdown --- */
.profile-dropdown {
  position: absolute;
  right: 0;
  top: 60px;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  min-width: 200px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  z-index: 101;
}

.profile-info {
  display: flex;
  align-items: center;
  padding: 10px;
  gap: 10px;
}

.profile-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: linear-gradient(45deg, #ff9a9e, #fad0c4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
}

.profile-info h4 {
  margin: 0;
  font-size: 1rem;
  color: #333;
}

.profile-info p {
  margin: 0;
  color: #777;
  font-size: 0.85rem;
}

.profile-dropdown hr {
  margin: 0;
  border: none;
  border-top: 1px solid #eee;
}

.profile-dropdown button {
  background: none;
  border: none;
  padding: 10px;
  text-align: left;
  cursor: pointer;
  width: 100%;
  color: #333;
  transition: 0.3s;
}

.profile-dropdown button:hover {
  background-color: #f5f5f5;
}

@media (max-width: 768px) {
  .user-header-left h1 {
    display: none;
  }

  .profile-button span {
    display: none;
  }

  .profile-button {
    padding: 8px;
  }
}
</style>
