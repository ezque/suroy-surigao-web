<template>
  <div class="sidebar-container">
    <!-- Profile Section -->
    <div class="profile-section">
      <div class="profile-avatar">
        <img
          :src="userData.avatar || '/images/default-avatar.png'"
          :alt="userData.name"
          class="avatar-image"
        />
        <div class="online-status" :class="{ online: userData.isOnline }"></div>
      </div>

      <div class="profile-info">
        <h3 class="user-name">{{ userData.name }}</h3>
        <p class="user-email">{{ userData.email }}</p>
        <div class="user-role-badge" :class="roleClass">
          {{ roleLabel }}
        </div>
      </div>

      <!-- Stats Section -->
      <div v-if="userRole === '2'" class="stats-section">
        <div class="stat-item">
          <span class="stat-number">{{ agencyStats.tours }}</span>
          <span class="stat-label">Tours</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ agencyStats.bookings }}</span>
          <span class="stat-label">Bookings</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ agencyStats.rating }}</span>
          <span class="stat-label">Rating</span>
        </div>
      </div>

      <div v-else-if="userRole === '3'" class="stats-section">
        <div class="stat-item">
          <span class="stat-number">{{ userStats.bookings }}</span>
          <span class="stat-label">Bookings</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ userStats.favorites }}</span>
          <span class="stat-label">Favorites</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ userStats.reviews }}</span>
          <span class="stat-label">Reviews</span>
        </div>
      </div>
    </div>

    <!-- Navigation Section -->
    <div class="navigation-section">
      <!-- ================= ADMIN ================= -->
      <template v-if="userRole === '1'">
        <button
          class="nav-item"
          :class="{ active: currentPage === 'dashboard' }"
          @click="$emit('selectPage', 'dashboard')"
        >
          <i class="material-icons">dashboard</i>
          <span class="nav-label">Dashboard</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'agency' }"
          @click="$emit('selectPage', 'agency')"
        >
          <i class="material-icons">business</i>
          <span class="nav-label">Agencies</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'spots' }"
          @click="$emit('selectPage', 'spots')"
        >
          <i class="material-icons">map</i>
          <span class="nav-label">Manage Spots</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'managePost' }"
          @click="$emit('selectPage', 'managePost')"
        >
          <i class="material-icons">article</i>
          <span class="nav-label">Manage Post</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'manageUser' }"
          @click="$emit('selectPage', 'manageUser')"
        >
          <i class="material-icons">group</i>
          <span class="nav-label">Manage Users</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'reviewsAndFeedbacks' }"
          @click="$emit('selectPage', 'reviewsAndFeedbacks')"
        >
          <i class="material-icons">feedback</i>
          <span class="nav-label">Reviews & Feedbacks</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'settings' }"
          @click="$emit('selectPage', 'settings')"
        >
          <i class="material-icons">settings</i>
          <span class="nav-label">Settings</span>
        </button>
      </template>

      <!-- ================= AGENCY ================= -->
      <template v-else-if="userRole === '2'">
        <button
          class="nav-item"
          :class="{ active: currentPage === 'agencyDashboard' }"
          @click="$emit('selectPage', 'agencyDashboard')"
        >
          <i class="material-icons">dashboard</i>
          <span class="nav-label">Dashboard</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'ManageTours' }"
          @click="$emit('selectPage', 'ManageTours')"
        >
          <i class="material-icons">work</i>
          <span class="nav-label">Manage Tours & Packages</span>
        </button>
          <button
              class="nav-item"
              :class="{ active: currentPage === 'Reservation' }"
              @click="$emit('selectPage', 'Reservation')"
          >
              <i class="material-icons">work</i>
              <span class="nav-label">Reservation</span>
          </button>

        <button
          class="nav-item"
          :class="{ active: currentPage === 'agencyFeedbacks' }"
          @click="$emit('selectPage', 'agencyFeedbacks')"
        >
          <i class="material-icons">star</i>
          <span class="nav-label">Reviews & Feedbacks</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'agencySettings' }"
          @click="$emit('selectPage', 'agencySettings')"
        >
          <i class="material-icons">settings</i>
          <span class="nav-label">Settings</span>
        </button>
      </template>

      <!-- ================= USER ================= -->
      <template v-else>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'userDashboard' }"
          @click="$emit('selectPage', 'userDashboard')"
        >
          <i class="material-icons">home</i>
          <span class="nav-label">Home</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'spots' }"
          @click="$emit('selectPage', 'spots')"
        >
          <i class="material-icons">place</i>
          <span class="nav-label">Spots</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'favorites' }"
          @click="$emit('selectPage', 'favorites')"
        >
          <i class="material-icons">favorite</i>
          <span class="nav-label">Favorites</span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'messages' }"
          @click="$emit('selectPage', 'messages')"
        >
          <i class="material-icons">chat</i>
          <span class="nav-label">Messages</span>
          <span v-if="userStats.unreadMessages > 0" class="nav-badge">
            {{ userStats.unreadMessages }}
          </span>
        </button>
        <button
          class="nav-item"
          :class="{ active: currentPage === 'settings' }"
          @click="$emit('selectPage', 'settings')"
        >
          <i class="material-icons">settings</i>
          <span class="nav-label">Settings</span>
        </button>
      </template>
    </div>

    <!-- Footer Section -->
    <div class="sidebar-footer">
      <button class="logout-btn" @click="handleLogout">
        <i class="material-icons">logout</i>
        <span>Logout</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  userRole: String,
  currentPage: String,
  userData: {
    type: Object,
    default: () => ({
      name: 'User Name',
      email: 'user@example.com',
      avatar: null,
      isOnline: true
    })
  }
});

