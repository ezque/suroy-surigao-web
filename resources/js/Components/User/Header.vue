<template>
  <header class="user-header">
    <!-- Left Section: Logo & Welcome -->
    <div class="user-header-left">
      <div class="logo">
        <img src="/public/images/logo/logo.png" alt="Explore Icon" class="icon-image" />
        <span>Suroy surigao</span>
      </div>
      <h1 class="welcome-text">Welcome back, {{ userName }}!</h1>
    </div>

    <!-- Right Section: Messages, Notifications, Profile -->
    <div class="right-controls">
      <!-- Messages -->
      <div class="icon-button" @click="toggleMessages">
        <i class="material-icons">mail_outline</i>
        <div v-if="messageList.length > 0" class="notification-badge">{{ messageList.length }}</div>
        
        <!-- Messages dropdown -->
        <div v-if="showMessages" class="dropdown messages-dropdown">
          <div class="dropdown-header">
            <h3>Messages</h3>
            <span class="badge">{{ messageList.length }}</span>
          </div>
          <div class="dropdown-content">
            <div v-if="messageList.length === 0" class="empty-state">
              <i class="material-icons">mail_outline</i>
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
        <i class="material-icons">notifications_none</i>
        <div v-if="notifications.length > 0" class="notification-badge">{{ notifications.length }}</div>

        <!-- Notification dropdown -->
        <div v-if="showNotifications" class="dropdown notif-dropdown">
          <div class="dropdown-header">
            <h3>Notifications</h3>
            <span class="badge">{{ notifications.length }}</span>
          </div>
          <div class="dropdown-content">
            <div v-if="notifications.length === 0" class="empty-state">
              <i class="material-icons">notifications_none</i>
              <p>No new notifications</p>
            </div>
            <div v-else class="notification-list">
              <div v-for="(notif, index) in notifications" :key="index" 
                   class="notification-item" :class="{'unread': notif.unread}">
                <div class="notification-icon" :class="notif.type">
                  <i class="material-icons">
                    {{ notif.type === 'success' ? 'check_circle' : 
                       notif.type === 'warning' ? 'warning' : 'info' }}
                  </i>
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
        <div class="profile-info-inline">
          <span class="profile-name">{{ userName }}</span>
          <span class="profile-role">Traveler</span>
        </div>
        <i class="material-icons chevron" :class="{ 'rotated': showProfileDropdown }">
          expand_more
        </i>

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
              <i class="material-icons">person</i>
              <span>My Profile</span>
            </button>
            <button class="dropdown-item" @click="selectPage('settings')">
              <i class="material-icons">settings</i>
              <span>Settings</span>
            </button>
            <button class="dropdown-item" @click="selectPage('bookings')">
              <i class="material-icons">event</i>
              <span>My Bookings</span>
            </button>
            <button class="dropdown-item" @click="selectPage('help')">
              <i class="material-icons">help_outline</i>
              <span>Help & Support</span>
            </button>
          </div>
          <div class="dropdown-divider"></div>
          <button class="dropdown-item logout-btn" @click="logout">
            <i class="material-icons">logout</i>
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
    .substring(0, 2)
);

