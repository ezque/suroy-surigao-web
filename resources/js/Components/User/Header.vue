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

    <!-- Right Section: Notifications Only -->
    <div class="right-controls">
      <!-- Notifications -->
      <div class="notification-wrapper" @click="toggleNotifications">
        <div class="icon-button">
          <i class="material-icons">notifications_none</i>
          <div v-if="unreadCount > 0" class="notification-badge">{{ unreadCount }}</div>
        </div>

        <!-- Notification dropdown -->
        <div v-if="showNotifications" class="dropdown notif-dropdown">
          <div class="dropdown-header">
            <div class="header-title">
              <i class="material-icons">notifications</i>
              <h3>Notifications</h3>
            </div>
            <div class="header-actions">
              <button class="action-btn" @click.stop="markAllAsRead" v-if="unreadCount > 0">
                <i class="material-icons">done_all</i>
                Mark all read
              </button>
              <span class="badge">{{ unreadCount }} unread</span>
            </div>
          </div>
          
          <div class="dropdown-content">
            <div v-if="notifications.length === 0" class="empty-state">
              <i class="material-icons">notifications_off</i>
              <p>No notifications yet</p>
              <small>We'll notify you when something arrives</small>
            </div>
            
            <div v-else class="notification-list">
              <div v-for="(notif, index) in notifications" :key="index" 
                   class="notification-item" :class="{'unread': notif.unread, [notif.type]: true}">
                <div class="notification-icon">
                  <i class="material-icons">{{ getNotificationIcon(notif.type) }}</i>
                </div>
                <div class="notification-content">
                  <p class="notification-message">{{ notif.message }}</p>
                  <div class="notification-meta">
                    <span class="notification-time">{{ notif.time }}</span>
                    <span class="notification-category">{{ notif.category }}</span>
                  </div>
                </div>
                <button v-if="notif.unread" class="mark-read-btn" @click.stop="markAsRead(index)">
                  <i class="material-icons">check_circle</i>
                </button>
              </div>
            </div>
          </div>
          
          <div class="dropdown-footer">
            <button class="view-all-btn" @click.stop="viewAllNotifications">
              <i class="material-icons">list_alt</i>
              View Notification History
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Overlay for closing dropdowns when clicking outside -->
    <div v-if="showNotifications" class="dropdown-overlay" @click="closeAllDropdowns"></div>
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
const firstName = computed(() => userName.value.split(' ')[0]);
const initials = computed(() =>
  userName.value
    .split(" ")
    .map((n) => n[0])
    .join("")
    .toUpperCase()
    .substring(0, 2)
);

// Enhanced Notifications data
const notifications = ref([
  { 
    message: "Your booking for 'Surigao Island Hopping' has been confirmed!", 
    time: "2h ago", 
    type: "booking", 
    category: "Booking",
    unread: true 
  },
  { 
    message: "New destination 'Siargao Cloud 9' has been added to your favorites", 
    time: "5h ago", 
    type: "favorite", 
    category: "Favorite",
    unread: true 
  },
  { 
    message: "Payment for your trip to 'Enchanted River' was successful", 
    time: "1d ago", 
    type: "payment", 
    category: "Payment",
    unread: false 
  },
  { 
    message: "Your review was helpful to 12 travelers", 
    time: "2d ago", 
    type: "review", 
    category: "Review",
    unread: false 
  },
  { 
    message: "Special offer: 20% off on all island tours this weekend", 
    time: "3d ago", 
    type: "promo", 
    category: "Promotion",
    unread: true 
  },
  { 
    message: "Reminder: Your tour to 'Tinuy-an Falls' starts in 2 days", 
    time: "1d ago", 
    type: "reminder", 
    category: "Reminder",
    unread: true 
  },
]);

// Computed properties
const unreadCount = computed(() => {
  return notifications.value.filter(notif => notif.unread).length;
});

// Dropdown state
const showNotifications = ref(false);

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
};

const closeAllDropdowns = () => {
  showNotifications.value = false;
};

// Notification actions
const markAsRead = (index) => {
  notifications.value[index].unread = false;
};

const markAllAsRead = () => {
  notifications.value.forEach(notif => notif.unread = false);
};

const viewAllNotifications = () => {
  emit('selectPage', 'notifications');
  closeAllDropdowns();
};

const getNotificationIcon = (type) => {
  const icons = {
    booking: 'confirmation_number',
    favorite: 'favorite',
    payment: 'payments',
    review: 'star',
    promo: 'local_offer',
    reminder: 'event',
    system: 'info'
  };
  return icons[type] || 'notifications';
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (!event.target.closest('.notification-wrapper')) {
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
  height: 70px;
  background: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 30px;
  box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
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
  gap: 12px;
  font-weight: 700;
  font-size: 1.4rem;
  color: #00b4db;
}

.logo span {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 800;
}

.welcome-text {
  font-size: 1.1rem;
  font-weight: 500;
  color: #555;
  margin: 0;
}

/* ===== RIGHT CONTROLS ===== */
.right-controls {
  display: flex;
  align-items: center;
  gap: 20px;
  position: relative;
}

/* Notification Wrapper */
.notification-wrapper {
  position: relative;
  cursor: pointer;
}

/* Icon Button */
.icon-button {
  position: relative;
  background: #f8f9fa;
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #666;
  transition: all 0.3s ease;
  cursor: pointer;
  border: 2px solid transparent;
}

.icon-button:hover {
  background: #00b4db;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 180, 219, 0.3);
  border-color: #e8f7fb;
}

.icon-button i {
  font-size: 24px;
}

.notification-badge {
  position: absolute;
  top: -6px;
  right: -6px;
  background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
  color: white;
  font-size: 0.75rem;
  font-weight: 700;
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 3px 8px rgba(255, 107, 107, 0.4);
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.1); }
  100% { transform: scale(1); }
}

