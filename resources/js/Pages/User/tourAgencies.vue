<template>
    <div class="tour-agencies-body">
        <!-- Header Section -->
        <div class="header-section">
            <div class="header-content">
                <h1 class="main-title">Discover Tour Agencies</h1>
                <p class="subtitle">Find the perfect adventure with trusted local experts in Surigao</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="controls-section">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search agencies or packages..."
                    class="search-input"
                    @input="handleSearch"
                />
                <button v-if="searchQuery" class="clear-search" @click="clearSearch">✕</button>
            </div>

            <div class="filter-controls">
                <select v-model="sortBy" class="filter-select" @change="sortAgencies">
                    <option value="name">Sort by Name</option>
                    <option value="packages">Most Packages</option>
                    <option value="availability">Most Available</option>
                </select>

                <button
                    class="filter-toggle"
                    :class="{ active: showFilters }"
                    @click="showFilters = !showFilters"
                >
                    <span>⚙️</span>
                    Filters
                </button>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div v-if="showFilters" class="advanced-filters">
            <div class="filter-group">
                <label>Price Range</label>
                <div class="price-range">
                    <input type="range" min="0" max="10000" step="500" v-model="priceRange[0]" class="range-slider">
                    <input type="range" min="0" max="10000" step="500" v-model="priceRange[1]" class="range-slider">
                    <div class="price-display">₱{{ priceRange[0] }} - ₱{{ priceRange[1] }}</div>
                </div>
            </div>

            <div class="filter-group">
                <label>Duration</label>
                <div class="duration-filters">
                    <label class="filter-checkbox">
                        <input type="checkbox" v-model="durationFilters.dayTour" />
                        <span class="checkmark"></span>
                        Day Tour
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" v-model="durationFilters.overnight" />
                        <span class="checkmark"></span>
                        Overnight
                    </label>
                    <label class="filter-checkbox">
                        <input type="checkbox" v-model="durationFilters.multiDay" />
                        <span class="checkmark"></span>
                        Multi-day
                    </label>
                </div>
            </div>

            <div class="filter-actions">
                <button class="apply-filters" @click="applyFilters">Apply Filters</button>
                <button class="reset-filters" @click="resetFilters">Reset</button>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Finding the best agencies for you...</p>
        </div>

        <!-- Agencies Grid -->
        <div v-else-if="!selectedAgency" class="agencies-container">
            <div class="results-info">
                <h2 class="section-title">
                    {{ filteredAgencies.length }} Agency{{ filteredAgencies.length !== 1 ? 'ies' : '' }} Found
                    <span v-if="searchQuery" class="search-term">for "{{ searchQuery }}"</span>
                </h2>
            </div>

            <div class="agencies-grid">
                <div
                    v-for="agency in filteredAgencies"
                    :key="agency.id"
                    class="agency-card"
                    @click="viewAgency(agency)"
                >
                    <!-- Agency Image with Badges -->
                    <div class="card-image">
                        <img :src="agency.image" :alt="agency.name" class="agency-img" />
                        <div class="image-overlay">
                            <div class="agency-badges">
                                <span v-if="agency.isVerified" class="badge verified">✓ Verified</span>
                                <span v-if="agency.isPopular" class="badge popular">🔥 Popular</span>
                                <span class="badge packages">{{ agency.packages.length }} Packages</span>
                            </div>
                        </div>
                        <div class="favorite-btn" @click.stop="toggleFavorite(agency)">
                            <span class="heart-icon">{{ isFavorite(agency.id) ? '❤️' : '🤍' }}</span>
                        </div>
                    </div>

                    <!-- Agency Content -->
                    <div class="card-content">
                        <div class="agency-header">
                            <h3 class="agency-name">{{ agency.name }}</h3>
                            <div class="agency-rating" v-if="agency.rating">
                                <span class="stars">⭐ {{ agency.rating }}</span>
                                <span class="reviews">({{ agency.reviewCount }})</span>
                            </div>
                        </div>

                        <p class="agency-desc">{{ agency.shortDesc }}</p>

                        <!-- Quick Stats -->
                        <div class="agency-stats">
                            <div class="stat">
                                <span class="stat-icon">🏢</span>
                                <span class="stat-text">{{ agency.established || '2015' }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-icon">👥</span>
                                <span class="stat-text">{{ agency.totalCustomers?.toLocaleString() || '500+' }}</span>
                            </div>
                            <div class="stat">
                                <span class="stat-icon">🚌</span>
                                <span class="stat-text">{{ agency.toursCompleted?.toLocaleString() || '1K+' }}</span>
                            </div>
                        </div>

                        <!-- Package Preview -->
                        <div class="packages-preview">
                            <div class="preview-header">
                                <span class="preview-title">Available Packages</span>
                                <span class="available-count">{{ getAvailablePackages(agency) }} available</span>
                            </div>
                            <div class="package-tags">
                                <span
                                    v-for="pkg in agency.packages.slice(0, 3)"
                                    :key="pkg.id"
                                    class="package-tag"
                                    :class="{ 'fully-booked': pkg.availableSlots === 0 }"
                                >
                                  {{ pkg.title }}
                                </span>
                                <span v-if="agency.packages.length > 3" class="more-tag">
                                  +{{ agency.packages.length - 3 }} more
                                </span>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="price-range-info">
                            <span class="price-label">From</span>
                            <span class="starting-price">₱{{ getStartingPrice(agency).toLocaleString() }}</span>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer">
                        <button class="view-details-btn">
                            View Details
                            <span class="btn-arrow">→</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- No Results -->
            <div v-if="filteredAgencies.length === 0" class="no-results">
                <div class="no-results-icon">🔍</div>
                <h3>No agencies found</h3>
                <p>Try adjusting your search or filters to find more options.</p>
                <button class="reset-search-btn" @click="resetSearch">Reset Search</button>
            </div>
        </div>

        <!-- Selected Agency Details -->
        <ExploreTourAgencies
            v-else
            :agency="selectedAgency"
            @back="selectedAgency = null"
        />
    </div>
</template>

<script setup>
    import { ref, computed, onMounted } from "vue";
    import ExploreTourAgencies from "./exploreTourAgencies.vue";

    const selectedAgency = ref(null);
    const searchQuery = ref("");
    const sortBy = ref("name");
    const showFilters = ref(false);
    const loading = ref(false);
    const favoriteAgencies = ref(new Set());

    // Filter states
    const priceRange = ref([0, 10000]);

    const durationFilters = ref({
        dayTour: false,
        overnight: false,
        multiDay: false
    });

    const agencies = ref([
        {
            id: 1,
            name: "Surigao Explorer Tours",
            shortDesc: "Eco-friendly island hopping and mountain adventures with certified local guides.",
            image: "/images/agency1.jpg",
            rating: 4.8,
            reviewCount: 127,
            established: "2015",
            totalCustomers: 2500,
            toursCompleted: 1200,
            isVerified: true,
            isPopular: true,
            packages: [
                {
                    id: 101,
                    title: "Island Hopping Adventure",
                    shortDesc: "Explore 3 scenic islands in one day with lunch and snorkeling.",
                    price: 2500,
                    capacity: 15,
                    availableSlots: 8,
                    days: 1,
                    nights: 0,
                    startDate: "2025-11-10",
                    startTime: "7:00 AM",
                    endDate: "2025-11-10",
                    endTime: "6:00 PM",
                    pickUpPoint: "Surigao Boulevard",
                    destinations: ["Naked Island", "Daku Island", "Guyam Island"],
                    inclusions: ["Lunch", "Boat", "Guide", "Entrance Fees"],
                    exclusions: ["Snorkeling Gear", "Drinks"],
                },
                {
                    id: 102,
                    title: "Mountain Eco Hike",
                    shortDesc: "1-day trekking adventure with local guides and panoramic views.",
                    price: 1800,
                    capacity: 10,
                    availableSlots: 0,
                    days: 1,
                    nights: 0,
                    startDate: "2025-12-05",
                    startTime: "6:30 AM",
                    endDate: "2025-12-05",
                    endTime: "5:00 PM",
                    pickUpPoint: "Surigao City Plaza",
                    destinations: ["Mt. Balingasag", "Waterfall Trail"],
                    inclusions: ["Lunch", "Guide", "Permit Fees"],
                    exclusions: ["Snacks", "Souvenirs"],
                },
                {
                    id: 103,
                    title: "Sunset Cruise Experience",
                    shortDesc: "Evening cruise with dinner and live music.",
                    price: 3500,
                    capacity: 20,
                    availableSlots: 12,
                    days: 1,
                    nights: 0,
                    startDate: "2025-11-15",
                    startTime: "4:00 PM",
                    endDate: "2025-11-15",
                    endTime: "9:00 PM",
                    pickUpPoint: "Surigao Marina",
                    destinations: ["Coastal Bay", "Lighthouse Point"],
                    inclusions: ["Dinner", "Live Music", "Drinks"],
                    exclusions: ["Transportation"],
                }
            ],
        },
        {
            id: 2,
            name: "Island Hoppers PH",
            shortDesc: "Adventure-packed tours with professional guides and premium amenities.",
            image: "/images/agency2.jpg",
            rating: 4.6,
            reviewCount: 89,
            established: "2018",
            totalCustomers: 1800,
            toursCompleted: 850,
            isVerified: true,
            isPopular: false,
            packages: [
                {
                    id: 201,
                    title: "Siargao Surf & Nature Tour",
                    shortDesc: "2 days of surf lessons and island exploration.",
                    price: 4800,
                    capacity: 10,
                    availableSlots: 4,
                    days: 2,
                    nights: 1,
                    startDate: "2025-12-10",
                    startTime: "6:00 AM",
                    endDate: "2025-12-11",
                    endTime: "5:00 PM",
                    pickUpPoint: "Dapa Port",
                    destinations: ["Cloud 9", "Magpupungko", "Sugba Lagoon"],
                    inclusions: ["Accommodation", "Breakfast", "Transport"],
                    exclusions: ["Dinner", "Personal Expenses"],
                },
                {
                    id: 202,
                    title: "Private Island Getaway",
                    shortDesc: "Exclusive private island experience for couples and small groups.",
                    price: 7500,
                    capacity: 6,
                    availableSlots: 2,
                    days: 1,
                    nights: 0,
                    startDate: "2025-11-20",
                    startTime: "8:00 AM",
                    endDate: "2025-11-20",
                    endTime: "7:00 PM",
                    pickUpPoint: "Private Dock",
                    destinations: ["Secret Lagoon", "Coral Garden"],
                    inclusions: ["Private Boat", "Gourmet Lunch", "Snorkeling Gear"],
                    exclusions: ["Alcohol", "Photos"],
                }
            ],
        },
        {
            id: 3,
            name: "Mountain Trek Adventures",
            shortDesc: "Specialized in mountain climbing and eco-tourism experiences.",
            image: "/images/agency3.jpg",
            rating: 4.9,
            reviewCount: 203,
            established: "2012",
            totalCustomers: 3200,
            toursCompleted: 1500,
            isVerified: true,
            isPopular: true,
            packages: [
                {
                    id: 301,
                    title: "Summit Challenge",
                    shortDesc: "2-day mountain climbing expedition with camping.",
                    price: 3200,
                    capacity: 8,
                    availableSlots: 3,
                    days: 2,
                    nights: 1,
                    startDate: "2025-11-25",
                    startTime: "5:00 AM",
                    endDate: "2025-11-26",
                    endTime: "4:00 PM",
                    pickUpPoint: "Base Camp Office",
                    destinations: ["Mt. Kabutongan", "Summit Camp"],
                    inclusions: ["Camping Gear", "Meals", "Guide"],
                    exclusions: ["Personal Equipment", "Insurance"],
                }
            ],
        }
    ]);

    // Computed properties
    const totalAgencies = computed(() => agencies.value.length);
    const totalPackages = computed(() =>
        agencies.value.reduce((sum, agency) => sum + agency.packages.length, 0)
    );
    const availablePackages = computed(() =>
        agencies.value.reduce((sum, agency) =>
            sum + agency.packages.filter(pkg => pkg.availableSlots > 0).length, 0
        )
    );

    const filteredAgencies = computed(() => {
        let filtered = [...agencies.value];

        // Search filter
        if (searchQuery.value) {
            const query = searchQuery.value.toLowerCase();
            filtered = filtered.filter(agency =>
                agency.name.toLowerCase().includes(query) ||
                agency.shortDesc.toLowerCase().includes(query) ||
                agency.packages.some(pkg =>
                    pkg.title.toLowerCase().includes(query) ||
                    pkg.destinations.some(dest => dest.toLowerCase().includes(query))
                )
            );
        }

        // Price filter
        filtered = filtered.filter(agency =>
            agency.packages.some(pkg =>
                pkg.price >= priceRange.value[0] && pkg.price <= priceRange.value[1]
            )
        );

        // Duration filter
        const activeDurationFilters = Object.keys(durationFilters.value).filter(
            key => durationFilters.value[key]
        );

        if (activeDurationFilters.length > 0) {
            filtered = filtered.filter(agency =>
                agency.packages.some(pkg => {
                    if (durationFilters.value.dayTour && pkg.days === 1 && pkg.nights === 0) return true;
                    if (durationFilters.value.overnight && pkg.nights === 1) return true;
                    if (durationFilters.value.multiDay && pkg.days > 1) return true;
                    return false;
                })
            );
        }

        // Sort agencies
        switch (sortBy.value) {
            case 'name':
                filtered.sort((a, b) => a.name.localeCompare(b.name));
                break;
            case 'packages':
                filtered.sort((a, b) => b.packages.length - a.packages.length);
                break;
            case 'availability':
                filtered.sort((a, b) =>
                    getAvailablePackages(b) - getAvailablePackages(a)
                );
                break;
        }

        return filtered;
    });

    // Methods
    const viewAgency = (agency) => {
        selectedAgency.value = agency;
    };

    const toggleFavorite = (agency) => {
        if (favoriteAgencies.value.has(agency.id)) {
            favoriteAgencies.value.delete(agency.id);
        } else {
            favoriteAgencies.value.add(agency.id);
        }
        // Save to localStorage in real app
    };

    const isFavorite = (agencyId) => {
        return favoriteAgencies.value.has(agencyId);
    };

    const getAvailablePackages = (agency) => {
        return agency.packages.filter(pkg => pkg.availableSlots > 0).length;
    };

    const getStartingPrice = (agency) => {
        const prices = agency.packages.map(pkg => pkg.price);
        return Math.min(...prices);
    };

    const handleSearch = () => {
        // Add debouncing in real app
    };

    const clearSearch = () => {
        searchQuery.value = "";
    };

    const resetSearch = () => {
        searchQuery.value = "";
        resetFilters();
    };

    const applyFilters = () => {
        showFilters.value = false;
    };

    const resetFilters = () => {
        priceRange.value = [0, 10000];
        durationFilters.value = {
            dayTour: false,
            overnight: false,
            multiDay: false
        };
    };

    const sortAgencies = () => {
        // Sorting is handled in computed property
    };

    onMounted(() => {
        // Load favorites from localStorage in real app
        loading.value = true;
        setTimeout(() => {
            loading.value = false;
        }, 1000);
    });
</script>

<style scoped>
.tour-agencies-body {
    padding: 30px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
    animation: fadeIn 0.5s ease-out;
}

/* Header Section */
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
}

