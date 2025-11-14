<template>
    <div class="w-full min-h-screen overflow-y-auto bg-gray-50 p-6 md:p-10">

        <!-- HEADER -->
        <header class="mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900">
                Welcome back, {{ agencyName }}!
            </h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">
                Here's your analytics overview.
            </p>
        </header>

        <!-- DASHBOARD -->
        <main class="max-w-7xl mx-auto">

            <!-- STATS GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">

                <!-- TOTAL PACKAGES -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center gap-5">

                        <!-- Icon -->
                        <div class="w-16 h-16 bg-blue-500 text-white rounded-2xl flex items-center justify-center shadow-md">
                            <span class="material-icons text-4xl">inventory_2</span>
                        </div>

                        <!-- Text -->
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Packages</p>
                            <h3 class="text-4xl font-extrabold text-slate-900">
                                {{ stats.totalPackages }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- TOTAL REVENUE -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center gap-5">

                        <div class="w-16 h-16 bg-green-500 text-white rounded-2xl flex items-center justify-center shadow-md">
                            <span class="material-icons text-4xl">payments</span>
                        </div>

                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                            <h3 class="text-4xl font-extrabold text-slate-900">
                                ₱{{ stats.totalRevenue }}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- TOTAL RESERVATIONS -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="flex items-center gap-5">

                        <div class="w-16 h-16 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-md">
                            <span class="material-icons text-4xl">event_available</span>
                        </div>

                        <div>
                            <p class="text-gray-500 text-sm font-medium">Total Reservations</p>
                            <h3 class="text-4xl font-extrabold text-slate-900">
                                {{ stats.totalReservation }}
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</template>

<script setup>
    import { ref, onMounted } from "vue";
    import axios from "axios";

    const agencyName = ref("Suroy Tours");

    const stats = ref({
        totalPackages: 0,
        totalRevenue: 0,
        totalReservation: 0,
    });

    onMounted(() => {
        fetchDashboardData();
        fetchRevenue();
        fetchTotalReservation();
    });

    // Fetch package count
    const fetchDashboardData = async () => {
        try {
            const response = await axios.get("/totalPackage");
            stats.value.totalPackages = response.data.total_packages;
        } catch (error) {
            console.error("Error fetching total packages:", error);
        }
    };

    // Fetch revenue
    const fetchRevenue = async () => {
        try {
            const response = await axios.get("/totalRevenue");
            stats.value.totalRevenue = response.data.total_revenue;
            console.log(stats.value.totalRevenue);
        } catch (error) {
            console.error("Error fetching revenue:", error);
        }
    };

    // Fetch reservation count
    const fetchTotalReservation = async () => {
        try {
            const response = await axios.get("/totalReservation");
            stats.value.totalReservation = response.data.total_reservations;
        } catch (error) {
            console.error("Error fetching reservations:", error);
        }
    };
</script>
