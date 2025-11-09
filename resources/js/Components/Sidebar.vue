<template>
    <div class="flex h-screen w-72 flex-col bg-gradient-to-br from-teal-700 to-teal-900 shadow-2xl">
        <!-- Profile Section -->
        <div class="border-b border-white/10 bg-white/5 p-5 text-center backdrop-blur-sm">
            <div class="relative mx-auto mb-4 h-20 w-20">
                <img
                    src="/public/images/logo/logo.png"
                    alt="avatar"
                    class="h-full w-full rounded-full border-4 border-white/20 object-cover"
                />
            </div>

            <div>
                <h3 class="text-lg font-semibold text-white capitalize">
                    {{
                        userRole === 'admin'
                            ? 'Administration'
                            : userRole === 'agency'
                                ? (agencyDetails?.agency?.agency_name || userFullName)
                                : userFullName
                    }}
                </h3>


                <span
                    class="mt-3 inline-block rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider text-white"
                    :class="{
                        'bg-gradient-to-r from-red-500 to-red-600': userRole === 'admin',
                        'bg-gradient-to-r from-amber-500 to-amber-600': userRole === 'agency',
                        'bg-white/20': userRole === 'user'
                    }"
                >
                  {{ roleLabel }}
                </span>
            </div>
        </div>

        <!-- Navigation Section -->
        <nav class="flex-1 space-y-1 overflow-y-auto p-4 scrollbar-thin scrollbar-thumb-white/30 scrollbar-track-white/10">
            <!-- ADMIN (Role 1) -->
            <template v-if="userRole === 'admin'">
                <button
                    @click="$emit('selectPage', 'dashboard')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'dashboard' }"
                >
                    <span class="material-icons text-xl">dashboard</span>
                    <span class="flex-1">Dashboard</span>
                </button>

                <button
                    @click="$emit('selectPage', 'agency')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'agency' }"
                >
                    <span class="material-icons text-xl">business</span>
                    <span class="flex-1">Agencies</span>
                </button>

                <button
                    @click="$emit('selectPage', 'spots')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'spots' }"
                >
                    <span class="material-icons text-xl">map</span>
                    <span class="flex-1">Manage Spots</span>
                </button>

                <button
                    @click="$emit('selectPage', 'managePost')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'managePost' }"
                >
                    <span class="material-icons text-xl">article</span>
                    <span class="flex-1">Manage Blog</span>
                </button>

                <button
                    @click="$emit('selectPage', 'manageUser')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'manageUser' }"
                >
                    <span class="material-icons text-xl">group</span>
                    <span class="flex-1">Manage Users</span>
                </button>

                <button
                    @click="$emit('selectPage', 'adminSettings')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'adminSettings' }"
                >
                    <span class="material-icons text-xl">settings</span>
                    <span class="flex-1">Settings</span>
                </button>
            </template>

            <!-- AGENCY (Role 2) -->
            <template v-else-if="userRole === 'agency'">
                <button
                    @click="$emit('selectPage', 'agencyDashboard')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'agencyDashboard' }"
                >
                    <span class="material-icons text-xl">dashboard</span>
                    <span class="flex-1">Dashboard</span>
                </button>

                <button
                    @click="$emit('selectPage', 'ManageTours')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'ManageTours' }"
                >
                    <span class="material-icons text-xl">work</span>
                    <span class="flex-1">Manage Tours & Packages</span>
                </button>

                <button
                    @click="$emit('selectPage', 'Reservation')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'Reservation' }"
                >
                    <span class="material-icons text-xl">work</span>
                    <span class="flex-1">Reservation</span>
                </button>

                <button
                    @click="$emit('selectPage', 'agencySettings')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'agencySettings' }"
                >
                    <span class="material-icons text-xl">settings</span>
                    <span class="flex-1">Settings</span>
                </button>
            </template>

            <!-- USER (Role 3 or default) -->
            <template v-else>
                <button
                    @click="$emit('selectPage', 'userDashboard')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'userDashboard' }"
                >
                    <span class="material-icons text-xl">home</span>
                    <span class="flex-1">Home</span>
                </button>

                <button
                    @click="$emit('selectPage', 'spots')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'spots' }"
                >
                    <span class="material-icons text-xl">place</span>
                    <span class="flex-1">Spots</span>
                </button>

                <button
                    @click="$emit('selectPage', 'favorites')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'favorites' }"
                >
                    <span class="material-icons text-xl">favorite</span>
                    <span class="flex-1">Favorites</span>
                </button>

                <button
                    @click="$emit('selectPage', 'settings')"
                    class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
                    :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-emerald-500 ring-inset': currentPage === 'settings' }"
                >
                    <span class="material-icons text-xl">settings</span>
                    <span class="flex-1">Settings</span>
                </button>
            </template>
        </nav>

        <!-- Footer: Logout -->
        <div class="border-t border-white/10 bg-black/10 p-4">
            <button
                @click="handleLogout"
                class="flex w-full items-center gap-3 rounded-lg bg-red-500/10 px-4 py-3 text-red-300 transition-all hover:bg-red-500/20 hover:text-red-200"
            >
                <span class="material-icons text-xl">logout</span>
                <span class="font-medium">Logout</span>
            </button>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import { Inertia } from '@inertiajs/inertia';

    const props = defineProps({
        userRole: String,
        agencyDetails: Object,
        currentPage: String,
        userInformation: Object,
    });

    const emit = defineEmits(['selectPage']);

    const roleLabel = computed(() => {
        const roles = { 'admin': 'Administrator', 'agency': 'Agency Owner', 'user': 'User' };
        return roles[props.userRole] || 'User';
    });
    const userFullName = computed(() => {
        const info = props.userInformation?.user_info
        if (!info) return 'Unknown'
        const first = info.firstName || ''
        const last = info.lastName || ''
        return `${first} ${last}`.trim() || 'Unknown'
    })

    const handleLogout = () => {
        if (confirm('Are you sure you want to logout?')) {
            Inertia.post(
                '/logout',
                {},
                {
                    onFinish: () => window.location.reload()
                }
            );
        }
    };
</script>
