<template>
    <div class="reserve-package-modal">
        <div class="flex justify-between items-center px-8 py-6 border-b border-gray-200 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-t-3xl">
            <h2 class="text-2xl font-semibold m-0">Reserve {{ selectedPackage?.title }}</h2>
            <button
                @click="$emit('close')"
                class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-2xl transition-all"
            >
                ×
            </button>
        </div>

        <div v-if="reservationSuccess" class="flex flex-col items-center justify-center text-center min-h-[400px] p-10 bg-white rounded-b-3xl">
            <div class="text-6xl mb-4 text-emerald-500">✅</div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Reservation Submitted!</h3>
            <p class="text-gray-600 max-w-md">
                Your reservation and payment proof have been submitted. Please wait for the agency to verify your payment and confirm your booking.
            </p>
            <button
                @click="$emit('close')"
                class="mt-6 px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-all"
            >
                Close
            </button>
        </div>

        <div v-else>
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
                        v-if="step.id < 4"
                        class="absolute top-4 left-[60%] right-[-40%] h-0.5 bg-gray-300 z-0"
                        :class="{ 'bg-emerald-500': currentStep > step.id }"
                    ></div>
                </div>
            </div>

            <div v-if="currentStep === 1" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <div class="flex gap-4 p-5 bg-gray-50 rounded-xl">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            <img
                                src="/public/images/logo/logo.png"
                                :alt="selectedPackage?.title"
                                class="w-full h-full object-cover"
                            />
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 m-0">{{ selectedPackage?.package_name }}</h3>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mt-2">
                                <div class="flex items-center gap-1.5">
                                    <span>💰</span>
                                    <span>₱{{ selectedPackage?.price?.toLocaleString() }} / pax</span>
                                </div>
                            </div>
                        </div>
                    </div>

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
                            <span class="text-green-600 font-semibold">{{ selectedPackage?.available_slot }} slots available</span>
                        </p>
                    </div>

                    <div>
                        <label for="specialRequests" class="block text-sm font-semibold text-gray-700 mb-2">Special Requests</label>
                        <textarea
                            id="specialRequests"
                            v-model="formData.specialRequests"
                            placeholder="Any dietary restrictions, accessibility needs..."
                            rows="3"
                            class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-blue-500"
                        ></textarea>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-lg mt-6">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total Amount:</span>
                            <span class="text-3xl font-bold text-emerald-600">₱{{ totalAmount.toLocaleString() }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="$emit('close')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                        Cancel
                    </button>
                    <button
                        @click="nextStep"
                        :disabled="!canProceedToStep2"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        Continue to Contact Info →
                    </button>
                </div>
            </div>

            <div v-if="currentStep === 2" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800">Contact Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name *</label>
                            <input v-model="formData.contactInfo.firstName" type="text" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name *</label>
                            <input v-model="formData.contactInfo.lastName" type="text" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address *</label>
                            <input v-model="formData.contactInfo.email" type="email" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                            <input v-model="formData.contactInfo.phone" type="tel" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                        <input v-model="formData.contactInfo.address" type="text" class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                    </div>

                    <div class="bg-gray-50 p-5 rounded-lg space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700">Emergency Contact</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Name *</label>
                                <input v-model="formData.emergencyContact.name" type="text" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                                <input v-model="formData.emergencyContact.phone" type="tel" required class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Relationship</label>
                            <input v-model="formData.emergencyContact.relationship" type="text" class="w-full p-4 border-2 border-gray-300 rounded-lg text-sm" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="prevStep" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                        ← Back
                    </button>
                    <button
                        @click="nextStep"
                        :disabled="!canProceedToStep3"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        Continue to Review →
                    </button>
                </div>
            </div>

            <div v-if="currentStep === 3" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800">Review Reservation</h3>
                    <p class="text-sm text-gray-600">Please review your details before proceeding to payment.</p>

                    <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4 shadow-sm">
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Package</span>
                            <span class="font-bold text-gray-800">{{ selectedPackage?.package_name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Number of People</span>
                            <span class="font-bold text-gray-800">{{ formData.numberOfPeople }}</span>
                        </div>
                         <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Date</span>
                            <span class="font-bold text-gray-800">{{ selectedPackage?.start_date }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Contact Name</span>
                            <span class="font-bold text-gray-800">{{ formData.contactInfo.firstName }} {{ formData.contactInfo.lastName }}</span>
                        </div>
                         <div class="flex justify-between border-b pb-2">
                            <span class="text-gray-500">Contact Phone</span>
                            <span class="font-bold text-gray-800">{{ formData.contactInfo.phone }}</span>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-xl flex justify-between items-center">
                        <span class="text-blue-900 font-medium">Total to Pay:</span>
                        <span class="text-2xl font-bold text-blue-700">₱{{ totalAmount.toLocaleString() }}</span>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="prevStep" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                        ← Back
                    </button>
                    <button
                        @click="nextStep"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600"
                    >
                        Proceed to Payment →
                    </button>
                </div>
            </div>

            <div v-if="currentStep === 4" class="flex flex-col min-h-[400px]">
                <div class="flex-1 overflow-y-auto p-8 space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800">Secure Payment</h3>
                    
                    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
                         <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Amount:</span>
                            <span class="font-medium">₱{{ totalAmount.toLocaleString() }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-indigo-200">
                            <span class="text-indigo-900 font-bold">Reservation Fee (50%):</span>
                            <span class="text-xl font-bold text-indigo-700">₱{{ reservationFee.toLocaleString() }}</span>
                        </div>
                         <p class="text-xs text-indigo-500 mt-2">* Please pay the reservation fee to secure your slot.</p>
                    </div>

                    <div class="bg-blue-500 text-white rounded-xl p-6 shadow-lg relative overflow-hidden">
                        <div class="absolute top-[-20px] right-[-20px] w-24 h-24 bg-white/20 rounded-full"></div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-white text-blue-600 font-bold px-3 py-1 rounded">GCash</div>
                            <span class="font-medium">Scan or Send Money</span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm opacity-90">Account Name:</p>
                            <p class="text-lg font-bold tracking-wide">SUROY SURIGAO ADMIN</p>
                            <p class="text-sm opacity-90 mt-2">Account Number:</p>
                            <p class="text-2xl font-bold tracking-widest">0912 345 6789</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Payment Screenshot *</label>
                        <div 
                            class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition-colors cursor-pointer relative"
                            @dragover.prevent
                            @drop.prevent="handleFileDrop"
                        >
                            <input 
                                type="file" 
                                ref="fileInput"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="handleFileChange"
                                required
                            />
                            
                            <div v-if="previewImage" class="relative">
                                <img :src="previewImage" class="max-h-40 mx-auto rounded-lg shadow-sm" />
                                <p class="text-sm text-green-600 mt-2 font-medium">Image uploaded successfully!</p>
                                <p class="text-xs text-gray-400">{{ formData.paymentFile?.name }}</p>
                            </div>
                            
                            <div v-else>
                                <span class="material-icons text-4xl text-gray-300">cloud_upload</span>
                                <p class="text-sm text-gray-600 font-medium mt-2">Click to upload screenshot</p>
                                <p class="text-xs text-gray-400">JPG, PNG allowed</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
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
                                <span v-if="formData.agreeToTerms" class="absolute inset-0 flex items-center justify-center text-white text-xs">✓</span>
                            </span>
                            I agree to the <span class="text-blue-500">Terms & Conditions</span> and verify that the payment proof is authentic.
                        </label>
                    </div>
                </div>

                <div class="flex justify-between p-5 bg-slate-50 border-t border-gray-200 rounded-b-3xl">
                    <button @click="prevStep" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                        ← Back
                    </button>
                    <button
                        @click="submitReservation"
                        :disabled="!formData.agreeToTerms || !formData.paymentFile || isSubmitting"
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all flex items-center gap-2"
                    >
                        <span v-if="isSubmitting" class="w-4 h-4 border-2 border-t-transparent border-white rounded-full animate-spin"></span>
                        {{ isSubmitting ? 'Verifying...' : 'Submit Payment' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, computed, watch } from 'vue';
    import axios from "axios";

    const props = defineProps({
        agency: Object,
        package: Object,
        userInformation: Object,
    });

    const emit = defineEmits(['close', 'reservation-completed', 'spots']);

    const currentStep = ref(1);
    const isSubmitting = ref(false);
    const reservationSuccess = ref(false);
    const previewImage = ref(null);

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
        paymentFile: null, // Store the file object here
        agreeToTerms: false
    });

    const steps = [
        { id: 1, label: 'Details' },
        { id: 2, label: 'Contact' },
        { id: 3, label: 'Review' },
        { id: 4, label: 'Payment' }
    ];

    const selectedPackage = computed(() => props.package);
    
    const totalAmount = computed(() => {
        return (selectedPackage.value?.price || 0) * (formData.value.numberOfPeople || 0);
    });

    // RESERVATION FEE LOGIC: Currently set to 50%. Change 0.5 to 1 for full payment, or fixed amount.
    const reservationFee = computed(() => {
        return totalAmount.value * 0.5; 
    });

    const availableSlots = computed(() => {
        const maxSlots = selectedPackage.value?.available_slot || 0;
        // Limit dropdown to 10 or max slots to avoid huge lists
        const limit = Math.min(maxSlots, 20); 
        return Array.from({ length: limit }, (_, i) => i + 1);
    });

    const canProceedToStep2 = computed(() => formData.value.numberOfPeople > 0);
    const canProceedToStep3 = computed(() => {
        const c = formData.value.contactInfo;
        const e = formData.value.emergencyContact;
        return c.firstName && c.lastName && c.email && c.phone && e.name && e.phone;
    });

    const nextStep = () => {
        if (currentStep.value < 4) currentStep.value++;
    };

    const prevStep = () => {
        if (currentStep.value > 1) currentStep.value--;
    };

    // Handle File Selection
    const handleFileChange = (event) => {
        const file = event.target.files[0];
        if (file) {
            formData.value.paymentFile = file;
            previewImage.value = URL.createObjectURL(file);
        }
    };

    // Handle File Drop
    const handleFileDrop = (event) => {
        const file = event.dataTransfer.files[0];
        if (file) {
             formData.value.paymentFile = file;
             previewImage.value = URL.createObjectURL(file);
        }
    };

    const submitReservation = async () => {
        isSubmitting.value = true;

        // Use FormData to send Files + Text
        const submitData = new FormData();
        
        submitData.append('user_id', props.userInformation?.id || ''); // Assuming userInformation has ID
        submitData.append('package_id', selectedPackage.value.id);
        submitData.append('number_of_people', formData.value.numberOfPeople);
        submitData.append('special_req', formData.value.specialRequests);
        submitData.append('total_amount', totalAmount.value);
        submitData.append('reservation_fee', reservationFee.value); // Sending the fee amount too
        submitData.append('full_name', `${formData.value.contactInfo.firstName} ${formData.value.contactInfo.lastName}`);
        submitData.append('email', formData.value.contactInfo.email);
        submitData.append('phone_number', formData.value.contactInfo.phone);
        submitData.append('complete_address', formData.value.contactInfo.address);
        submitData.append('e_full_name', formData.value.emergencyContact.name);
        submitData.append('e_contact', formData.value.emergencyContact.phone);
        submitData.append('e_relationship', formData.value.emergencyContact.relationship);
        submitData.append('status', 'pending');
        
        // Append the Image
        if (formData.value.paymentFile) {
            submitData.append('payment_screenshot', formData.value.paymentFile);
        }

        try {
            // NOTE: Ensure your axios headers are set to multipart/form-data implicitly by sending FormData
            const response = await axios.post('/add-reservation', submitData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            if (response.data.success) {
                reservationSuccess.value = true;
                emit('spots');
            }
        } catch (error) {
            console.error("Submission Error:", error.response?.data || error);
            alert("Something went wrong. Please check your inputs.");
        } finally {
            isSubmitting.value = false;
        }
    };

    // Auto-fill logic when userInformation loads
    watch(
        () => props.userInformation,
        (newVal) => {
            if (newVal?.user_info) {
                formData.value.contactInfo.firstName = newVal.user_info.firstName || "";
                formData.value.contactInfo.lastName = newVal.user_info.lastName || "";
                formData.value.contactInfo.email = newVal.email || ""; // Email is usually on the main user object
                formData.value.contactInfo.phone = newVal.user_info.phone_num || "";
                formData.value.contactInfo.address = newVal.user_info.address || ""; // If address exists
            }
        },
        { immediate: true, deep: true }
    );
</script>