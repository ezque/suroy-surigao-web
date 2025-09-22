<template>
    <div class="auth-main-body">
        <div class="auth-card">
            <div class="card-left-side">
                <div class="form-logo">
                    <img src="/public/images/auth-logo.png" alt="logo" />
                </div>
                <Login
                    v-if="activeForm === 'login'"
                    @switchForm="activeForm = 'register'"
                />
                <Register
                    v-else
                    @switchForm="activeForm = 'login'"
                />
            </div>
            <div class="card-right-side">
                <img src="/public/images/logo/logo.png" alt="logo" />
                <h2>welcome!</h2>
                <h3>your journey starts here</h3>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { ref } from 'vue'
    import Login from "./Login.vue"
    import Register from "./Register.vue"

    const activeForm = ref("login")

</script>
<style scoped>
    .auth-main-body {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: url("/images/ilovesurigao.jpg") no-repeat center center/cover;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
    }
    .auth-card {
        width: 100%;
        max-width: 950px;
        max-height: 850px;
        display: flex;
        flex-direction: row;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 35px rgba(0, 0, 0, 0.25);
        animation: fadeIn 0.6s ease;
        border: 6px solid #A6D7EE;
    }
    .card-left-side {
        flex: 1;
        background-color: #A6D7EE;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 25px 15px;
    }
    .form-logo {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .form-logo img {
        width: 200px;
        height: auto;
    }

    .card-right-side {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: white;
        text-align: center;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        padding: 40px 20px;
    }

    .card-right-side img {
        width: 300px;
        margin-bottom: 20px;
    }

    .card-right-side h2 {
        margin: 0;
        font-size: 2.4em;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .card-right-side h3 {
        margin-top: 12px;
        font-size: 1.1em;
        padding: 8px 20px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        font-weight: 500;
    }
</style>
