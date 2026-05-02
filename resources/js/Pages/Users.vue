<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    title: String,
    users: Array
})

defineOptions({
    layout: AppLayout,
})
</script>

<template>


        <h1>{{ title }}</h1>

        <ul>
            <li v-for="user in users" :key="user.id">
                {{ user.name }} - {{ user.email }}
            </li>
        </ul>


</template>
