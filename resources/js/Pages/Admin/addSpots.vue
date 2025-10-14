<template>
    <div class="spot-add-form-body">
        <div class="form-header">
            <h2>{{ props.editingSpot ? "Edit Spot" : "Add New Spot" }}</h2>
        </div>

        <div class="cardForm">
            <!-- Success Message -->
            <div v-if="submitted" class="success-message">
                <i class="fas fa-check-circle"></i>
                {{ props.editingSpot ? "Spot updated successfully!" : "Spot added successfully!" }}
            </div>

            <!-- Spot Name -->
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

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    class="form-control"
                    v-model="form.description"
                    placeholder="Describe the spot"
                ></textarea>
            </div>

            <!-- Location -->
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

            <!-- Category -->
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

            <!-- Upload Images -->
            <div class="form-group">
                <label>Upload Images <span class="required">*</span></label>

                <div class="image-upload-container">
                    <!-- Preview thumbnails -->
                    <div v-for="(preview, index) in imagePreviews" :key="index" class="image-thumb">
                        <img :src="preview" alt="Preview" />
                        <button class="remove-btn" @click="removeImage(index)">×</button>
                    </div>

                    <!-- Upload box -->
                    <label class="upload-box">
                        <span>+</span>
                        <input type="file" accept="image/*" multiple @change="handleFileUpload" />
                    </label>
                </div>

                <div class="error-message" v-if="errors.images">{{ errors.images }}</div>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline" @click="$emit('selectPage', 'spots')">
                    Cancel
                </button>
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

    const props = defineProps({
        editingSpot: Object
    });
    const emit = defineEmits(["selectPage"]);

    const form = reactive({
        spot_name: props.editingSpot?.spot_name || "",
        description: props.editingSpot?.description || "",
        location: props.editingSpot?.location || "",
        category: props.editingSpot?.category || "",
        images: [],
    });

    const imagePreviews = ref(
        props.editingSpot?.images?.map(img => `/storage/${img.spot_image}`) || []
    );

    const errors = reactive({});
    const submitted = ref(false);
    const loading = ref(false);

    const handleFileUpload = (event) => {
        const files = Array.from(event.target.files);
        const validImages = files.filter((file) => file.type.startsWith("image/"));

        if (validImages.length) {
            validImages.forEach((file) => {
                form.images.push(file);
                imagePreviews.value.push(URL.createObjectURL(file));
            });
            errors.images = "";
        } else {
            errors.images = "Please upload valid image files.";
        }

        event.target.value = "";
    };

    const removeImage = (index) => {
        form.images.splice(index, 1);
        imagePreviews.value.splice(index, 1);
    };

    const validateForm = () => {
        errors.spot_name = form.spot_name ? "" : "Spot name is required.";
        if (!props.editingSpot) {
            errors.images = form.images.length ? "" : "At least one image is required.";
        }
        return !errors.spot_name && !errors.images;
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

            if (props.editingSpot) {
                await axios.post(`/update-spot/${props.editingSpot.id}`, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
            } else {
                await axios.post("/add-new-spot", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
            }

            submitted.value = true;
            setTimeout(() => (submitted.value = false), 3000);

            if (!props.editingSpot) resetForm();
        } catch (err) {
            if (err.response?.status === 422) {
                Object.assign(errors, err.response.data.errors);
            }
        } finally {
            loading.value = false;
        }
    };

    const resetForm = () => {
        form.spot_name = "";
        form.description = "";
        form.location = "";
        form.category = "";
        form.images = [];
        imagePreviews.value = [];
        Object.keys(errors).forEach((key) => (errors[key] = ""));
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
</style>
