<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Edit, CircleXIcon } from 'lucide-vue-next';
import Input from '@/Components/ui/input/Input.vue';
import Button from '@/Components/ui/button/Button.vue';
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';
import Label from '@/Components/ui/label/Label.vue';
import Alert from '@/Components/ui/alert/Alert.vue';

defineOptions({
    layout: AppLayout,
})

const isEditing = ref(false);

const props = defineProps({
    client: Object,
})

const form = useForm({
    id: props.client.id,
    name: props.client.name,
    email: props.client.email,
    phone: props.client.phone,
    cuit: props.client.cuit,
})

function submit() {
    form.post('/client/update', {
        onSuccess: () => {
            isEditing.value = false;
            form.reset();
        },
    });
}

function deleteClient(clientId) {
    form.post(`/client/${clientId}/delete`, {
        onSuccess: () => {
            form.reset();
        },
    });
}

</script>

<template>
    <NotificationToast />
    <section class="border p-4 rounded-lg shadow-md bg-background">
        <div class="flex items-center justify-between mb-4">
                <div class="mb-6 flex items-center justify-between space-x-4">
                    <h2 class="text-2xl font-semibold">{{ client.name }}</h2>
                    <Alert v-if="isEditing"
                            @accept="deleteClient(client.id)"
                            :label="'Borrar cliente'"
                            :variant="'destructive'"
                            :title="'Eliminar cliente?'"
                            :description="'Esta acción no se puede deshacer. Eliminará la información de este cliente de forma permanente.'"
                            />
                </div>
                <Button variant="outline" @click="isEditing = !isEditing" class="ml-auto"
                :class="{
                    'bg-primary text-white hover:bg-primary/10' : !isEditing,
                    'bg-secondary text-primary hover:bg-secondary/50' : isEditing,

                }">
                <div v-if="!isEditing" class="flex items-center">
                    <Edit class="size-4" />
                    <p class="ml-2">Editar cliente</p>
                </div>
                <div v-else class="flex items-center">
                    <CircleXIcon class="size-4"/>
                    <p class="ml-2">Cancelar</p>
                </div>
                </Button>
            </div>

        <form @submit.prevent="submit">

            <div class="mb-4">
                <Label for="name" class="block mb-1">Nombre</Label>
                <Input
                type="text"
                id="name"
                v-model="form.name"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.name)"
                />
                <p v-if="form.errors.name" class="text-sm text-destructive">
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="email" class="block mb-1">Email</Label>
                <Input
                type="text"
                id="email"
                v-model="form.email"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.email)"
                />
                <p v-if="form.errors.email" class="text-sm text-destructive">
                    {{ form.errors.email }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="phone" class="block mb-1">Teléfono</Label>
                <Input
                type="text"
                id="phone"
                v-model="form.phone"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.phone)"
                />
                <p v-if="form.errors.phone" class="text-sm text-destructive">
                    {{ form.errors.phone }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="cuit" class="block mb-1">CUIT</Label>
                <Input
                type="text"
                id="cuit"
                v-model="form.cuit"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.cuit)"
                />
                <p v-if="form.errors.cuit" class="text-sm text-destructive">
                    {{ form.errors.cuit }}
                </p>
            </div>

            <Button  :disabled="form.processing" type="submit" v-if="isEditing">Editar cliente</Button>

        </form>
    </section>
</template>
