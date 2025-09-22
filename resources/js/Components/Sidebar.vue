<template>
  <div class="sidebar-container">
    <div class="side-label-container">
        <h2 v-if="userRole === '1'">Admin</h2>
        <h2 v-else-if="userRole === '2'">Agency</h2>
    </div>
    <div class="sidebar-button-container">
         <button @click="$emit('selectPage', 'dashboard')" v-if="userRole === '1'">
            <Home class="icon" />
            Dashboard
         </button>
          <button @click="$emit('selectPage', 'agency')" v-if="userRole === '1'">
            <Building2 class="icon" />
            Agencies
          </button>
          <button @click="$emit('selectPage', 'spots')" v-if="userRole === '1'">
            <MapPin class="icon" />
            Manage Spots
          </button>
          <button @click="$emit('selectPage', 'post')" v-if="userRole === '1'">
            <FileText class="icon" />
            Manage Post
          </button>
          <button @click="$emit('selectPage', 'manageUser')" v-if="userRole === '1'">
            <Users class="icon" />
            Manage Users
          </button>
          <button @click="$emit('selectPage', 'reviews')" v-if="userRole === '1'">
            <MessageSquare class="icon" />
            Reviews & Feedbacks
          </button>
          <button @click="$emit('selectPage', 'settings')" v-if="userRole === '1'">
            <Settings class="icon" />
            Settings
          </button>

          <!-- Agency Sidebar -->
          <button @click="$emit('selectPage', 'agencyDashboard')" v-if="userRole === '2'">
            <Home class="icon" />
            Dashboard
          </button>
          <button @click="$emit('selectPage', 'ManageTours')" v-if="userRole === '2'">
            <Briefcase class="icon" />
            Manage Tours & Package
          </button>
          <button @click="$emit('selectPage', 'ManageBookings')" v-if="userRole === '2'">
            <Book class="icon" />
            Manage Bookings
          </button>
          <button @click="$emit('selectPage', 'ReviewsFedback')" v-if="userRole === '2'">
            <Star class="icon" />
            Reviews and Feedback
          </button>
          <button @click="$emit('selectPage', 'agencySettings')" v-if="userRole === '2'">
            <Settings class="icon" />
            Settings
          </button>

          <!-- Logout -->
          <button class="logout-btn" @click="handleLogout">
            <LogOut class="icon" />
            Logout
          </button>

    </div>
  </div>
</template>

<script setup>
    import { Inertia } from "@inertiajs/inertia";

    const props = defineProps({
      userRole: String
    });
    const emit = defineEmits(["selectPage"]);
    const handleLogout = () => {
      if (confirm("Are you sure you want to logout?")) {
        Inertia.post("/logout", {}, {
          onFinish: () => {
            window.location.reload();
          }
        });
      }
    };
</script>


<style scoped>
    .sidebar-container {
      width: 15%;
      height: 100%;
      background-color: #007A8C;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Admin/Agency Label */
    .side-label-container h2 {
      font-size: 1.5em;
      font-weight: 700;
      margin-top: 10px;
      margin-left: 10px;
      text-transform: uppercase;
      color: white; /* 🔥 makes the title white */
    }

    /* Buttons */
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
      color: white; /* 🔥 makes text white */
    }

    /* Button hover */
    .sidebar-button-container button:hover {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 6px;
    }

    /* Lucide Icons */
    .icon {
      width: 22px;
      height: 22px;
      stroke: white; /* 🔥 makes icons white */
    }

    /* Logout button text & icon */
    .logout-btn {
      color: #ffecec; /* light pink text */
      font-weight: 600;
    }
    .logout-btn .icon {
      stroke: #ffecec; /* same as text */
    }
    .logout-btn:hover {
      background: rgba(255, 0, 0, 0.2);
    }
    .logout-btn:hover .icon {
      stroke: red; /* red on hover */
    }
</style>
