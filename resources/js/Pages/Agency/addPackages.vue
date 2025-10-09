<template>
  <div class="agency-add-form-body">
    <div class="form-header">
      <h2>Add Package</h2>
    </div>

    <div class="cardForm">
      <!-- Success Message -->
      <div v-if="submitted" class="success-message">
        Package saved successfully!
      </div>

      <!-- Image Upload -->
      <div class="form-group">
        <label>Package Images <span class="required">*</span></label>

        <div class="image-upload-container">
          <!-- Preview thumbnails -->
          <div v-for="(preview, index) in formData.imagePreviews" :key="index" class="image-thumb">
            <img :src="preview" alt="Preview" />
            <button class="remove-btn" @click="removeImage(index)">×</button>
          </div>

          <!-- Upload button -->
          <label class="upload-box">
            <span>+</span>
            <input type="file" accept="image/*" multiple @change="handleImageUpload" />
          </label>
        </div>

        <div class="error-message" v-if="errors.images">{{ errors.images }}</div>
      </div>

      <!-- Package Name -->
      <div class="form-group">
        <label for="name">Name of Package <span class="required">*</span></label>
        <input
          type="text"
          id="name"
          class="form-control"
          v-model="formData.name"
          placeholder="Enter package name"
        />
        <div class="error-message" v-if="errors.name">{{ errors.name }}</div>
      </div>

      <!-- Description -->
      <div class="form-group">
        <label for="description">Description <span class="required">*</span></label>
        <textarea
          id="description"
          class="form-control"
          v-model="formData.description"
          placeholder="Enter description"
        ></textarea>
        <div class="error-message" v-if="errors.description">{{ errors.description }}</div>
      </div>

      <!-- Services -->
      <div class="form-group">
        <label>Services <span class="required">*</span></label>
        <div v-for="(service, index) in formData.services" :key="index" class="service-row">
          <input
            type="text"
            class="form-control"
            v-model="formData.services[index]"
            placeholder="Enter service details"
          />
          <button
            class="remove-service-btn"
            v-if="formData.services.length > 1"
            @click="removeService(index)"
          >
            ×
          </button>
        </div>
        <button class="add-service-btn" @click="addService">+ Add Service</button>
        <div class="error-message" v-if="errors.services">{{ errors.services }}</div>
      </div>

      <!-- Duration -->
      <div class="form-group">
        <label for="duration">Duration <span class="required">*</span></label>
        <input
          type="text"
          id="duration"
          class="form-control"
          v-model="formData.duration"
          placeholder="Enter duration"
        />
        <div class="error-message" v-if="errors.duration">{{ errors.duration }}</div>
      </div>

      <!-- Availability -->
      <div class="form-group">
        <label>Availability <span class="required">*</span></label>
        <div class="availability-row">
          <input
            type="date"
            class="form-control"
            v-model="formData.availableFrom"
            placeholder="From"
          />
          <span class="to-text">to</span>
          <input
            type="date"
            class="form-control"
            v-model="formData.availableTo"
            placeholder="To"
          />
        </div>
        <div class="error-message" v-if="errors.availableFrom || errors.availableTo">
          {{ errors.availableFrom || errors.availableTo }}
        </div>
      </div>

      <!-- Capacity -->
      <div class="form-group">
        <label for="capacity">Capacity <span class="required">*</span></label>
        <input
          type="number"
          id="capacity"
          class="form-control"
          v-model.number="formData.capacity"
          placeholder="Enter max capacity"
          min="1"
        />
        <div class="error-message" v-if="errors.capacity">{{ errors.capacity }}</div>
      </div>

      <!-- ✅ Added Price -->
      <div class="form-group">
        <label for="price">Price (₱) <span class="required">*</span></label>
        <input
          type="number"
          id="price"
          class="form-control"
          v-model.number="formData.price"
          placeholder="Enter price"
          min="0"
        />
        <div class="error-message" v-if="errors.price">{{ errors.price }}</div>
      </div>

      <!-- Buttons -->
      <div class="btn-group">
        <button type="button" class="btn btn-outline" @click="cancel">Cancel</button>
        <button type="submit" class="btn btn-primary" @click="handleSubmit">Save</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";

