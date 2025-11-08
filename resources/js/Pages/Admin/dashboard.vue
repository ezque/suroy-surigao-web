<template>
    <div class="admin-dashboard-body">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1>Dashboard Overview</h1>
            <p class="subtitle">Welcome back! Here's what's happening with your platform today.</p>
        </div>

        <!-- Dashboard Content -->
        <main class="dashboard-content">
            <div class="card card-users">
                <div class="card-icon-wrapper">
                    <img src="/public/images/users.png" alt="Users" class="card-icon" />
                </div>
                <div class="card-content">
                    <h3>Total Users</h3>
                    <p class="stat-number">{{ totalUsers }}</p>
                </div>
            </div>

        <div class="card card-agencies">
            <div class="card-icon-wrapper">
                <img src="/public/images/agency.png" alt="Agencies" class="card-icon" />
            </div>
            <div class="card-content">
                <h3>Total Agencies</h3>
                <p class="stat-number">{{ agencyTotal }}</p>
            </div>
        </div>

        <div class="card card-spots">
        <div class="card-icon-wrapper">
        <img src="/public/images/manage-spots.png" alt="Spots" class="card-icon" />
        </div>
        <div class="card-content">
        <h3>Total Spots</h3>
        <p class="stat-number">136</p>
        </div>
        </div>
        </main>

        <!-- Additional Stats Section -->
        <section class="quick-stats">
        <div class="stat-item">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <i class="material-icons">visibility</i>
        </div>
        <div class="stat-details">
        <p class="stat-label">Total Views</p>
        <p class="stat-value">24,589</p>
        </div>
        </div>

        <div class="stat-item">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <i class="material-icons">star</i>
        </div>
        <div class="stat-details">
        <p class="stat-label">Reviews</p>
        <p class="stat-value">3,421</p>
        </div>
        </div>

      

        <div class="stat-item">
        <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <i class="material-icons">verified</i>
        </div>
        <div class="stat-details">
        <p class="stat-label">Verified</p>
        <p class="stat-value">42</p>
        </div>
        </div>
        </section>
    </div>
</template>

<script setup>
    const props = defineProps({
        totalUsers: Number,
        agencyTotal: Number,
    })
</script>

<style scoped>
.admin-dashboard-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #E8F4F8 0%, #FFFFFF 100%);
  padding: 30px;
  overflow-y: auto;
}

/* Dashboard Header */
.dashboard-header {
  margin-bottom: 40px;
  padding: 20px 40px;
  background: linear-gradient(135deg, #007A8C 0%, #009FB7 100%);
  border-radius: 20px;
  color: white;
  box-shadow: 0 8px 25px rgba(0, 122, 140, 0.3);
}

.dashboard-header h1 {
  font-size: 2.2rem;
  font-weight: 700;
  margin: 0 0 10px 0;
}

.subtitle {
  font-size: 1rem;
  opacity: 0.95;
  margin: 0;
  font-weight: 300;
}

/* Content Grid */
.dashboard-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 30px;
  padding: 0 40px;
  margin-bottom: 40px;
}

/* Cards */
.card {
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  padding: 30px;
  text-align: center;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  position: relative;
  overflow: hidden;
  border: 2px solid transparent;
}

.card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 4px;
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.card:hover::before {
  transform: scaleX(1);
}

.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

/* Card Variants */
.card-users::before {
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.card-agencies::before {
  background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
}

.card-spots::before {
  background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
}

.card-icon-wrapper {
  width: 80px;
  height: 80px;
  margin: 0 auto 20px;
  background: linear-gradient(135deg, #E8F4F8 0%, #d4ebf2 100%);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.card:hover .card-icon-wrapper {
  transform: scale(1.1) rotate(5deg);
  background: linear-gradient(135deg, #007A8C 0%, #009FB7 100%);
}

.card-icon {
  width: 45px;
  height: 45px;
  transition: filter 0.3s ease;
}

.card:hover .card-icon {
  filter: brightness(0) invert(1);
}

.card-content h3 {
  font-size: 1.1rem;
  color: #555;
  margin: 0 0 15px 0;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 800;
  background: linear-gradient(135deg, #007A8C 0%, #009FB7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 10px 0;
}

.growth {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 0.85rem;
  padding: 5px 12px;
  border-radius: 20px;
  font-weight: 500;
  margin-top: 10px;
}

.growth.positive {
  background: #e8f5e9;
  color: #2e7d32;
}

.growth.negative {
  background: #ffebee;
  color: #c62828;
}

.growth i {
  font-size: 16px;
}

/* Quick Stats Section */
.quick-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
  padding: 0 40px;
}

.stat-item {
  background: white;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
  transition: all 0.3s ease;
}

.stat-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon i {
  color: white;
  font-size: 28px;
}

.stat-details {
  flex: 1;
}

.stat-label {
  font-size: 0.85rem;
  color: #888;
  margin: 0 0 5px 0;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #333;
  margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
  .dashboard-header {
    padding: 15px 20px;
  }

  .dashboard-header h1 {
    font-size: 1.6rem;
  }

  .dashboard-content,
  .quick-stats {
    padding: 0 20px;
    grid-template-columns: 1fr;
  }

  .card {
    padding: 25px;
  }

  .stat-number {
    font-size: 2rem;
  }
}
</style>
