<template>
  <div class="agencyDashboard-body">
    <main class="dashboard-content">
      <div class="card">
        <img src="/public/images/agency.png" alt="Bookings" class="card-icon" />
        <h3>Total Bookings</h3>
        <p>789</p>
      </div>
    </main>
  </div>
</template>

<script setup>
</script>

<style scoped>
    .agencyDashboard-body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* full viewport height */
    width: 100%;
    overflow: hidden;
    }

    /* Content Grid */
    .dashboard-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 30px;
    padding: 40px;
    align-items: start;
    width: 100%;
    max-width: 400px; /* restrict width so it doesn't stretch too much */
    }

    /* Cards */
    .card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    padding: 50px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    /* removed margin-top */
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
