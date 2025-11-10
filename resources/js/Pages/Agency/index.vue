<template>
    <div class="agency-body-container">
        <Sidebar
            :userRole="userRole"
            :agencyDetails="agencyDetails"
            @selectPage="selectActivePage"
            :currentPage="activePage"
        />
        <div class="agency-main-body">
            <Header
                :userInformation="userInformation"
            />
            <AgencyDashboard
                v-if="activePage === 'agencyDashboard'"
            />
            <AgencySettings
                v-if="activePage === 'agencySettings'"
            />
            <ManageReservation
                v-if="activePage === 'manageReservation'"
                :allReservations="allReservations"
            />
            <Packages
                v-if="activePage === 'ManageTours'"
                @selectPage="selectActivePage"
                :allSpots="allSpots"
                :allPackages="allPackages"
            />
            <ReviewsAndFeedbacks
                v-if="activePage === 'agencyFeedbacks'"
            />
            <Reservation
                v-if="activePage === 'Reservation'"
                :allReservations="allReservations"
                :totalReservations="totalReservations"
            />
        </div>
    </div>
</template>
<script setup>
    import Sidebar from "../../Components/Sidebar.vue";
    import Header from "../../Components/Header.vue";
    import AgencyDashboard from "./agencyDashboard.vue";
    import AgencySettings from "./agencySettings.vue";
    import Packages from "./Packages.vue";
    import ReviewsAndFeedbacks from "./agencyFeedbacks.vue";
    import {computed, onMounted, ref} from "vue";
    import ManageReservation from "./manageReservation.vue";
    import Reservation from "./Reservation.vue";

    const props = defineProps({
        userInformation: Object,
        agencyDetails: Object,
        allSpots: Array,
        allPackages: Array,
        allReservations: Array,
        totalReservations: Number,
    })

    onMounted(() => {
        console.log(props.allPackages);
    });

    const userRole = computed(() => props.userInformation.role);
    const activePage = ref('agencyDashboard');
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
        background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
    }
</style>
