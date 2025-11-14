<template>
    <div class="reserve-package-modal">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-8 py-6 border-b border-gray-200 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-t-3xl">
            <h2 class="text-2xl font-semibold m-0">Reserve {{ selectedPackage?.title }}</h2>
            <button
                @click="$emit('close')"
                class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-2xl transition-all"
            >
                ×
            </button>
        </div>

        <!-- ✅ Success Message -->
        <div v-if="reservationSuccess" class="flex flex-col items-center justify-center text-center min-h-[400px] p-10 bg-white rounded-b-3xl">
            <div class="text-6xl mb-4 text-emerald-500">✅</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Reservation Successful!</h3>
            <p class="text-gray-600 max-w-md">
                Your reservation has been submitted successfully. Please wait for the agency to confirm your booking.
            </p>
            <button
                @click="$emit('close')"
                class="mt-6 px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-all"
            >
                Close
            </button>
        </div>

        <!-- Steps & Content (shown when not success) -->
        <div v-else>
            <!-- Progress Steps -->
            <div class="flex justify-between px-8 py-5 bg-slate-50 border-b border-gray-200">
                <div
                    v-for="step in steps"
                    :key="step.id"
                    class="flex flex-col items-center flex-1 relative"
                    :class="{ 'active': currentStep === step.id, 'completed': currentStep > step.id }"
                >
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all z-10"
                        :class="{
            'bg-blue-500 text-white shadow-lg shadow-blue-500/30': currentStep === step.id,
            'bg-emerald-500 text-white': currentStep > step.id,
            'bg-gray-300 text-slate-500': currentStep < step.id
          }"
                    >
                        {{ step.id }}
                    </div>
                    <span
                        class="text-xs font-medium mt-2 transition-colors"
                        :class="{
            'text-blue-500 font-semibold': currentStep === step.id,
            'text-slate-500': currentStep !== step.id
          }"
                    >
          {{ step.label }}
        </span>
                    <div
                        v-if="step.id < 3"
                        class="absolute top-4 left-[60%] right-[-40%] h-0.5 bg-gray-300 z-0"
                        :class="{ 'bg-emerald-500': currentStep > step.id }"
                    ></div>
                </div>
            </div>

            <!-- Step 1: Group Details -->
            <div v-if="currentStep === 1" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <!-- Package Summary -->
                    <div class="flex gap-4 p-5 bg-gray-50 rounded-xl">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            <img
                                src="/public/images/logo/logo.png"
                                :alt="selectedPackage?.title"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 m-0">{{ selectedPackage?.title }}</h3>
                            <p class="text-sm text-gray-600 mt-1 mb-3 leading-relaxed">{{ selectedPackage?.shortDesc }}</p>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <span>💰</span>
                                    <span>₱{{ selectedPackage?.price?.toLocaleString() }} per person</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span>People</span>
                                    <span>Capacity: {{ selectedPackage?.capacity }} people</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Size -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Number of People *</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <button
                                v-for="num in availableSlots"
                                :key="num"
                                @click="formData.numberOfPeople = num"
                                type="button"
                                class="px-5 py-3 min-w-[50px] text-center font-semibold rounded-lg border-2 transition-all"
                                :class="[
                formData.numberOfPeople === num
                  ? 'border-blue-500 bg-blue-500 text-white'
                  : 'border-gray-300 bg-white text-gray-700 hover:border-blue-500 hover:text-blue-500'
              ]"
                            >
                                {{ num }}
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">
                            <span class="text-green-600 font-semibold">{{ selectedPackage?.availableSlots }} slots available</span>
                        </p>
                    </div>

                    <!-- Special Requests -->
                    <div>
                        <label for="specialRequests" class="block text-sm font-semibold text-gray-700 mb-2">Special Requests</label>
                        <textarea
                            id="specialRequests"
                            v-model="formData.specialRequests"
                            placeholder="Any dietary restrictions, accessibility needs, or special requirements..."
                            rows="3"
                            class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 resize-y min-h-[80px] font-inherit"
                        ></textarea>
                    </div>

                    <!-- Total Amount -->
                    <div class="bg-gray-50 p-5 rounded-lg mt-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total Amount:</span>
                            <span class="text-3xl font-bold text-emerald-600">₱{{ totalAmount.toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="$emit('close')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                        Cancel
                    </button>
                    <button
                        @click="nextStep"
                        :disabled="!canProceedToStep2"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all flex items-center gap-2"
                    >
                        Continue to Contact Info →
                    </button>
                </div>
            </div>

            <!-- Step 2: Contact Information -->
            <div v-if="currentStep === 2" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                            <input
                                id="firstName"
                                v-model="formData.contactInfo.firstName"
                                type="text"
                                required
                                placeholder="Enter your first name"
                                class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                            />
                        </div>
                        <div>
                            <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                            <input
                                id="lastName"
                                v-model="formData.contactInfo.lastName"
                                type="text"
                                required
                                placeholder="Enter your last name"
                                class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                            <input
                                id="email"
                                v-model="formData.contactInfo.email"
                                type="email"
                                required
                                placeholder="your.email@example.com"
                                class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                            />
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                            <input
                                id="phone"
                                v-model="formData.contactInfo.phone"
                                type="tel"
                                required
                                placeholder="0912 345 6789"
                                class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                            />
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                        <input
                            id="address"
                            v-model="formData.contactInfo.address"
                            type="text"
                            placeholder="Your complete address"
                            class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                        />
                    </div>

                    <!-- Emergency Contact -->
                    <div class="bg-gray-50 p-5 rounded-lg space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700">Emergency Contact</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="emergencyName" class="block text-sm font-semibold text-gray-700 mb-2">Contact Name *</label>
                                <input
                                    id="emergencyName"
                                    v-model="formData.emergencyContact.name"
                                    type="text"
                                    required
                                    placeholder="Full name of emergency contact"
                                    class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                                />
                            </div>
                            <div>
                                <label for="emergencyPhone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input
                                    id="emergencyPhone"
                                    v-model="formData.emergencyContact.phone"
                                    type="tel"
                                    required
                                    placeholder="0912 345 6789"
                                    class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                                />
                            </div>
                        </div>
                        <div>
                            <label for="emergencyRelationship" class="block text-sm font-semibold text-gray-700 mb-2">Relationship</label>
                            <input
                                id="emergencyRelationship"
                                v-model="formData.emergencyContact.relationship"
                                type="text"
                                placeholder="e.g., Spouse, Parent, Friend"
                                class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 font-inherit"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="prevStep" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center gap-2">
                        ← Back
                    </button>
                    <button
                        @click="nextStep"
                        :disabled="!canProceedToStep3"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all flex items-center gap-2"
                    >
                        Continue to Confirmation →
                    </button>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div v-if="currentStep === 3" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6 text-center">
                    <div class="text-5xl mb-4">Checkmark</div>
                    <h3 class="text-2xl font-semibold text-gray-800">Confirm Your Reservation</h3>
                    <p class="text-sm text-gray-600">Please review your reservation details before confirming</p>

                    <div class="text-left space-y-5">
                        <!-- Package Details -->
                        <div class="pb-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Package Details</h4>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Package:</span>
                                <span class="text-gray-800 font-semibold">{{ selectedPackage?.package_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 font-medium">Description:</span>
                                <span class="text-gray-800 font-semibold text-right">{{ selectedPackage?.description }}</span>
                            </div>
                        </div>

                        <!-- Group Info -->
                        <div class="pb-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Group Information</h4>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Number of People:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.numberOfPeople }} persons</span>
                            </div>
                            <div v-if="formData.specialRequests" class="flex justify-between">
                                <span class="text-gray-600 font-medium">Special Requests:</span>
                                <span class="text-gray-800 font-semibold text-right">{{ formData.specialRequests }}</span>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="pb-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Contact Information</h4>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Name:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.contactInfo.firstName }} {{ formData.contactInfo.lastName }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Email:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.contactInfo.email }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Phone:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.contactInfo.phone }}</span>
                            </div>
                            <div v-if="formData.contactInfo.address" class="flex justify-between">
                                <span class="text-gray-600 font-medium">Address:</span>
                                <span class="text-gray-800 font-semibold text-right">{{ formData.contactInfo.address }}</span>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="pb-4 border-b border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-700 mb-3">Emergency Contact</h4>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Name:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.emergencyContact.name }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 font-medium">Phone:</span>
                                <span class="text-gray-800 font-semibold">{{ formData.emergencyContact.phone }}</span>
                            </div>
                            <div v-if="formData.emergencyContact.relationship" class="flex justify-between">
                                <span class="text-gray-600 font-medium">Relationship:</span>
                                <span class="text-gray-800 font-semibold text-right">{{ formData.emergencyContact.relationship }}</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="bg-gray-50 p-5 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-700">Total Amount:</span>
                                <span class="text-3xl font-bold text-emerald-600">₱{{ totalAmount.toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="pt-6 border-t border-gray-200 mt-6">
                        <label class="flex items-start gap-3 cursor-pointer text-sm text-gray-700">
                            <input
                                type="checkbox"
                                v-model="formData.agreeToTerms"
                                required
                                class="hidden"
                            />
                            <span
                                class="w-5 h-5 border-2 border-gray-300 rounded flex-shrink-0 mt-0.5 transition-all relative"
                                :class="{ 'bg-blue-500 border-blue-500': formData.agreeToTerms }"
                            >
              <span
                  v-if="formData.agreeToTerms"
                  class="absolute inset-0 flex items-center justify-center text-white text-xs"
              >✓</span>
            </span>
                            I agree to the
                            <a href="#" class="text-blue-500 font-semibold hover:underline">Terms and Conditions</a> and
                            <a href="#" class="text-blue-500 font-semibold hover:underline">Cancellation Policy</a> *
                        </label>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="prevStep" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center gap-2">
                        ← Back
                    </button>
                    <button
                        @click="submitReservation"
                        :disabled="!formData.agreeToTerms || isSubmitting"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all flex items-center gap-2"
                    >
          <span
              v-if="isSubmitting"
              class="w-4 h-4 border-2 border-t-transparent border-white rounded-full animate-spin"
          ></span>
                        {{ isSubmitting ? 'Processing...' : 'Confirm Reservation' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed } from 'vue';
    import axios from "axios";

    const props = defineProps({
        agency: Object,
        package: Object
    });

    const emit = defineEmits(['close', 'reservation-completed']);

    const currentStep = ref(1);
    const isSubmitting = ref(false);
    const reservationSuccess = ref(false); // <-- NEW: tracks if reservation succeeded

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

    const steps = [
        { id: 1, label: 'Group Details' },
        { id: 2, label: 'Contact Info' },
        { id: 3, label: 'Confirmation' }
    ];

    const selectedPackage = computed(() => props.package);
    const totalAmount = computed(() => {
        return (selectedPackage.value?.price || 0) * (formData.value.numberOfPeople || 0);
    });

    const availableSlots = computed(() => {
        const maxSlots = Math.min(selectedPackage.value?.available_slot || 0, selectedPackage.value?.available_slot || 0);
        return Array.from({ length: maxSlots }, (_, i) => i + 1);
    });

    const canProceedToStep2 = computed(() => formData.value.numberOfPeople > 0);
    const canProceedToStep3 = computed(() => {
        const c = formData.value.contactInfo;
        const e = formData.value.emergencyContact;
        return c.firstName && c.lastName && c.email && c.phone && e.name && e.phone;
    });

    const nextStep = () => {
        if (currentStep.value < 3) currentStep.value++;
    };

    const prevStep = () => {
        if (currentStep.value > 1) currentStep.value--;
    };

    const submitReservation = async () => {
        isSubmitting.value = true;

        try {
            const response = await axios.post('/add-reservation', {
                user_id: null,
                package_id: selectedPackage.value.id,
                number_of_people: formData.value.numberOfPeople,
                special_req: formData.value.specialRequests,
                total_amount: totalAmount.value,
                full_name: `${formData.value.contactInfo.firstName} ${formData.value.contactInfo.lastName}`,
                email: formData.value.contactInfo.email,
                phone_number: formData.value.contactInfo.phone,
                complete_address: formData.value.contactInfo.address,
                e_full_name: formData.value.emergencyContact.name,
                e_contact: formData.value.emergencyContact.phone,
                e_relationship: formData.value.emergencyContact.relationship,
                status: 'pending'
            });

            if (response.data.success) {
                reservationSuccess.value = true;
                emit('spots');

                // ✅ Show a pop-up alert
                alert('🎉 Your reservation was successful! Thank you for booking.');
            }
        } catch (error) {
            console.error(error.response?.data || error);
        } finally {
            isSubmitting.value = false;
        }
    };
</script>
