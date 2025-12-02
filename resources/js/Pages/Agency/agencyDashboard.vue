<template>
    <div class="w-full min-h-screen overflow-y-auto bg-slate-50 p-6 md:p-10">

        <!-- HEADER -->
        <header class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">
                    Welcome back, {{ agencyName }}!
                </h1>
                <p class="text-slate-500 mt-1 text-sm md:text-base">
                    Here is your performance report for {{ currentMonth }}.
                </p>
            </div>
            <div class="flex gap-3">
                <button @click="downloadReport('sales')" class="flex items-center gap-2 bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition shadow-sm">
                    <span class="material-icons text-lg">description</span> Monthly Report
                </button>
                <button @click="downloadReport('guests')" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-md">
                    <span class="material-icons text-lg">download</span> Export Data
                </button>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main class="max-w-7xl mx-auto space-y-8">

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                        <span class="material-icons text-3xl">inventory_2</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Total Packages</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">{{ stats.totalPackages }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center">
                        <span class="material-icons text-3xl">payments</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Total Revenue</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">₱{{ stats.totalRevenue.toLocaleString() }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center">
                        <span class="material-icons text-3xl">event_available</span>
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium">Total Reservations</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">{{ stats.totalReservation }}</h3>
                    </div>
                </div>
            </div>

            <!-- REVENUE & TOP PACKAGES -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Revenue Chart -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Revenue Overview</h3>
                        <select class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-1 text-slate-600 outline-none">
                            <option>This Year</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas ref="revenueChart" class="w-full h-64"></canvas>
                </div>

                <!-- Top Packages -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6">Top Selling Packages</h3>
                    <div class="space-y-6">
                        <div v-for="pkg in topPackages" :key="pkg.id">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-slate-700">{{ pkg.name }}</span>
                                <span class="text-xs font-bold text-slate-500">{{ pkg.sales }} sold</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5">
                                <div
                                    class="bg-gradient-to-r from-blue-500 to-cyan-400 h-2.5 rounded-full transition-all duration-1000"
                                    :style="{ width: `${pkg.percentage}%` }"
                                ></div>
                            </div>
                        </div>
                        <div v-if="topPackages.length === 0" class="text-center py-4 text-slate-400 text-sm">
                            No sales data yet.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reservations -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-slate-800">Recent Reservations</h3>
                    <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">View All</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold">Customer</th>
                            <th class="p-4 font-semibold">Package</th>
                            <th class="p-4 font-semibold">Date</th>
                            <th class="p-4 font-semibold">Amount</th>
                            <th class="p-4 font-semibold">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                        <tr v-for="res in recentReservations" :key="res.id" class="hover:bg-slate-50 transition">
                            <td class="p-4 font-medium text-slate-900">{{ res.customer }}</td>
                            <td class="p-4 text-slate-600">{{ res.package }}</td>
                            <td class="p-4 text-slate-500">{{ res.date }}</td>
                            <td class="p-4 font-medium text-emerald-600">₱{{ res.amount.toLocaleString() }}</td>
                            <td class="p-4">
                                  <span
                                      class="px-2.5 py-1 rounded-full text-xs font-bold"
                                      :class="{
                                      'bg-orange-100 text-orange-700': res.status === 'Pending',
                                      'bg-green-100 text-green-700': res.status === 'Confirmed',
                                      'bg-red-100 text-red-700': res.status === 'Cancelled'
                                    }"
                                  >
                                    {{ res.status }}
                                  </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</template>

<script setup>
    import { ref, onMounted, computed } from "vue";
    import axios from "axios";
    import { Chart, registerables } from "chart.js";

    Chart.register(...registerables);

    // GENERAL
    const agencyName = ref("Suroy Tours");
    const currentMonth = new Date().toLocaleString("default", { month: "long", year: "numeric" });

    // STATS
    const stats = ref({
        totalPackages: 0,
        totalRevenue: 0,
        totalReservation: 0,
    });

    // CHART
    const revenueChart = ref(null);
    const chartInstance = ref(null);
    const monthlyData = ref([]);
    const renderChart = () => {
        if (chartInstance.value) chartInstance.value.destroy();
        chartInstance.value = new Chart(revenueChart.value, {
            type: "line",
            data: {
                labels: monthlyData.value.map((d) => d.month),
                datasets: [
                    {
                        label: "Revenue (₱)",
                        data: monthlyData.value.map((d) => d.amount),
                        fill: false,
                        borderColor: "#3b82f6",
                        backgroundColor: "#3b82f6",
                        tension: 0.3,
                        pointRadius: 5,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `₱${context.formattedValue}`;
                            },
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (value) => `₱${value}` },
                    },
                },
            },
        });
    };

    // TOP PACKAGES
    const topPackages = ref([]);

    // RECENT RESERVATIONS
    const recentReservations = ref([]);


    const fetchRecentReservation = async () => {
        try {
            const response = await axios.get("/recentReservations"); // backend endpoint
            // response should be an array of top 3 recent reservations
            recentReservations.value = response.data;
        } catch (error) {
            console.error(error);
        }
    };
    // FUNCTIONS
    const fetchDashboardData = async () => {
        try {
            const response = await axios.get("/totalPackage");
            stats.value.totalPackages = response.data.total_packages || 0;
        } catch (error) {
            console.error(error);
        }
    };

    const fetchRevenue = async () => {
        try {
            const response = await axios.get("/totalRevenue");
            stats.value.totalRevenue = response.data.total_revenue || 0;
            monthlyData.value = response.data.monthly_revenue || [];
            renderChart(); // call after data is set
        } catch (error) {
            console.error(error);
        }
    };

    const fetchTopSellingPackage = async () => {
        try {
            const response = await axios.get("/topSellingPackages");
            // Assuming your backend returns an array like [{ id, name, sales }]
            const packages = response.data;

            // Calculate percentage for the progress bar
            const maxSales = Math.max(...packages.map(p => p.sales), 1); // avoid divide by zero
            topPackages.value = packages.map(p => ({
                ...p,
                percentage: Math.round((p.sales / maxSales) * 100),
            }));
        } catch (error) {
            console.error(error);
        }
    };


    const fetchTotalReservation = async () => {
        try {
            const response = await axios.get("/totalReservation");
            stats.value.totalReservation = response.data.total_reservations || 0;
        } catch (error) {
            console.error(error);
        }
    };

    const downloadReport = (type) => {
        alert(`Generating ${type} report...`);
    };

    onMounted(() => {
        fetchDashboardData();
        fetchRevenue();
        fetchTotalReservation();
        renderChart();
        fetchTopSellingPackage();
        fetchRecentReservation();
    });
</script>
