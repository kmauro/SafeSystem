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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';

defineOptions({
    layout: AppLayout,
})

const isEditing = ref(false);

const props = defineProps({
    installation: Object,
    clients: Object,
    categories: Object,
    states: Object,
    users: Object,
    devices: Object,
})

const form = useForm({
    id: props.installation.id,
    address: props.installation.address,
    description: props.installation.description,
    annotation: props.installation.annotation,
    category_id: props.installation.category_id,
    client_id: props.installation.client_id,
    state_id: props.installation.state_id,
    assigned_user_id: props.installation.assigned_user_id,
})

const installationDeviceForm = useForm({
    device_id: '',
    code: '',
})

const removeInstallationDeviceForm = useForm({})

function submit() {
    form.post('/installation/update', {
        onSuccess: () => {
            isEditing.value = false;
            form.reset();
        },
    });
}

function deleteClient(installationId) {
    form.post(`/installation/${installationId}/delete`, {
        onSuccess: () => {
            form.reset();
        },
    });
}

function addInstallationDevice() {
    installationDeviceForm.post(`/installation/${props.installation.id}/devices`, {
        preserveScroll: true,
        onSuccess: () => {
            installationDeviceForm.reset();
        },
    });
}

function deleteInstallationDevice(installationDeviceId) {
    removeInstallationDeviceForm.post(`/installation/${props.installation.id}/devices/${installationDeviceId}/delete`, {
        preserveScroll: true,
    });
}

</script>

<template>
    <NotificationToast />
    <section class="border p-4 rounded-lg shadow-md bg-background">
        <div class="flex items-center justify-between mb-4">
                <div class="mb-6 flex items-center justify-between space-x-4">
                    <h2 class="text-2xl font-semibold">{{ installation.address }}</h2>
                    <Alert v-if="isEditing"
                            @accept="deleteClient(installation.id)"
                            :label="'Borrar instalacion'"
                            :variant="'destructive'"
                            :title="'Eliminar instalacion?'"
                            :description="'Esta acción no se puede deshacer. Eliminará la información de esta instalacion de forma permanente.'"
                            />
                </div>
                <Button variant="outline" @click="isEditing = !isEditing" class="ml-auto"
                :class="{
                    'bg-primary text-white hover:bg-primary/10' : !isEditing,
                    'bg-secondary text-primary hover:bg-secondary/50' : isEditing,

                }">
                <div v-if="!isEditing" class="flex items-center">
                    <Edit class="size-4" />
                    <p class="ml-2">Editar instalacion</p>
                </div>
                <div v-else class="flex items-center">
                    <CircleXIcon class="size-4"/>
                    <p class="ml-2">Cancelar</p>
                </div>
                </Button>
            </div>

        <form @submit.prevent="submit">

            <Input id="id"
            v-model="form.id"
            hidden
            />

            <div class="mb-4">
                <Label for="address" class="block mb-1">Direccion</Label>
                <Input
                type="text"
                id="address"
                v-model="form.address"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.address)"
                />
                <p v-if="form.errors.address" class="text-sm text-destructive">
                    {{ form.errors.address }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="description" class="block mb-1">Descripción</Label>
                <Input
                type="text"
                id="description"
                v-model="form.description"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.description)"
                />
                <p v-if="form.errors.description" class="text-sm text-destructive">
                    {{ form.errors.description }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="annotation" class="block mb-1">Anotaciones:</Label>
                <Input
                type="text"
                id="annotation"
                v-model="form.annotation"
                :disabled = "!isEditing"
                :aria-invalid="Boolean(form.errors.annotation)"
                />
                <p v-if="form.errors.annotation" class="text-sm text-destructive">
                    {{ form.errors.annotation }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="category_id" class="block mb-1">Categoria</Label>
                <Select id="category" v-model="form.category_id" :disabled="!isEditing">
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
            </div>

            <div class="mb-4">
                <Label for="client_id" class="block mb-1">Cliente</Label>
                <Select id="client" v-model="form.client_id" :disabled="!isEditing">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Selecciona un cliente" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="client in props.clients" :key="client.id" :value="client.id">
                            {{ client.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.client_id" class="text-sm text-destructive">
                    {{ form.errors.client_id }}
                </p>
            </div>

            <div class="mb-4">
                <Label for="state" class="block mb-1">Estado</Label>
                <Select id="state" v-model="form.state_id" :disabled="!isEditing">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Selecciona un Estado" />
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
            </div>

            <div class="mb-4">
                <Label for="assigned_user" class="block mb-1">Responsable</Label>
                <Select id="assigned_user" v-model="form.assigned_user_id" :disabled="!isEditing">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Selecciona un responsable" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="user in props.users" :key="user.id" :value="user.id">
                            {{ user.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.assigned_user_id" class="text-sm text-destructive">
                    {{ form.errors.assigned_user_id }}
                </p>
            </div>

            <Button  :disabled="form.processing" type="submit" v-if="isEditing">Editar instalación</Button>

        </form>
    </section>

    <section class="mt-4 border p-4 rounded-lg shadow-md bg-background">
        <div class="mb-4">
            <h2 class="text-2xl font-semibold">Dispositivos</h2>
        </div>

        <form @submit.prevent="addInstallationDevice" class="mb-6 grid gap-4 lg:grid-cols-[1fr_1fr_auto] lg:items-end">
            <div>
                <Label for="installation_device" class="block mb-1">Dispositivo</Label>
                <Select id="installation_device" v-model="installationDeviceForm.device_id">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Selecciona un dispositivo" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="device in props.devices" :key="device.id" :value="device.id">
                            {{ device.name }} <span v-if="device.type">- {{ device.type.type }}</span>
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="installationDeviceForm.errors.device_id" class="text-sm text-destructive">
                    {{ installationDeviceForm.errors.device_id }}
                </p>
            </div>

            <div>
                <Label for="installation_device_code" class="block mb-1">Codigo</Label>
                <Input
                    id="installation_device_code"
                    v-model="installationDeviceForm.code"
                    type="text"
                    :aria-invalid="Boolean(installationDeviceForm.errors.code)"
                />
                <p v-if="installationDeviceForm.errors.code" class="text-sm text-destructive">
                    {{ installationDeviceForm.errors.code }}
                </p>
            </div>

            <Button :disabled="installationDeviceForm.processing" type="submit">
                Agregar dispositivo
            </Button>
        </form>

        <div v-if="installation.devices.length === 0" class="flex items-center justify-center">
            <p class="text-muted-foreground">No hay dispositivos agregados a esta instalacion.</p>
        </div>

        <section
            v-for="device in installation.devices"
            :key="device.pivot.id"
            class="border p-4 rounded-lg shadow-md flex flex-col justify-between gap-4 mb-4 md:flex-row md:items-center"
        >
            <div class="space-y-1">
                <h3 class="text-xl font-semibold">{{ device.name }}</h3>
                <p class="text-sm text-muted-foreground">{{ device.type?.type }}</p>
                <p class="text-sm">Codigo: {{ device.pivot.code }}</p>
            </div>

            <Alert
                :label="'Quitar dispositivo'"
                :variant="'destructive'"
                :title="'Quitar dispositivo?'"
                :description="'Esta accion quitara el dispositivo de la instalacion.'"
                @accept="deleteInstallationDevice(device.pivot.id)"
            />
        </section>
    </section>
</template>
