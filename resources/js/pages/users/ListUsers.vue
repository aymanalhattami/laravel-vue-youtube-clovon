<script setup>
import {onMounted, reactive, ref} from "vue";

const users = ref([]);
const form = reactive({
    name: '',
    email: '',
    password: ''
});

const getUsers = () => {
    axios.get('http://laravel-vue-youtube-clovon.test/api/users').then((response) => {
        users.value = response.data;
    })
}

onMounted(() => {
    getUsers();
});

const createUser = () => {
    axios.post('http://laravel-vue-youtube-clovon.test/api/users', form).then((response) => {
        users.value.unshift(response.data);

        form.name = '';
        form.email = '';
        form.password = '';

        $('#createUserModal').modal('hide');
    })
}
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Button trigger modal -->
            <button class="mb-2 btn btn-primary" data-target="#createUserModal" data-toggle="modal" type="button">
                Launch demo modal
            </button>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="createUserModal" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade"
             tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 id="exampleModalLabel" class="modal-title h3 fs-5">Add New User</h1>
                        <button aria-label="Close" class="btn-close" data-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Name</label>
                            <input class="form-control" placeholder="Jone Doe" v-model="form.name" type="text">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Email address</label>
                            <input class="form-control" placeholder="name@example.com" v-model="form.email" type="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Password</label>
                            <input class="form-control" placeholder="" v-model="form.password" type="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" type="button" @click="createUser">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