.header-content {
    flex: 1;
}

.main-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1a365d;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #2d3748, #4a5568);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.subtitle {
    font-size: 1.1rem;
    color: #718096;
    margin: 0;
    max-width: 500px;
}

.header-stats {
    display: flex;
    gap: 30px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    display: block;
}

.stat-label {
    font-size: 0.9rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Controls Section */
.controls-section {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 300px;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
}

.search-input {
    width: 100%;
    padding: 12px 45px 12px 45px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
}

.clear-search {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    padding: 4px;
}

.filter-controls {
    display: flex;
    gap: 12px;
    align-items: center;
}

.filter-select {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    font-size: 0.9rem;
}

.filter-toggle {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-toggle.active {
    background: #4299e1;
    color: white;
    border-color: #4299e1;
}

/* Advanced Filters */
.advanced-filters {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    animation: slideDown 0.3s ease-out;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #2d3748;
}

.price-range {
    position: relative;
}

.range-slider {
    width: 100%;
    margin: 10px 0;
}

.price-display {
    text-align: center;
    font-weight: 600;
    color: #4299e1;
    margin-top: 10px;
}

.duration-filters {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.filter-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.apply-filters, .reset-filters {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.apply-filters {
    background: #4299e1;
    color: white;
}

.reset-filters {
    background: #e2e8f0;
    color: #4a5568;
}

/* Loading State */
.loading-state {
    text-align: center;
    padding: 60px 20px;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #4299e1;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

/* Agencies Container */
.agencies-container {
    animation: fadeInUp 0.5s ease-out;
}

.results-info {
    margin-bottom: 30px;
}

.section-title {
    font-size: 1.5rem;
    color: #2d3748;
    margin: 0;
}

.search-term {
    color: #4299e1;
    font-weight: 600;
}

/* Agencies Grid */
.agencies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 25px;
}

.agency-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.agency-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

/* Card Image */
.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.agency-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.agency-card:hover .agency-img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.4));
}

.agency-badges {
    position: absolute;
    top: 15px;
    left: 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    align-items: flex-start;
}

.badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    backdrop-filter: blur(10px);
}

