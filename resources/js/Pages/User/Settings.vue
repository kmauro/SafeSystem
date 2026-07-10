<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Input from '@/Components/ui/input/Input.vue';
import Button from '@/Components/ui/button/Button.vue';
import Label from '@/Components/ui/label/Label.vue';
import { useForm } from '@inertiajs/vue3';
import { stateClasses, getStateClasses } from '@/Constants/ui';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';
defineOptions({
    layout: AppLayout,
})

const categoryForm = useForm({
    name: null,
})
const stateForm = useForm({
    name: null,
    color: 'default',
})

const typeForm = useForm({
    type: null,
})

const featureForm = useForm({
    feature: null,
})

function submit(form, route) {
    form.post(route, {
        onSuccess: () => {
            form.feature = null;
            form.type = null;
            form.name = null;
            form.color = null;
        },
    });
}
</script>


<template>
    <NotificationToast />
    <div class="p-4">
        <h1 class="text-2xl font-bold mb-4">Configuración</h1>
        <p>Aquí puedes configurar tus preferencias y ajustes de la cuenta.</p>
    </div>

    <form @submit.prevent="submit(featureForm, '/config/addFeature')" class="border p-4 bg-card space-y-2 rounded-lg shadow-md bg-background mt-4">
        <h2 class="text-lg font-semibold mb-2">Caracteristicas</h2>
        <Label for="feature">Nombre de la caracteristica:</Label>
        <Input
        id="feature"
         placeholder="Caracteristica"
         v-model="featureForm.feature"
         :aria-invalid="Boolean(featureForm.errors.feature)"
          />
        <p v-if="featureForm.errors.feature" class="text-sm text-destructive">
            {{ featureForm.errors.feature }}
        </p>
        <Button type="submit" class="mt-2">Agregar</Button>
    </form>

    <form @submit.prevent="submit(typeForm, '/config/addDeviceType')" class="border p-4 bg-card space-y-2 rounded-lg shadow-md bg-background mt-4">
        <h2 class="text-lg font-semibold mb-2">Tipo de dispositivo</h2>
        <Label for="type">Tipo:</Label>
        <Input
        id="type"
         placeholder="Tipo"
         v-model="typeForm.type"
         :aria-invalid="Boolean(typeForm.errors.type)"
          />
        <p v-if="typeForm.errors.type" class="text-sm text-destructive">
            {{ typeForm.errors.type }}
        </p>
        <Button type="submit" class="mt-2">Agregar</Button>
    </form>

    <form @submit.prevent="submit(categoryForm, '/config/addCategory')" class="border p-4 bg-card space-y-2 rounded-lg shadow-md bg-background mt-4">
        <h2 class="text-lg font-semibold mb-2">Categorias</h2>
        <Label for="category">Nombre de la categoria:</Label>
        <Input
        id="category"
         placeholder="Categoria"
         v-model="categoryForm.name"
         :aria-invalid="Boolean(categoryForm.errors.name)"
          />
        <p v-if="categoryForm.errors.name" class="text-sm text-destructive">
            {{ categoryForm.errors.name }}
        </p>
        <Button type="submit" class="mt-2">Agregar</Button>
    </form>

    <form @submit.prevent="submit(stateForm, '/config/addState')" class="border p-4 bg-card rounded-lg shadow-md bg-background mt-4 space-y-2">
        <h2>Agregar Estado para expediente</h2>
        <Label for="stateName">Nombre:</Label>
        <Input
        id="stateName"
        v-model="stateForm.name"
        placeholder="Estado"
        :aria-invalid="Boolean(stateForm.errors.name)"
        />
        <p v-if="stateForm.errors.name" class="text-sm text-destructive">
            {{ stateForm.errors.name }}
        </p>
        <div class="w-60 mt-4">
            <Select id="object" :aria-invalid="Boolean(stateForm.errors.color)" v-model="stateForm.color">
                <SelectTrigger class="w-full">
                    <SelectValue placeholder="Selecciona un color" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                    v-for="state in stateClasses"
                    :key="state.value"
                    :value="state.value"
                    >
                    {{ state.label }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="stateForm.errors.color" class="text-sm text-destructive">{{ stateForm.errors.color }}</p>
        </div>
        <span>Vista previa:</span>
        <section class="border p-4 rounded-lg shadow-md flex justify-between mb-4" :class="getStateClasses(stateForm.color)">
            <div class="space-y-2">
                <h2 class="text-2xl font-semibold">Av. Falsa 123</h2>
            </div>
            <div>
                <Link
                href="#"
                class="flex items-center bg-primary text-white font-semibold hover:bg-primary/10 gap-2 rounded-lg px-3 py-2 text-sm text-sidebar-foreground"
                >
                    <span>Ver mas</span>
                </Link>
            </div>
        </section>

        <Button type="submit" :disabled="stateForm.processing">Agregar estado</Button>
    </form>
</template>
