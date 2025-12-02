<template>
  <div class="flex h-screen w-72 flex-col bg-gradient-to-br from-[#009FB7] to-[#007A8A] shadow-2xl">
    <div class="border-b border-white/10 bg-white/5 p-5 text-center backdrop-blur-sm">
      <div class="relative mx-auto mb-4 h-20 w-20">
        <img
          src="/public/images/logo/logo5.png"
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
            'bg-gradient-to-r from-[#009FB7] to-[#007A8A]': userRole === 'agency',
            'bg-white/20': userRole === 'user'
          }"
        >
          {{ roleLabel }}
        </span>
      </div>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto p-4 scrollbar-thin scrollbar-thumb-white/30 scrollbar-track-white/10">
      <template v-if="userRole === 'admin'">
        <button
          v-for="item in adminMenu"
          :key="item.page"
          @click="$emit('selectPage', item.page)"
          class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-[#009FB7] ring-inset': currentPage === item.page }"
        >
          <span class="material-icons text-xl">{{ item.icon }}</span>
          <span class="flex-1">{{ item.label }}</span>
        </button>
      </template>

      <template v-else-if="userRole === 'agency'">
        <button
          v-for="item in agencyMenu"
          :key="item.page"
          @click="$emit('selectPage', item.page)"
          class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-[#009FB7] ring-inset': currentPage === item.page }"
        >
          <span class="material-icons text-xl">{{ item.icon }}</span>
          <span class="flex-1">{{ item.label }}</span>
        </button>
      </template>

      <template v-else>
        <button
          v-for="item in userMenu"
          :key="item.page"
          @click="$emit('selectPage', item.page)"
          class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-left text-white/80 transition-all hover:bg-white/10 hover:text-white"
          :class="{ 'bg-white/15 font-semibold text-white ring-2 ring-[#009FB7] ring-inset': currentPage === item.page }"
        >
          <span class="material-icons text-xl">{{ item.icon }}</span>
          <span class="flex-1">{{ item.label }}</span>
        </button>
      </template>
    </nav>

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
import { computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'

/* Props */
const props = defineProps({
  userRole: String,
  agencyDetails: Object,
  currentPage: String,
  userInformation: Object,
})

/* Emits */
defineEmits(['selectPage'])

/* Computed properties */
const roleLabel = computed(() => {
  const roles = { admin: 'Administrator', agency: 'Agency Owner', user: 'User' }
  return roles[props.userRole] || 'User'
})

const userFullName = computed(() => {
  const info = props.userInformation?.user_info
  if (!info) return 'Unknown'
  const first = info.firstName || ''
  const last = info.lastName || ''
  return `${first} ${last}`.trim() || 'Unknown'
})

/* Menus */
const adminMenu = [
  { label: 'Dashboard', icon: 'dashboard', page: 'dashboard' },
  { label: 'Agencies', icon: 'business', page: 'agency' },
  { label: 'Manage Spots', icon: 'map', page: 'spots' },
  { label: 'Manage Blog', icon: 'article', page: 'managePost' },
  { label: 'Manage Users', icon: 'group', page: 'manageUser' },
  { label: 'Settings', icon: 'settings', page: 'adminSettings' },
]

const agencyMenu = [
  { label: 'Dashboard', icon: 'dashboard', page: 'agencyDashboard' },
  { label: 'Manage Tours & Packages', icon: 'work', page: 'ManageTours' },
  { label: 'Reservation', icon: 'event', page: 'Reservation' },
  { label: 'Settings', icon: 'settings', page: 'agencySettings' },
]

const userMenu = [
  { label: 'Home', icon: 'home', page: 'userDashboard' },
  { label: 'Reservation', icon: 'event_note', page: 'userReservation' },
  { label: 'Spots', icon: 'place', page: 'spots' },
  { label: 'Favorites', icon: 'favorite', page: 'favorites' },
  { label: 'Settings', icon: 'settings', page: 'settings' },
]

/* Logout */
const handleLogout = () => {
  if (confirm('Are you sure you want to logout?')) {
    Inertia.post('/logout', {}, { onFinish: () => window.location.reload() })
  }
}
</script>