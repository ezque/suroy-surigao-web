<template>
    <section v-if="!loading && filteredAgencies.length > 0" class="animate-[fadeInUp_0.5s_ease-out]">
        <h2 class="text-xl font-semibold text-[#1e293b] mb-6">
            {{ filteredAgencies.length }} Agency{{ filteredAgencies.length !== 1 ? 'ies' : '' }} Found
            <span v-if="searchQuery" class="text-[#3b82f6] font-medium">for “{{ searchQuery }}”</span>
        </h2>

        <div class="grid gap-8 grid-cols-[repeat(auto-fill,minmax(360px,1fr))]">
            <article
                v-for="agency in filteredAgencies"
                :key="agency.id"
                class="group bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.1)] transition-all duration-300 hover:-translate-y-1"
            >
                <!-- Image -->
                <div class="relative">
                    <img
                        src="/public/images/logo/logo.png"
                        alt="Agency image"
                        class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/50"></div>

                    <!-- Tags -->
                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                        <span
                            v-if="agency.isVerified"
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/90 text-white backdrop-blur-sm"
                        >
                          ✓ Verified
                        </span>
                        <span
                            v-if="agency.isPopular"
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-500/90 text-white backdrop-blur-sm"
                        >
                          🔥 Popular
                        </span>
                    </div>

                    <!-- Like -->
                    <button
                        @click.stop="toggleLike(agency)"
                        class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/90 flex items-center justify-center hover:scale-110 transition-all duration-300 shadow-md"
                    >
                        <span>{{ isLiked(agency.id) ? '❤️' : '🤍' }}</span>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-[#1e293b] capitalize">
                            {{ agency.agency?.agency_name || "No Data found" }}
                        </h3>
                        <div class="text-sm text-[#f59e0b] font-semibold">
                            ⭐ 2.5 <span class="text-[#94a3b8]">(20)</span>
                        </div>
                    </div>

                    <p class="text-[#64748b] text-sm leading-relaxed mb-4">
                        This is a short description of the agency.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 mb-4 py-3 border-y border-[#e2e8f0] text-xs text-[#64748b]">
                        <span>🏢 Established 2025</span>
                        <span>👥 500 Customers</span>
                        <span>🚌 1k+ Tours</span>
                    </div>

                    <!-- Packages -->
                    <div class="mb-4">
                        <div class="flex justify-between mb-2 text-sm font-semibold">
                            <span class="text-[#1e293b]">Available Packages</span>
                            <span class="text-[#10b981]">{{ agency.available_package }} available</span>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <span
                                  v-for="pkg in agency.packages.slice(0, 3)"
                                  :key="pkg.id"
                                  :class="[
                                  'px-2 py-1 rounded-md text-xs font-medium',
                                  pkg.available_slot === 0
                                    ? 'bg-red-100 text-red-600'
                                    : 'bg-gray-100 text-[#475569]'
                                ]"
                            >
                                {{ pkg.package_name }}
                            </span>

                            <span
                                v-if="agency.packages.length > 3"
                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-medium"
                            >
                                +{{ agency.packages.length - 3 }} more
                            </span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-sm text-[#64748b] font-medium">From</span>
                        <span class="text-lg font-semibold text-[#1e3a8a]">
                          ₱{{ startingPrice(agency).toLocaleString() }}
                        </span>
                    </div>

                    <!-- Button -->
                    <button
                        @click="$emit('selectAgency', agency)"
                        class="w-full bg-gradient-to-r from-[#3b82f6] to-[#1d4ed8] text-white py-3 rounded-xl font-semibold flex items-center justify-center gap-2 transition-all duration-300 hover:from-[#2563eb] hover:to-[#1e40af] hover:-translate-y-0.5"
                    >
                        View Details
                        <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                    </button>
                </div>
            </article>
        </div>
    </section>
</template>

<script setup>
    defineProps({
        filteredAgencies: Array,
        loading: Boolean,
        searchQuery: String,
        isLiked: Function,
        toggleLike: Function,
        startingPrice: Function,
    });
    const emit = defineEmits(["selectAgency"]);
</script>
