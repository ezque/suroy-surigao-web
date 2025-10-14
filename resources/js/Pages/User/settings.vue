<template>
  <div class="user-settings-body">
    <div class="settings-header">
      <h1>Settings</h1>
      <p>Manage your account preferences and personal details.</p>
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

        <div v-if="activeSection === 'account'">
          <div class="section-header">
            <h2>Account Settings</h2>
            <p>Manage your account security and preferences.</p>
          </div>
          <div class="tab-navigation">
              <button :class="{ active: activeAccountTab === 'password' }" @click="activeAccountTab = 'password'">Password</button>
              <button :class="{ active: activeAccountTab === 'security' }" @click="activeAccountTab = 'security'">Security</button>
          </div>
          
          <div v-if="activeAccountTab === 'password'" class="content-card">
              <div class="card-header">
                  <h3>Change Password</h3>
                  <p>Update your password regularly to keep your account secure.</p>
              </div>
              <div class="card-body">
                  <div class="form-group">
                      <label for="currentPassword">Current Password</label>
                      <input type="password" id="currentPassword" placeholder="Enter current password" />
                  </div>
                  <div class="form-group">
                      <label for="newPassword">New Password</label>
                      <input type="password" id="newPassword" placeholder="Enter new password" />
                  </div>
                   <div class="form-group">
                      <label for="confirmPassword">Confirm New Password</label>
                      <input type="password" id="confirmPassword" placeholder="Confirm new password" />
                  </div>
              </div>
              <div class="card-footer">
                  <button class="btn-primary">Update Password</button>
              </div>
          </div>

          <div v-if="activeAccountTab === 'security'" class="content-card">
               <div class="card-header">
                  <h3>Two-Factor Authentication</h3>
                  <p>Add an extra layer of security to your account during login.</p>
              </div>
              <div class="card-body">
                  <div class="toggle-option">
                    <div class="toggle-info">
                      <h4>Enable 2FA</h4>
                      <p>When enabled, you'll be prompted for a code from your authenticator app.</p>
                    </div>
                    <label class="toggle-switch">
                      <input type="checkbox" v-model="settings.twoFactorAuth" />
                      <span class="slider"></span>
                    </label>
                  </div>
              </div>
          </div>
        </div>

        <div v-if="activeSection === 'notifications'" class="content-card">
            <div class="card-header">
                <h2>Notifications</h2>
                <p>Choose how you want to be notified.</p>
            </div>
            <div class="card-body">
                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>New Messages</h4>
                        <p>Get notified when you receive new messages.</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" v-model="settings.notifyMessages" />
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Booking Updates</h4>
                        <p>Receive updates and confirmations about your bookings.</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" v-model="settings.notifyBookings" />
                        <span class="slider"></span>
                    </label>
                </div>
                 <div class="toggle-option">
                    <div class="toggle-info">
                        <h4>Weekly Newsletter</h4>
                        <p>Receive travel tips and destination highlights by email.</p>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" v-model="settings.emailNewsletter" />
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn-primary">Save Preferences</button>
            </div>
        </div>

        <div v-if="activeSection === 'privacy'" class="content-card danger-zone">
             <div class="card-header">
                <h2>Danger Zone</h2>
                <p>These actions are permanent and cannot be undone.</p>
            </div>
            <div class="card-body">
                <div class="danger-action">
                    <div>
                        <h4>Delete Account</h4>
                        <p>Once you delete your account, there is no going back. Please be certain.</p>
                    </div>
                    <button class="btn-danger">Delete My Account</button>
                </div>
            </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const activeSection = ref('profile');
const activeAccountTab = ref('password');

const menuItems = [
  { id: 'profile', label: 'Profile', icon: 'person' },
  { id: 'account', label: 'Account', icon: 'lock' },
  { id: 'notifications', label: 'Notifications', icon: 'notifications' },
  { id: 'privacy', label: 'Privacy & Data', icon: 'shield' },
];

const userProfile = reactive({
  name: 'Maria Clara',
  email: 'maria.clara@example.com',
  phone: '+63 917 123 4567',
  location: 'Surigao City, Philippines',
  bio: 'A passionate traveler and guide, eager to share the beauty of the Caraga region with the world.',
  avatar: 'https://i.pravatar.cc/150?img=25', // Using a placeholder image
});

const settings = reactive({
  twoFactorAuth: false,
  notifyMessages: true,
  notifyBookings: true,
  emailNewsletter: false,
});
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
.section-header {
    margin-bottom: 16px;
}
.section-header h2 {
    font-size: 1.5rem;
    color: #1e293b;
    margin: 0;
}
.section-header p {
    color: #64748b;
    margin-top: 4px;
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
.card-header h2, .card-header h3 {
  font-size: 1.25rem;
  color: #1e293b;
  margin: 0;
}
.card-header p {
  margin: 4px 0 0 0;
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

/* ===== TABS ===== */
.tab-navigation {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}
.tab-navigation button {
    padding: 8px 16px;
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    color: #64748b;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px; /* Overlap the main border */
    transition: all 0.2s ease;
}
.tab-navigation button:hover {
    color: #0D9488;
}
.tab-navigation button.active {
    color: #0D9488;
    border-bottom-color: #0D9488;
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
.btn-primary, .btn-secondary, .btn-danger {
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
.btn-danger {
  background: #dc2626;
  color: white;
}
.btn-danger:hover {
  background: #b91c1c;
}

/* ===== TOGGLE SWITCH ===== */
.toggle-option {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 0;
  border-bottom: 1px solid #f1f5f9;
}
.toggle-option:last-child { border-bottom: none; }
.toggle-info h4 { margin: 0 0 4px 0; font-size: 1rem; color: #1e293b; }
.toggle-info p { margin: 0; font-size: 0.9rem; color: #64748b; }
.toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #0D9488; }
input:checked + .slider:before { transform: translateX(20px); }

/* ===== DANGER ZONE ===== */
.danger-zone { border-color: #fca5a5; }
.danger-zone .card-header { background-color: #fef2f2; }
.danger-zone .card-header h2 { color: #b91c1c; }
.danger-action { display: flex; justify-content: space-between; align-items: center; }
.danger-action h4 { margin: 0; }
.danger-action p { margin: 4px 0 0 0; color: #64748b; font-size: 0.9rem; }

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
  .danger-action { flex-direction: column; align-items: flex-start; gap: 12px; }
}
</style>