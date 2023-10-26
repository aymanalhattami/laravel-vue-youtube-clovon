<script setup>

import { onMounted, reactive, ref } from "vue";
import axios from "axios";
import {useRoute, useRouter} from "vue-router";
import { useToastr } from "@/toastr.js";
import { Form } from 'vee-validate';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

const router = useRouter();
const route = useRoute();
const toastr = useToastr();

const editMode = ref(false);
const clients = ref([]);

const form = reactive({
    title: '',
    description: '',
    client_id: '',
    start_time: '',
    end_time: '',
});

const getAppointment = () => {
  axios.get(`http://laravel-vue-youtube-clovon.test/api/appointments/${route.params.id}`)
      .then((response) => {
          form.title = response.data.title;
          form.description = response.data.description;
          form.client_id = response.data.client_id;
          form.start_time = response.data.start_time;
          form.end_time = response.data.end_time;
      })
};

onMounted(() => {
    if(route.name === 'admin.appointments.edit'){
        editMode.value = true;

        getAppointment();
    }

    getClients();

    flatpickr('.flatpickr', {
        enableTime: true
    });
})

const getClients = () => {
    axios.get('http://laravel-vue-youtube-clovon.test/api/clients')
        .then((response) => {
            clients.value = response.data;
        })
}

const handleSubmit = (values, actions) => {
    if(editMode.value){
        editAppointment(values, actions);
    }else{
        createAppointment(values, actions);
    }
}

const editAppointment = (values, actions) => {
    axios.put(`http://laravel-vue-youtube-clovon.test/api/appointments/${route.params.id}`, form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success(response.data.message);
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
}

const createAppointment = (values, actions) => {
    axios.post('http://laravel-vue-youtube-clovon.test/api/appointments', form)
        .then((response) => {
            router.push('/admin/appointments');
            toastr.success(response.data.message);
        })
        .catch((error) => {
            actions.setErrors(error.response.data.errors);
        })
}

</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        Appointment
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/appointments">Appointments</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot:default="{ errors }">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input v-model="form.title" type="text" class="form-control" id="title" placeholder="Enter Title" :class="{'is-invalid' : errors.title}">
                                            <span class="text-danger">{{ errors.title }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Client Name</label>
                                            <select id="client" class="form-control" v-model="form.client_id" :class="{'is-invalid' : errors.client_id}">
                                                <option value="">Select</option>
                                                <option v-for="client in clients" :value="client.id">{{ client.first_name }} {{ client.last_name }}</option>
                                            </select>
                                            <span class="text-danger">{{ errors.client_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Start Date</label>
                                            <input v-model="form.start_time" type="text" class="form-control flatpickr" id="date" :class="{'is-invalid' : errors.start_time}">
                                            <span class="text-danger">{{ errors.start_time }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time">End Date</label>
                                            <input v-model="form.end_time" type="text" class="form-control flatpickr" id="time" :class="{'is-invalid' : errors.end_time}">
                                            <span class="text-danger">{{ errors.start_time }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control" id="description" rows="3" placeholder="Enter Description" :class="{'is-invalid' : errors.description}"></textarea>
                                    <span class="text-danger">{{ errors.description }}</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
