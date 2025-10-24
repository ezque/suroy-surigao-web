<template>
  <div class="reserve-package-modal">
    <!-- Modal Header -->
    <div class="modal-header">
      <h2 class="modal-title">Reserve {{ selectedPackage?.title }}</h2>
      <button class="close-btn" @click="$emit('close')">×</button>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps">
      <div class="step" :class="{ active: currentStep === 1, completed: currentStep > 1 }">
        <div class="step-number">1</div>
        <span class="step-label">Group Details</span>
      </div>
      <div class="step" :class="{ active: currentStep === 2, completed: currentStep > 2 }">
        <div class="step-number">2</div>
        <span class="step-label">Contact Info</span>
      </div>
      <div class="step" :class="{ active: currentStep === 3 }">
        <div class="step-number">3</div>
        <span class="step-label">Confirmation</span>
      </div>
    </div>

    <!-- Step 1: Group Details -->
    <div v-if="currentStep === 1" class="form-step">
      <div class="step-content">
        <!-- Package Summary -->
        <div class="package-summary-card">
          <div class="package-image">
            <img :src="selectedPackage?.image || '/images/default-package.jpg'" :alt="selectedPackage?.title" />
          </div>
          <div class="package-details">
            <h3 class="package-title">{{ selectedPackage?.title }}</h3>
            <p class="package-description">{{ selectedPackage?.shortDesc }}</p>
            <div class="package-meta">
              <div class="meta-item">
                <span class="meta-icon">💰</span>
                <span class="meta-text">₱{{ selectedPackage?.price?.toLocaleString() }} per person</span>
              </div>
              <div class="meta-item">
                <span class="meta-icon">👥</span>
                <span class="meta-text">Capacity: {{ selectedPackage?.capacity }} people</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Group Size -->
        <div class="form-group">
          <label class="form-label">Number of People *</label>
          <div class="group-size-selector">
            <button
              v-for="num in availableSlots"
              :key="num"
              class="size-option"
              :class="{ active: formData.numberOfPeople === num }"
              @click="formData.numberOfPeople = num"
              type="button"
            >
              {{ num }}
            </button>
          </div>
          <div class="slots-info">
            <span class="available-info">{{ selectedPackage?.availableSlots }} slots available</span>
          </div>
        </div>

        <!-- Special Requests -->
        <div class="form-group">
          <label for="specialRequests" class="form-label">Special Requests</label>
          <textarea
            id="specialRequests"
            v-model="formData.specialRequests"
            placeholder="Any dietary restrictions, accessibility needs, or special requirements..."
            rows="3"
            class="form-textarea"
          ></textarea>
        </div>

        <!-- Total Amount -->
        <div class="total-amount-section">
          <div class="amount-row">
            <span class="amount-label">Total Amount:</span>
            <span class="amount-value">₱{{ totalAmount.toLocaleString() }}</span>
          </div>
        </div>
      </div>

      <div class="step-actions">
        <button class="btn btn-secondary" @click="$emit('close')">Cancel</button>
        <button class="btn btn-primary" @click="nextStep" :disabled="!canProceedToStep2">
          Continue to Contact Info →
        </button>
      </div>
    </div>

    <!-- Step 2: Contact Information -->
    <div v-if="currentStep === 2" class="form-step">
      <div class="step-content">
        <h3 class="section-title">Contact Information</h3>
        
        <div class="form-row">
          <div class="form-group">
            <label for="firstName" class="form-label">First Name *</label>
            <input
              type="text"
              id="firstName"
              v-model="formData.contactInfo.firstName"
              class="form-input"
              required
              placeholder="Enter your first name"
            />
          </div>
          <div class="form-group">
            <label for="lastName" class="form-label">Last Name *</label>
            <input
              type="text"
              id="lastName"
              v-model="formData.contactInfo.lastName"
              class="form-input"
              required
              placeholder="Enter your last name"
            />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="email" class="form-label">Email Address *</label>
            <input
              type="email"
              id="email"
              v-model="formData.contactInfo.email"
              class="form-input"
              required
              placeholder="your.email@example.com"
            />
          </div>
          <div class="form-group">
            <label for="phone" class="form-label">Phone Number *</label>
            <input
              type="tel"
              id="phone"
              v-model="formData.contactInfo.phone"
              class="form-input"
              required
              placeholder="0912 345 6789"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="address" class="form-label">Address</label>
          <input
            type="text"
            id="address"
            v-model="formData.contactInfo.address"
            class="form-input"
            placeholder="Your complete address"
          />
        </div>

        <!-- Emergency Contact -->
        <div class="emergency-contact-section">
          <h4 class="section-subtitle">Emergency Contact</h4>
          <div class="form-row">
            <div class="form-group">
              <label for="emergencyName" class="form-label">Contact Name *</label>
              <input
                type="text"
                id="emergencyName"
                v-model="formData.emergencyContact.name"
                class="form-input"
                required
                placeholder="Full name of emergency contact"
              />
            </div>
            <div class="form-group">
              <label for="emergencyPhone" class="form-label">Phone Number *</label>
              <input
                type="tel"
                id="emergencyPhone"
                v-model="formData.emergencyContact.phone"
                class="form-input"
                required
                placeholder="0912 345 6789"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="emergencyRelationship" class="form-label">Relationship</label>
            <input
              type="text"
              id="emergencyRelationship"
              v-model="formData.emergencyContact.relationship"
              class="form-input"
              placeholder="e.g., Spouse, Parent, Friend"
            />
          </div>
        </div>
      </div>

      <div class="step-actions">
        <button class="btn btn-secondary" @click="prevStep">← Back</button>
        <button class="btn btn-primary" @click="nextStep" :disabled="!canProceedToStep3">
          Continue to Confirmation →
        </button>
      </div>
    </div>

    <!-- Step 3: Confirmation -->
    <div v-if="currentStep === 3" class="form-step">
      <div class="step-content">
        <div class="confirmation-content">
          <div class="confirmation-icon">✅</div>
          <h3 class="confirmation-title">Confirm Your Reservation</h3>
          <p class="confirmation-subtitle">Please review your reservation details before confirming</p>
          
          <div class="reservation-summary">
            <div class="summary-section">
              <h4 class="summary-title">Package Details</h4>
              <div class="summary-item">
                <span class="summary-label">Package:</span>
                <span class="summary-value">{{ selectedPackage?.title }}</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Description:</span>
                <span class="summary-value">{{ selectedPackage?.shortDesc }}</span>
              </div>
            </div>

            <div class="summary-section">
              <h4 class="summary-title">Group Information</h4>
              <div class="summary-item">
                <span class="summary-label">Number of People:</span>
                <span class="summary-value">{{ formData.numberOfPeople }} persons</span>
              </div>
              <div v-if="formData.specialRequests" class="summary-item">
                <span class="summary-label">Special Requests:</span>
                <span class="summary-value">{{ formData.specialRequests }}</span>
              </div>
            </div>

            <div class="summary-section">
              <h4 class="summary-title">Contact Information</h4>
              <div class="summary-item">
                <span class="summary-label">Name:</span>
                <span class="summary-value">{{ formData.contactInfo.firstName }} {{ formData.contactInfo.lastName }}</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Email:</span>
                <span class="summary-value">{{ formData.contactInfo.email }}</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Phone:</span>
                <span class="summary-value">{{ formData.contactInfo.phone }}</span>
              </div>
              <div v-if="formData.contactInfo.address" class="summary-item">
                <span class="summary-label">Address:</span>
                <span class="summary-value">{{ formData.contactInfo.address }}</span>
              </div>
            </div>

            <div class="summary-section">
              <h4 class="summary-title">Emergency Contact</h4>
              <div class="summary-item">
                <span class="summary-label">Name:</span>
                <span class="summary-value">{{ formData.emergencyContact.name }}</span>
              </div>
              <div class="summary-item">
                <span class="summary-label">Phone:</span>
                <span class="summary-value">{{ formData.emergencyContact.phone }}</span>
              </div>
              <div v-if="formData.emergencyContact.relationship" class="summary-item">
                <span class="summary-label">Relationship:</span>
                <span class="summary-value">{{ formData.emergencyContact.relationship }}</span>
              </div>
            </div>

            <div class="total-section">
              <div class="total-item">
                <span class="total-label">Total Amount:</span>
                <span class="total-value">₱{{ totalAmount.toLocaleString() }}</span>
              </div>
            </div>
          </div>

          <!-- Terms and Conditions -->
          <div class="terms-section">
            <label class="checkbox-label">
              <input
                type="checkbox"
                v-model="formData.agreeToTerms"
                class="checkbox-input"
                required
              />
              <span class="checkmark"></span>
              I agree to the <a href="#" class="terms-link">Terms and Conditions</a> and 
              <a href="#" class="terms-link">Cancellation Policy</a> *
            </label>
          </div>
        </div>
      </div>

      <div class="step-actions">
        <button class="btn btn-secondary" @click="prevStep">← Back</button>
        <button 
          class="btn btn-primary" 
          @click="submitReservation" 
          :disabled="!formData.agreeToTerms || isSubmitting"
        >
          <span v-if="isSubmitting" class="loading-spinner"></span>
          {{ isSubmitting ? 'Processing...' : `Confirm Reservation` }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  agency: Object,
  package: Object
});