const emit = defineEmits(["selectPage"]);

// Mock data - in real app, this would come from props or API
const agencyStats = ref({
  tours: 12,
  bookings: 45,
  rating: 4.8,
  pendingBookings: 3
});

const userStats = ref({
  bookings: 5,
  favorites: 12,
  reviews: 8,
  unreadMessages: 3
});

// Computed properties
const roleLabel = computed(() => {
  const roles = {
    '1': 'Administrator',
    '2': 'Agency Owner',
    '3': 'User'
  };
  return roles[props.userRole] || 'User';
});

const roleClass = computed(() => {
  const classes = {
    '1': 'role-admin',
    '2': 'role-agency',
    '3': 'role-user'
  };
  return classes[props.userRole] || 'role-user';
});

const handleLogout = () => {
  if (confirm("Are you sure you want to logout?")) {
    Inertia.post("/logout", {}, {
      onFinish: () => {
        window.location.reload();
      },
    });
  }
};
</script>

<style scoped>
.sidebar-container {
  width: 280px;
  height: 100vh;
  background: linear-gradient(135deg, #047a8a 0%, #034c57 100%);
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

/* Profile Section */
.profile-section {
  padding: 25px 20px;
  background: rgba(255, 255, 255, 0.08);
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  text-align: center;
}

.profile-avatar {
  position: relative;
  width: 80px;
  height: 80px;
  margin: 0 auto 15px;
}

.avatar-image {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid rgba(255, 255, 255, 0.2);
  background: rgba(255, 255, 255, 0.1);
}

.online-status {
  position: absolute;
  bottom: 5px;
  right: 5px;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #6b7280;
  border: 2px solid #047a8a;
}

.online-status.online {
  background: #10b981;
}

.profile-info {
  margin-bottom: 15px;
}

.user-name {
  color: white;
  font-size: 1.2rem;
  font-weight: 600;
  margin: 0 0 5px 0;
  line-height: 1.3;
}

.user-email {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.85rem;
  margin: 0 0 12px 0;
  line-height: 1.2;
}

.user-role-badge {
  display: inline-block;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-admin {
  background: linear-gradient(135deg, #ef4444, #dc2626);
  color: white;
}

.role-agency {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  color: white;
}

.role-user {
  background: rgba(255, 255, 255, 0.2);
  color: white;
}

/* Stats Section */
.stats-section {
  display: flex;
  justify-content: space-around;
  background: rgba(255, 255, 255, 0.05);
  border-radius: 12px;
  padding: 12px 10px;
  margin-top: 10px;
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
}

.stat-number {
  color: white;
  font-size: 1.1rem;
  font-weight: 700;
  line-height: 1;
  margin-bottom: 4px;
}

.stat-label {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.7rem;
  text-align: center;
}

/* Navigation Section */
.navigation-section {
  flex: 1;
  padding: 20px 0;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 20px;
  border: none;
  background: none;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  text-align: left;
  color: rgba(255, 255, 255, 0.8);
  position: relative;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  border-left-color: rgba(255, 255, 255, 0.3);
}

.nav-item.active {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  border-left-color: #10b981;
  font-weight: 600;
}

.nav-item .material-icons {
  font-size: 22px;
  width: 24px;
  text-align: center;
}

.nav-label {
  flex: 1;
  font-size: 0.95rem;
}

.nav-badge {
  background: #ef4444;
  color: white;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 12px;
  min-width: 20px;
  text-align: center;
}

/* Footer Section */
.sidebar-footer {
  padding: 20px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(0, 0, 0, 0.1);
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border: none;
  background: rgba(239, 68, 68, 0.1);
  color: #fca5a5;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  border-radius: 8px;
  font-weight: 500;
}

.logout-btn:hover {
  background: rgba(239, 68, 68, 0.2);
  color: #fecaca;
}

/* Scrollbar Styling */
.navigation-section::-webkit-scrollbar {
  width: 4px;
}

.navigation-section::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

.navigation-section::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.navigation-section::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}

/* Responsive Design */
@media (max-width: 768px) {
  .sidebar-container {
    width: 70px;
  }

  .profile-info,
  .stats-section,
  .nav-label,
  .logout-btn span {
    display: none;
  }

  .profile-avatar {
    width: 40px;
    height: 40px;
    margin-bottom: 0;
  }

  .profile-section {
    padding: 20px 10px;
  }

  .nav-item {
    justify-content: center;
    padding: 16px 0;
  }

  .logout-btn {
    justify-content: center;
    padding: 16px 0;
  }

  .nav-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 0.6rem;
    padding: 2px 4px;
    min-width: 16px;
  }
}
</style>
