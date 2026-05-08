<script setup>
import { Link } from '@inertiajs/vue3'
import { ShieldCheck } from 'lucide-vue-next'
import ThemeToggle from '@/Components/ThemeToggle.vue'

defineProps({
  title: String,
  subtitle: String,
  actionText: String,
  actionLabel: String,
  actionHref: String,
})
</script>

<template>
  <div class="min-h-screen bg-background text-foreground">
    <div class="mx-auto grid min-h-screen w-full max-w-6xl grid-cols-1 lg:grid-cols-[1fr_420px]">
      <section class="hidden flex-col justify-between border-r border-border px-10 py-8 lg:flex">
        <Link href="/login" class="flex items-center gap-3 text-lg font-semibold">
          <span class="grid size-11 place-items-center rounded-lg bg-primary text-primary-foreground">
            <ShieldCheck class="size-5" />
          </span>
          SafeSystem
        </Link>
        <ThemeToggle />

        <div class="max-w-xl space-y-4">
          <p class="text-sm font-medium uppercase text-muted-foreground">Acceso seguro</p>
          <h1 class="text-4xl font-semibold leading-tight">Gestiona tu sistema desde un entorno protegido.</h1>
          <p class="text-base text-muted-foreground">
            Ingresa con tu cuenta o crea una nueva para continuar.
          </p>
        </div>

        <div class="text-sm text-muted-foreground">SafeSystem</div>
      </section>

      <main class="flex min-h-screen items-center justify-center px-6 py-10">
        <div class="w-full max-w-sm">
          <div class="mb-8 flex items-center justify-between lg:hidden">
            <Link href="/login" class="flex items-center gap-2 text-base font-semibold">
              <span class="grid size-9 place-items-center rounded-lg bg-primary text-primary-foreground">
                <ShieldCheck class="size-4" />
              </span>
              SafeSystem
            </Link>
          </div>

          <div class="rounded-lg border border-border bg-card p-6 text-card-foreground shadow-sm">
            <div class="mb-6 space-y-2">
              <h2 class="text-2xl font-semibold">{{ title }}</h2>
              <p class="text-sm text-muted-foreground">{{ subtitle }}</p>
            </div>

            <slot />

            <p class="mt-6 text-center text-sm text-muted-foreground">
              {{ actionText }}
              <Link :href="actionHref" class="font-medium text-primary underline-offset-4 hover:underline">
                {{ actionLabel }}
              </Link>
            </p>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
