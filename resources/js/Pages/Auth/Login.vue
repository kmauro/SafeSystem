<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Button } from '@/Components/ui/button'
import { Checkbox } from '@/Components/ui/checkbox'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Link, useForm } from '@inertiajs/vue3'
import { LogIn } from 'lucide-vue-next'

defineProps({
  title: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
}).dontRemember('password')

function submit() {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <AuthLayout
    :title="title"
    subtitle="Ingresa tus credenciales para continuar."
    action-text="No tienes cuenta?"
    action-label="Crear cuenta"
    action-href="/signup"
  >
    <form class="space-y-5" @submit.prevent="submit">
      <div class="space-y-2">
        <Label for="email">Email</Label>
        <Input
          id="email"
          v-model="form.email"
          type="email"
          name="email"
          autocomplete="email"
          autofocus
          :aria-invalid="Boolean(form.errors.email)"
          placeholder="nombre@correo.com"
        />
        <p v-if="form.errors.email" class="text-sm text-destructive">{{ form.errors.email }}</p>
      </div>

      <div class="space-y-2">
        <div class="flex items-center justify-between gap-3">
          <Label for="password">Password</Label>
          <Link href="/signup" class="text-sm font-medium text-primary underline-offset-4 hover:underline">
            Sign Up
          </Link>
        </div>
        <Input
          id="password"
          v-model="form.password"
          type="password"
          name="password"
          autocomplete="current-password"
          :aria-invalid="Boolean(form.errors.password)"
          placeholder="Tu password"
        />
        <p v-if="form.errors.password" class="text-sm text-destructive">{{ form.errors.password }}</p>
      </div>

      <Label for="remember" class="flex cursor-pointer items-center gap-3 text-muted-foreground">
        <Checkbox id="remember" v-model="form.remember" name="remember" />
        Recordarme
      </Label>

      <Button type="submit" class="w-full" :disabled="form.processing">
        <LogIn class="size-4" />
        {{ form.processing ? 'Ingresando...' : 'Ingresar' }}
      </Button>
    </form>
  </AuthLayout>
</template>
