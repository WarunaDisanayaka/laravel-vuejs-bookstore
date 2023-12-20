<template>
    <AdminLayout>
        <StoreList :users = "users" ></StoreList>
    </AdminLayout>
</template>

<script setup>
import UserList from "@/Pages/Admin/User/UserList.vue";

defineProps({
    users:Array
})
import AdminLayout from '../Components/AdminLayout.vue'
import ProductList from './StoreList.vue'
import StoreList from "@/Pages/Admin/Stores/StoreList.vue";
</script>
