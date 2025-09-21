<template>
    <div class="agency-body-container">
        <Sidebar
            :userRole="userRole"
            @selectPage="selectActivePage"
        />
        <div class="agency-main-body">
            <AgencyDashboard
                v-if="activePage === 'agencyDashboard'"
            />
            <AddPackage
                v-if="activePage === 'agencyAddPackage'"
            />
            <AgencySettings
                v-if="activePage === 'agencySettings'"
            />
            <ManageBooking
                v-if="activePage === 'ManageBookings'"
            />
            <Packages
                v-if="activePage === 'ManageTours'"
            />
            <ReviewsAndFeedbacks
                v-if="activePage === 'agencyFeedbacks'"
            />
        </div>
    </div>
</template>
<script setup>
    import Sidebar from "../../Components/Sidebar.vue";
    import AddPackage from "./addPackages.vue";
    import AgencyDashboard from "./agencyDashboard.vue";
    import AgencySettings from "./agencySettings.vue";
    import ManageBooking from "./manageBookings.vue";
    import Packages from "./Packages.vue";
    import ReviewsAndFeedbacks from "./agencyFeedbacks.vue";

    import {computed, ref} from "vue";

    const props = defineProps({
        userInformation: Object,
    })

    const userRole = computed(() => props.userInformation.role);
    const activePage = ref('dashboard');
    const selectActivePage = (pageName) => {
        activePage.value = pageName;
    }
</script>
<style scoped>
    .agency-body-container {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }
    .agency-main-body {
        width: 85%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #6ABBE4, #EEF8F9);
    }
</style>
