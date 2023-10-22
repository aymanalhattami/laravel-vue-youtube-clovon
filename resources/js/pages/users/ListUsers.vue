<script setup>
import {onMounted, ref} from "vue";

    const users = ref([]);

    const getUsers = () => {
        axios.get('http://laravel-vue-youtube-clovon.test/api/users').then((response) => {
            users.value = response.data;
        })
    }

    onMounted(() => {
        getUsers();
    })
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
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
<!--                        <th>Role</th>-->
<!--                        <th>Created at</th>-->
<!--                        <th>Action</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.id }}</td>
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
<!--                        <td>Admin</td>-->
<!--                        <td>11-7-2014</td>-->
<!--                        <td></td>-->
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
