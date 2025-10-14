<template>
  <div class="user-settings-body">
    <!-- Header Section -->
    <div class="settings-header">
      <div class="header-content">
        <h1>Settings</h1>
        <p class="subtitle">Manage your account preferences and settings</p>
      </div>
    </div>

    <!-- Settings Container -->
    <div class="settings-container">
      <!-- Sidebar Navigation -->
      <div class="settings-sidebar">
        <div class="sidebar-menu">
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
        </div>
      </div>

      <!-- Settings Content -->
      <div class="settings-content">
        <!-- Profile Settings -->
        <div v-if="activeSection === 'profile'" class="settings-section">
          <div class="section-header">
            <h2>Profile Information</h2>
            <p>Update your personal details and profile picture</p>
          </div>

          <div class="profile-photo-section">
            <div class="profile-photo">
              <img :src="userProfile.avatar" alt="Profile" />
              <button class="change-photo-btn">
                <i class="material-icons">camera_alt</i>
              </button>
            </div>
            <div class="photo-info">
              <h3>{{ userProfile.name }}</h3>
              <p>{{ userProfile.email }}</p>
              <button class="btn-outline">Change Photo</button>
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" v-model="userProfile.name" />
            </div>
            <div class="form-group">
              <label>Email Address</label>
              <input type="email" v-model="userProfile.email" />
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" v-model="userProfile.phone" />
            </div>
            <div class="form-group">
              <label>Location</label>
              <input type="text" v-model="userProfile.location" />
            </div>
          </div>

          <div class="form-group full-width">
            <label>Bio</label>
            <textarea v-model="userProfile.bio" rows="4" placeholder="Tell us about yourself..."></textarea>
          </div>

          <div class="form-actions">
            <button class="btn-outline">Cancel</button>
            <button class="btn-primary">Save Changes</button>
          </div>
        </div>

        <!-- Account Settings -->
        <div v-if="activeSection === 'account'" class="settings-section">
          <div class="section-header">
            <h2>Account Settings</h2>
            <p>Manage your account security and preferences</p>
          </div>

          <div class="settings-card">
            <div class="card-header">
              <i class="material-icons">lock</i>
              <div>
                <h3>Change Password</h3>
                <p>Update your password regularly to keep your account secure</p>
              </div>
            </div>
            <div class="card-content">
              <div class="form-group">
                <label>Current Password</label>
                <input type="password" placeholder="Enter current password" />
              </div>
              <div class="form-group">
                <label>New Password</label>
                <input type="password" placeholder="Enter new password" />
              </div>
              <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" placeholder="Confirm new password" />
              </div>
              <button class="btn-primary">Update Password</button>
            </div>
          </div>

          <div class="settings-card">
            <div class="card-header">
              <i class="material-icons">verified_user</i>
              <div>
                <h3>Two-Factor Authentication</h3>
                <p>Add an extra layer of security to your account</p>
              </div>
            </div>
            <div class="card-content">
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>Enable 2FA</h4>
                  <p>Secure your account with two-factor authentication</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.twoFactorAuth" />
                  <span class="slider"></span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Notification Settings -->
        <div v-if="activeSection === 'notifications'" class="settings-section">
          <div class="section-header">
            <h2>Notification Preferences</h2>
            <p>Choose what notifications you want to receive</p>
          </div>

          <div class="settings-card">
            <div class="card-header">
              <i class="material-icons">notifications</i>
              <div>
                <h3>Push Notifications</h3>
                <p>Manage how you receive notifications</p>
              </div>
            </div>
            <div class="card-content">
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>New Messages</h4>
                  <p>Get notified when you receive new messages</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.notifyMessages" />
                  <span class="slider"></span>
                </label>
              </div>
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>Booking Updates</h4>
                  <p>Receive updates about your bookings</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.notifyBookings" />
                  <span class="slider"></span>
                </label>
              </div>
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>Special Offers</h4>
                  <p>Get notified about deals and promotions</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.notifyOffers" />
                  <span class="slider"></span>
                </label>
              </div>
            </div>
          </div>

          <div class="settings-card">
            <div class="card-header">
              <i class="material-icons">email</i>
              <div>
                <h3>Email Notifications</h3>
                <p>Control your email preferences</p>
              </div>
            </div>
            <div class="card-content">
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>Weekly Newsletter</h4>
                  <p>Receive travel tips and destination highlights</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.emailNewsletter" />
                  <span class="slider"></span>
                </label>
              </div>
              <div class="toggle-option">
                <div class="toggle-info">
                  <h4>Marketing Emails</h4>
                  <p>Get promotional emails and special deals</p>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" v-model="settings.emailMarketing" />
                  <span class="slider"></span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Privacy Settings -->
        <div v-if="activeSection === 'privacy'" class="settings-section">
          <div class="section-header">
            <h2>Privacy & Data</h2>
            <p>Control your privacy and data preferences</p>
          </div>

          <div class="settings-card">
            <div class="card-header">
              <i class="material-icons">visibility</i>
              <div>
                <h3>Profile Visibility</h3>
                <p>Choose who can see your profile</p>
              </div>
            </div>
            <div class="card-content">
              <div class="radio-group">
                <label class="radio-option">
                  <input type="radio" name="visibility" value="public" v-model="settings.profileVisibility" />
                  <div class="radio-content">
                    <h4>Public</h4>
                    <p>Anyone can see your profile</p>
                  </div>
                </label>
                <label class="radio-option">
                  <input type="radio" name="visibility" value="friends" v-model="settings.profileVisibility" />
                  <div class="radio-content">
                    <h4>Friends Only</h4>
                    <p>Only your connections can see your profile</p>
                  </div>
                </label>
                <label class="radio-option">
                  <input type="radio" name="visibility" value="private" v-model="settings.profileVisibility" />
                  <div class="radio-content">
                    <h4>Private</h4>
                    <p>Your profile is hidden from everyone</p>
                  </div>
                </label>
              </div>
            </div>
          </div>

          <div class="settings-card danger-zone">
            <div class="card-header">
              <i class="material-icons">warning</i>
              <div>
                <h3>Danger Zone</h3>
                <p>Irreversible actions</p>
              </div>
            </div>
            <div class="card-content">
              <div class="danger-action">
                <div>
                  <h4>Download Your Data</h4>
                  <p>Get a copy of all your data</p>
                </div>
                <button class="btn-outline">Download</button>
              </div>
              <div class="danger-action">
                <div>
                  <h4>Delete Account</h4>
                  <p>Permanently delete your account and all data</p>
                </div>
                <button class="btn-danger">Delete Account</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';

