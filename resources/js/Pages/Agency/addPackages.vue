<template>
    <div class="agency-add-form-body">
        <div class="form-header">
            <h2>Package</h2>
        </div>

        <div class="cardForm">
            <!-- Package Name -->
            <div class="form-group">
                <label for="package_name">Name of Package <span class="required">*</span></label>
                <input
                    type="text"
                    id="name"
                    class="form-control"
                    v-model="package_name"
                    placeholder="Enter package name"
                />
                <div class="error-message" v-if="errors.package_name">{{ errors.package_name }}</div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Description <span class="required">*</span></label>
                <textarea
                    id="description"
                    class="form-control"
                    v-model="description"
                    placeholder="Enter description"
                ></textarea>
                <div class="error-message" v-if="errors.description">{{ errors.description }}</div>
            </div>

            <!-- Dynamic Services -->
            <div
                class="form-group"
                v-for="(service, index) in services"
                :key="index"
            >
                <label>Service {{ index + 1 }} <span class="required">*</span></label>
                <input
                    type="text"
                    class="form-control"
                    v-model="services[index]"
                    placeholder="Enter service details"
                />
            </div>
            <button
                type="button"
                class="btn btn-outline mt-2"
                @click="addService"
            >
                + Add Another Service
            </button>
            <div class="error-message" v-if="errors.services">{{ errors.services }}</div>

            <!-- Duration -->
            <div class="form-group">
                <label for="duration">Duration <span class="required">*</span></label>
                <input
                    type="text"
                    id="duration"
                    class="form-control"
                    v-model="duration"
                    placeholder="Enter duration"
                />
                <div class="error-message" v-if="errors.duration">{{ errors.duration }}</div>
            </div>

            <!-- Capacity -->
            <div class="form-group">
                <label for="capacity">Capacity <span class="required">*</span></label>
                <input
                    type="number"
                    id="capacity"
                    class="form-control"
                    v-model.number="capacity"
                    placeholder="Enter max capacity"
                    min="1"
                />
                <div class="error-message" v-if="errors.capacity">{{ errors.capacity }}</div>
            </div>
            <div class="form-group">
                <label for="price">Price <span class="required">*</span></label>
                <input
                    type="number"
                    id="price"
                    class="form-control"
                    v-model.number="price"
                    placeholder="Enter Price"
                    min="1"
                />
                <div class="error-message" v-if="errors.price">{{ errors.price }}</div>
            </div>

            <!-- available_On -->
            <div class="form-group">
                <label for="available_On">Available From <span class="required">*</span></label>
                <input
                    type="text"
                    id="available_On"
                    class="form-control"
                    v-model="available_On"
                />
                <div class="error-message" v-if="errors.available_On">{{ errors.available_On }}</div>
            </div>



            <!-- Buttons -->
            <div class="btn-group">
                <button type="button" class="btn btn-outline" @click="cancel">
                    Cancel
                </button>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click="handleSubmit"
                >
                    Save
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, reactive } from "vue";
    import axios from "axios";

    // form refs
    const package_name = ref("");
    const description = ref("");
    const services = ref([""]);
    const duration = ref("");
    const capacity = ref("");
    const available_On = ref("");
    const price = ref("");

    // error handling
    const errors = reactive({});
    const submitted = ref(false);

    const emit = defineEmits(["submit", "cancel"]);

    // Add extra service field
    const addService = () => {
        services.value.push("");
    };

    const handleSubmit = async () => {
        try {
            const payload = {
                package_name: package_name.value,
                description: description.value,
                services: services.value, // array will be sent properly
                duration: duration.value,
                capacity: capacity.value,
                available_On: available_On.value,
                price: price.value,
            };

            const response = await axios.post('/add-package', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });

            alert('Package added successfully!');

            // reset form
            package_name.value = "";
            description.value = "";
            services.value = [""];
            duration.value = "";
            capacity.value = "";
            available_On.value = "";
            price.value = "";

        } catch (error) {
            console.error(error);
            if (error.response && error.response.data && error.response.data.errors) {
                errors.value = error.response.data.errors;
            }
            alert('Failed to submit package.');
        }
    };


    const cancel = () => {
        emit("cancel");
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
