<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import Button from '@/Components/ui/button/Button.vue';
import { useForm } from '@inertiajs/vue3';
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';
import Combobox from '@/Components/ui/Combobox/Combobox.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    clients: Object,
    categories: Object,
    states: Object,
    users: Object,
})

const form = useForm({
    id:'',
    address: '',
    category_id: '',
    state_id: '',
    client_id: '',
    client_name: '',
    assigned_user_id: 'id Actual',
    description: '',
    annotation: '',
})

function submit() {
    form.post('/installation/add', {
        onSuccess: () => {
            form.reset();
        },
    });
}

</script>

<template>
    <NotificationToast />
    <form @submit.prevent="submit" class=" space-y-3 border p-4 rounded-lg shadow-md bg-background">
        <h2 class="text-lg font-semibold mb-4">Agregar Instalación</h2>

        <div class="mb-4">
            <Label for="address" class="block mb-1">Direccion</Label>
            <Input
            id="address"
            v-model="form.address"
            :aria-invalid="Boolean(form.errors.address)"
            />
            <p v-if="form.errors.address" class="text-sm text-destructive">
                {{ form.errors.address }}
            </p>
        </div>

        <div class="mb-4">
            <Label for="description" class="block mb-1">Descripción</Label>
            <Input
            id="description"
            type="text"
            v-model="form.description"
            :aria-invalid="Boolean(form.errors.description)"
            />
            <p v-if="form.errors.description" class="text-sm text-destructive" >{{ form.errors.description }}</p>
        </div>

        <div class="mb-4">
            <Label for="annotation" class="block mb-1">Anotaciones</Label>
            <Input id="annotation"
             type="text"
             v-model="form.annotation"
             :aria-invalid="Boolean(form.errors.annotation)"
             />
             <p v-if="form.errors.annotation" class="text-sm text-destructive">
                {{ form.errors.annotation }}
            </p>
        </div>

        <Label for="client">Cliente:</Label>
            <Combobox
            id="client"
            v-model="form.client_id"
            v-model:new-value="form.client_name"
            :options="props.clients"
            />
            <p v-if="form.errors.client_id" class="text-sm text-destructive">
                {{ form.errors.client_id }}
            </p>

            <Label for="category">Tipo de instalacion:</Label>
            <Select id="category" v-model="form.category_id">
                <SelectTrigger class="w-full">
                    <SelectValue placeholder="Selecciona una categoria" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="category in props.categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="form.errors.category_id" class="text-sm text-destructive">
                {{ form.errors.category_id }}
            </p>

            <Label for="state">Estado:</Label>
            <Select id="state" v-model="form.state_id">
                <SelectTrigger class="w-full">
                    <SelectValue placeholder="Selecciona un estado" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem v-for="state in props.states" :key="state.id" :value="state.id">
                        {{ state.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="form.errors.state_id" class="text-sm text-destructive">
                {{ form.errors.state_id }}
            </p>

            <Label for="user">Responsable:</Label>
            <Combobox
            id="user"
            v-model="form.assigned_user_id"
            v-model:new-value="form.assigned_user_name"
            :options="props.users"
            />
            <p v-if="form.errors.assigned_user_id" class="text-sm text-destructive">
                {{ form.errors.assigned_user_id }}
            </p>

        <Button type="submit">Agregar Instalación</Button>

    </form>
</template>
