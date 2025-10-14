<template>
  <header class="user-header">
    <!-- Left Section: Logo & Welcome -->
    <div class="user-header-left">
      <h1 class="welcome-text">Welcome back, {{ userName }}</h1>
    </div>

    <!-- Right Section: Messages, Notifications, Profile -->
    <div class="right-controls">
      <!-- Messages -->
      <div class="icon-button" @click="toggleMessages">
        <div class="icon-wrapper">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" 
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="notification-badge">{{ messages }}</div>
        
        <!-- Messages dropdown -->
        <div v-if="showMessages" class="dropdown messages-dropdown">
          <div class="dropdown-header">
            <h3>Messages</h3>
            <span class="badge">{{ messages }}</span>
          </div>
          <div class="dropdown-content">
            <div v-if="messageList.length === 0" class="empty-state">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <p>No new messages</p>
            </div>
            <div v-else class="message-list">
              <div v-for="(msg, index) in messageList" :key="index" class="message-item">
                <div class="message-avatar">
                  {{ msg.sender.charAt(0).toUpperCase() }}
                </div>
                <div class="message-content">
                  <div class="message-header">
                    <span class="sender">{{ msg.sender }}</span>
                    <span class="time">{{ msg.time }}</span>
                  </div>
                  <p class="message-preview">{{ msg.preview }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="dropdown-footer">
            <button class="view-all-btn">View All Messages</button>
          </div>
        </div>
      </div>

      <!-- Notifications -->
      <div class="icon-button" @click="toggleNotifications">
        <div class="icon-wrapper">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" 
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21" 
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div class="notification-badge">{{ notifications.length }}</div>

        <!-- Notification dropdown -->
        <div v-if="showNotifications" class="dropdown notif-dropdown">
          <div class="dropdown-header">
            <h3>Notifications</h3>
            <span class="badge">{{ notifications.length }}</span>
          </div>
          <div class="dropdown-content">
            <div v-if="notifications.length === 0" class="empty-state">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 8C18 6.4087 17.3679 4.88258 16.2426 3.75736C15.1174 2.63214 13.5913 2 12 2C10.4087 2 8.88258 2.63214 7.75736 3.75736C6.63214 4.88258 6 6.4087 6 8C6 15 3 17 3 17H21C21 17 18 15 18 8Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M13.73 21C13.5542 21.3031 13.3019 21.5547 12.9982 21.7295C12.6946 21.9044 12.3504 21.9965 12 21.9965C11.6496 21.9965 11.3054 21.9044 11.0018 21.7295C10.6982 21.5547 10.4458 21.3031 10.27 21" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <p>No new notifications</p>
            </div>
            <div v-else class="notification-list">
              <div v-for="(notif, index) in notifications" :key="index" 
                   class="notification-item" :class="{'unread': notif.unread}">
                <div class="notification-icon" :class="notif.type">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path v-if="notif.type === 'success'" d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path v-if="notif.type === 'info'" d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path v-if="notif.type === 'warning'" d="M12 9V11M12 15H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle v-if="notif.type === 'info' || notif.type === 'warning'" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                  </svg>
                </div>
                <div class="notification-content">
                  <p>{{ notif.message }}</p>
                  <small>{{ notif.time }}</small>
                </div>
              </div>
            </div>
          </div>
          <div class="dropdown-footer">
            <button class="view-all-btn">View All Notifications</button>
          </div>
        </div>
      </div>

      <!-- Profile -->
      <div class="profile-button" @click="toggleProfileDropdown">
        <div class="profile-avatar">
          {{ initials }}
        </div>
        <span class="profile-name">{{ userName }}</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" 
             class="chevron" :class="{ 'rotated': showProfileDropdown }">
          <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <!-- Profile Dropdown -->
        <div v-if="showProfileDropdown" class="dropdown profile-dropdown">
          <div class="profile-info">
            <div class="profile-avatar-large">
              {{ initials }}
            </div>
            <div class="profile-details">
              <h4>{{ userName }}</h4>
              <p>{{ userEmail }}</p>
            </div>
          </div>
          <div class="dropdown-divider"></div>
          <div class="dropdown-menu">
            <button class="dropdown-item" @click="selectPage('profile')">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>My Profile</span>
            </button>
            <button class="dropdown-item" @click="selectPage('settings')">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M19.4 15C19.2669 15.3068 19.1995 15.6375 19.2 15.9714C19.2 16.3052 19.2684 16.6357 19.4024 16.9424C19.5364 17.249 19.7328 17.5247 19.9786 17.7514C20.2244 17.9781 20.5139 18.1505 20.8286 18.2571L21 18.3143C21.1067 18.4338 21.2 18.5657 21.2771 18.7086C21.36 18.8514 21.4257 19.0029 21.4743 19.16L21.5257 19.3714C21.5714 19.5029 21.6 19.6457 21.6 19.8C21.6 19.9543 21.5714 20.0971 21.5257 20.2286C21.4743 20.36 21.4057 20.4857 21.3257 20.5971C21.2457 20.7086 21.1543 20.8057 21.0514 20.8857C20.9486 20.9657 20.8343 21.0286 20.7143 21.0714L20.4 21.1714C20.0857 21.2781 19.7514 21.3143 19.4229 21.2771C19.0943 21.24 18.7771 21.1314 18.4971 20.9571C18.2171 20.7829 17.9794 20.5486 17.8029 20.2686C17.6263 19.9886 17.5143 19.6714 17.4771 19.3429C17.44 19.0143 17.4771 18.68 17.5857 18.3657L17.6571 18.1714C17.7486 17.88 17.7486 17.5657 17.6571 17.2743C17.5657 16.9829 17.3886 16.7257 17.1486 16.5371C16.9086 16.3486 16.6171 16.2371 16.3143 16.2171C16.0114 16.1971 15.7114 16.2686 15.4514 16.4229C15.1914 16.5771 14.9829 16.8057 14.8514 17.08L14.7429 17.3143C14.6362 17.629 14.4486 17.9086 14.2 18.1229C13.9514 18.3371 13.6514 18.4771 13.3314 18.5286C13.0114 18.58 12.6857 18.5414 12.3886 18.4171C12.0914 18.2929 11.8343 18.0886 11.6457 17.8257C11.4571 17.5629 11.3457 17.2514 11.3229 16.9286C11.3 16.6057 11.3686 16.2829 11.52 16" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Settings</span>
            </button>
            <button class="dropdown-item" @click="selectPage('help')">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15848 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 17H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Help & Support</span>
            </button>
          </div>
          <div class="dropdown-divider"></div>
          <button class="dropdown-item logout-btn" @click="logout">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9" 
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M16 17L21 12L16 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Logout</span>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Overlay for closing dropdowns when clicking outside -->
    <div v-if="showNotifications || showProfileDropdown || showMessages" 
         class="dropdown-overlay" @click="closeAllDropdowns"></div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";

const props = defineProps({
  userInformation: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['selectPage', 'logout']);

const userName = computed(() => props.userInformation?.name || "User");
const userEmail = computed(() => props.userInformation?.email || "user@email.com");
const initials = computed(() =>
  userName.value
    .split(" ")
    .map((n) => n[0])
    .join("")
    .toUpperCase()
);

// Message and Notification data
const messages = ref(3);
const messageList = ref([
  { sender: "Travel Support", preview: "Your booking has been confirmed...", time: "2h ago", unread: true },
  { sender: "John Doe", preview: "Thanks for joining our travel group...", time: "1d ago", unread: true },
  { sender: "TravelHub", preview: "New destinations available in your area...", time: "3d ago", unread: false },
]);

const notifications = ref([
  { message: "Your booking has been confirmed!", time: "2h ago", type: "success", unread: true },
  { message: "New destination added near you.", time: "5h ago", type: "info", unread: true },
  { message: "Payment for your trip was successful", time: "1d ago", type: "success", unread: false },
  { message: "Your review was helpful to 12 travelers", time: "2d ago", type: "info", unread: false },
]);

// Dropdown toggles
const showNotifications = ref(false);
const showProfileDropdown = ref(false);
const showMessages = ref(false);

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  showProfileDropdown.value = false;
  showMessages.value = false;
};

const toggleProfileDropdown = () => {
  showProfileDropdown.value = !showProfileDropdown.value;
  showNotifications.value = false;
  showMessages.value = false;
};

const toggleMessages = () => {
  showMessages.value = !showMessages.value;
  showNotifications.value = false;
  showProfileDropdown.value = false;
};

const closeAllDropdowns = () => {
  showNotifications.value = false;
  showProfileDropdown.value = false;
  showMessages.value = false;
};

const selectPage = (page) => {
  emit('selectPage', page);
  closeAllDropdowns();
};

const logout = () => {
  emit('logout');
  closeAllDropdowns();
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.icon-button') && 
      !event.target.closest('.profile-button') && 
      !event.target.closest('.dropdown')) {
    closeAllDropdowns();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
/* --- Base Styles --- */
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
  z-index: 1000;
}

/* --- Left Section --- */
.user-header-left {
  display: flex;
  align-items: center;
  gap: 25px;
}

.logo {
  display: flex;
  align-items: center;
  color: white;
  font-weight: 700;
  font-size: 1.5rem;
  gap: 10px;
}

.welcome-text {
  font-size: 1.2rem;
  font-weight: 500;
  color: #fff;
  margin: 0;
}

/* --- Right Controls --- */
.right-controls {
  display: flex;
  align-items: center;
  gap: 15px;
  position: relative;
}

/* Icon Buttons (Messages & Notifications) */
.icon-button {
  position: relative;
  background: rgba(255, 255, 255, 0.1);
  width: 42px;
  height: 42px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  transition: all 0.3s ease;
  cursor: pointer;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.icon-button:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.icon-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
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
  border: 2px solid #1a2f3a;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* --- Profile Button --- */
.profile-button {
  display: flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.1);
  padding: 8px 15px;
  border-radius: 25px;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  border: 1px solid rgba(255, 255, 255, 0.1);
  gap: 10px;
}

.profile-button:hover {
  background: rgba(255, 255, 255, 0.2);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.profile-avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: linear-gradient(45deg, #ff9a9e, #fad0c4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
  font-size: 0.9rem;
}

.profile-name {
  font-weight: 500;
}

.chevron {
  transition: transform 0.3s ease;
}

.chevron.rotated {
  transform: rotate(180deg);
}

/* --- Dropdown Base Styles --- */
.dropdown {
  position: absolute;
  right: 0;
  top: 55px;
  background: white;
  border-radius: 12px;
  width: 320px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  color: #333;
  z-index: 1001;
  overflow: hidden;
  animation: dropdownFadeIn 0.2s ease-out;
}

@keyframes dropdownFadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  border-bottom: 1px solid #f0f0f0;
}

.dropdown-header h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #333;
}

