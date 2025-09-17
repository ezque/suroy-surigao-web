<template>
    <div class="agency-body-container">
        <Sidebar
            :userRole="userRole"
            @selectPage="selectActivePage"
        />
        <div class="agency-main-body">

        </div>
    </div>
</template>
<script setup>
    import Sidebar from "../../Components/Sidebar.vue";
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
