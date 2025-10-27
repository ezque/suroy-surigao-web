<template>
    <div class="admin-body">
        <Sidebar
            :userRole="userRole"
            @selectPage="selectActivePage"
        />
        <div class="admin-main-body">
            <Header />
            <Dashboard
                v-if="activePage === 'dashboard'"
                :totalUsers="totalUsers"
                :agencyTotal="agencyTotal"
            />
            <Agency
                v-else-if="activePage === 'agency'"
                @selectPage="selectActivePage"
                :agencyDetails="agencyDetails"
            />
            <AgencyAddForm
                v-else-if="activePage === 'agencyAddForm'"
            />
            <Spots
                v-else-if="activePage === 'spots'"
                @selectPage="selectActivePage"
                :allSpots="allSpots"
            />
            <AddSpots
                v-else-if="activePage === 'addSpots'"
                :editingSpot="editingSpot"
                @selectPage="selectActivePage"
            />

            <ManagePost
                v-else-if="activePage === 'managePost'"
                @selectPage="selectActivePage"
            />
            <AddPost
                v-else-if="activePage === 'addPost'"
                @selectPage="selectActivePage"
            />
            <ManageUser
                v-else-if="activePage === 'manageUser'"
                :allUsers="allUsers"
            />
             <ReviewsAndFeedbacks
                v-else-if="activePage === 'reviewsAndFeedbacks'"
                :allUsers="allUsers"
            />
        </div>
    </div>
</template>
<script setup>
    import { computed, ref } from "vue";
    import Sidebar from "../../Components/Sidebar.vue";
    import Header from "../../Components/Header.vue";
    import Dashboard from "./dashboard.vue";
    import AgencyAddForm from "./agencyAddForm.vue";
    import ManagePost from './managePost.vue';
    import AddPost from './addPost.vue';
    import AddSpots from "./addSpots.vue";
    import Agency from "./agency.vue";
    import ManageUser from "./manageUser.vue";
    import Spots from "./spots.vue";
    import ReviewsAndFeedbacks from "./reviewsAndFeedbacks.vue";


    const props = defineProps({
        userInformation: Object,
        agencyDetails: Array,
        allUsers: Array,
        allSpots: Array,
        totalUsers: Number,
        agencyTotal: Number,
    })

    const userRole = computed(() => props.userInformation.role);

    const activePage = ref('dashboard');
    const editingSpot = ref(null)

    const selectActivePage = (payload) => {
        if (typeof payload === "string") {
            activePage.value = payload
            editingSpot.value = null
        } else {
            activePage.value = payload.page
            editingSpot.value = payload.spot
        }
    }





</script>

<style scoped>
    .admin-body {
        width: 100%;
        height: 100vh;
        overflow: hidden;
        display: flex;
        flex-direction: row;
    }
    .admin-main-body {
        width: 85%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #6ABBE4, #EEF8F9);
    }
</style>