const emit = defineEmits(['close', 'reservation-completed']);

// Form state
const currentStep = ref(1);
const isSubmitting = ref(false);

// Form data
const formData = ref({
  numberOfPeople: 1,
  specialRequests: '',
  contactInfo: {
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    address: ''
  },
  emergencyContact: {
    name: '',
    phone: '',
    relationship: ''
  },
  agreeToTerms: false
});

// Computed properties
const selectedPackage = computed(() => props.package);
const totalAmount = computed(() => {
  return (selectedPackage.value?.price || 0) * (formData.value.numberOfPeople || 0);
});

const availableSlots = computed(() => {
  const maxSlots = Math.min(selectedPackage.value?.availableSlots || 0, selectedPackage.value?.capacity || 0);
  return Array.from({ length: maxSlots }, (_, i) => i + 1);
});

const canProceedToStep2 = computed(() => {
  return formData.value.numberOfPeople > 0;
});

const canProceedToStep3 = computed(() => {
  const contact = formData.value.contactInfo;
  return contact.firstName && contact.lastName && contact.email && contact.phone &&
         formData.value.emergencyContact.name && formData.value.emergencyContact.phone;
});

// Methods
const nextStep = () => {
  if (currentStep.value < 3) {
    currentStep.value++;
  }
};

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--;
  }
};

