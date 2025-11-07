<template>
  <div class="adminSettings-body">
    <header class="settings-header">
      <h1>Admin Settings</h1>
      <p>Manage your admin profile and account preferences.</p>
    </header>

    <div class="settings-layout">
      <nav class="settings-nav">
        <a
          href="#"
          @click.prevent="activeSection = 'profile'"
          :class="{ active: activeSection === 'profile' }"
        >
          <i class="material-icons">person</i>
          <span>Admin Profile</span>
        </a>
        <a
          href="#"
          @click.prevent="activeSection = 'security'"
          :class="{ active: activeSection === 'security' }"
        >
          <i class="material-icons">security</i>
          <span>Security</span>
        </a>
        <a
          href="#"
          @click.prevent="activeSection = 'danger'"
          :class="{ active: activeSection === 'danger' }"
        >
          <i class="material-icons">warning</i>
          <span>Danger Zone</span>
        </a>
      </nav>

      <main class="settings-content">
        <!-- ===== ADMIN PROFILE ===== -->
        <div v-if="activeSection === 'profile'" class="settings-card">
          <div class="card-header">
            <h3>Profile</h3>
            <p>This information will be used for your admin account.</p>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="adminName">Full Name</label>
              <input
                type="text"
                id="adminName"
                v-model="adminData.name"
                placeholder="e.g., John Doe"
              />
            </div>
            <div class="form-group">
              <label for="adminEmail">Email Address</label>
              <input
                type="email"
                id="adminEmail"
                v-model="adminData.email"
                placeholder="e.g., admin@suroysurigao.com"
              />
            </div>
            <div class="form-group">
              <label for="adminPhone">Phone Number</label>
              <input
                type="tel"
                id="adminPhone"
                v-model="adminData.phone"
                placeholder="+63 912 345 6789"
              />
            </div>
            <div class="form-group">
              <label for="adminPosition">Position</label>
              <input
                type="text"
                id="adminPosition"
                v-model="adminData.position"
                placeholder="e.g., System Administrator"
              />
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary" @click="saveProfile">Save Changes</button>
          </div>
        </div>

        <!-- ===== SECURITY ===== -->
        <div v-if="activeSection === 'security'" class="settings-card">
          <div class="card-header">
            <h3>Security</h3>
            <p>Manage your account security and password.</p>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="currentPassword">Current Password</label>
              <input
                type="password"
                id="currentPassword"
                v-model="securityData.currentPassword"
                placeholder="Enter your current password"
              />
            </div>
            <div class="form-group">
              <label for="newPassword">New Password</label>
              <input
                type="password"
                id="newPassword"
                v-model="securityData.newPassword"
                placeholder="Enter your new password"
              />
            </div>
            <div class="form-group">
              <label for="confirmPassword">Confirm New Password</label>
              <input
                type="password"
                id="confirmPassword"
                v-model="securityData.confirmPassword"
                placeholder="Confirm your new password"
              />
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary" @click="updatePassword">Update Password</button>
          </div>
        </div>

        <!-- ===== DANGER ZONE ===== -->
        <div v-if="activeSection === 'danger'" class="settings-card danger-zone">
          <div class="card-header">
            <h3>Danger Zone</h3>
            <p>These actions are irreversible. Please be certain.</p>
          </div>
          <div class="card-body">
            <div class="danger-action">
              <div>
                <h4>Deactivate Admin Account</h4>
                <p>Your admin account will be temporarily disabled and you won't be able to access the system.</p>
              </div>
              <button class="btn btn-danger-outline" @click="deactivateAccount">Deactivate Account</button>
            </div>
            <div class="danger-action" style="margin-top: 24px;">
              <div>
                <h4>Delete Admin Account</h4>
                <p>Permanently delete your admin account and all associated data. This action cannot be undone.</p>
              </div>
              <button class="btn btn-danger" @click="deleteAccount">Delete Account</button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";

const props = defineProps({
  userInformation: Object
})

const activeSection = ref("profile");

// --- Reactive Data Models ---
const adminData = ref({});
const securityData = ref({
  currentPassword: "",
  newPassword: "",
  confirmPassword: ""
});

// --- Functions ---
const saveProfile = () => {
  console.log("Saving Admin Profile:", adminData.value);
  alert("Admin profile saved successfully!");
  // Add API call here
};

const updatePassword = () => {
  if (!securityData.value.currentPassword || !securityData.value.newPassword) {
    alert("Please fill in all password fields.");
    return;
  }

  if (securityData.value.newPassword !== securityData.value.confirmPassword) {
    alert("New passwords do not match.");
    return;
  }

  console.log("Updating password:", securityData.value);
  alert("Password updated successfully!");
  
  // Reset password fields
  securityData.value = {
    currentPassword: "",
    newPassword: "",
    confirmPassword: ""
  };
};

