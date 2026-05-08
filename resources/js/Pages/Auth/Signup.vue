<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Link, useForm } from '@inertiajs/vue3'
import { UserPlus } from 'lucide-vue-next'

defineProps({
  title: String,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
}).dontRemember('password', 'password_confirmation')

function submit() {
  form.post('/signup', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <AuthLayout
    :title="title"
    subtitle="Crea tu cuenta para acceder a SafeSystem."
    action-text="Ya tienes cuenta?"
    action-label="Log In"
    action-href="/login"
  >
    <form class="space-y-5" @submit.prevent="submit">
      <div class="space-y-2">
        <Label for="name">Nombre</Label>
        <Input
          id="name"
          v-model="form.name"
          type="text"
          name="name"
          autocomplete="name"
          autofocus
          :aria-invalid="Boolean(form.errors.name)"
          placeholder="Tu nombre"
        />
        <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
      </div>

      <div class="space-y-2">
        <Label for="email">Email</Label>
        <Input
          id="email"
          v-model="form.email"
          type="email"
          name="email"
          autocomplete="email"
          :aria-invalid="Boolean(form.errors.email)"
          placeholder="nombre@correo.com"
        />
        <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
      </div>

      <div class="space-y-2">
        <Label for="password">Password</Label>
        <Input
          id="password"
          v-model="form.password"
          type="password"
          name="password"
          autocomplete="new-password"
          :aria-invalid="Boolean(form.errors.password)"
          placeholder="Minimo 8 caracteres"
        />
        <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
      </div>

      <div class="space-y-2">
        <Label for="password_confirmation">Confirmar password</Label>
        <Input
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          name="password_confirmation"
          autocomplete="new-password"
          placeholder="Repite tu password"
        />
      </div>

      <Button type="submit" class="w-full" :disabled="form.processing">
        <UserPlus class="size-4" />
        {{ form.processing ? 'Creando cuenta...' : 'Crear cuenta' }}
      </Button>

      <p class="text-center text-xs text-muted-foreground">
        Al crear una cuenta aceptas usar el sistema de forma responsable.
      </p>
    </form>
  </AuthLayout>
</template>
