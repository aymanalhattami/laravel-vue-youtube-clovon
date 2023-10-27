<script setup>
import {reactive, ref} from "vue";
import axios from "axios";
import {useRouter} from "vue-router";

const router = useRouter();

const form = reactive({
    email: '',
    password: ''
});

const errorMessage = ref('');
const loading = ref(false);

const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.post('http://laravel-vue-youtube-clovon.test/login', form)
        .then((response) => {
            window.location.href = 'http://laravel-vue-youtube-clovon.test/admin/dashboard'
            // router.push('http://laravel-vue-youtube-clovon.test/admin/dashboard');
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
        })
        .finally(() => {
            loading.value = false;
        })
}

</script>

<template>
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a class="h1" href="#"><b>Admin</b>Login</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <div v-if="errorMessage" class="alert alert-danger">
                    {{ errorMessage }}
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="input-group mb-3">
                        <input v-model="form.email" class="form-control" placeholder="Email" type="email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.password" class="form-control" placeholder="Password" type="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input id="remember" type="checkbox">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button class="btn btn-primary btn-block" type="submit">
                                <span v-if="loading" class="spinner-border spinner-border-sm" role="status"></span>
                                <span v-else>Sign In</span>
                            </button>
                        </div>

                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