/* ===== DROPDOWN ===== */
.dropdown {
  position: absolute;
  right: 0;
  top: 60px;
  background: white;
  border-radius: 16px;
  width: 400px;
  max-height: 500px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  color: #333;
  z-index: 1001;
  overflow: hidden;
  animation: dropdownFadeIn 0.2s ease-out;
  border: 1px solid #e8e8e8;
}

@keyframes dropdownFadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Dropdown Header */
.dropdown-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background: linear-gradient(135deg, #f8f9fa 0%, #e8f7fb 100%);
  border-bottom: 1px solid #e8e8e8;
}

.header-title {
  display: flex;
  align-items: center;
  gap: 10px;
}

.header-title i {
  color: #00b4db;
  font-size: 22px;
}

.header-title h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 700;
  color: #333;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.action-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: rgba(0, 180, 219, 0.1);
  border: none;
  padding: 6px 12px;
  border-radius: 8px;
  color: #00b4db;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background: rgba(0, 180, 219, 0.2);
  transform: translateY(-1px);
}

.action-btn i {
  font-size: 16px;
}

.badge {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

/* Dropdown Content */
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

/* Empty State */
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 50px 20px;
  color: #999;
  text-align: center;
}

.empty-state i {
  font-size: 52px;
  margin-bottom: 16px;
  opacity: 0.4;
}

.empty-state p {
  margin: 0 0 8px 0;
  font-size: 1rem;
  font-weight: 600;
  color: #666;
}

.empty-state small {
  font-size: 0.85rem;
  color: #999;
}

/* ===== NOTIFICATION LIST ===== */
.notification-list {
  padding: 8px 0;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  padding: 16px 20px;
  transition: all 0.3s ease;
  gap: 14px;
  border-bottom: 1px solid #f8f9fa;
  position: relative;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item:hover {
  background: #f8f9fa;
  transform: translateX(4px);
}

.notification-item.unread {
  background: #f0f9ff;
  border-left: 4px solid #00b4db;
}

.notification-item.unread:hover {
  background: #e6f7ff;
}

/* Notification Icon */
.notification-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 2px;
}

.notification-icon i {
  font-size: 20px;
}

.notification-item.booking .notification-icon {
  background: #e8f5e8;
  color: #28a745;
}

.notification-item.favorite .notification-icon {
  background: #ffe8ec;
  color: #e91e63;
}

.notification-item.payment .notification-icon {
  background: #e8f4fd;
  color: #2196f3;
}

.notification-item.review .notification-icon {
  background: #fff8e1;
  color: #ff9800;
}

.notification-item.promo .notification-icon {
  background: #f3e5f5;
  color: #9c27b0;
}

.notification-item.reminder .notification-icon {
  background: #e8f5e8;
  color: #4caf50;
}

/* Notification Content */
.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-message {
  margin: 0 0 8px 0;
  font-size: 0.9rem;
  line-height: 1.4;
  color: #333;
  font-weight: 500;
}

.notification-meta {
  display: flex;
  align-items: center;
  gap: 12px;
}

.notification-time {
  color: #999;
  font-size: 0.8rem;
  font-weight: 500;
}

.notification-category {
  background: rgba(0, 180, 219, 0.1);
  color: #00b4db;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 10px;
}

/* Mark Read Button */
.mark-read-btn {
  background: none;
  border: none;
  color: #28a745;
  cursor: pointer;
  padding: 8px;
  border-radius: 8px;
  transition: all 0.2s ease;
  opacity: 0;
}

.notification-item:hover .mark-read-btn,
.notification-item.unread .mark-read-btn {
  opacity: 1;
}

.mark-read-btn:hover {
  background: rgba(40, 167, 69, 0.1);
  transform: scale(1.1);
}

.mark-read-btn i {
  font-size: 18px;
}

/* Dropdown Footer */
.dropdown-footer {
  padding: 16px 20px;
  border-top: 1px solid #e8e8e8;
  text-align: center;
  background: #f8f9fa;
}

.view-all-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  border: none;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.9rem;
  padding: 12px 24px;
  border-radius: 10px;
  width: 100%;
}

.view-all-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 180, 219, 0.4);
}

.view-all-btn i {
  font-size: 18px;
}

/* ===== USER MINIMAL ===== */
.user-minimal {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 16px;
  background: #f8f9fa;
  border-radius: 12px;
  border: 2px solid transparent;
  transition: all 0.3s ease;
}

.user-minimal:hover {
  background: #e8f7fb;
  border-color: #00b4db;
}

.user-avatar {
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

.user-greeting {
  font-weight: 600;
  font-size: 0.9rem;
  color: #333;
  white-space: nowrap;
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
  
  .dropdown {
    width: 350px;
  }
}

@media (max-width: 768px) {
  .user-greeting {
    display: none;
  }
  
  .user-minimal {
    padding: 8px;
  }
  
  .dropdown {
    width: 320px;
    right: -10px;
  }
}

@media (max-width: 480px) {
  .user-header {
    padding: 0 15px;
    height: 65px;
  }
  
  .logo span {
    display: none;
  }
  
  .icon-button {
    width: 44px;
    height: 44px;
  }
  
  .dropdown {
    width: calc(100vw - 30px);
    right: -5px;
  }
  
  .notification-meta {
    flex-direction: column;
    gap: 4px;
    align-items: flex-start;
  }
}

.icon-image {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}
</style>