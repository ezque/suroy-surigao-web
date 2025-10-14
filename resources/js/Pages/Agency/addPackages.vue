<template>
  <div class="add-package-page">
    <!-- HEADER -->
    <div class="header-section">
      <h2>Add New Tour Package</h2>
      <button @click="cancel" class="back-btn">
        <i class="material-icons">arrow_back</i> Back to Manage Packages
      </button>
    </div>

    <!-- MAIN FORM -->
    <form class="package-form" @submit.prevent="handleSubmit">
      <!-- PACKAGE DETAILS -->
      <section class="form-section">
        <h3>📦 Package Details</h3>
        <div class="form-grid">
          <label>
            Package Name <span class="required">*</span>
            <input type="text" v-model="package_name" placeholder="Enter package name" required />
            <div class="error-message" v-if="errors.package_name">{{ errors.package_name }}</div>
          </label>

          <label>
            Short Description
            <input type="text" v-model="shortDesc" placeholder="Enter short description" />
          </label>

          <label>
            ₱ Price Per Head <span class="required">*</span>
            <input type="number" v-model="price" min="0" placeholder="e.g., 1500" />
            <div class="error-message" v-if="errors.price">{{ errors.price }}</div>
          </label>

          <label>
            👥 Capacity <span class="required">*</span>
            <input type="number" v-model="capacity" min="1" placeholder="e.g., 20 people" />
            <div class="error-message" v-if="errors.capacity">{{ errors.capacity }}</div>
          </label>

          <label>
            🎟 Available Slot
            <input type="number" v-model="available_On" min="1" placeholder="e.g., 10" />
            <div class="error-message" v-if="errors.available_On">{{ errors.available_On }}</div>
          </label>
        </div>
      </section>

      <!-- TOUR SCHEDULE -->
      <section class="form-section">
        <h3>🗓️ Tour Schedule</h3>
        <div class="form-grid">
          <label>
            Start Date
            <input type="date" v-model="startDate" />
          </label>
          <label>
            Start Time
            <input type="time" v-model="startTime" />
          </label>
          <label>
            End Date
            <input type="date" v-model="endDate" />
          </label>
          <label>
            End Time
            <input type="time" v-model="endTime" />
          </label>
        </div>
      </section>

      <!-- TOUR INFORMATION -->
      <section class="form-section">
        <h3>🧭 Tour Information</h3>
        <div class="form-grid">
          <label>
            Pick-up Point
            <input type="text" v-model="pickupPoint" placeholder="Enter pick-up location" />
          </label>

          <div class="select-destination-container">
            <label>Tour Destinations</label>
            <select v-model="selectedSpot" @change="addDestination">
              <option disabled value="">Select a spot</option>
              <option v-for="spot in availableSpots" :key="spot.id" :value="spot.name">{{ spot.name }}</option>
            </select>

            <div v-if="destinations.length" class="selected-spots">
              <span v-for="(spot, index) in destinations" :key="index" class="spot-chip">
                {{ spot }}
                <button type="button" class="remove-btn" @click="removeDestination(index)">×</button>
              </span>
            </div>
          </div>

          <label class="textarea-label">
            Inclusions
            <textarea v-model="inclusions" placeholder="List all inclusions"></textarea>
          </label>

          <label class="textarea-label">
            Exclusions
            <textarea v-model="exclusions" placeholder="List all exclusions"></textarea>
          </label>
        </div>
      </section>

      <!-- ACTION BUTTONS -->
      <div class="btn-group">
        <button type="button" class="btn btn-outline" @click="cancel">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Package</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import axios from "axios";

const package_name = ref("");
const shortDesc = ref("");
const price = ref("");
const capacity = ref("");
const available_On = ref("");
const startDate = ref("");
const startTime = ref("");
const endDate = ref("");
const endTime = ref("");
const pickupPoint = ref("");
const destinations = ref([]);
const inclusions = ref("");
const exclusions = ref("");

const selectedSpot = ref("");
const availableSpots = ref([
  { id: 1, name: "Tinuy-an Falls" },
  { id: 2, name: "Enchanted River" },
  { id: 3, name: "Britania Islands" },
  { id: 4, name: "Siargao Surfing Spot" },
]);

const errors = reactive({});
const emit = defineEmits(["cancel"]);

const addDestination = () => {
  if (selectedSpot.value && !destinations.value.includes(selectedSpot.value)) {
    destinations.value.push(selectedSpot.value);
    selectedSpot.value = "";
  }
};

const removeDestination = (index) => {
  destinations.value.splice(index, 1);
};

const handleSubmit = async () => {
  try {
    const payload = {
      package_name: package_name.value,
      shortDesc: shortDesc.value,
      price: price.value,
      capacity: capacity.value,
      available_On: available_On.value,
      startDate: startDate.value,
      startTime: startTime.value,
      endDate: endDate.value,
      endTime: endTime.value,
      pickupPoint: pickupPoint.value,
      destinations: destinations.value,
      inclusions: inclusions.value,
      exclusions: exclusions.value,
    };

    await axios.post("/add-package", payload);
    alert("Package added successfully!");
  } catch (error) {
    console.error(error);
    alert("Failed to submit package.");
  }
};

const cancel = () => {
  emit("cancel");
};
</script>

<style scoped>
.add-package-page {
  width: 100%;
  min-height: 100vh;
  background: linear-gradient(to bottom right, #e8f4f8, #ffffff);
  padding: 40px 60px;
  box-sizing: border-box;
  display: flex;
  flex-direction: column;
}

/* HEADER SECTION */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #3498db, #2c3e50);
  color: white;
  padding: 20px 30px;
  border-radius: 10px;
  margin-bottom: 30px;
}

.header-section h2 {
  font-size: 1.6rem;
  font-weight: 700;
}

.back-btn {
  background-color: #ffffff33;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.3s;
}

.back-btn:hover {
  background-color: #ffffff55;
}

/* FORM */
.package-form {
  flex: 1;
  width: 100%;
  background-color: white;
  border-radius: 12px;
  padding: 30px 40px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.form-section {
  margin-bottom: 35px;
}

.form-section h3 {
  color: #2c3e50;
  font-size: 1.2rem;
  margin-bottom: 12px;
  border-left: 5px solid #3498db;
  padding-left: 10px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px 20px;
}

label {
  display: flex;
  flex-direction: column;
  font-weight: 500;
  color: #333;
}

input,
select,
textarea {
  margin-top: 6px;
  padding: 10px 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 0.95rem;
}

textarea {
  resize: vertical;
  min-height: 80px;
}

input:focus,
select:focus,
textarea:focus {
  border-color: #3498db;
  outline: none;
}

/* Destinations */
.selected-spots {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 8px;
}

.spot-chip {
  background-color: #3498db;
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.85rem;
}

.remove-btn {
  background: none;
  border: none;
  color: #fff;
  font-weight: bold;
  cursor: pointer;
}

/* Buttons */
.btn-group {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 25px;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
}

.btn-primary {
  background: linear-gradient(90deg, #3498db, #2c3e50);
  color: white;
}

.btn-outline {
  background: transparent;
  border: 2px solid #95a5a6;
  color: #7f8c8d;
}

.required {
  color: #e74c3c;
}
</style>
