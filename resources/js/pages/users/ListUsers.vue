<script setup>
import {onMounted, reactive, ref} from "vue";
import {Form, Field} from "vee-validate";
import * as yub from 'yup';

const users = ref([]);

const schema = yub.object({
    name: yub.string().required(),
    email: yub.string().email().required(),
    password: yub.string().required().min(8)
});

const getUsers = () => {
    axios.get('http://laravel-vue-youtube-clovon.test/api/users').then((response) => {
        users.value = response.data;
    })
}

onMounted(() => {
    getUsers();
});

const createUser = (values, { resetForm }) => {
    axios.post('http://laravel-vue-youtube-clovon.test/api/users', values).then((response) => {
        users.value.unshift(response.data);
        resetForm()
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

        <div id="createUserModal" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <Form @submit="createUser" :validation-schema="schema" v-slot="{ errors }">
                    <div class="modal-header">
                        <h1 id="exampleModalLabel" class="modal-title h3 fs-5">Add New User</h1>
                        <button aria-label="Close" class="btn-close" data-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Name</label>
                            <Field class="form-control" placeholder="Jone Doe" name="name" type="text" :class="{ 'is-invalid': errors.name }" />
                            <span class="text-danger">{{ errors.name }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Email address</label>
                            <Field class="form-control" placeholder="name@example.com" name="email" type="email" :class="{ 'is-invalid': errors.email }" />
                            <span class="text-danger">{{ errors.email }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="exampleFormControlInput1">Password</label>
                            <Field class="form-control" placeholder="" name="password" type="password" :class="{ 'is-invalid': errors.password }" />
                            <span class="text-danger">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