const formData = reactive({
  name: "",
  description: "",
  services: [""],
  duration: "",
  capacity: null,
  availableFrom: "",
  availableTo: "",
  images: [],
  imagePreviews: [],
  price: null, // ✅ added
});

const errors = reactive({});
const submitted = ref(false);

const emit = defineEmits(["submit", "cancel"]);

const handleImageUpload = (event) => {
  const files = Array.from(event.target.files);
  const validImages = files.filter((file) => file.type.startsWith("image/"));

  if (validImages.length) {
    validImages.forEach((file) => {
      formData.images.push(file);
      formData.imagePreviews.push(URL.createObjectURL(file));
    });
    errors.images = "";
  } else {
    errors.images = "Please upload valid image files.";
  }

  event.target.value = "";
};

const removeImage = (index) => {
  formData.images.splice(index, 1);
  formData.imagePreviews.splice(index, 1);
};

const addService = () => {
  formData.services.push("");
};

const removeService = (index) => {
  formData.services.splice(index, 1);
};

const validateForm = () => {
  errors.images = formData.images.length ? "" : "At least one image is required.";
  errors.name = formData.name ? "" : "Package name is required.";
  errors.description = formData.description ? "" : "Description is required.";
  errors.services = formData.services.some((s) => s.trim())
    ? ""
    : "At least one service is required.";
  errors.duration = formData.duration ? "" : "Duration is required.";
  errors.capacity =
    formData.capacity && formData.capacity > 0 ? "" : "Capacity must be positive.";
  errors.availableFrom = formData.availableFrom ? "" : "Available from date is required.";
  errors.availableTo = formData.availableTo ? "" : "Available to date is required.";
  errors.price = formData.price && formData.price > 0 ? "" : "Price is required."; // ✅ added validation

  return Object.values(errors).every((err) => !err);
};

const handleSubmit = () => {
  if (validateForm()) {
    emit("submit", { ...formData });
    submitted.value = true;
    setTimeout(() => (submitted.value = false), 3000);
    resetForm();
  }
};

const cancel = () => {
  emit("cancel");
  resetForm();
};

const resetForm = () => {
  formData.name = "";
  formData.description = "";
  formData.services = [""];
  formData.duration = "";
  formData.capacity = null;
  formData.availableFrom = "";
  formData.availableTo = "";
  formData.images = [];
  formData.imagePreviews = [];
  formData.price = null; // ✅ reset
  Object.keys(errors).forEach((key) => (errors[key] = ""));
};
</script>

<style scoped>
/* 🧠 No design or style changed — exactly the same as your original */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.agency-add-form-body {
  width: 100%;
  max-width: 600px;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  margin: 40px auto;
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

.required {
  color: #e74c3c;
}

/* Image Upload Section */
.image-upload-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.upload-box {
  width: 100px;
  height: 100px;
  border: 2px dashed #ccc;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 30px;
  color: #888;
  cursor: pointer;
  position: relative;
}

.upload-box:hover {
  border-color: #3498db;
  color: #3498db;
}

.upload-box input {
  opacity: 0;
  position: absolute;
  width: 100%;
  height: 100%;
  cursor: pointer;
}

.image-thumb {
  position: relative;
  width: 100px;
  height: 100px;
}

.image-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 10px;
  border: 2px solid #ddd;
}

.remove-btn {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 14px;
  width: 22px;
  height: 22px;
  cursor: pointer;
}

.remove-btn:hover {
  background: #c0392b;
}

/* Services styling */
.service-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}

.remove-service-btn {
  background: #e74c3c;
  border: none;
  color: white;
  font-weight: bold;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
}

.remove-service-btn:hover {
  background: #c0392b;
}

.add-service-btn {
  background: #ecf0f1;
  border: 1px dashed #95a5a6;
  color: #7f8c8d;
  font-size: 15px;
  padding: 8px 15px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 5px;
}

.add-service-btn:hover {
  background: #dfe6e9;
}

/* Availability row */
.availability-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.to-text {
  color: #7f8c8d;
  font-weight: 500;
}
</style>
