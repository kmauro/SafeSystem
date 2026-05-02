<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Button  from '@/Components/ui/button/Button.vue';

</script>

<template>
    <aside class="sidebar">
        <ul>
            <li><Link href="/">Dashboard</Link></li>
            <li><Link href="/products">Productos</Link></li>
            <li><Link href="/users">Usuarios</Link></li>
            <Button>Crear Producto</Button>
        </ul>
    </aside>
</template>

<style scoped>
.sidebar {
    width: 250px;
    background: white;
    color: #333;
    padding: 1rem;
}
</style>
