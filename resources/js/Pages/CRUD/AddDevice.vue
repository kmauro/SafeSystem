<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import Button from '@/Components/ui/button/Button.vue'
import Checkbox from '@/Components/ui/checkbox/Checkbox.vue'
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import { useForm } from '@inertiajs/vue3'

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    deviceTypes: Object,
    features: Object,
})

const form = useForm({
    name: '',
    type_id: '',
    features: [],
})

const toggleFeature = (featureId, selected) => {
    if (selected) {
        form.features = [...new Set([...form.features, featureId])]

        return
    }

    form.features = form.features.filter((id) => id !== featureId)
}

function submit() {
    form.post('/crud/agregar', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <NotificationToast />

    <form @submit.prevent="submit" class="space-y-3 rounded-lg border bg-background p-4 shadow-md">
        <h2 class="mb-4 text-lg font-semibold">Agregar Dispositivo</h2>

        <div class="mb-4">
            <Label for="name" class="mb-1 block">Nombre</Label>
            <Input
                id="name"
                v-model="form.name"
                :aria-invalid="Boolean(form.errors.name)"
            />
            <p v-if="form.errors.name" class="text-sm text-destructive">
                {{ form.errors.name }}
            </p>
        </div>

        <div class="mb-4">
            <Label for="type" class="mb-1 block">Tipo de producto</Label>
            <Select id="type" v-model="form.type_id">
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
                    @update:model-value="toggleFeature(feature.id, $event)"
                />
                <span>{{ feature.feature }}</span>
            </label>

            <p v-if="form.errors.features" class="text-sm text-destructive">
                {{ form.errors.features }}
            </p>
        </fieldset>

        <Button :disabled="form.processing" type="submit">Agregar Dispositivo</Button>
    </form>
</template>
