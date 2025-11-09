<template>
    <div class="register-form-container">
        <h2>register</h2>

        <label>Email</label>
        <input
            placeholder="Email"
            type="email"
            id="email"
            v-model="registerForm.email" />
        <span v-if="errors.email && errors.email.length" class="error">
            {{ errors.email[0] }}
        </span>

        <label>Password</label>
        <input
            placeholder="Password"
            type="password"
            id="password"
            v-model="registerForm.password"
        />
        <span v-if="errors.password && errors.password.length" class="error">
            {{ errors.password[0] }}
        </span>

        <label>Personal Information</label>
        <div class="register-group">
            <input
                placeholder="First Name"
                v-model="registerForm.firstName"
                id="firstName"
            />
            <input
                placeholder="Last Name"
                v-model="registerForm.lastName"
                id="lastName"
            />
        </div>
        <span v-if="errors.firstName && errors.firstName.length" class="error">
            {{ errors.firstName[0] }}
        </span>
        <span v-if="errors.lastName && errors.lastName.length" class="error">
            {{ errors.lastName[0] }}
        </span>

        <div class="register-group">
            <input
                placeholder="Phone Number"
                v-model="registerForm.phone_num"
                type="tel"
                id="phoneNumber"
            />
            <select v-model="registerForm.gender">
                <option disabled value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>
        <span v-if="errors.phone_num && errors.phone_num.length" class="error">
            {{ errors.phone_num[0] }}
        </span>
        <span v-if="errors.gender && errors.gender.length" class="error">
            {{ errors.gender[0] }}
        </span>

        <button @click="handleRegister" :disabled="loading">Register</button>
        <p>
            Already Have an Account?
            <a @click="goLogin">Login Here</a>
        </p>
    </div>
</template>
<script setup>
    import { reactive, ref } from "vue";
    import axios from "axios";

    const registerForm = reactive({
        email: "",
        password: "",
        firstName: "",
        lastName: "",
        phone_num: "",
        gender: "",
    });

    const loading = ref(false);
    const errors = ref({});
    const emit = defineEmits(["switchForm"]);

    const goLogin = () => {
        emit("switchForm", "login");
    };
    const handleRegister = async () => {
        loading.value = true;
        errors.value = {}; // reset errors

        try {
            const response = await axios.post("/register", registerForm, {
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });

            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            } else {
                goLogin();
            }
        } catch (error) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
            } else {
                console.error("Unexpected register error:", error);
            }
        } finally {
            loading.value = false;
        }
    };
</script>

<style scoped>
    .register-form-container {
        width: 100%;
        max-width: 340px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        overflow: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .register-form-container h2{
        font-size: 1.8em;
        font-weight: 600;
        margin-bottom: 8px;
        color: #007a8c;
        text-align: center;
        text-transform: uppercase;
    }

    label {
        font-size: 0.95em;
        font-weight: 500;
        color: #333;
    }

    input,
    select {
        width: 100%;
        padding: 12px 14px;
        border-radius: 12px;
        border: 1px solid #ccc;
        background: #fff;
        font-size: 0.95em;
        transition: all 0.3s ease;
    }
    input:focus,
    select:focus {
        border-color: #007a8c;
        box-shadow: 0 0 8px rgba(0, 122, 140, 0.3);
        outline: none;
    }

    .register-form-container a {
        text-align: right;
        font-size: 0.9em;
        color: #f24822;
        cursor: pointer;
        transition: color 0.2s;
    }
    .register-form-container a:hover {
        color: #c93018;
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 25px;
        background: #007a8c;
        color: white;
        font-weight: bold;
        font-size: 1em;
        cursor: pointer;
        margin-top: 6px;
        transition: background 0.3s, transform 0.2s;
    }
    button:hover {
        background: #005f6b;
        transform: translateY(-2px);
    }
    button:disabled {
        background: #a5cfd6;
        cursor: not-allowed;
    }
    .register-form-container p {
        text-align: center;
        margin-top: 6px;
        font-size: 0.95em;
        color: #333;
    }
    .register-group {
        display: flex;
        gap: 12px;
    }
    .register-group input,
    .register-group select {
        flex: 1;
    }
</style>
