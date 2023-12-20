<template>
    <AdminLayout>
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64"></div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-32 md:h-64"></div>
            </div>
            <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-150 mb-4">
<!--                <div>-->
<!--                    <canvas id="myChart"></canvas>-->
<!--                </div>-->
            </div>
            <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-500 mb-4">
<!--                <div>-->
<!--                    <canvas id="myBarChart"></canvas>-->
<!--                </div>-->
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { initFlowbite } from 'flowbite';
import AdminLayout from './Components/AdminLayout.vue';
import Chart from 'chart.js/auto';
import {usePage} from "@inertiajs/vue3";

const mostExpensiveBooks = ref(null);
const mostBoughtBook = ref(null);

const store_id = usePage().props.isAdmin;



// initialize components based on data attribute selectors
onMounted(async () => {
    initFlowbite();

    if (store_id) {
        console.log(store_id);
    }

    const response = await fetch('/admin/metrics/most-expensive-books');
    const data = await response.json();

    // Assign data to the variable
    mostExpensiveBooks.value = data.most_expensive_books;

    // Fetch data for the most bought book
    const mostBoughtResponse = await fetch('/admin/metrics/most-bought-book');
    const mostBoughtData = await mostBoughtResponse.json();
    mostBoughtBook.value = mostBoughtData.most_bought_books;

    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: mostExpensiveBooks.value.map((book) => book.title),
            datasets: [
                {
                    label: 'Most expensive',
                    data: mostExpensiveBooks.value.map((book) => book.price),
                    borderWidth: 1,
                },
            ],
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Book Title', // Custom X-axis label
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Price', // Custom Y-axis label
                    },
                    beginAtZero: true,
                },
            },
        },
    });


    // ...
    const barChartCtx = document.getElementById('myBarChart');
    const barChartData = {
        labels: mostBoughtBook.value ? mostBoughtBook.value.map(book => book.title) : ['No Book'],
        datasets: [
            {
                label: 'Most Bought Books',
                data: mostBoughtBook.value ? mostBoughtBook.value.map(book => book.count) : [0],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            },
        ],
    };
    new Chart(barChartCtx, {
        type: 'bar',
        data: barChartData,
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Book Title', // Custom X-axis label
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Count', // Custom Y-axis label
                    },
                    beginAtZero: true,
                },
            },
        },
    });

});

</script>