// Message and Notification data
const messageList = ref([
  { sender: "Travel Support", preview: "Your booking has been confirmed...", time: "2h ago" },
  { sender: "John Doe", preview: "Thanks for joining our travel group...", time: "1d ago" },
  { sender: "TravelHub", preview: "New destinations available in your area...", time: "3d ago" },
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
.user-header {
  width: 100%;
  height: 65px;
  background: rgb(248, 250, 250);
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  position: relative;
  z-index: 1000;
  border-bottom: 1px solid #f0f0f0;
}

/* ===== LEFT SECTION ===== */
.user-header-left {
  display: flex;
  align-items: center;
  gap: 30px;
}

.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  font-size: 1.3rem;
  color: #00b4db;
}

.logo i {
  font-size: 28px;
}

.logo span {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.welcome-text {
  font-size: 1rem;
  font-weight: 500;
  color: #666;
  margin: 0;
}

/* ===== RIGHT CONTROLS ===== */
.right-controls {
  display: flex;
  align-items: center;
  gap: 12px;
  position: relative;
}

/* Icon Buttons */
.icon-button {
  position: relative;
  background: #f8f9fa;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #666;
  transition: all 0.2s ease;
  cursor: pointer;
}

.icon-button:hover {
  background: #00b4db;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 180, 219, 0.3);
}

.icon-button i {
  font-size: 22px;
}

.notification-badge {
  position: absolute;
  top: -4px;
  right: -4px;
  background: #ff4757;
  color: white;
  font-size: 0.7rem;
  font-weight: 700;
  min-width: 18px;
  height: 18px;
  padding: 0 4px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 2px 6px rgba(255, 71, 87, 0.4);
}

/* ===== PROFILE BUTTON ===== */
.profile-button {
  display: flex;
  align-items: center;
  background: #f8f9fa;
  padding: 6px 12px 6px 6px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s ease;
  position: relative;
  gap: 10px;
  margin-left: 8px;
}

.profile-button:hover {
  background: #e8f7fb;
  box-shadow: 0 4px 12px rgba(0, 180, 219, 0.2);
}

.profile-avatar {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: white;
  font-size: 0.85rem;
  flex-shrink: 0;
}

.profile-info-inline {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.profile-name {
  font-weight: 600;
  font-size: 0.9rem;
  color: #333;
  line-height: 1;
}

.profile-role {
  font-size: 0.75rem;
  color: #999;
  line-height: 1;
}

.chevron {
  font-size: 20px;
  color: #999;
  transition: transform 0.3s ease;
}

.chevron.rotated {
  transform: rotate(180deg);
}

/* ===== DROPDOWN BASE ===== */
.dropdown {
  position: absolute;
  right: 0;
  top: 52px;
  background: white;
  border-radius: 12px;
  min-width: 320px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  color: #333;
  z-index: 1001;
  overflow: hidden;
  animation: dropdownFadeIn 0.2s ease-out;
  border: 1px solid #f0f0f0;
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
  padding: 16px 20px;
  background: #f8f9fa;
  border-bottom: 1px solid #e8e8e8;
}

.dropdown-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #333;
}

.dropdown-header .badge {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 4px 8px;
  border-radius: 10px;
  min-width: 20px;
  text-align: center;
}

.dropdown-content {
  max-height: 350px;
  overflow-y: auto;
}

.dropdown-content::-webkit-scrollbar {
  width: 6px;
}

.dropdown-content::-webkit-scrollbar-track {
  background: #f8f9fa;
}

.dropdown-content::-webkit-scrollbar-thumb {
  background: #ddd;
  border-radius: 3px;
}

.dropdown-content::-webkit-scrollbar-thumb:hover {
  background: #bbb;
}

.dropdown-footer {
  padding: 12px 20px;
  border-top: 1px solid #e8e8e8;
  text-align: center;
  background: #f8f9fa;
}

.view-all-btn {
  background: none;
  border: none;
  color: #00b4db;
  font-weight: 600;
  cursor: pointer;
  transition: color 0.2s;
  font-size: 0.9rem;
}

.view-all-btn:hover {
  color: #0083b0;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  color: #999;
  text-align: center;
}

.empty-state i {
  font-size: 48px;
  margin-bottom: 12px;
  opacity: 0.5;
}

.empty-state p {
  margin: 0;
  font-size: 0.9rem;
}

/* ===== NOTIFICATION LIST ===== */
.notification-list {
  padding: 8px 0;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  padding: 14px 20px;
  transition: background 0.2s;
  gap: 12px;
  border-bottom: 1px solid #f8f9fa;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item:hover {
  background: #f8f9fa;
}

.notification-item.unread {
  background: #e8f7fb;
}

.notification-item.unread:hover {
  background: #d9f2f9;
}

.notification-icon {
  width: 34px;
  height: 34px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.notification-icon i {
  font-size: 18px;
}

.notification-icon.success {
  background: #d4edda;
  color: #28a745;
}

.notification-icon.info {
  background: #d1ecf1;
  color: #00b4db;
}

.notification-icon.warning {
  background: #fff3cd;
  color: #ffc107;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-content p {
  margin: 0 0 4px 0;
  font-size: 0.875rem;
  line-height: 1.4;
  color: #333;
}

.notification-content small {
  color: #999;
  font-size: 0.75rem;
}

/* ===== MESSAGE LIST ===== */
.messages-dropdown {
  min-width: 360px;
}

.message-list {
  padding: 8px 0;
}

.message-item {
  display: flex;
  align-items: flex-start;
  padding: 14px 20px;
  transition: background 0.2s;
  gap: 12px;
  cursor: pointer;
  border-bottom: 1px solid #f8f9fa;
}

.message-item:last-child {
  border-bottom: none;
}

.message-item:hover {
  background: #f8f9fa;
}

.message-avatar {
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
  font-size: 0.9rem;
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
  font-size: 0.875rem;
  color: #333;
}

.time {
  color: #999;
  font-size: 0.75rem;
  flex-shrink: 0;
  margin-left: 8px;
}

.message-preview {
  margin: 0;
  font-size: 0.8125rem;
  color: #666;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.4;
}

/* ===== PROFILE DROPDOWN ===== */
.profile-dropdown {
  min-width: 280px;
}

.profile-info {
  display: flex;
  align-items: center;
  padding: 20px;
  gap: 15px;
  background: linear-gradient(135deg, #e8f7fb 0%, #f8f9fa 100%);
}

.profile-avatar-large {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: white;
  font-size: 1.1rem;
  flex-shrink: 0;
}

.profile-details h4 {
  margin: 0 0 4px 0;
  font-size: 1rem;
  color: #333;
  font-weight: 600;
}

.profile-details p {
  margin: 0;
  color: #666;
  font-size: 0.8125rem;
}

.dropdown-divider {
  height: 1px;
  background: #e8e8e8;
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
  transition: all 0.2s;
  gap: 12px;
  color: #555;
  font-size: 0.875rem;
}

.dropdown-item:hover {
  background: #f8f9fa;
  color: #00b4db;
}

.dropdown-item i {
  flex-shrink: 0;
  font-size: 20px;
  color: inherit;
}

.logout-btn {
  color: #e74c3c;
}

.logout-btn:hover {
  background: #ffebee;
  color: #c0392b;
}

/* ===== OVERLAY ===== */
.dropdown-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 999;
  background: transparent;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 968px) {
  .user-header {
    padding: 0 20px;
  }
  
  .welcome-text {
    display: none;
  }
  
  .logo span {
    display: none;
  }
}

@media (max-width: 768px) {
  .profile-info-inline {
    display: none;
  }
  
  .profile-button {
    padding: 6px;
  }
  
  .dropdown {
    width: 300px;
  }
  
  .messages-dropdown {
    width: 320px;
  }
}

@media (max-width: 480px) {
  .user-header {
    padding: 0 15px;
    height: 60px;
  }
  
  .right-controls {
    gap: 8px;
  }
  
  .icon-button {
    width: 36px;
    height: 36px;
  }
  
  .icon-button i {
    font-size: 20px;
  }
  
  .dropdown {
    width: calc(100vw - 30px);
    right: -5px;
  }
}
.icon-image {
  width: 24px;
  height: 24px;
  object-fit: contain;
}
.icon-image {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

</style>