.badge.verified {
    background: rgba(34, 197, 94, 0.9);
    color: white;
}

.badge.popular {
    background: rgba(249, 115, 22, 0.9);
    color: white;
}

.badge.packages {
    background: rgba(59, 130, 246, 0.9);
    color: white;
}

.favorite-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.favorite-btn:hover {
    background: white;
    transform: scale(1.1);
}

/* Card Content */
.card-content {
    padding: 20px;
}

.agency-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.agency-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a365d;
    margin: 0;
    flex: 1;
}

.agency-rating {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 0.9rem;
}

.stars {
    color: #f59e0b;
    font-weight: 600;
}

.reviews {
    color: #718096;
    font-size: 0.8rem;
}

.agency-desc {
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.agency-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    padding: 12px 0;
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
}

.stat {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: #64748b;
}

.stat-icon {
    font-size: 0.9rem;
}

.packages-preview {
    margin-bottom: 15px;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.preview-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #374151;
}

.available-count {
    font-size: 0.8rem;
    color: #10b981;
    font-weight: 600;
}

.package-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.package-tag {
    background: #f1f5f9;
    color: #475569;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.package-tag.fully-booked {
    background: #fecaca;
    color: #dc2626;
}

.more-tag {
    background: #dbeafe;
    color: #1d4ed8;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
}

