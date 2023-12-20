<template>
    <AdminLayout>
        <RoleList :users = "users" ></RoleList>
    </AdminLayout>
</template>

<script setup>
import UserList from "@/Pages/Admin/User/UserList.vue";

defineProps({
    users:Array
})
import AdminLayout from '../Components/AdminLayout.vue'
import ProductList from './RoleList.vue'
import RoleList from "@/Pages/Admin/Roles/RoleList.vue";
</script>
