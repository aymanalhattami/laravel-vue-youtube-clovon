<script setup>
import {onMounted, ref} from "vue";
import {useToastr} from "@/toastr.js";
import Swal from "sweetalert2";

const toastr = useToastr();

onMounted(() => {
        getAppointments();
        getAppointmentStatus();
    })

    const appointments = ref([]);
    const appointmentStatus = ref([]);
    const selectedStatus = ref(null);

    const getAppointments = (status = null) => {
        selectedStatus.value = status;
        const params = {};
        if(status){
            params.status = status;
        }
        axios.get('http://laravel-vue-youtube-clovon.test/api/appointments', {
            params: params
        })
            .then((response) => {
                appointments.value = response.data;
            })
    }

    const getAppointmentStatus = () => {
        axios.get('http://laravel-vue-youtube-clovon.test/api/appointments/status')
            .then((response) => {
                appointmentStatus.value = response.data;
            }).catch((error) => {
                toastr.error(error.response.data.message);
        })
    }

const deleteAppointment = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`http://laravel-vue-youtube-clovon.test/api/appointments/${id}`)
                .then(() => {
                    appointments.value.data = appointments.value.data.filter((appointment) => appointment.id !== id );
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                })
        }
    })
}
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Appointments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Appointments</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <router-link class="btn btn-primary" to="/admin/appointments/create">
                                        <i class="fa fa-plus-circle mr-1"></i> Add New Appointment
                                    </router-link>
                                </div>
                                <div class="btn-group">
                                    <button @click="getAppointments()" type="button" class="btn" :class="selectedStatus == null ? 'btn-secondary' : ''">
                                        <span class="mr-1">All</span>
                                        <span class="badge badge-pill badge-info">{{ appointmentStatus.length > 0 ? appointmentStatus.map((status) => status.count).reduce((a, b) => a + b) : '' }}</span>
                                    </button>

                                    <button v-for="status in appointmentStatus" @click="getAppointments(status.value)" type="button" class="btn" :class="selectedStatus == status.value ? 'btn-secondary' : ''">
                                        <span class="mr-1">{{ status.name }}</span>
                                        <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Client Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="appointment in appointments.data" :key="appointment.id">
                                            <td>{{ appointment.id }}</td>
                                            <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>
                                            <td>{{ appointment.start_time }}</td>
                                            <td>{{ appointment.end_time }}</td>
                                            <td>
                                                <span class="badge " :class="`badge-${appointment.status.color}`">{{ appointment.status.name }}</span>
                                            </td>
                                            <td>
                                                <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                                    <i class="fa fa-edit mr-2"></i>
                                                </router-link>

                                                <a href="" @click.prevent="deleteAppointment(appointment.id)">
                                                    <i class="fa fa-trash text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