const activeSection = ref('profile');

const menuItems = [
  { id: 'profile', label: 'Profile', icon: 'person' },
  { id: 'account', label: 'Account', icon: 'settings' },
  { id: 'notifications', label: 'Notifications', icon: 'notifications' },
  { id: 'privacy', label: 'Privacy', icon: 'shield' },
];

const userProfile = reactive({
  name: 'John Doe',
  email: 'john.doe@example.com',
  phone: '+63 912 345 6789',
  location: 'Surigao City, Philippines',
  bio: 'Travel enthusiast exploring the beautiful islands of Surigao!',
  avatar: 'https://i.pravatar.cc/150?img=12',
});

const settings = reactive({
  twoFactorAuth: false,
  notifyMessages: true,
  notifyBookings: true,
  notifyOffers: false,
  emailNewsletter: true,
  emailMarketing: false,
  profileVisibility: 'public',
});
</script>

<style scoped>
.user-settings-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
  overflow-y: auto;
}

/* ===== HEADER ===== */
.settings-header {
  padding: 30px;
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.header-content h1 {
  color: #004C5E;
  font-size: 2rem;
  font-weight: 700;
  margin: 0 0 5px 0;
}

.subtitle {
  color: #666;
  font-size: 0.95rem;
  margin: 0;
}

/* ===== SETTINGS CONTAINER ===== */
.settings-container {
  display: flex;
  max-width: 1400px;
  margin: 0 auto;
  padding: 30px;
  gap: 30px;
}

/* ===== SIDEBAR ===== */
.settings-sidebar {
  width: 250px;
  flex-shrink: 0;
}

.sidebar-menu {
  background: white;
  border-radius: 12px;
  padding: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 30px;
}

.menu-item {
  width: 100%;
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border: none;
  background: transparent;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
  color: #666;
  font-size: 0.95rem;
  font-weight: 500;
  text-align: left;
}

.menu-item:hover {
  background: #f8f9fa;
}

.menu-item.active {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
}

.menu-item i {
  font-size: 22px;
}

/* ===== SETTINGS CONTENT ===== */
.settings-content {
  flex: 1;
  min-width: 0;
}

.settings-section {
  background: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.section-header {
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #f0f0f0;
}

.section-header h2 {
  color: #333;
  font-size: 1.5rem;
  margin: 0 0 8px 0;
}

.section-header p {
  color: #666;
  margin: 0;
  font-size: 0.9rem;
}

/* ===== PROFILE PHOTO ===== */
.profile-photo-section {
  display: flex;
  align-items: center;
  gap: 25px;
  margin-bottom: 30px;
  padding: 25px;
  background: #f8f9fa;
  border-radius: 12px;
}

.profile-photo {
  position: relative;
}

.profile-photo img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid white;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.change-photo-btn {
  position: absolute;
  bottom: 0;
  right: 0;
  width: 35px;
  height: 35px;
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  border: 3px solid white;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  transition: all 0.2s ease;
}

.change-photo-btn:hover {
  transform: scale(1.1);
}

.photo-info h3 {
  margin: 0 0 5px 0;
  color: #333;
}

.photo-info p {
  margin: 0 0 15px 0;
  color: #666;
  font-size: 0.9rem;
}

/* ===== FORMS ===== */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  color: #555;
  font-weight: 500;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
  padding: 12px 15px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: #fafafa;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #00b4db;
  background: white;
  box-shadow: 0 0 0 3px rgba(0, 180, 219, 0.1);
}

.form-group textarea {
  resize: vertical;
  font-family: inherit;
}

/* ===== BUTTONS ===== */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 2px solid #f0f0f0;
}

