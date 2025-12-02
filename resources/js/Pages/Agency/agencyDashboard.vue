<template>
    <div class="w-full min-h-screen overflow-y-auto bg-slate-50 p-6 md:p-10">

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

        <main class="max-w-7xl mx-auto space-y-8">

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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800">Revenue Overview</h3>
                        <select class="bg-slate-50 border border-slate-200 text-sm rounded-lg px-3 py-1 text-slate-600 outline-none">
                            <option>This Year</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end justify-between h-64 gap-2 pt-4 pb-2">
                        <div v-for="(data, index) in monthlyData" :key="index" class="flex flex-col items-center gap-2 group w-full">
                            <div class="relative w-full flex justify-center items-end h-full">
                                <div class="absolute bottom-full mb-2 opacity-0 group-hover:opacity-100 transition bg-slate-800 text-white text-xs rounded py-1 px-2 pointer-events-none whitespace-nowrap z-10">
                                    ₱{{ data.amount.toLocaleString() }}
                                </div>
                                <div 
                                    class="w-full max-w-[40px] bg-blue-500 rounded-t-md hover:bg-blue-600 transition-all duration-300"
                                    :style="{ height: `${(data.amount / maxMonthlyRevenue) * 100}%` }"
                                ></div>
                            </div>
                            <span class="text-xs text-slate-500 font-medium">{{ data.month }}</span>
                        </div>
                    </div>
                </div>

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

                         <button class="w-full mt-4 py-2 text-sm text-blue-600 font-semibold hover:bg-blue-50 rounded-lg transition">
                            View All Packages →
                        </button>
                    </div>
                </div>
            </div>

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

    const agencyName = ref("Suroy Tours");
    const currentMonth = new Date().toLocaleString('default', { month: 'long', year: 'numeric' });

    // 1. STATS DATA
    const stats = ref({
        totalPackages: 0,
        totalRevenue: 0,
        totalReservation: 0,
    });

    // 2. CHART DATA (Dummy data for now - replace with API)
    const monthlyData = ref([
        { month: 'Jan', amount: 15000 },
        { month: 'Feb', amount: 25000 },
        { month: 'Mar', amount: 18000 },
        { month: 'Apr', amount: 32000 },
        { month: 'May', amount: 45000 }, // Peak
        { month: 'Jun', amount: 38000 },
        { month: 'Jul', amount: 20000 },
    ]);
    
    // Calculate max value for bar height scaling
    const maxMonthlyRevenue = computed(() => Math.max(...monthlyData.value.map(d => d.amount)) * 1.1);

    // 3. TOP PACKAGES DATA
    const topPackages = ref([
        { id: 1, name: 'Siargao Island Hopping', sales: 124, percentage: 90 },
        { id: 2, name: 'Sohoton Cove Tour', sales: 85, percentage: 65 },
        { id: 3, name: 'Magpupungko Pools', sales: 45, percentage: 35 },
    ]);

    // 4. RECENT ACTIVITY DATA
    const recentReservations = ref([
        { id: 1, customer: 'John Doe', package: 'Siargao Island Hopping', date: 'Dec 02, 2025', amount: 3500, status: 'Pending' },
        { id: 2, customer: 'Maria Clara', package: 'Sohoton Cove', date: 'Dec 01, 2025', amount: 5000, status: 'Confirmed' },
        { id: 3, customer: 'Cris Ibarra', package: 'Cloud 9 Surfing', date: 'Nov 29, 2025', amount: 1200, status: 'Completed' },
    ]);

    // --- API CALLS ---
    onMounted(() => {
        fetchDashboardData();
        fetchRevenue();
        fetchTotalReservation();
        // You would also fetch chart data here in real life
    });

    const fetchDashboardData = async () => {
        try {
            const response = await axios.get("/totalPackage");
            stats.value.totalPackages = response.data.total_packages;
        } catch (error) { console.error(error); }
    };

    const fetchRevenue = async () => {
        try {
            const response = await axios.get("/totalRevenue");
            stats.value.totalRevenue = response.data.total_revenue || 0;
        } catch (error) { console.error(error); }
    };

    const fetchTotalReservation = async () => {
        try {
            const response = await axios.get("/totalReservation");
            stats.value.totalReservation = response.data.total_reservations;
        } catch (error) { console.error(error); }
    };

    // --- REPORT ACTIONS ---
    const downloadReport = (type) => {
        alert(`Generating ${type} report... (This would trigger a PDF/CSV download)`);
        // Logic to hit an endpoint like /api/export-report would go here
    };
</script>