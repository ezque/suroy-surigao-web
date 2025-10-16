<template>
      <div class="add-package-wrapper">
            <div class="add-package-container">
                  <h2 class="form-title">Add New Tour Package</h2>

                  <form class="package-form" @submit.prevent="addPackage">
                        <!-- Package Details -->
                        <div class="form-section">
                          <h3>📦 Package Details</h3>
                          <div class="form-grid">
                            <label>
                              Package Name
                              <input type="text" placeholder="Enter package name" v-model="packageData.name" required />
                            </label>

                            <label>
                              Short Description
                              <input type="text" placeholder="Enter short description" v-model="packageData.shortDesc" />
                            </label>

                            <label>
                              ₱ Price Per Head
                              <input type="number" placeholder="e.g., 1500" v-model="packageData.pricePerHead" min="0" />
                            </label>

                            <label>
                              👥 Capacity
                              <input type="number" placeholder="e.g., 20 people" v-model="packageData.capacity" min="1" />
                            </label>

                            <label>
                              🎟 Available Slot
                              <input type="number" placeholder="e.g., 10" v-model="packageData.availableSlot" min="1" />
                            </label>
                          </div>
                        </div>

                        <!-- Tour Schedule -->
                        <div class="form-section">
                          <h3>🗓️ Tour Schedule</h3>
                          <div class="form-grid">
                            <label>
                              Start Date
                              <input type="date" v-model="packageData.startDate" />
                            </label>
                            <label>
                              Start Time
                              <input type="time" v-model="packageData.startTime" />
                            </label>
                            <label>
                              End Date
                              <input type="date" v-model="packageData.endDate" />
                            </label>
                            <label>
                              End Time
                              <input type="time" v-model="packageData.endTime" />
                            </label>
                          </div>
                        </div>

                        <!-- Tour Information -->
                        <div class="form-section">
                              <h3>🧭 Tour Information</h3>
                              <div class="form-grid">
                                    <label>
                                        Pick-up Point
                                        <input type="text" placeholder="Enter pick-up location" v-model="packageData.pickupPoint" />
                                    </label>

                                    <div class="select-destination-container">
                                          <label>Tour Destinations</label>
                                                <select v-model="selectedSpot" @change="addDestination" class="select-spot">
                                                    <option disabled value="">Select a spot</option>
                                                    <option v-for="spot in allSpots" :key="spot.id" :value="spot.id">
                                                        {{ spot.spot_name }}
                                                    </option>
                                                </select>

                                                <div v-if="packageData.destinations.length" class="selected-spots">
                                                    <span v-for="(spot, index) in packageData.destinations" :key="index" class="spot-chip">
                                                        {{ getSpotName(spot) }}
                                                        <button type="button" @click="removeDestination(index)" class="remove-btn">×</button>
                                                    </span>
                                                </div>
                                    </div>

                                    <label class="textarea-label">
                                        Inclusions
                                        <textarea placeholder="List all inclusions" v-model="packageData.inclusions"></textarea>
                                    </label>

                                    <label class="textarea-label">
                                        Exclusions
                                        <textarea placeholder="List all exclusions" v-model="packageData.exclusions"></textarea>
                                    </label>
                              </div>
                        </div>

                        <div class="button-container">
                            <button type="submit" class="add-btn">Add Package</button>
                        </div>
                  </form>
            </div>
      </div>
</template>

<script setup>
    import { ref } from 'vue';

    const props = defineProps({
        allSpots: Array,
    });

    const selectedSpot = ref('');

    const packageData = ref({
        name: '',
        shortDesc: '',
        pricePerHead: 0,
        capacity: 0,
        availableSlot: 0,
        startDate: '',
        startTime: '',
        endDate: '',
        endTime: '',
        pickupPoint: '',
        destinations: [],
        inclusions: '',
        exclusions: ''
    });

    const addDestination = () => {
        if (selectedSpot.value && !packageData.value.destinations.includes(selectedSpot.value)) {
            packageData.value.destinations.push(selectedSpot.value); // push id
            selectedSpot.value = '';
        }
    };

    const removeDestination = (index) => {
        packageData.value.destinations.splice(index, 1);
    };

    // Helper to display the spot_name by id
    const getSpotName = (id) => {
        const spot = props.allSpots.find(s => s.id === id);
        return spot ? spot.spot_name : 'Unknown';
    };

    const addPackage = () => {
        console.log('Adding package:', packageData.value);
        alert('Package successfully added!');
        // Future backend call goes here
    };
</script>


<style scoped>
/* Scrollable wrapper */
.add-package-wrapper {
  height: 100%;
  overflow-y: auto;
  padding: 20px;
  background-color: #f9fafb;
}

/* Main container */
.add-package-container {
  width: 100%;
  max-width: 750px;
  margin: 0 auto;
  padding: 25px 30px;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  font-family: 'Poppins', sans-serif;
}

.form-title {
  text-align: center;
  margin-bottom: 25px;
  font-size: 1.6rem;
  font-weight: 600;
  color: #2b2b2b;
}

.form-section {
  margin-bottom: 25px;
}

.form-section h3 {
  margin-bottom: 10px;
  font-size: 1.1rem;
  color: #333;
  border-left: 4px solid #0077ff;
  padding-left: 10px;
}

/* Form layout */
.form-grid {
  display: grid;
  gap: 12px;
  grid-template-columns: 1fr 1fr;
}

label {
  display: flex;
  flex-direction: column;
  font-weight: 500;
  color: #444;
  font-size: 0.9rem;
}

input, select, textarea {
  width: 100%;
  padding: 10px 12px;
  margin-top: 4px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.2s ease;
}

textarea {
  resize: vertical;
  min-height: 70px;
}

input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: #0077ff;
  box-shadow: 0 0 4px rgba(0, 119, 255, 0.3);
}

/* Destinations section */
.select-destination-container {
  grid-column: span 2;
}

.selected-spots {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 8px;
}

.spot-chip {
  background-color: #0077ff;
  color: #fff;
  padding: 5px 10px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.9rem;
}

.remove-btn {
  background: none;
  border: none;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
}

/* Button */
.button-container {
  text-align: center;
  margin-top: 20px;
}

.add-btn {
  background-color: #0077ff;
  color: #fff;
  border: none;
  padding: 10px 22px;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

.add-btn:hover {
  background-color: #005fcc;
}

/* Responsive */
@media (max-width: 600px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>
