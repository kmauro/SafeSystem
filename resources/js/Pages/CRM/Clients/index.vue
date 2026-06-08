<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';
import { Link } from '@inertiajs/vue3';

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    clients: Object,
})

const clients = props.clients;
</script>

<template>
    <NotificationToast />
    <section v-for="client in clients" class="border p-4 rounded-lg shadow-md flex justify-between mb-4" :key="client.id" >
            <div class="space-y-2">
                <h2 class="text-2xl font-semibold">{{ client.name }}</h2>
            </div>
            <div>
                <Link
                view-transition
                :href="`/clients/${client.id}`"
                class="flex items-center bg-primary text-white font-semibold hover:bg-primary/10 gap-2 rounded-lg px-3 py-2 text-sm text-sidebar-foreground"
            >
                <span>Ver mas</span>
            </Link>
            </div>
        </section>
</template>
