<template>
    <AdminLayout>
        <OrderList :orders = "orders" ></OrderList>
    </AdminLayout>
</template>

<script setup>
import UserList from "@/Pages/Admin/User/UserList.vue";

defineProps({
    orders:Array
})
import AdminLayout from '../Components/AdminLayout.vue'
import ProductList from './OrderList.vue'
import StoreList from "@/Pages/Admin/Stores/StoreList.vue";
import OrderList from "@/Pages/Admin/Orders/OrderList.vue";
</script>
