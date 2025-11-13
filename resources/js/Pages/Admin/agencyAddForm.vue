<template>
  <div class="add-agency-body">
    <div class="header-section">
      <h2>Add New Agency</h2>
      <button class="back-btn" @click="goBack">
        <i class="material-icons">arrow_back</i> Back to Manage Agencies
      </button>
    </div>

    <div class="form-container">
      <div v-if="submitted" class="success-message">
        <i class="material-icons">check_circle</i>
        <span>Agency information saved successfully!</span>
      </div>

      <form @submit.prevent="store">
        <div class="form-group">
          <label for="agencyName">
            Agency Name <span class="required">*</span>
          </label>
          <input
            type="text"
            id="agencyName"
            v-model="form.agency_name"
            placeholder="Enter agency name"
            required
          />
          <div class="error-message" v-if="errors.agency_name">
            {{ errors.agency_name }}
          </div>
        </div>

        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            placeholder="Describe your agency"
            rows="4"
          ></textarea>
        </div>

        <div class="form-group">
          <label for="contact_number">
            Contact Number <span class="required">*</span>
          </label>
          <input
            type="text"
            id="contact_number"
            v-model="form.contact_number"
            placeholder="Enter contact number"
            required
          />
          <div class="error-message" v-if="errors.contact_number">
            {{ errors.contact_number }}
          </div>
        </div>

        <div class="form-group">
          <label for="location">Location Address</label>
          <input
            type="text"
            id="location"
            v-model="form.location_address"
            placeholder="Enter location address"
          />
        </div>

        <div class="form-actions">
          <button type="submit" class="submit-btn">
            <i class="material-icons">save</i> Save Agency
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import axios from "axios";

const emit = defineEmits(['selectPage']);

const form = reactive({
  agency_name: "",
  description: "",
  contact_number: "",
  location_address: "",
});

const errors = reactive({});
const submitted = ref(false);
const goBack = () => emit("back");
const validateForm = () => {
  errors.agency_name = form.agency_name ? "" : "Agency name is required.";
  errors.contact_number = form.contact_number ? "" : "Contact information is required.";
  return !errors.agency_name && !errors.contact_number;
};

const store = async () => {
  if (!validateForm()) {
    return;
  }

  try {
    const formData = new FormData();

    formData.append('agency_name', form.agency_name);
    formData.append('description', form.description);
    formData.append('contact_number', form.contact_number);
    formData.append('location_address', form.location_address);

    const response = await axios.post('/add-agency', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    submitted.value = true;

    // Reset form
    form.agency_name = "";
    form.description = "";
    form.contact_number = "";
    form.location_address = "";

    // Hide success message after 3 seconds
    setTimeout(() => {
      submitted.value = false;
      emit('selectPage', 'agency');
    }, 2000);
  } catch (error) {
    console.error(error);
    if (error.response && error.response.data && error.response.data.errors) {
      Object.assign(errors, error.response.data.errors);
    }
    alert('Failed to submit Agency.');
  }
};
</script>

<style scoped>
.add-agency-body {
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, #E8F4F8, #FFFFFF);
  padding: 20px;
  overflow-y: auto;
}

/* ===== HEADER SECTION ===== */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header-section h2 {
  color: #004C5E;
  font-weight: 700;
}

.back-btn {
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  text-decoration: none;
  transition: 0.2s;
}

.back-btn:hover {
  background-color: #5a6268;
}

/* ===== FORM ===== */
.form-container {
  max-width: 600px;
  margin: 0 auto;
  background: white;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.success-message {
  background-color: #d4edda;
  border: 1px solid #c3e6cb;
  color: #155724;
  padding: 12px 15px;
  border-radius: 6px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
}

.success-message i {
  font-size: 24px;
  color: #28a745;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  color: #004C5E;
  font-weight: 500;
  margin-bottom: 5px;
  font-size: 14px;
}

.required {
  color: #E74C3C;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 16px;
  transition: border-color 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #007A8C;
  box-shadow: 0 0 0 3px rgba(0, 122, 140, 0.1);
}

.form-group textarea {
  resize: vertical;
  font-family: inherit;
}

.error-message {
  color: #E74C3C;
  font-size: 13px;
  margin-top: 5px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.error-message::before {
  content: "⚠";
  font-size: 14px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

.submit-btn {
  background-color: #007A8C;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  font-size: 16px;
  transition: 0.2s;
}

.submit-btn:hover {
  background-color: #009FB7;
}

.submit-btn i {
  font-size: 20px;
}
</style>
