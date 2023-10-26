import Dashboard from "./components/Dashboard.vue";
import ListAppointments from "./pages/appointements/ListAppointments.vue";
import ListUsers from "@/pages/users/UserList.vue";
import UpdateSetting from "@/pages/settings/UpdateSetting.vue";
import UpdateProfile from "@/pages/profile/UpdateProfile.vue";
import AppointmentForm from "@/pages/appointements/AppointmentForm.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard
    },
    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments
    },
    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: ListUsers
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile
    }
]