.dropdown-header .badge {
  background: #4dabf7;
  color: white;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 10px;
}

.dropdown-content {
  max-height: 300px;
  overflow-y: auto;
}

.dropdown-footer {
  padding: 15px 20px;
  border-top: 1px solid #f0f0f0;
  text-align: center;
}

.view-all-btn {
  background: none;
  border: none;
  color: #4dabf7;
  font-weight: 500;
  cursor: pointer;
  transition: color 0.2s;
}

.view-all-btn:hover {
  color: #339af0;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px 20px;
  color: #888;
  text-align: center;
}

.empty-state svg {
  margin-bottom: 10px;
  opacity: 0.5;
}

.empty-state p {
  margin: 0;
  font-size: 0.9rem;
}

/* --- Notification Dropdown --- */
.notification-list {
  padding: 10px 0;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  padding: 12px 20px;
  transition: background 0.2s;
  gap: 12px;
}

.notification-item:hover {
  background: #f8f9fa;
}

.notification-item.unread {
  background: #f0f8ff;
}

.notification-item.unread:hover {
  background: #e6f3ff;
}

.notification-icon {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}

.notification-icon.success {
  background: #e8f5e8;
  color: #2ecc71;
}

.notification-icon.info {
  background: #e3f2fd;
  color: #3498db;
}

