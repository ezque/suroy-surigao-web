<template>
  <div class="agency-details" :class="{ 'modal-open': showReservationForm }">
    <!-- Header with Back Button -->
    <div class="header-section">
      <button class="back-btn" @click="$emit('back')">
        <span class="back-icon">←</span> Back to Agencies
      </button>
      <div class="header-actions">
        <button class="share-btn" @click="shareAgency">
          <span class="share-icon">📤</span> Share
        </button>
        <button class="favorite-btn" :class="{ favorited: isFavorited }" @click="toggleFavorite">
          <span class="heart-icon">{{ isFavorited ? '❤️' : '🤍' }}</span>
          {{ isFavorited ? 'Favorited' : 'Favorite' }}
        </button>
      </div>
    </div>

    <!-- Agency Header -->
    <div class="agency-header">
      <div class="banner-container">
        <img :src="agency.image" :alt="agency.name" class="agency-banner" />
        <div class="banner-overlay">
          <div class="agency-rating" v-if="agency.rating">
            <span class="rating-stars">
              <span v-for="n in 5" :key="n" class="star" :class="{ filled: n <= agency.rating }">
                {{ n <= agency.rating ? '⭐' : '☆' }}
              </span>
            </span>
            <span class="rating-value">{{ agency.rating }}/5</span>
          </div>
        </div>
      </div>

      <div class="agency-info">
        <h1 class="agency-name">{{ agency.name }}</h1>
        <p class="agency-desc">{{ agency.shortDesc }}</p>

        <div class="agency-meta">
              <div class="meta-item" v-if="agency.established">
                <span class="meta-icon">📅</span>
                <span>Est. {{ agency.established }}</span>
              </div>
              <div class="meta-item" v-if="agency.toursCompleted">
                <span class="meta-icon">🚌</span>
                <span>{{ agency.toursCompleted.toLocaleString() }}+ Tours</span>
              </div>
              <div class="meta-item" v-if="agency.responseTime">
                <span class="meta-icon">💬</span>
                <span>Response: {{ agency.responseTime }}</span>
              </div>
        </div>
      </div>
    </div>

    
    <!-- Packages Section -->
    <div class="packages-section">
      <div class="section-header">
        <h2 class="section-title">Available Tour Packages</h2>
        <div class="filter-controls">
          <button
            class="filter-btn"
            :class="{ active: filter === 'all' }"
            @click="filter = 'all'"
          >
            All Packages
          </button>
          <button
            class="filter-btn"
            :class="{ active: filter === 'available' }"
            @click="filter = 'available'"
          >
            Available
          </button>
        </div>
      </div>

      <div class="packages-grid">
        <div
          v-for="pkg in filteredPackages"
          :key="pkg.id"
          class="package-card"
          :class="{ 'fully-booked': pkg.availableSlots === 0 }"
        >
          <!-- Package Header -->
          <div class="package-header">
            <div class="package-title-section">
              <h3 class="package-name">{{ pkg.title }}</h3>
              <div class="package-badge" :class="pkg.badgeType" v-if="pkg.badge">
                {{ pkg.badge }}
              </div>
            </div>
            <div class="package-status">
              <span
                class="availability-status"
                :class="pkg.availableSlots > 0 ? 'available' : 'full'"
              >
                <span class="status-dot" :class="pkg.availableSlots > 0 ? 'available' : 'full'"></span>
                {{ pkg.availableSlots > 0 ? `${pkg.availableSlots} slots left` : "Fully Booked" }}
              </span>
            </div>
          </div>

          <!-- Package Description -->
          <p class="package-desc">{{ pkg.shortDesc }}</p>

          <!-- Quick Info -->
          <div class="quick-info">
            <div class="info-item">
              <span class="info-icon">💰</span>
              <span class="info-text">₱{{ pkg.price.toLocaleString() }} / person</span>
            </div>
            <div class="info-item">
              <span class="info-icon">👥</span>
              <span class="info-text">Up to {{ pkg.capacity }} persons</span>
            </div>
            <div class="info-item">
              <span class="info-icon">🕒</span>
              <span class="info-text">{{ calculateDuration(pkg) }}</span>
            </div>
          </div>

          <!-- Tour Schedule -->
          <div class="detail-section">
            <h4 class="detail-title">
              <span class="detail-icon">🕒</span>
              Tour Schedule
            </h4>
            <div class="schedule-grid">
              <div class="schedule-item">
                <span class="schedule-label">Start</span>
                <span class="schedule-value">{{ formatDate(pkg.startDate) }} at {{ pkg.startTime }}</span>
              </div>
              <div class="schedule-item">
                <span class="schedule-label">End</span>
                <span class="schedule-value">{{ formatDate(pkg.endDate) }} at {{ pkg.endTime }}</span>
              </div>
            </div>
          </div>

          <!-- Pick-up Point -->
          <div class="detail-section">
            <h4 class="detail-title">
              <span class="detail-icon">📍</span>
              Pick-up Point
            </h4>
            <p class="detail-content">{{ pkg.pickUpPoint }}</p>
          </div>

          <!-- Destinations -->
          <div class="detail-section">
            <h4 class="detail-title">
              <span class="detail-icon">🏖️</span>
              Destinations
            </h4>
            <div class="destinations-grid">
              <span
                v-for="destination in pkg.destinations"
                :key="destination"
                class="destination-tag"
              >
                {{ destination }}
              </span>
            </div>
          </div>

          <!-- Inclusions & Exclusions -->
          <div class="features-grid">
            <div class="feature-column">
              <h4 class="feature-title positive">
                <span class="feature-icon">✅</span>
                Inclusions
              </h4>
              <ul class="feature-list">
                <li v-for="inclusion in pkg.inclusions" :key="inclusion">
                  <span class="check-icon">✔</span>
                  {{ inclusion }}
                </li>
              </ul>
            </div>
            <div class="feature-column">
              <h4 class="feature-title negative">
                <span class="feature-icon">❌</span>
                Exclusions
              </h4>
              <ul class="feature-list">
                <li v-for="exclusion in pkg.exclusions" :key="exclusion">
                  <span class="x-icon">✖</span>
                  {{ exclusion }}
                </li>
              </ul>
            </div>
          </div>

          <!-- Action Button -->
          <div class="package-actions">
            <button
              class="book-btn"
              :class="{ 'fully-booked': pkg.availableSlots === 0 }"
              :disabled="pkg.availableSlots === 0"
              @click="openReservationForm(pkg)"
            >
              <span v-if="pkg.availableSlots > 0" class="btn-content">
                <span class="btn-icon">💬</span>
                Inquire / Reserve Now
                <span class="btn-price">₱{{ pkg.price.toLocaleString() }}</span>
              </span>
              <span v-else class="btn-content">
                <span class="btn-icon">🔒</span>
                Fully Booked
              </span>
            </button>
          </div>
        </div>
      </div>

      <!-- No Packages Message -->
      <div v-if="filteredPackages.length === 0" class="no-packages">
        <div class="no-packages-icon">📭</div>
        <h3>No Packages Available</h3>
        <p>There are no {{ filter === 'available' ? 'available' : '' }} packages at the moment.</p>
      </div>
    </div>

    <!-- Reservation Form Modal -->
     <div v-if="showReservationForm" class="reservation-modal-overlay" @click="closeReservationForm">
      <div class="reservation-modal-container" @click.stop>
        <ReservePackage
          :agency="agency"
          :package="selectedPackage"
          @close="closeReservationForm"
          @reservation-completed="handleReservationComplete"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import ReservePackage from "./reservePackage.vue";

