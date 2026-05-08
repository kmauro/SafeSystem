<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Button } from '@/Components/ui/button'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { LayoutDashboard, Table2 } from 'lucide-vue-next'

defineProps({
  title: String,
})

defineOptions({
  layout: AppLayout,
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
</script>

<template>
  <div class="space-y-6">
    <section class="rounded-lg border border-border bg-card p-6 shadow-sm">
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="space-y-2">
          <p class="text-sm font-medium text-muted-foreground">SafeSystem</p>
          <h1 class="text-2xl font-semibold">Bienvenido, {{ user?.name }}</h1>
          <p class="max-w-2xl text-sm text-muted-foreground">
            Tu sesion esta activa. Puedes continuar al modulo CRM o al panel CRUD.
          </p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
          <Button as-child variant="default">
            <Link href="/crm/dashboard">
              <LayoutDashboard class="size-4" />
              CRM
            </Link>
          </Button>
          <Button as-child variant="outline">
            <Link href="/crud/dashboard">
              <Table2 class="size-4" />
              CRUD
            </Link>
          </Button>
        </div>
      </div>
    </section>
  </div>
</template>
