<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import Button from '@/Components/ui/button/Button.vue';
import { computed, ref } from 'vue';
import { Edit, CircleXIcon } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { NativeSelect, NativeSelectOption } from '@/Components/ui/native-select';

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    user: Object,
    roles: Array,
    isCurrentUser: Boolean,
})

const isEditing = ref(false);
const changingPass = ref(false);

const showMessage = ref(false);

const page = usePage();
const successMessage = computed(() => page.props.flash.success);
const errorMessage = computed(() => page.props.flash.error);

const profileForm = useForm({
    id : props.user.id,
    name : props.user.name,
    email : props.user.email,
    surname : props.user.surname,
    username : props.user.username,
    phone : props.user.phone,
    role_id : props.user.role_id,
})

const passForm = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
})

const updateProfile = () => {
    profileForm.post('/profile', {
        onSuccess: () => onSuccess(isEditing)
    }, {
        onError: () => onError()
    });
}

const updatePassword = () => {
    passForm.post('/profile/updatePassword', {
        onSuccess: () => onSuccess(changingPass, passForm)
    }, {
        onError: () => onError()
    });
}

function onSuccess(editingForm, form = null) {
    if(form){
        form.reset()
    }
    editingForm.value = false
    showMessage.value = true
    setTimeout(() => {
        showMessage.value = false
    }, 2000)
}

function onError() {
    showMessage.value = true
    setTimeout(() => {
        showMessage.value = false
    }, 2000)

}

</script>

<template>

    <Transition
        enter-active-class="transition duration-300"
        enter-from-class="opacity-0 -translate-y-3"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-300"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-3"
    >
        <div
            v-if="
            showMessage"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50
                   bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg"
            :class="{'bg-green-600' : successMessage,
                     'bg-red-600' : errorMessage}"
        >
            {{ successMessage ? successMessage : errorMessage }}
        </div>
    </Transition>

    <section class="border p-4 rounded-lg shadow-md bg-background">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Información del Perfil</h2>
            <Button variant="outline" @click="isEditing = !isEditing" class="ml-auto"
            :class="{
                'bg-primary text-white hover:bg-destructive/90' : isEditing,

            }">
                <Edit v-if="!isEditing" class="size-4" />
                <CircleXIcon v-else class="size-4" />
            </Button>
        </div>
        <form @submit.prevent="updateProfile" class="mt-4 space-y-4">
            <Label for="username">Nombre de Usuario:</Label>
            <Input
            type="text"
            id="username"
            v-model="profileForm.username"
            disabled
            :aria-invalid="Boolean(profileForm.errors.username)"
            />
            <p v-if="profileForm.errors.username" class="text-sm text-destructive">
                {{ profileForm.errors.username }}
            </p>

            <Label for="role">Rol:</Label>
            <NativeSelect id="role" v-model="profileForm.role_id" :disabled="!isEditing" :aria-invalid="Boolean(profileForm.errors.role_id)">
                <NativeSelectOption v-for="role in roles" :key="role.id" :value="role.id">
                    {{ role.role_name }}
                </NativeSelectOption>

            </NativeSelect>
            <p v-if="profileForm.errors.role_id" class="text-sm text-destructive">
                {{ profileForm.errors.role_id }}
            </p>

            <Label for="name">Nombre:</Label>
            <Input
            type="text"
            id="name"
            v-model="profileForm.name"
            :disabled = "!isEditing"
            :aria-invalid="Boolean(profileForm.errors.name)"
            />
            <p v-if="profileForm.errors.name" class="text-sm text-destructive">
                {{ profileForm.errors.name }}
            </p>

            <Label for="surname">Apellido:</Label>
            <Input
            type="text"
            id="surname"
            v-model="profileForm.surname"
            :disabled = "!isEditing"
            :aria-invalid="Boolean(profileForm.errors.surname)"
            />
            <p v-if="profileForm.errors.surname" class="text-sm text-destructive">
                {{ profileForm.errors.surname }}
            </p>

            <Label for="phone">Telefono:</Label>
            <Input
            type="text"
            id="phone"
            v-model="profileForm.phone"
            :disabled = "!isEditing"
            :aria-invalid="Boolean(profileForm.errors.phone)"
            />
            <p v-if="profileForm.errors.phone" class="text-sm text-destructive">
                {{ profileForm.errors.phone }}
            </p>

            <Label for="email">Correo Electrónico:</Label>
            <Input
            type="email"
            id="email"
            v-model="profileForm.email"
            :disabled = "!isEditing"
            :aria-invalid="Boolean(profileForm.errors.email)"
            />
            <p v-if="profileForm.errors.email" class="text-sm text-destructive">
                {{ profileForm.errors.email }}
            </p>
            <Button :disabled="profileForm.processing" v-if="isEditing">Actualizar Perfil</Button>

        </form>
    </section>

    <section v-if="isCurrentUser" class="mt-6 border p-4 rounded-lg shadow-md bg-background">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold mb-4">Cambiar Contraseña</h2>
            <Button variant="outline" @click="changingPass = !changingPass" class="ml-auto">
                <Edit v-if="!changingPass" class="size-4" />
                <CircleXIcon v-else class="size-4" />
            </Button>
        </div>
        <form @submit.prevent="updatePassword" class="space-y-4">
            <Label for="current_password">Contraseña Actual:</Label>
            <Input
            type="password"
            id="current_password"
            v-model="passForm.current_password"
            :disabled = "!changingPass"
            :aria-invalid="Boolean(passForm.errors.current_password)"
            />
            <p v-if="passForm.errors.current_password" class="text-sm text-destructive">
                {{ passForm.errors.current_password }}
            </p>
            <Label for="new_password">Nueva Contraseña:</Label>
            <Input
            type="password"
            id="new_password"
            v-model="passForm.new_password"
            :disabled = "!changingPass"
            :aria-invalid="Boolean(passForm.errors.new_password)"
            />
            <p v-if="passForm.errors.new_password" class="text-sm text-destructive">
                {{ passForm.errors.new_password }}
            </p>
            <Label for="new_password_confirmation">Confirmar Nueva Contraseña:</Label>
            <Input
            type="password"
            id="new_password_confirmation"
            v-model="passForm.new_password_confirmation"
            :disabled = "!changingPass"
            :aria-invalid="Boolean(passForm.errors.new_password_confirmation)"
            />
            <p v-if="passForm.errors.new_password_confirmation" class="text-sm text-destructive">
                {{ passForm.errors.new_password_confirmation }}
            </p>
            <Button :disabled="passForm.processing" v-if="changingPass">Actualizar Contraseña</Button>

        </form>
    </section>

</template>