const deactivateAccount = () => {
  const confirmed = confirm("Are you sure you want to deactivate your admin account? You can reactivate it later by contacting support.");
  if (confirmed) {
    console.log("Deactivating admin account");
    alert("Admin account has been deactivated.");
    // Add API call here
  }
};

const deleteAccount = () => {
  const confirmed = confirm("WARNING: This will permanently delete your admin account and all associated data. This action cannot be undone. Are you absolutely sure?");
  if (confirmed) {
    const finalConfirmation = confirm("This is your final warning. All data will be lost permanently. Type 'DELETE' to confirm.");
    if (finalConfirmation) {
      console.log("Deleting admin account");
      alert("Admin account has been scheduled for deletion.");
      // Add API call here
    }
  }
};

// --- Data Fetching Simulation ---
onMounted(() => {
  fetchSettingsData();
});

const fetchSettingsData = () => {
  // Use the userInformation prop to populate the form
  adminData.value = {
    name: props.userInformation.name || "Admin User",
    email: props.userInformation.email || "admin@suroysurigao.com",
    phone: props.userInformation.phone || "+63 917 123 4567",
    position: props.userInformation.position || "System Administrator"
  };
};
</script>

<style scoped>
/* ===== MAIN LAYOUT ===== */
.adminSettings-body {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  background-color: #f8fafc;
  padding: 24px 32px;
}
.settings-header {
  margin-bottom: 24px;
}
.settings-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0;
}
.settings-header p {
  margin-top: 4px;
  color: #64748b;
  font-size: 1rem;
}
.settings-layout {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 32px;
  align-items: start;
}

/* ===== NAVIGATION SIDEBAR ===== */
.settings-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.settings-nav a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  text-decoration: none;
  color: #334155;
  font-weight: 500;
  transition: background-color 0.2s, color 0.2s;
}
.settings-nav a:hover {
  background-color: #f1f5f9;
}
.settings-nav a.active {
  background-color: #007a8c;
  color: white;
}
.settings-nav a .material-icons {
  font-size: 20px;
}

/* ===== CONTENT CARD ===== */
.settings-card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}
.card-header {
  padding: 24px;
  border-bottom: 1px solid #e2e8f0;
}
.card-header h3 {
  margin: 0 0 4px 0;
  font-size: 1.25rem;
  color: #1e293b;
}
.card-header p {
  margin: 0;
  color: #64748b;
}
.card-body {
  padding: 24px;
}
.card-footer {
  padding: 16px 24px;
  background-color: #f8fafc;
  border-top: 1px solid #e2e8f0;
  border-radius: 0 0 12px 12px;
  display: flex;
  justify-content: flex-end;
}

/* ===== FORM ELEMENTS ===== */
.form-group {
  margin-bottom: 20px;
}
.form-group label {
  display: block;
  font-weight: 500;
  color: #334155;
  margin-bottom: 8px;
}
.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  font-size: 1rem;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007a8c;
  box-shadow: 0 0 0 3px rgba(0, 122, 140, 0.15);
}

/* ===== DANGER ZONE ===== */
.danger-zone {
  border-color: #ef4444;
}
.danger-zone .card-header h3 {
  color: #b91c1c;
}
.danger-action {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 0;
}
.danger-action:not(:last-child) {
  border-bottom: 1px solid #e2e8f0;
}
.danger-action h4 {
  color: #1e293b;
  margin: 0 0 4px 0;
}
.danger-action p {
  color: #64748b;
  margin: 0;
  font-size: 0.9rem;
}

/* ===== BUTTONS ===== */
.btn {
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.9rem;
}
.btn-primary {
  background-color: #007a8c;
  color: white;
}
.btn-primary:hover {
  background-color: #009fb7;
}
.btn-danger-outline {
  background-color: transparent;
  color: #b91c1c;
  border: 1px solid #ef4444;
}
.btn-danger-outline:hover {
  background-color: #fee2e2;
}
.btn-danger {
  background-color: #dc2626;
  color: white;
}
.btn-danger:hover {
  background-color: #b91c1c;
}

/* ===== RESPONSIVENESS ===== */
@media (max-width: 992px) {
  .settings-layout {
    grid-template-columns: 1fr;
  }
  .settings-nav {
    flex-direction: row;
    overflow-x: auto;
    padding-bottom: 8px;
  }
}
@media (max-width: 768px) {
  .adminSettings-body {
    padding: 16px;
  }
  .danger-action {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }
  .card-body {
    padding: 16px;
  }
  .card-header {
    padding: 16px;
  }
}
</style>