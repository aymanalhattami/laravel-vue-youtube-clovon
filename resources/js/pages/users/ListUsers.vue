<script setup>
import {onMounted, reactive, ref} from "vue";
import {Form, Field} from "vee-validate";
import * as yub from 'yup';
import {useToastr} from "@/toastr.js";

const users = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref();
const toastr = useToastr();
const userIdBeingDeleted = ref(null);

const createUserSchema = yub.object({
    name: yub.string().required(),
    email: yub.string().email().required(),
    password: yub.string().required().min(8)
});

const editUserSchema = yub.object({
    name: yub.string().required(),
    email: yub.string().email().required(),
    password: yub.string().when((password, schema) => {
        return password ? schema.min(8) : schema;
    })
});

const confirmUserDeletion = (user) => {
    userIdBeingDeleted.value = user.id;
    $('#deleteUserModal').modal('show');
}

const deleteUser = () => {
    axios.delete('http://laravel-vue-youtube-clovon.test/api/users/' + userIdBeingDeleted.value).then((response) => {
        toastr.success('Deleted Successfully');
        users.value = users.value.filter(user => user.id !== userIdBeingDeleted.value);
        $('#deleteUserModal').modal('hide');
    }).catch((error) => {
        toastr.error(error.response.data.message);
    })
}

const getUsers = () => {
    axios.get('http://laravel-vue-youtube-clovon.test/api/users').then((response) => {
        users.value = response.data;
    })
}

onMounted(() => {
    getUsers();
});

const createUser = (values, { resetForm, setErrors }) => {
    axios.post('http://laravel-vue-youtube-clovon.test/api/users', values).then((response) => {
        users.value.unshift(response.data);
        $('#userFormModal').modal('hide');
        form.value.resetForm();
        toastr.success('Created Successfully');
    }).catch((error) => {
        if(error.response.data.errors){
            setErrors(error.response.data.errors);
        }
    })
}

const updateUser = (values, { setErrors }) => {
    axios.put('http://laravel-vue-youtube-clovon.test/api/users/' + formValues.value.id, values).then((response) => {
        const index = users.value.findIndex(user => user.id === response.data.id);
        users.value[index] = response.data;
        $('#userFormModal').modal('hide');
        form.value.resetForm();
        toastr.success('Updated Successfully');
    }).catch((error) => {
        if(error.response.data.errors){
            setErrors(error.response.data.errors);
        }
        console.log(error);
    })
    //     .finally(() => {
    //         form.value.resetForm();
    // })
}

const adduser = () => {
    editing.value = false;
    $('#userFormModal').modal('show');
}

const editUser = (user) => {
    editing.value = true;
    form.value.resetForm()
    $('#userFormModal').modal('show');
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email
    };
}

const handleSubmit = (values, actions) => {
    editing.value ? updateUser(values, actions) : createUser(values, actions)
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
            <button @click="adduser" class="mb-2 btn btn-primary" type="button">
                New User
            </button>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created at</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.role }}</td>
                        <td>{{ user.created_at }}</td>
                        <td>
                            <a href="" @click.prevent="editUser(user)">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="" @click.prevent="confirmUserDeletion(user)">
                                <i class="fa fa-trash text-danger ml-2"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="userFormModal" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-header">
                        <h1 id="exampleModalLabel" class="modal-title h3 fs-5">
                            <span v-if="editing">Edit User</span>
                            <span v-else>Add New User</span>
                        </h1>
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

        <div id="deleteUserModal" aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 id="exampleModalLabel" class="modal-title h3 fs-5">
                            <span>Delete User</span>
                        </h1>
                        <button aria-label="Close" class="btn-close" data-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure to delete ?</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                        <button class="btn btn-danger" type="button" @click.prevent="deleteUser">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
