<template>
  <div class="admin-dashboard-body">
    <!-- Dashboard Content -->
    <main class="dashboard-content">
      <div class="card">
        <img src="/public/images/users.png" alt="Users" class="card-icon" />
        <h3>Total Users</h3>
        <p>1,245</p>
      </div>

      <div class="card">
        <img src="/public/images/agency.png" alt="Agencies" class="card-icon" />
        <h3>Total Agencies</h3>
        <p>48</p>
      </div>

      <div class="card">
        <img src="/public/images/manage-spots.png" alt="Spots" class="card-icon" />
        <h3>Total Spots</h3>
        <p>136</p>
      </div>

      
    </main>
  </div>
</template>

<script setup>
</script>

<style scoped>
/* Content Grid */
.dashboard-content {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 30px;
  padding: 40px;
  align-items: start;
}

/* Cards */
.card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  padding: 50px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  margin-top: 45%;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.card-icon {
  width: 50px;
  height: 50px;
  margin-bottom: 15px;
}

.card h3 {
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 10px;
}

.card p {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2575fc;
}
</style>
