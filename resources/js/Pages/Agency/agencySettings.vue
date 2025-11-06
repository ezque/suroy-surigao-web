<template>
  <div class="agencySettings-body">
    <header class="settings-header">
      <h1>Agency Settings</h1>
      <p>Manage your agency profile and account preferences.</p>
    </header>

    <div class="settings-layout">
      <nav class="settings-nav">
        <a
          href="#"
          @click.prevent="activeSection = 'profile'"
          :class="{ active: activeSection === 'profile' }"
        >
          <i class="material-icons">storefront</i>
          <span>Agency Profile</span>
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
        <!-- ===== AGENCY PROFILE ===== -->
        <div v-if="activeSection === 'profile'" class="settings-card">
          <div class="card-header">
            <h3>Profile</h3>
            <p>This information will be displayed publicly on your agency page.</p>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label for="agencyName">Agency Name</label>
              <input
                type="text"
                id="agencyName"
                v-model="agencyData.name"
                placeholder="e.g., Suroy Surigao Tours"
              />
            </div>
            <div class="form-group">
              <label for="agencyDescription">Description</label>
              <textarea
                id="agencyDescription"
                v-model="agencyData.description"
                rows="4"
                placeholder="Tell us about your agency..."
              ></textarea>
            </div>
            <div class="form-group">
              <label for="agencyPhone">Phone Number</label>
              <input
                type="tel"
                id="agencyPhone"
                v-model="agencyData.phone"
                placeholder="+63 912 345 6789"
              />
            </div>
            <div class="form-group">
              <label for="agencyAddress">Address</label>
              <input
                type="text"
                id="agencyAddress"
                v-model="agencyData.address"
                placeholder="e.g., San Nicolas Street, Surigao City"
              />
            </div>
          </div>
          <div class="card-footer">
            <button class="btn btn-primary" @click="saveProfile">Save Changes</button>
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
                <h4>Deactivate Agency Account</h4>
                <p>Your agency profile and packages will be hidden from public view.</p>
              </div>
              <button class="btn btn-danger-outline">Deactivate Account</button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const activeSection = ref("profile");

// --- Reactive Data Models ---
const agencyData = ref({});

// --- Functions ---
const saveProfile = () => {
  console.log("Saving Agency Profile:", agencyData.value);
  alert("Agency profile saved!");
  // Add API call here
};

// --- Data Fetching Simulation ---
onMounted(() => {
  fetchSettingsData();
});

const fetchSettingsData = () => {
  agencyData.value = {
    name: "Suroy Surigao Tours",
    description:
      "Your premier guide to the beautiful islands and landscapes of Surigao. We offer the best local tours with experienced guides.",
    phone: "+63 917 123 4567",
    address: "San Nicolas Street, Surigao City, 8400",
  };
};
</script>

<style scoped>
/* ===== MAIN LAYOUT ===== */
.agencySettings-body {
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
.btn {
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
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
  .agencySettings-body {
    padding: 16px;
  }
  .danger-action {
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
  }
}
</style>
