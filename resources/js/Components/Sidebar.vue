<template>
    <div class="sidebar-container">
        <div class="side-label-container">
            <h2 v-if="userRole === '1'">admin</h2>
            <h2 v-else-if="userRole === '2'">agency</h2>
        </div>
        <div class="sidebar-button-container">
            <button
                @click="$emit('selectPage', 'dashboard')"
            >
                <span class="material-icons-outlined">home</span>
                Dashboard
            </button>
            <button
                @click="$emit('selectPage', 'agency')"
            >
                <span class="material-icons-outlined">groups</span>
                Agencies
            </button>
            <button
                @click="$emit('selectPage', 'spots')"
            >
                <span class="material-icons-outlined">groups</span>
                Spots
            </button>
        </div>
    </div>
</template>
<script setup>

    const props = defineProps({
        userRole: String
    })
    const emit = defineEmits(["selectPage"])

</script>
<style scoped>
    .sidebar-container {
        width: 15%;
        height: 100%;
        background-color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .side-label-container {
        width: 100%;
    }
    .side-label-container h2 {
        font-size: 1.5em;
        font-weight: 700;
        margin-top: 10px;
        margin-left: 10px;
        text-transform: uppercase;
    }
    .sidebar-button-container {
        width: 85%;
        height: 100%;
        display: flex;
        flex-direction: column;
        padding-left: 10px;
        padding-right: 10px;
    }
    .sidebar-button-container button {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        background-color: gray;
        cursor: pointer;
        text-transform: capitalize;

    }
    .sidebar-button-container button span {
        color: #464255;
        margin-right: 20px;
        margin-left: 10px;
    }

</style>