.notification-icon.warning {
  background: #fff8e1;
  color: #f39c12;
}

.notification-content {
  flex: 1;
}

.notification-content p {
  margin: 0 0 4px 0;
  font-size: 0.9rem;
  line-height: 1.4;
}

.notification-content small {
  color: #888;
  font-size: 0.75rem;
}

/* --- Messages Dropdown --- */
.messages-dropdown {
  width: 350px;
}

.message-list {
  padding: 10px 0;
}

.message-item {
  display: flex;
  align-items: flex-start;
  padding: 12px 20px;
  transition: background 0.2s;
  gap: 12px;
  cursor: pointer;
}

.message-item:hover {
  background: #f8f9fa;
}

.message-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(45deg, #a8edea, #fed6e3);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
  flex-shrink: 0;
}

.message-content {
  flex: 1;
  min-width: 0;
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
}

.sender {
  font-weight: 600;
  font-size: 0.9rem;
  color: #333;
}

.time {
  color: #888;
  font-size: 0.75rem;
}

.message-preview {
  margin: 0;
  font-size: 0.85rem;
  color: #666;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* --- Profile Dropdown --- */
.profile-dropdown {
  width: 280px;
}

.profile-info {
  display: flex;
  align-items: center;
  padding: 20px;
  gap: 15px;
}

.profile-avatar-large {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(45deg, #ff9a9e, #fad0c4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  color: #333;
  font-size: 1.2rem;
}

.profile-details h4 {
  margin: 0 0 4px 0;
  font-size: 1rem;
  color: #333;
}

.profile-details p {
  margin: 0;
  color: #777;
  font-size: 0.85rem;
}

.dropdown-divider {
  height: 1px;
  background: #f0f0f0;
  margin: 0;
}

.dropdown-menu {
  padding: 8px 0;
}

.dropdown-item {
  display: flex;
  align-items: center;
  width: 100%;
  background: none;
  border: none;
  padding: 12px 20px;
  text-align: left;
  cursor: pointer;
  transition: background 0.2s;
  gap: 12px;
  color: #555;
  font-size: 0.9rem;
}

.dropdown-item:hover {
  background: #f8f9fa;
  color: #333;
}

.dropdown-item svg {
  flex-shrink: 0;
}

.logout-btn {
  color: #e74c3c;
}

.logout-btn:hover {
  background: #ffeaea;
  color: #c0392b;
}

/* --- Overlay --- */
.dropdown-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 999;
  background: transparent;
}

/* --- Responsive Design --- */
@media (max-width: 768px) {
  .user-header {
    padding: 0 15px;
  }
  
  .welcome-text {
    display: none;
  }
  
  .profile-name {
    display: none;
  }
  
  .profile-button {
    padding: 8px;
  }
  
  .dropdown {
    width: 280px;
    right: -10px;
  }
  
  .messages-dropdown {
    width: 300px;
  }
}

@media (max-width: 480px) {
  .logo span {
    display: none;
  }
  
  .user-header-left {
    gap: 10px;
  }
  
  .dropdown {
    width: 260px;
  }
  
  .messages-dropdown {
    width: 280px;
  }
}
</style>