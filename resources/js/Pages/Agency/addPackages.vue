<template>
      <div class="agency-add-form-body">
            <div class="form-header">
              <h2>Package</h2>
            </div>

            <div class="cardForm">
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
                  <div class="form-group">
                        <label for="services">Services 1 <span class="required">*</span></label>
                        <input
                          type="text"
                          id="services"
                          class="form-control"
                          v-model="formData.services"
                          placeholder="Enter service details"
                        />
                        <div class="error-message" v-if="errors.services">{{ errors.services }}</div>
                  </div>
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

                  <div class="form-group">
                        <label for="location">Location <span class="required">*</span></label>
                        <input
                          type="text"
                          id="location"
                          class="form-control"
                          v-model="formData.location"
                          placeholder="Enter location"
                        />
                        <div class="error-message" v-if="errors.location">{{ errors.location }}</div>
                  </div>

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

                  <div class="form-group">
                        <label for="availability">Available from - to <span class="required">*</span></label>
                        <input
                          type="text"
                          id="availability"
                          class="form-control"
                          v-model="formData.availability"
                          placeholder="Enter availability range"
                        />
                        <div class="error-message" v-if="errors.availability">{{ errors.availability }}</div>
                  </div>

                  <div class="btn-group">
                        <button type="button" class="btn btn-outline" @click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary" @click="handleSubmit">Saved</button>
                  </div>
            </div>
      </div>
</template>

<script setup>
    import { ref, reactive } from 'vue';

    const formData = reactive({
          name: '',
          description: '',
          services: '',
          duration: '',
          location: '',
          capacity: null,
          availability: ''
    });

    const errors = reactive({});
    const submitted = ref(false);

    const emit = defineEmits(['submit', 'cancel']);

    const validateForm = () => {
          errors.name = formData.name ? '' : 'Name of Package is required.';
          errors.description = formData.description ? '' : 'Description is required.';
          errors.services = formData.services ? '' : 'Services are required.';
          errors.duration = formData.duration ? '' : 'Duration is required.';
          errors.location = formData.location ? '' : 'Location is required.';
          errors.capacity = formData.capacity && formData.capacity > 0 ? '' : 'Capacity must be a positive number.';
          errors.availability = formData.availability ? '' : 'Availability range is required.';
          return !errors.name && !errors.description && !errors.services && !errors.duration && !errors.location && !errors.capacity && !errors.availability;
    };

    const handleSubmit = () => {
          if (validateForm()) {
                emit('submit', { ...formData });
                submitted.value = true;
                setTimeout(() => (submitted.value = false), 3000);
                formData.name = '';
                formData.description = '';
                formData.services = '';
                formData.duration = '';
                formData.location = '';
                formData.capacity = null;
                formData.availability = '';
          }
    };

    const cancel = () => {
          emit('cancel');
          formData.name = '';
          formData.description = '';
          formData.services = '';
          formData.duration = '';
          formData.location = '';
          formData.capacity = null;
          formData.availability = '';
          errors.name = '';
          errors.description = '';
          errors.services = '';
          errors.duration = '';
          errors.location = '';
          errors.capacity = '';
          errors.availability = '';
    };
</script>

<style scoped>
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
