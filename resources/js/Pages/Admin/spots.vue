<template>
    <div class="admin-spots-body">
        <div class="page-label">
            <h2>Spots</h2>
            <button
                @click="$emit('selectPage', 'addSpots')"
            >
                Add Spots
            </button>
        </div>
    </div>
</template>
<script setup>
    const emit = defineEmits(["selectPage"])
</script>
<style scoped>
    .admin-spots-body {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background-color: maroon;
    }
    .page-label {
        width: 100%;
        height: 100px;
        border: 1px solid black;
    }
</style>
