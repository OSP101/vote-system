<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import Welcometest from '@/Components/Welcometest.vue';
import { defineProps } from 'vue';

// รับข้อมูลจาก Inertia
const props = defineProps({
  items: Array
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcometest :items="props.items" />

                </div>
            </div>
        </div>


    </AppLayout>


</template>
