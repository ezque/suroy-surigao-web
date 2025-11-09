<template>
  <div class="agencyDashboard-body">
     <header class="dashboard-header">
        <div class="header-text">
          <h1>Welcome back, {{ agencyName }}!</h1>
          <p>Here's your business summary for {{ currentDate }}</p>
        </div>
     </header>

      <main class="dashboard-content">
      <div class="stats-grid">
      <div class="stat-card">
      <div class="card-icon icon-packages">
      <i class="material-icons">inventory</i>
      </div>
      <div class="card-info">
      <p class="card-label">Total Packages</p>
      <h3 class="card-value">{{ stats.totalPackages }}</h3>
      </div>
      </div>
      </div>
      </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// --- Reactive Data ---
const agencyName = ref('Suroy Tours');
const stats = ref({
averageRating: 0,
totalPackages: 0,
});
const recentActivities = ref([]);

const currentDate = new Date().toLocaleDateString('en-US', {
year: 'numeric',
month: 'long',
day: 'numeric'
});

// --- Lifecycle Hooks ---
onMounted(() => {
// In a real application, you would fetch this data from your API
fetchDashboardData();
});

const fetchDashboardData = () => {
// Simulating an API call
setTimeout(() => {
stats.value = {
averageRating: 4.8,
totalPackages: 12,
};
recentActivities.value = [
{ id: 1, customerName: 'John D.', packageName: 'Island Hopping', status: 'Confirmed' },
{ id: 2, customerName: 'Sarah M.', packageName: 'Mountain Trek', status: 'Pending' },
{ id: 3, customerName: 'Mike C.', packageName: 'River Cruise', status: 'Confirmed' },
{ id: 4, customerName: 'Emily R.', packageName: 'Island Hopping', status: 'Cancelled' },
];
}, 1000); // 1-second delay
};
</script>

<style scoped>
/* ===== GLOBAL & LAYOUT ===== */
.agencyDashboard-body {
width: 100%;
height: 100vh;
overflow-y: auto;
background-color: #f8fafc;
padding: 24px 32px;
}
.dashboard-content {
width: 100%;
max-width: 1400px;
margin: 0 auto;
}

/* ===== HEADER ===== */
.dashboard-header {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 24px;
}
.header-text h1 {
font-size: 1.75rem;
font-weight: 700;
color: #1e293b;
margin: 0;
}
.header-text p {
margin-top: 4px;
color: #64748b;
font-size: 1rem;
}

/* ===== STATS GRID ===== */
.stats-grid {
display: grid;
/* Adjusted to handle a single item gracefully */
grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
gap: 24px;
margin-bottom: 24px;
}
.stat-card {
background: #fff;
border-radius: 12px;
border: 1px solid #e2e8f0;
padding: 24px;
display: flex;
align-items: center;
gap: 16px;
transition: transform 0.2s, box-shadow 0.2s;
}
.stat-card:hover {
transform: translateY(-4px);
box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}
.card-icon {
width: 48px;
height: 48px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
flex-shrink: 0;
}
.card-icon .material-icons {
font-size: 24px;
}
.icon-packages { background-color: #e0f2fe; color: #0ea5e9; }
.icon-rating { background-color: #fef3c7; color: #f59e0b; }
.card-label {
color: #64748b;
margin: 0 0 4px 0;
font-size: 0.9rem;
}
.card-value {
font-size: 1.75rem;
font-weight: 700;
color: #1e293b;
margin: 0;
}

/* ===== MAIN CONTENT GRID ===== */
.dashboard-main-grid {
display: grid;
grid-template-columns: 1fr;
gap: 24px;
align-items: start;
}
.card {
background: #fff;
border-radius: 12px;
border: 1px solid #e2e8f0;
padding: 24px;
}
.card h4 {
font-size: 1.1rem;
font-weight: 600;
color: #1e293b;
margin: 0 0 16px 0;
padding-bottom: 16px;
border-bottom: 1px solid #e2e8f0;
}

/* ===== ACTIVITY LIST ===== */
.activity-list table {
width: 100%;
border-collapse: collapse;
}
.activity-list th, .activity-list td {
text-align: left;
padding: 12px 0;
border-bottom: 1px solid #f1f5f9;
}
.activity-list th {
font-size: 0.8rem;
color: #64748b;
font-weight: 600;
}
.activity-list td {
font-size: 0.9rem;
color: #334155;
}
.activity-list tbody tr:last-child td {
border-bottom: none;
}
.status-badge {
padding: 4px 10px;
border-radius: 999px;
font-size: 0.75rem;
font-weight: 600;
}
.status-badge.confirmed { background-color: #dcfce7; color: #166534; }
.status-badge.pending { background-color: #fef9c3; color: #854d0e; }
.status-badge.cancelled { background-color: #fee2e2; color: #991b1b; }

/* ===== RESPONSIVENESS ===== */
@media (max-width: 768px) {
.agencyDashboard-body {
padding: 16px;
}
.header-text h1 {
font-size: 1.5rem;
}
}
</style>
