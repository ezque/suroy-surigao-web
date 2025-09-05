<template>
  <div class="agency-add-form-body">
    <div class="form-header">
      <h2>Agency Information</h2>
    </div>

    <div class="cardForm">
      <div v-if="submitted" class="success-message">
        <i class="fas fa-check-circle"></i> Agency information saved successfully!
      </div>

      <!-- Agency Name -->
      <div class="form-group">
        <label for="agencyName">Name of Agency <span class="required">*</span></label>
        <input
          type="text"
          id="agencyName"
          class="form-control"
          v-model="form.agency_name"
          placeholder="Enter agency name"
        />
        <div class="error-message" v-if="errors.agency_name">{{ errors.agency_name }}</div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label for="description">Description</label>
        <textarea
          id="description"
          class="form-control"
          v-model="form.description"
          placeholder="Describe your agency"
        ></textarea>
      </div>

      <!-- Contact -->
      <div class="form-group">
        <label for="contact_number">Contact Information <span class="required">*</span></label>
        <input
          type="text"
          id="contact_number"
          class="form-control"
          v-model="form.contact_number"
          placeholder="Phone"
        />
        <div class="error-message" v-if="errors.contact_number">{{ errors.contact_number }}</div>
      </div>

      <!-- Location -->
      <div class="form-group">
        <label for="location">Location</label>
        <input
          type="text"
          id="location"
          class="form-control"
          v-model="form.location_address"
          placeholder="City, country"
        />
      </div>

      <!-- Buttons -->
      <div class="btn-group">
        <button type="button" class="btn btn-outline">Cancel</button>
        <button type="submit" class="btn btn-primary" @click="store">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, reactive } from "vue";
    import axios from "axios";

    const form = reactive({
        agency_name: "",
        description: "",
        contact_number: "",
        location_address: "",
    });

    const errors = reactive({});
    const submitted = ref(false);

    const validateForm = () => {
        errors.agency_name = form.agency_name ? "" : "Agency name is required.";
        errors.contact_number = form.contact_number ? "" : "Contact information is required.";
        return !errors.agency_name && !errors.contact_number;
    };

    const store = async () => {
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

            form.agency_name = "";
            form.description = "";
            form.contact_number = "";
            form.location_address = "";

            alert('Agency added successfully!');
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
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

body {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.agency-add-form-body {
  width: 100%;
  max-width: 600px;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  margin: auto;
}

.form-header {
  background: linear-gradient(90deg, #3498db, #2c3e50);
  color: white;
  padding: 25px 30px;
  text-align: center;
}

.form-header h2 {
  font-weight: 600;
  font-size: 28px;
}

.cardForm {
  padding: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #34495e;
}

.form-control {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 16px;
  transition: all 0.3s;
}

.form-control:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

textarea.form-control {
  min-height: 100px;
  resize: vertical;
}

select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px;
}

.btn-group {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 25px;
}

.btn {
  padding: 12px 25px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-primary {
  background: linear-gradient(90deg, #3498db, #2c3e50);
  color: white;
}

.btn-primary:hover {
  background: linear-gradient(90deg, #2980b9, #1c2833);
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.btn-outline {
  background: transparent;
  border: 2px solid #95a5a6;
  color: #7f8c8d;
}

.btn-outline:hover {
  background: #f5f7fa;
  transform: translateY(-2px);
}

.error-message {
  color: #e74c3c;
  font-size: 14px;
  margin-top: 5px;
}

.success-message {
  background-color: #2ecc71;
  color: white;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
  text-align: center;
}
</style>
