<template>
  <div class="sidebar-container">
    <!-- Label Section -->
    <div class="side-label-container">
      <h2 v-if="userRole === '1'">Admin</h2>
      <h2 v-else-if="userRole === '2'">Agency</h2>
      <h2 v-else>User</h2>
    </div>

    <!-- Buttons -->
    <div class="sidebar-button-container">
      <!-- ================= ADMIN ================= -->
      <template v-if="userRole === '1'">
        <button @click="$emit('selectPage', 'dashboard')">
          <i class="material-icons">dashboard</i> Dashboard
        </button>
        <button @click="$emit('selectPage', 'agency')">
          <i class="material-icons">business</i> Agencies
        </button>
        <button @click="$emit('selectPage', 'spots')">
          <i class="material-icons">map</i> Manage Spots
        </button>
        <button @click="$emit('selectPage', 'managePost')">
          <i class="material-icons">article</i> Manage Post
        </button>
        <button @click="$emit('selectPage', 'manageUser')">
          <i class="material-icons">group</i> Manage Users
        </button>
        <button @click="$emit('selectPage', 'reviewsAndFeedbacks')">
          <i class="material-icons">feedback</i> Reviews & Feedbacks
        </button>
        <button @click="$emit('selectPage', 'settings')">
          <i class="material-icons">settings</i> Settings
        </button>
      </template>

      <!-- ================= AGENCY ================= -->
      <template v-else-if="userRole === '2'">
        <button @click="$emit('selectPage', 'agencyDashboard')">
          <i class="material-icons">dashboard</i> Dashboard
        </button>
        <button @click="$emit('selectPage', 'ManageTours')">
          <i class="material-icons">work</i> Manage Tours & Packages
        </button>
        <button @click="$emit('selectPage', 'manageReservation')">
          <i class="material-icons">book</i> Manage Reservation
        </button>
        <button @click="$emit('selectPage', 'agencyFeedbacks')">
          <i class="material-icons">star</i> Reviews & Feedbacks
        </button>
        <button @click="$emit('selectPage', 'agencySettings')">
          <i class="material-icons">settings</i> Settings
        </button>
      </template>

      <!-- ================= USER ================= -->
      <template v-else>
        <button @click="$emit('selectPage', 'userDashboard')">
          <i class="material-icons">home</i> Home
        </button>
        <button @click="$emit('selectPage', 'spots')">
          <i class="material-icons">place</i> Spots
        </button>
        <button @click="$emit('selectPage', 'favorites')">
          <i class="material-icons">favorite</i> Favorites
        </button>
        <button @click="$emit('selectPage', 'messages')">
          <i class="material-icons">chat</i> Messages
        </button>
        <button @click="$emit('selectPage', 'settings')">
          <i class="material-icons">settings</i> Settings
        </button>
      </template>

      <!-- Logout -->
      <button class="logout-btn" @click="handleLogout">
        <i class="material-icons">logout</i> Logout
      </button>
    </div>
  </div>
</template>

<script setup>
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  userRole: String,
});

const emit = defineEmits(["selectPage"]);

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
      width: 15%;
      height: 100vh;
      background-color: #047a8a;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
    }

    .side-label-container h2 {
      font-size: 1.5em;
      font-weight: 700;
      text-transform: uppercase;
      color: white;
      margin-bottom: 30px;
    }

    .sidebar-button-container {
      width: 100%;
      display: flex;
      flex-direction: column;
    }

    .sidebar-button-container button {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      border: none;
      background: none;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.2s;
      width: 100%;
      text-align: left;
      color: white;
    }

    .sidebar-button-container button:hover {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 6px;
    }

    .material-icons {
      font-size: 22px;
      color: white;
    }

    .logout-btn {
      color: #ffecec;
      font-weight: 600;
      margin-top: auto;
      border-top: 1px solid rgba(255,255,255,0.2);
    }

    .logout-btn:hover {
      background: rgba(255, 0, 0, 0.2);
    }
</style>