.btn-primary,
.btn-outline,
.btn-danger {
  padding: 12px 25px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.95rem;
}

.btn-primary {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
  color: white;
  border: none;
  box-shadow: 0 4px 15px rgba(0, 180, 219, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 180, 219, 0.4);
}

.btn-outline {
  background: white;
  border: 2px solid #e0e0e0;
  color: #666;
}

.btn-outline:hover {
  border-color: #00b4db;
  color: #00b4db;
}

.btn-danger {
  background: #E74C3C;
  color: white;
  border: none;
}

.btn-danger:hover {
  background: #C0392B;
}

/* ===== SETTINGS CARDS ===== */
.settings-card {
  margin-bottom: 25px;
  border: 1px solid #e8e8e8;
  border-radius: 12px;
  overflow: hidden;
}

.card-header {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 20px;
  background: #f8f9fa;
  border-bottom: 1px solid #e8e8e8;
}

.card-header i {
  font-size: 28px;
  color: #00b4db;
}

.card-header h3 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 1.1rem;
}

.card-header p {
  margin: 0;
  color: #666;
  font-size: 0.85rem;
}

.card-content {
  padding: 25px;
}

/* ===== TOGGLE SWITCH ===== */
.toggle-option {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  border-bottom: 1px solid #f0f0f0;
}

.toggle-option:last-child {
  border-bottom: none;
}

.toggle-info h4 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 0.95rem;
}

.toggle-info p {
  margin: 0;
  color: #666;
  font-size: 0.85rem;
}

.toggle-switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 26px;
}

.toggle-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 26px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

input:checked + .slider {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
}

input:checked + .slider:before {
  transform: translateX(24px);
}

/* ===== RADIO OPTIONS ===== */
.radio-group {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.radio-option {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 15px;
  border: 2px solid #e8e8e8;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.radio-option:hover {
  border-color: #00b4db;
  background: #f8fcfd;
}

.radio-option input {
  margin-top: 3px;
  cursor: pointer;
  accent-color: #00b4db;
}

.radio-content h4 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 0.95rem;
}

.radio-content p {
  margin: 0;
  color: #666;
  font-size: 0.85rem;
}

/* ===== DANGER ZONE ===== */
.danger-zone .card-header {
  background: #fff5f5;
  border-bottom-color: #ffebee;
}

.danger-zone .card-header i {
  color: #E74C3C;
}

.danger-action {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
  border-bottom: 1px solid #f0f0f0;
}

.danger-action:last-child {
  border-bottom: none;
}

.danger-action h4 {
  margin: 0 0 5px 0;
  color: #333;
  font-size: 0.95rem;
}

.danger-action p {
  margin: 0;
  color: #666;
  font-size: 0.85rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 968px) {
  .settings-container {
    flex-direction: column;
  }

  .settings-sidebar {
    width: 100%;
  }

  .sidebar-menu {
    display: flex;
    overflow-x: auto;
    position: static;
  }

  .menu-item {
    white-space: nowrap;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>