<template>
  <div class="spot-add-form-body">
    <div class="form-header">
      <h2>Add New Spot</h2>
    </div>

    <div class="cardForm">
      <div v-if="submitted" class="success-message">
        <i class="fas fa-check-circle"></i> Spot added successfully!
      </div>

      <div class="form-group">
        <label for="spotName">Spot Name <span class="required">*</span></label>
        <input
          type="text"
          id="spotName"
          class="form-control"
          v-model="form.spot_name"
          placeholder="Enter spot name"
        />
        <div class="error-message" v-if="errors.spot_name">{{ errors.spot_name }}</div>
      </div>

      <div class="form-group">
        <label for="description">Description</label>
        <textarea
          id="description"
          class="form-control"
          v-model="form.description"
          placeholder="Describe the spot"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="location">Location</label>
        <input
          type="text"
          id="location"
          class="form-control"
          v-model="form.location"
          placeholder="City, Province"
        />
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" v-model="form.category" class="form-control">
          <option disabled value="">Select category</option>
          <option>Beach</option>
          <option>Mountain</option>
          <option>Historical</option>
          <option>Adventure</option>
          <option>Cultural</option>
        </select>
      </div>

      <div class="form-group">
        <label for="images">Upload Images</label>
        <input
          type="file"
          id="images"
          multiple
          class="form-control"
          @change="handleFileUpload"
        />
      </div>

      <div class="btn-group">
        <button type="button" class="btn btn-outline" @click="resetForm">Cancel</button>
          <button
              type="button"
              class="btn btn-primary"
              :disabled="loading"
              @click="store"
          >
              {{ loading ? "Saving..." : "Save" }}
          </button>
      </div>
    </div>
  </div>
</template>

<script setup>
    import { ref, reactive } from "vue";
    import axios from "axios";

    const form = reactive({
        spot_name: "",
        description: "",
        location: "",
        category: "",
        images: []
    });

    const errors = reactive({});
    const submitted = ref(false);
    const loading = ref(false);

    const handleFileUpload = (event) => {
        form.images = Array.from(event.target.files);
    };

    const validateForm = () => {
        errors.spot_name = form.spot_name ? "" : "Spot name is required.";
        return !errors.spot_name;
    };
    const resetForm = () => {
        Object.keys(form).forEach((key) => (form[key] = key === "images" ? [] : ""));
        submitted.value = false;
        Object.keys(errors).forEach((key) => (errors[key] = ""));
    };

    const store = async () => {
        if (!validateForm() || loading.value) return;

        loading.value = true;

        try {
            let formData = new FormData();
            formData.append("spot_name", form.spot_name);
            formData.append("description", form.description);
            formData.append("location", form.location);
            formData.append("category", form.category);

            form.images.forEach((file, index) => {
                formData.append(`images[${index}]`, file);
            });

            await axios.post("/add-new-spot", formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });

            submitted.value = true;
            Object.keys(form).forEach((key) => (form[key] = key === "images" ? [] : ""));
        } catch (err) {
            if (err.response?.status === 422) {
                Object.assign(errors, err.response.data.errors);
            }
        } finally {
            loading.value = false;
        }
    };
</script>

<style scoped>
/* same design style as agency form */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.spot-add-form-body {
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
  border-color: #27ae60;
  box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.2);
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
