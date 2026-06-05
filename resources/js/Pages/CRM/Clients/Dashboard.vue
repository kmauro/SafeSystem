<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    title: String,
})

defineOptions({
    layout: AppLayout,
})
</script>

<template>


        <h1>{{ title }}</h1>

        <p>
            Bienvenido al sistema
        </p>


</template>
