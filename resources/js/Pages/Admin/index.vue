<template>
    <div class="admin-body">
        <Sidebar
            :userRole="userRole"
            @selectPage="selectActivePage"
        />
        <div class="admin-main-body">
            <Header/>
            <Dashboard
                v-if="activePage === 'dashboard'"
            />
            <Agency
                v-else-if="activePage === 'agency'"
                @selectPage="selectActivePage"
            />
            <AgencyAddForm
                v-else-if="activePage === 'agencyAddForm'"
            />
            <Spots
                v-else-if="activePage === 'spots'"
                @selectPage="selectActivePage"
            />
            <AddSpots
                v-else-if="activePage === 'addSpots'"
            />
            <ManagePost
                v-else-if="activePage === 'managePost'"
                @selectPage="selectActivePage"
            />
            <ManageUser
                v-else-if="activePage === 'manageUser'"
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
    import AddPost from "./addPost.vue";
    import AddSpots from "./addSpots.vue";
    import Agency from "./agency.vue";
    import ManagePost from "./managePost.vue";
    import ManageUser from "./manageUser.vue";
    import Spots from "./spots.vue";


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
