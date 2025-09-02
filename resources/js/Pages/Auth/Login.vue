<template>
    <div class="login-form-container">
        <h2>Login</h2>

        <label>Email</label>
        <input
            placeholder="Email"
            type="email"
            id="email"
            v-model="loginForm.email"
            required
        />
        <span v-if="errors.email && errors.email.length" class="error">
            {{ errors.email[0] }}
        </span>

        <label>Password</label>
        <input
            placeholder="Password"
            type="password"
            id="password"
            v-model="loginForm.password"
            required
        />
        <span v-if="errors.password && errors.password.length" class="error">
            {{ errors.password[0] }}
        </span>

        <a>Forgot Password</a>
        <button @click="handleLogin" :disabled="loading">Sign in</button>

        <p>
            Don't Have an Account yet?
            <a @click="goRegister">Register</a>
        </p>
    </div>
</template>

<script setup>
    import { reactive, ref } from "vue";
    import axios from "axios";

    const loginForm = reactive({
        email: '',
        password: '123456',
    });

    const loading = ref(false);
    const errors = ref({});
    const emit = defineEmits(["switchForm"]);

    const goRegister = () => {
        emit("switchForm", "register");
    };

    const handleLogin = async () => {
        loading.value = true;
        errors.value = {}; // reset errors

        try {
            const response = await axios.post("/login", loginForm, {
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content"),
                },
            });

            if (response.data.redirect) {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            if (error.response?.status === 422) {
                errors.value = error.response.data.errors;
            } else {
                console.error("Unexpected login error:", error);
            }
        } finally {
            loading.value = false;
        }
    };
</script>


<style scoped>
    .login-form-container {
        width: 100%;
        max-width: 340px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .login-form-container h2{
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

    /* Links */
    .login-form-container a {
        text-align: right;
        font-size: 0.9em;
        color: #f24822;
        cursor: pointer;
        transition: color 0.2s;
    }
    .login-form-container a:hover {
        color: #c93018;
    }

    /* Buttons */
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
    .login-form-container p {
        text-align: center;
        margin-top: 6px;
        font-size: 0.95em;
        color: #333;
    }
</style>
