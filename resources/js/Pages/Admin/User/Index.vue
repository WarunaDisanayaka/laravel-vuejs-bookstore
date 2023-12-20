<template>
    <AdminLayout>
        <UserList :users = "users" :stores="stores" ></UserList>
    </AdminLayout>
</template>

<script setup>
import UserList from "@/Pages/Admin/User/UserList.vue";

defineProps({
    users:Array,
    stores:Array
})
import AdminLayout from '../Components/AdminLayout.vue'
import ProductList from './UserList.vue'
</script>
