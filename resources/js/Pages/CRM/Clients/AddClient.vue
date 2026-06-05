<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import Button from '@/Components/ui/button/Button.vue';
import { useForm } from '@inertiajs/vue3';
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';

defineOptions({
    layout: AppLayout,
})

const form = useForm({
    name: '',
    email: '',
    phone: '',
    cuit: '',
})

function submit() {
    form.post('/client/add', {
        onSuccess: () => {
            form.reset();
        },
    });
}

</script>

<template>
    <NotificationToast />
    <form @submit.prevent="submit" class="border p-4 rounded-lg shadow-md bg-background">
        <h2 class="text-lg font-semibold mb-4">Agregar Cliente</h2>

        <div class="mb-4">
            <Label for="name" class="block mb-1">Nombre</Label>
            <Input id="name" v-model="form.name" required />
        </div>

        <div class="mb-4">
            <Label for="email" class="block mb-1">Email</Label>
            <Input id="email" type="email" v-model="form.email" required />
        </div>

        <div class="mb-4">
            <Label for="phone" class="block mb-1">Teléfono</Label>
            <Input id="phone" v-model="form.phone" required />
        </div>

        <div class="mb-4">
            <Label for="cuit" class="block mb-1">CUIT</Label>
            <Input id="cuit" v-model="form.cuit" />
        </div>

        <Button type="submit">Agregar Cliente</Button>

    </form>
</template>
