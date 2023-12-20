<script setup>
import UserLayouts from './Layouts/UserLayouts.vue';
import { onMounted } from 'vue';

defineProps({
    orders: Array,
});

onMounted(() => {
});
</script>

<template>
    <UserLayouts>
        <div class="relative max-w-screen-xl py-24 mx-auto overflow-x-auto">
            <table v-for="order in orders" :key="order.id"
                   class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-5">
                <thead class="text-xs text-gray-700 uppercase dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Order ID # {{ order.id }}
                        {{console.log(order)}}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{order['book_names'][0]}}
                    </th>

                    <td class="px-6 py-4">
                        {{order['total_price']}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </UserLayouts>
</template>
