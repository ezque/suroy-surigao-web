<template>
  <div class="user-settings-body">
    <div class="settings-header">
      <h1>Settings</h1>
      <p>Manage your personal profile information.</p>
    </div>

    <div class="settings-container">
      <aside class="settings-sidebar">
        <nav class="sidebar-menu">
          <button 
            v-for="item in menuItems" 
            :key="item.id"
            class="menu-item"
            :class="{ active: activeSection === item.id }"
            @click="activeSection = item.id"
          >
            <i class="material-icons">{{ item.icon }}</i>
            <span>{{ item.label }}</span>
          </button>
        </nav>
      </aside>

      <main class="settings-content">
        <!-- Profile Section -->
        <div v-if="activeSection === 'profile'" class="content-card">
          <div class="card-header">
            <h2>Public Profile</h2>
            <p>This information will be displayed on your public profile.</p>
          </div>
          <div class="card-body">
            <div class="profile-header">
              <img :src="userProfile.avatar" alt="Profile Avatar" class="profile-avatar"/>
              <div class="profile-info">
                <button class="btn-primary">Upload new picture</button>
                <button class="btn-secondary">Delete</button>
              </div>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" v-model="userProfile.name" />
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" v-model="userProfile.email" />
              </div>
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" v-model="userProfile.phone" />
              </div>
              <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" v-model="userProfile.location" />
              </div>
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" v-model="userProfile.bio" rows="4" placeholder="Tell us a little about yourself..."></textarea>
            </div>
          </div>
          <div class="card-footer">
            <button class="btn-primary">Save Changes</button>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const activeSection = ref('profile')

const menuItems = [
  { id: 'profile', label: 'Profile', icon: 'person' },
]

const userProfile = reactive({
  name: 'Maria Clara',
  email: 'maria.clara@example.com',
  phone: '+63 917 123 4567',
  location: 'Surigao City, Philippines',
  bio: 'A passionate traveler and guide, eager to share the beauty of the Caraga region with the world.',
  avatar: 'https://i.pravatar.cc/150?img=25', // Placeholder image
})
</script>

<style scoped>
/* ===== GLOBAL & LAYOUT ===== */
.user-settings-body {
  width: 100%;
  height: 100%;
  background-color: #f8fafc;
  overflow-y: auto;
}
.settings-header {
  background-color: white;
  padding: 24px 32px;
  border-bottom: 1px solid #e2e8f0;
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
}
.settings-container {
  display: flex;
  gap: 32px;
  padding: 32px;
  max-width: 1400px;
  margin: 0 auto;
  align-items: flex-start;
}

/* ===== SIDEBAR ===== */
.settings-sidebar {
  width: 240px;
  flex-shrink: 0;
  position: sticky;
  top: 32px;
}
.sidebar-menu {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.menu-item {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border: none;
  background: transparent;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #334155;
  font-size: 0.95rem;
  font-weight: 500;
  text-align: left;
}
.menu-item:hover {
  background: #f1f5f9;
  color: #0D9488;
}
.menu-item.active {
  background: #0D9488;
  color: white;
  box-shadow: 0 4px 10px rgba(13, 148, 136, 0.2);
}
.menu-item i {
  font-size: 20px;
}

/* ===== CONTENT AREA ===== */
.settings-content {
  flex: 1;
  min-width: 0;
}
.content-card {
  background: white;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  margin-bottom: 24px;
}
.card-header {
  padding: 24px;
  border-bottom: 1px solid #e2e8f0;
}
.card-header h2 {
  font-size: 1.25rem;
  color: #1e293b;
  margin: 0;
}
.card-header p {
  margin-top: 4px;
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

/* ===== PROFILE SECTION ===== */
.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.profile-info {
    display: flex;
    gap: 12px;
}

/* ===== FORMS ===== */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}
.form-group {
  display: flex;
  flex-direction: column;
  margin-bottom: 20px;
}
.form-group label {
  color: #334155;
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 0.9rem;
}
.form-group input,
.form-group textarea {
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s ease;
  background: white;
}
.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #0D9488;
  box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
}
.form-group textarea {
  resize: vertical;
  font-family: inherit;
  min-height: 100px;
}

/* ===== BUTTONS ===== */
.btn-primary, .btn-secondary {
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.9rem;
  border: none;
}
.btn-primary {
  background: #0D9488;
  color: white;
}
.btn-primary:hover {
  background: #0f766e;
}
.btn-secondary {
  background: #e2e8f0;
  color: #334155;
  border: 1px solid #cbd5e1;
}
.btn-secondary:hover {
  background: #cbd5e1;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 968px) {
  .settings-container { flex-direction: column; }
  .settings-sidebar { width: 100%; position: static; }
  .sidebar-menu { flex-direction: row; overflow-x: auto; padding-bottom: 8px; }
  .menu-item { white-space: nowrap; }
}
@media (max-width: 768px) {
  .user-settings-body, .settings-header, .settings-container { padding: 16px; }
  .form-grid { grid-template-columns: 1fr; }
  .profile-header { flex-direction: column; align-items: flex-start; }
}
</style>