const submitReservation = async () => {
  isSubmitting.value = true;
  
  try {
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000));
    
    // Generate reservation ID
    const reservationId = 'RES' + Date.now().toString().slice(-6);
    
    // Prepare reservation data
    const reservationData = {
      reservationId,
      package: selectedPackage.value,
      agency: props.agency,
      formData: formData.value,
      totalAmount: totalAmount.value,
      reservationDate: new Date().toISOString()
    };
    
    console.log('Reservation submitted:', reservationData);
    
    // Emit completion event
    emit('reservation-completed', reservationData);
    
  } catch (error) {
    console.error('Reservation failed:', error);
    alert('Reservation failed. Please try again.');
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.reserve-package-modal {
  padding: 0;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 30px;
  border-bottom: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 20px 20px 0 0;
}

.modal-title {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
}

.close-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  font-size: 1.5rem;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.3s ease;
}

.close-btn:hover {
  background: rgba(255, 255, 255, 0.3);
}

/* Progress Steps */
.progress-steps {
  display: flex;
  justify-content: space-between;
  padding: 20px 30px;
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  flex: 1;
  position: relative;
}

.step:not(:last-child)::after {
  content: '';
  position: absolute;
  top: 15px;
  left: 60%;
  right: -40%;
  height: 2px;
  background: #e2e8f0;
  z-index: 1;
}

.step.completed:not(:last-child)::after {
  background: #10b981;
}

.step-number {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #e2e8f0;
  color: #64748b;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.9rem;
  position: relative;
  z-index: 2;
  transition: all 0.3s ease;
}

