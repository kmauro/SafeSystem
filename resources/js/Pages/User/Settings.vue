<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout,
})
</script>


<template>
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Configuración</h1>
        <p>Aquí puedes configurar tus preferencias y ajustes de la cuenta.</p>
    </div>
    <section class="border p-4 bg-card rounded-lg shadow-md bg-background mt-4">
        <h2 class="text-lg font-semibold mb-2">Preferencias de Notificación</h2>
        <p>Aquí puedes configurar tus preferencias de notificación.</p>
    </section>
</template>