const props = defineProps({
  agency: Object,
});

const emit = defineEmits(['back']);

// Reactive data
const isFavorited = ref(false);
const filter = ref('all');

// Reservation modal state
const showReservationForm = ref(false);
const selectedPackage = ref(null);

// Computed properties
const filteredPackages = computed(() => {
  if (filter.value === 'available') {
    return props.agency.packages?.filter(pkg => pkg.availableSlots > 0) || [];
  }
  return props.agency.packages || [];
});

// Methods
const toggleFavorite = () => {
  isFavorited.value = !isFavorited.value;
};

const shareAgency = () => {
  if (navigator.share) {
    navigator.share({
      title: props.agency.name,
      text: `Check out ${props.agency.name} - ${props.agency.shortDesc}`,
      url: window.location.href,
    });
  } else {
    navigator.clipboard.writeText(window.location.href);
    alert('Agency link copied to clipboard!');
  }
};

// Reservation methods
const openReservationForm = (pkg) => {
  console.log('Opening reservation form for:', pkg.title);
  selectedPackage.value = pkg;
  showReservationForm.value = true;
};

const closeReservationForm = () => {
  showReservationForm.value = false;
  selectedPackage.value = null;
};

const handleReservationComplete = (reservationData) => {
  console.log('Reservation completed:', reservationData);
  alert(`Reservation confirmed! Your reservation ID: ${reservationData.reservationId}`);
  closeReservationForm();
};

