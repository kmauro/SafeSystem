<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { CircleXIcon, Edit } from 'lucide-vue-next'
import Input from '@/Components/ui/input/Input.vue'
import Button from '@/Components/ui/button/Button.vue'
import Checkbox from '@/Components/ui/checkbox/Checkbox.vue'
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue'
import Label from '@/Components/ui/label/Label.vue'
import Alert from '@/Components/ui/alert/Alert.vue'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'

defineOptions({
    layout: AppLayout,
})

const isEditing = ref(false)

const props = defineProps({
    device: Object,
    deviceTypes: Object,
    features: Object,
})

const form = useForm({
    id: props.device.id,
    name: props.device.name,
    type_id: props.device.type_id,
    features: props.device.features?.map((feature) => feature.id) ?? [],
})

const toggleFeature = (featureId, selected) => {
    if (selected) {
        form.features = [...new Set([...form.features, featureId])]

        return
    }

    form.features = form.features.filter((id) => id !== featureId)
}

function submit() {
    form.post('/crud/update', {
        onSuccess: () => {
            isEditing.value = false
        },
    })
}

function deleteDevice(deviceId) {
    form.post(`/crud/${deviceId}/delete`)
}
</script>

<template>
    <NotificationToast />

    <section class="rounded-lg border bg-background p-4 shadow-md">
        <div class="mb-4 flex items-center justify-between">
            <div class="mb-6 flex items-center justify-between space-x-4">
                <h2 class="text-2xl font-semibold">{{ device.name }}</h2>
                <Alert
                    v-if="isEditing"
                    :label="'Borrar dispositivo'"
                    :variant="'destructive'"
                    :title="'Eliminar dispositivo?'"
                    :description="'Esta accion no se puede deshacer. Eliminara la informacion de este dispositivo de forma permanente.'"
                    @accept="deleteDevice(device.id)"
                />
            </div>

            <Button
                variant="outline"
                class="ml-auto"
                :class="{
                    'bg-primary text-white hover:bg-primary/10': !isEditing,
                    'bg-secondary text-primary hover:bg-secondary/50': isEditing,
                }"
                @click="isEditing = !isEditing"
            >
                <div v-if="!isEditing" class="flex items-center">
                    <Edit class="size-4" />
                    <p class="ml-2">Editar dispositivo</p>
                </div>
                <div v-else class="flex items-center">
                    <CircleXIcon class="size-4" />
                    <p class="ml-2">Cancelar</p>
                </div>
            </Button>
        </div>

        <form @submit.prevent="submit">
            <Input
                id="id"
                v-model="form.id"
                hidden
            />

            <div class="mb-4">
                <Label for="name" class="mb-1 block">Nombre</Label>
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    :disabled="!isEditing"
                    :aria-invalid="Boolean(form.errors.name)"
                />
                <p v-if="form.errors.name" class="text-sm text-destructive">
                    {{ form.errors.name }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="type" class="mb-1 block">Tipo de producto</Label>
                <Select id="type" v-model="form.type_id" :disabled="!isEditing">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Selecciona un tipo" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="type in props.deviceTypes" :key="type.id" :value="type.id">
                            {{ type.type }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.type_id" class="text-sm text-destructive">
                    {{ form.errors.type_id }}
                </p>
            </div>

            <fieldset class="mb-4 space-y-2 rounded-lg border p-3">
                <legend class="px-1 text-sm font-medium">Caracteristicas</legend>

                <div v-if="props.features.length === 0" class="text-sm text-muted-foreground">
                    No hay caracteristicas disponibles.
                </div>

                <label
                    v-for="feature in props.features"
                    :key="feature.id"
                    :for="`feature-${feature.id}`"
                    class="flex items-center gap-2 text-sm"
                >
                    <Checkbox
                        :id="`feature-${feature.id}`"
                        :model-value="form.features.includes(feature.id)"
                        :disabled="!isEditing"
                        @update:model-value="toggleFeature(feature.id, $event)"
                    />
                    <span>{{ feature.feature }}</span>
                </label>

                <p v-if="form.errors.features" class="text-sm text-destructive">
                    {{ form.errors.features }}
                </p>
            </fieldset>

            <Button v-if="isEditing" :disabled="form.processing" type="submit">Editar dispositivo</Button>
        </form>
    </section>
</template>
