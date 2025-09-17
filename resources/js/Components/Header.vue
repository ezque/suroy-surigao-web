<template>
  <header class="header-container">
    <!-- Logo -->
    <div class="logo">
      <i class="fas fa-rocket"></i>
      <span>Suroy Surigao</span>
    </div>

    <!-- Search -->
    <div class="search-container">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search..." v-model="searchQuery" />
      </div>
    </div>

      <!-- Navigation Items -->
 <div class="nav-items">
    <!-- Messages -->
    <div class="icon-button">
      <img src="/public/images/message.png" alt="Messages" class="icon-img" />
      <div class="notification-badge">{{ messages }}</div>
    </div>

    <!-- Notifications -->
    <div class="icon-button">
      <img src="/public/images/bell.png" alt="Notifications" class="icon-img" />
      <div class="notification-badge">{{ notifications }}</div>
    </div>
  </div>



      <!-- Profile -->
      <div class="profile-button">
        <div class="profile-img">{{ initials }}</div>
        <span>{{ userName }}</span>
      </div>
  </header>
</template>

<script setup>
    import { ref, computed } from "vue";

    const searchQuery = ref("");
    const messages = ref(3);
    const notifications = ref(7);

    const userName = ref("John Smith");

    // get initials (for avatar)
    const initials = computed(() =>
      userName.value
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
    );
</script>

<style scoped>
    * {
      box-sizing: border-box;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-container {
      width: 100%;
      height: 70px;
      background: linear-gradient(135deg, #075574 0%, #2575fc 100%);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 25px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      position: relative;
    }

    .logo {
      display: flex;
      align-items: center;
      color: white;
      font-weight: 700;
      font-size: 1.5rem;
    }

    .logo i {
      margin-right: 10px;
      font-size: 1.8rem;
    }

    .nav-items {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    .icon-button {
      position: relative;
      background: rgba(255, 255, 255, 0.15);
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .icon-button:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-2px);
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #ff4757;
      color: white;
      font-size: 0.7rem;
      font-weight: 600;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid #6a11cb;
    }

    .profile-button {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.15);
      padding: 8px 15px;
      border-radius: 25px;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .profile-button:hover {
      background: rgba(255, 255, 255, 0.25);
    }

    .profile-img {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      margin-right: 10px;
      background: linear-gradient(45deg, #ff9a9e, #fad0c4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }

    .search-container {
      flex: 1;
      max-width: 400px;
      margin: 0 20px;
    }

    .search-box {
      position: relative;
      width: 100%;
    }

    .search-box input {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border-radius: 25px;
      border: none;
      background: rgba(255, 255, 255, 0.2);
      color: white;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .search-box input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
    }

    .search-box input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.7);
    }

    .icon-img {
      width: 22px;
      height: 22px;
      object-fit: contain;
    }

    @media (max-width: 768px) {
      .search-container {
        display: none;
      }

      .header-container {
        padding: 0 15px;
      }

      .profile-button span {
        display: none;
      }

      .profile-button {
        padding: 8px;
      }
    }
</style>