.step.active .step-number {
  background: #3b82f6;
  color: white;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.step.completed .step-number {
  background: #10b981;
  color: white;
}

.step-label {
  font-size: 0.7rem;
  color: #64748b;
  font-weight: 500;
  text-align: center;
}

.step.active .step-label {
  color: #3b82f6;
  font-weight: 600;
}

/* Form Steps */
.form-step {
  display: flex;
  flex-direction: column;
  min-height: 400px;
}

.step-content {
  padding: 30px;
  flex: 1;
  overflow-y: auto;
}

.step-actions {
  display: flex;
  justify-content: space-between;
  padding: 20px 30px;
  border-top: 1px solid #e2e8f0;
  background: #f8fafc;
  border-radius: 0 0 20px 20px;
}

/* Package Summary Card */
.package-summary-card {
  display: flex;
  gap: 15px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 10px;
  margin-bottom: 25px;
}

.package-image {
  width: 80px;
  height: 80px;
  border-radius: 8px;
  overflow: hidden;
  flex-shrink: 0;
}

.package-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.package-details {
  flex: 1;
}

.package-title {
  margin: 0 0 8px 0;
  font-size: 1.2rem;
  color: #2c3e50;
  font-weight: 600;
}

.package-description {
  margin: 0 0 12px 0;
  color: #6c7a89;
  font-size: 0.9rem;
  line-height: 1.4;
}

.package-meta {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #5d6d7e;
  font-size: 0.8rem;
}

.meta-icon {
  font-size: 1rem;
}

/* Form Elements */
.form-group {
  margin-bottom: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #374151;
  font-size: 0.9rem;
}

.form-input, .form-textarea {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
  font-family: inherit;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

/* Group Size Selector */
.group-size-selector {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 10px;
}

.size-option {
  padding: 12px 20px;
  border: 2px solid #e2e8f0;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.3s ease;
  min-width: 50px;
  text-align: center;
}

.size-option.active {
  border-color: #3b82f6;
  background: #3b82f6;
  color: white;
}

.size-option:hover:not(.active) {
  border-color: #3b82f6;
  color: #3b82f6;
}

.slots-info {
  font-size: 0.8rem;
  color: #64748b;
}

.available-info {
  color: #27ae60;
  font-weight: 600;
}

/* Total Amount Section */
.total-amount-section {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
}

.amount-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.amount-label {
  font-weight: 600;
  color: #374151;
}

.amount-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #059669;
}

/* Section Titles */
.section-title {
  color: #2c3e50;
  margin: 0 0 20px 0;
  font-size: 1.3rem;
  font-weight: 600;
}

.section-subtitle {
  color: #374151;
  margin: 0 0 15px 0;
  font-size: 1.1rem;
  font-weight: 600;
}

/* Emergency Contact Section */
.emergency-contact-section {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
}

/* Confirmation Styles */
.confirmation-content {
  text-align: center;
}

.confirmation-icon {
  font-size: 3rem;
  margin-bottom: 15px;
}

.confirmation-title {
  color: #2c3e50;
  margin: 0 0 8px 0;
  font-size: 1.5rem;
  font-weight: 600;
}

.confirmation-subtitle {
  color: #6c7a89;
  margin: 0 0 30px 0;
  font-size: 0.9rem;
}

.reservation-summary {
  text-align: left;
  margin-bottom: 25px;
}

.summary-section {
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 1px solid #e2e8f0;
}

.summary-title {
  color: #374151;
  margin: 0 0 12px 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.summary-label {
  color: #6c7a89;
  font-weight: 500;
  flex: 1;
}

.summary-value {
  color: #374151;
  font-weight: 600;
  flex: 2;
  text-align: right;
}

.total-section {
  background: #f8f9fa;
  padding: 20px;
  border-radius: 8px;
  margin-top: 20px;
}

.total-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total-label {
  font-size: 1.1rem;
  font-weight: 600;
  color: #374151;
}

.total-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #059669;
}

/* Terms and Conditions */
.terms-section {
  margin-top: 25px;
  padding-top: 20px;
  border-top: 1px solid #e2e8f0;
}

.checkbox-label {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  cursor: pointer;
  font-weight: normal;
  color: #374151;
  font-size: 0.9rem;
  line-height: 1.4;
}

.checkbox-input {
  display: none;
}

.checkmark {
  width: 20px;
  height: 20px;
  border: 2px solid #e2e8f0;
  border-radius: 4px;
  position: relative;
  flex-shrink: 0;
  transition: all 0.3s ease;
  margin-top: 2px;
}

.checkbox-input:checked + .checkmark {
  background: #3b82f6;
  border-color: #3b82f6;
}

.checkbox-input:checked + .checkmark::after {
  content: '✓';
  position: absolute;
  color: white;
  font-size: 14px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.terms-link {
  color: #3b82f6;
  text-decoration: none;
  font-weight: 600;
}

.terms-link:hover {
  text-decoration: underline;
}

/* Buttons */
.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
}

.btn-primary {
  background: #3b82f6;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #2563eb;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-primary:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-secondary {
  background: #e2e8f0;
  color: #374151;
}

.btn-secondary:hover {
  background: #cbd5e0;
}

.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid transparent;
  border-top: 2px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
  .modal-header {
    padding: 20px;
  }

  .progress-steps {
    padding: 15px 20px;
  }

  .step:not(:last-child)::after {
    display: none;
  }

  .step {
    flex: none;
    width: calc(33.333% - 10px);
  }

  .step-content {
    padding: 20px;
  }

  .package-summary-card {
    flex-direction: column;
    text-align: center;
  }

  .package-image {
    align-self: center;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .step-actions {
    flex-direction: column;
    gap: 10px;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }

  .summary-item {
    flex-direction: column;
    gap: 4px;
  }

  .summary-value {
    text-align: left;
  }
}

@media (max-width: 480px) {
  .reservation-modal-container {
    margin: 10px;
  }

  .group-size-selector {
    justify-content: center;
  }

  .size-option {
    flex: 1;
    min-width: 60px;
    text-align: center;
  }

  .modal-title {
    font-size: 1.3rem;
  }
}
</style>