.price-range-info {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 15px;
}

.price-label {
    font-size: 0.8rem;
    color: #64748b;
}

.starting-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #059669;
}

/* Card Footer */
.card-footer {
    padding: 0 20px 20px;
}

.view-details-btn {
    width: 100%;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.view-details-btn:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
}

.btn-arrow {
    transition: transform 0.3s ease;
}

.agency-card:hover .btn-arrow {
    transform: translateX(4px);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.no-results-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.no-results h3 {
    color: #374151;
    margin-bottom: 10px;
}

.no-results p {
    color: #6b7280;
    margin-bottom: 20px;
}

.reset-search-btn {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
}

.reset-search-btn:hover {
    background: #2563eb;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .tour-agencies-body {
        padding: 20px;
    }

    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .main-title {
        font-size: 2rem;
    }

    .header-stats {
        width: 100%;
        justify-content: space-around;
    }

    .controls-section {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        min-width: auto;
    }

    .agencies-grid {
        grid-template-columns: 1fr;
    }

    .agency-header {
        flex-direction: column;
        gap: 8px;
    }

    .agency-stats {
        justify-content: space-between;
    }
}

@media (max-width: 480px) {
    .advanced-filters {
        padding: 20px;
    }

    .duration-filters {
        flex-direction: column;
        gap: 10px;
    }

    .filter-actions {
        flex-direction: column;
    }
}
</style>
