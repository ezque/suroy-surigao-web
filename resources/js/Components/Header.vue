<template>
    <div class="w-full h-16 md:h-20 bg-white flex justify-between items-center px-4 md:px-8 shadow-sm border-b border-gray-200 relative z-50">
        <!-- Left Section: Logo -->
        <div class="flex items-center gap-3 md:gap-8">
            <div class="flex items-center gap-3 md:gap-3 font-bold text-lg md:text-xl text-blue-500">
                <img src="/public/images/logo/logo.png" alt="Logo" class="w-10 h-10 rounded-full object-cover" />
                <span class="bg-clip-text text-transparent font-extrabold bg-gradient-to-r from-blue-400 to-blue-700">Suroy Surigao</span>
            </div>
        </div>

        <!-- Right Section: Notifications -->
        <div class="relative flex items-center gap-4">
            <div @click="toggleNotifications" class="relative cursor-pointer notification-wrapper">
                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-blue-500 hover:text-white transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg border-2 border-transparent">
                    <i class="material-icons text-xl">notifications_none</i>
                    <div v-if="unreadCount > 0" class="absolute -top-1 -right-1 w-5 h-5 min-w-[20px] rounded-full bg-gradient-to-br from-red-400 to-red-600 text-white text-xs font-bold flex items-center justify-center border-2 border-white animate-pulse">
                        {{ unreadCount }}
                    </div>
                </div>

                <!-- Dropdown -->
                <div v-if="showNotifications" class="absolute right-0 top-14 md:top-16 w-80 md:w-96 max-h-[500px] bg-white rounded-xl shadow-xl z-50 overflow-hidden border border-gray-200 animate-fade-in notif-dropdown">
                    <!-- Dropdown Header -->
                    <div class="flex justify-between items-center px-5 py-4 bg-gradient-to-br from-gray-100 to-blue-100 border-b border-gray-200">
                        <h3 class="text-gray-800 font-semibold text-base md:text-lg">Notifications</h3>
                        <div class="flex items-center gap-2">
                            <button v-if="unreadCount > 0" @click.stop="markAllAsRead" class="flex items-center gap-1 bg-blue-100 text-blue-600 text-xs md:text-sm font-semibold px-2 py-1 rounded hover:bg-blue-200 transition">
                                <i class="material-icons text-sm">done_all</i>
                                Mark all read
                            </button>
                            <span class="bg-gradient-to-r from-blue-400 to-blue-700 text-white text-xs md:text-sm font-bold px-2 py-0.5 rounded">{{ unreadCount }} unread</span>
                        </div>
                    </div>

                    <!-- Dropdown Content -->
                    <div class="max-h-[350px] overflow-y-auto">
                        <div v-if="notifications.length === 0" class="flex flex-col items-center justify-center p-12 text-gray-400 text-center gap-2">
                            <i class="material-icons text-4xl opacity-40">notifications_off</i>
                            <p class="font-medium text-gray-600">No notifications yet</p>
                            <small>We'll notify you when something arrives</small>
                        </div>

                        <div v-else class="flex flex-col divide-y divide-gray-100">
                            <div v-for="(notif, index) in notifications" :key="notif.id"
                                 :class="['flex items-start justify-between p-4 gap-3 transition-all', notif.unread ? 'bg-blue-50 border-l-4 border-blue-500' : 'bg-white hover:bg-gray-50']">
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-800 font-medium text-sm md:text-base">{{ notif.message }}</p>
                                    <div class="flex flex-col md:flex-row gap-1 md:gap-3 mt-1 text-gray-500 text-xs">
                                        <span>{{ notif.time }}</span>
                                        <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-[0.65rem] font-semibold">{{ notif.category }}</span>
                                    </div>
                                </div>
                                <button v-if="notif.unread" @click.stop="markAsRead(index)" class="text-green-500 hover:bg-green-100 rounded p-1 transition-opacity opacity-0 hover:opacity-100">
                                    <i class="material-icons text-sm">check_circle</i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown Footer -->
                    <div class="p-4 bg-gray-100 border-t border-gray-200 text-center">
                        <button @click.stop="viewAllNotifications" class="w-full flex items-center justify-center gap-2 text-white bg-gradient-to-r from-blue-400 to-blue-700 font-semibold text-sm md:text-base px-4 py-2 rounded-lg hover:shadow-lg hover:-translate-y-0.5 transition-all">
                            <i class="material-icons text-sm md:text-base">list_alt</i>
                            View Notification History
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay -->
        <div v-if="showNotifications" @click="closeAllDropdowns" class="fixed inset-0 bg-transparent z-40"></div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import axios from "axios";

const props = defineProps({
    userInformation: { type: Object, required: true },
});

const emit = defineEmits(['selectPage', 'logout']);

const notifications = ref([]);
const showNotifications = ref(false);


const fetchNotifications = async () => {
    try {
        const response = await axios.get('/notifications', {
            headers: { "Content-Type": "multipart/form-data" },
        });

        notifications.value = response.data.map(n => ({
            id: n.id,
            message: n.message,
            unread: n.unread,
            time: n.time,
            category: n.type || "general"
        }));
    } catch (error) {
        console.error("Error fetching notifications:", error.response || error);
    }
};

onMounted(() => {
    fetchNotifications();
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

const handleClickOutside = (event) => {
    const notificationDropdown = document.querySelector(".notif-dropdown");
    const notificationButton = document.querySelector(".notification-wrapper");

    if (
        showNotifications.value &&
        notificationDropdown &&
        !notificationDropdown.contains(event.target) &&
        !notificationButton.contains(event.target)
    ) {
        showNotifications.value = false;
    }
};

const toggleNotifications = () => { showNotifications.value = !showNotifications.value; };
const closeAllDropdowns = () => { showNotifications.value = false; };
const unreadCount = computed(() => notifications.value.filter(n => n.unread).length);
const markAsRead = (index) => { notifications.value[index].unread = false; };
const markAllAsRead = () => { notifications.value.forEach(n => n.unread = false); };
const viewAllNotifications = () => { console.log("Redirect to full notifications page"); };
</script>

<style scoped>
@keyframes fade-in { from { opacity: 0; transform: translateY(-10px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
.animate-fade-in { animation: fade-in 0.2s ease-out; }
</style>
