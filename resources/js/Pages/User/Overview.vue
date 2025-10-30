<template>
    <div>
        <h3 class="text-[#1a3c5a] mb-[20px] text-[1.5rem] capitalize">About This Place</h3>
        <p class="text-[1.1rem] text-[#37474f] mb-[25px] leading-[1.7]">{{ spot.description || "No Description" }}</p>
    </div>
</template>
<script setup>
    const props = defineProps({
        spot: Object,
    });
</script>