const calculateDuration = (pkg) => {
  const start = new Date(pkg.startDate);
  const end = new Date(pkg.endDate);
  const diffTime = Math.abs(end - start);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return `${diffDays} Day${diffDays > 1 ? 's' : ''}`;
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};

onMounted(() => {
  const favorites = JSON.parse(localStorage.getItem('favoriteAgencies') || '[]');
  isFavorited.value = favorites.some(fav => fav.id === props.agency.id);
});

const testClick = () => {
  console.log('Overlay clicked');
};
</script>

<style scoped>
.agency-details {
  padding: 30px;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
  min-height: 100vh;
  animation: fadeInUp 0.6s ease-out;
}

/* Reservation Modal Styles */
.reservation-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
  animation: fadeIn 0.3s ease-out;
}

.reservation-modal-container {
  background: white;
  border-radius: 20px;
  max-width: 800px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ... (keep all your existing CSS styles from the previous ExploreTourAgencies) ... */

/* Header Section */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.back-btn {
  background: linear-gradient(135deg, #3498db, #2980b9);
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.back-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
}

.header-actions {
  display: flex;
  gap: 10px;
}

.share-btn, .favorite-btn {
  background: white;
  border: 2px solid #e3e8f0;
  padding: 10px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.3s ease;
}

.favorite-btn.favorited {
  background: #ff4757;
  color: white;
  border-color: #ff4757;
}

.share-btn:hover, .favorite-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Agency Header */
.agency-header {
  background: white;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
}

.banner-container {
  position: relative;
}

.agency-banner {
  width: 100%;
  height: 300px;
  object-fit: cover;
}

.banner-overlay {
  position: absolute;
  top: 20px;
  right: 20px;
}

.agency-rating {
  background: rgba(255, 255, 255, 0.95);
  padding: 8px 12px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 8px;
  backdrop-filter: blur(10px);
}

.rating-stars {
  display: flex;
  gap: 2px;
}

.star {
  font-size: 14px;
}

.rating-value {
  font-weight: 600;
  color: #2c3e50;
}

.agency-info {
  padding: 30px;
}

.agency-name {
  font-size: 2.5rem;
  color: #2c3e50;
  margin: 0 0 10px 0;
  font-weight: 800;
}

.agency-desc {
  color: #6c7a89;
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 20px;
}

.agency-meta {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #5d6d7e;
  font-size: 14px;
}

/* Quick Stats */
.quick-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  display: flex;
  align-items: center;
  gap: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
}

.stat-icon {
  font-size: 2.5rem;
}

.stat-value {
  font-size: 1.8rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 5px;
}

.stat-label {
  color: #6c7a89;
  font-size: 0.9rem;
}

/* Packages Section */
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.section-title {
  font-size: 2rem;
  color: #2c3e50;
  margin: 0;
  font-weight: 700;
}

.filter-controls {
  display: flex;
  gap: 10px;
}

.filter-btn {
  background: white;
  border: 2px solid #e3e8f0;
  padding: 10px 20px;
  border-radius: 25px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
}

.filter-btn.active {
  background: #3498db;
  color: white;
  border-color: #3498db;
}

.filter-btn:hover:not(.active) {
  border-color: #3498db;
  color: #3498db;
}

/* Packages Grid */
.packages-grid {
  display: grid;
  gap: 30px;
}

.package-card {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.package-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
  border-color: #3498db;
}

.package-card.fully-booked {
  opacity: 0.7;
  border-color: #e3e8f0;
}

.package-card.fully-booked:hover {
  transform: none;
  border-color: #e3e8f0;
}

/* Package Header */
.package-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.package-title-section {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.package-name {
  font-size: 1.5rem;
  color: #2c3e50;
  margin: 0;
  font-weight: 700;
}

.package-badge {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.package-badge.popular {
  background: #fff3cd;
  color: #856404;
}

.package-badge.new {
  background: #d1ecf1;
  color: #0c5460;
}

.package-badge.discount {
  background: #d4edda;
  color: #155724;
}

.availability-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 600;
  font-size: 0.9rem;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.status-dot.available {
  background: #27ae60;
}

.status-dot.full {
  background: #e74c3c;
}

.availability-status.available {
  color: #27ae60;
}

.availability-status.full {
  color: #e74c3c;
}

/* Package Description */
.package-desc {
  color: #5d6d7e;
  line-height: 1.6;
  margin-bottom: 20px;
  font-size: 1rem;
}

/* Quick Info */
.quick-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
  margin-bottom: 25px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.info-icon {
  font-size: 1.2rem;
}

.info-text {
  font-weight: 600;
  color: #2c3e50;
}

/* Detail Sections */
.detail-section {
  margin-bottom: 25px;
}

.detail-title {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #2c3e50;
  margin: 0 0 12px 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.detail-content {
  color: #5d6d7e;
  line-height: 1.5;
}

.schedule-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.schedule-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.schedule-label {
  font-size: 0.9rem;
  color: #6c7a89;
  font-weight: 600;
}

.schedule-value {
  color: #2c3e50;
  font-weight: 500;
}

/* Destinations */
.destinations-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.destination-tag {
  background: #e3f2fd;
  color: #1976d2;
  padding: 6px 12px;
  border-radius: 15px;
  font-size: 0.9rem;
  font-weight: 500;
}

/* Features Grid */
.features-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  margin: 25px 0;
}

.feature-column {
  display: flex;
  flex-direction: column;
}

.feature-title {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0 0 12px 0;
  font-size: 1rem;
  font-weight: 600;
}

.feature-title.positive {
  color: #27ae60;
}

.feature-title.negative {
  color: #e74c3c;
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.feature-list li {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  margin-bottom: 8px;
  color: #5d6d7e;
  line-height: 1.4;
}

.check-icon {
  color: #27ae60;
  font-weight: bold;
}

.x-icon {
  color: #e74c3c;
  font-weight: bold;
}

/* Package Actions */
.package-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 25px;
}

.book-btn {
  background: linear-gradient(135deg, #2ecc71, #27ae60);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  min-width: 200px;
}

.book-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(46, 204, 113, 0.4);
  background: linear-gradient(135deg, #27ae60, #219150);
}

.book-btn:disabled {
  background: #bdc3c7;
  cursor: not-allowed;
  transform: none;
}

.book-btn.fully-booked {
  background: #95a5a6;
}

.btn-content {
  display: flex;
  align-items: center;
  gap: 8px;
  justify-content: center;
}

.btn-price {
  background: rgba(255, 255, 255, 0.2);
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 0.9rem;
}

/* No Packages */
.no-packages {
  text-align: center;
  padding: 60px 30px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.no-packages-icon {
  font-size: 4rem;
  margin-bottom: 20px;
}

.no-packages h3 {
  color: #2c3e50;
  margin-bottom: 10px;
}

.no-packages p {
  color: #6c7a89;
}

/* Animations */
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

/* Responsive Design */
@media (max-width: 768px) {
  .agency-details {
    padding: 20px;
  }

  .header-section {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .header-actions {
    justify-content: center;
  }

  .agency-name {
    font-size: 2rem;
  }

  .section-header {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .filter-controls {
    justify-content: center;
  }

  .quick-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .features-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .package-header {
    flex-direction: column;
    gap: 10px;
  }

  .package-actions {
    justify-content: center;
  }

  .book-btn {
    width: 100%;
  }

  .reservation-modal-container {
    margin: 10px;
    max-height: 95vh;
  }
}

@media (max-width: 480px) {
  .quick-stats {
    grid-template-columns: 1fr;
  }

  .agency-meta {
    flex-direction: column;
    gap: 10px;
  }

  .quick-info {
    grid-template-columns: 1fr;
  }

  .schedule-grid {
    grid-template-columns: 1fr;
  }
}
</style>
