<script setup>
import {ref} from "vue";
import {useToastr} from "@/toastr.js";

const toastr = useToastr();

const props = defineProps({
    user: Object,
    selectAll: Boolean
})

const emit = defineEmits(['userDeleted', 'editUser', 'toggleSelection', 'confirmUserDeletion'])

const editUser = (user) => {
    emit('editUser', user);
}

const roles = ref([
    {
        name: 'Admin',
        value: 1
    },
    {
        name: 'User',
        value: 2
    }
])

const changeRole = (user, role) => {
    axios.put('http://laravel-vue-youtube-clovon.test/api/users/' + user.id + '/change-role', {
        role: role
    })
        .then((response) => {
            toastr.success('role changes');
        })
}

const toggleSelection = () => {
    emit('toggleSelection', props.user);
}
</script>

<template>
    <tr>
        <td><input :checked="selectAll" type="checkbox" @change="toggleSelection" /></td>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>
            <select class="form-control" @change="changeRole(user, $event.target.value)">
                <option v-for="role in roles" :value="role.value" :selected="user.role === role.name">{{ role.name }}</option>
            </select>
        </td>
        <td>{{ user.created_at }}</td>
        <td>
            <a href="" @click.prevent="editUser(user)">
                <i class="fa fa-edit"></i>
            </a>

            <a href="" @click.prevent="$emit('confirmUserDeletion', user.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
</template>

<style scoped>

</style>
