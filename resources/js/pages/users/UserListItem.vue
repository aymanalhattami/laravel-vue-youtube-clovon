<script setup>
import {ref} from "vue";
import {useToastr} from "@/toastr.js";

const toastr = useToastr();

defineProps({
    user: Object
})

const emit = defineEmits(['userDeleted', 'editUser'])

const userIdBeingDeleted = ref(null);

const confirmUserDeletion = (user) => {
    userIdBeingDeleted.value = user.id;
    $('#deleteUserModal').modal('show');
}

const deleteUser = () => {
    axios.delete('http://laravel-vue-youtube-clovon.test/api/users/' + userIdBeingDeleted.value).then((response) => {
        toastr.success('Deleted Successfully');
        $('#deleteUserModal').modal('hide');
        emit('userDeleted', userIdBeingDeleted.value);
    }).catch((error) => {
        toastr.error(error.response.data.message);
    })
}

const editUser = (user) => {
    emit('editUser', user);
}
</script>

<template>
    <tr>
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
</template>

<style scoped>

</style>
