<script setup>
import { ref } from 'vue'
// Iconos
import {
  LayoutDashboard,
  Plus,
  List,
  ChevronDown,
  LogOut,
  Settings,
  User,
} from 'lucide-vue-next'

import Collapsible from '../collapsible/Collapsible.vue'
import CollapsibleTrigger from '../collapsible/CollapsibleTrigger.vue'
import CollapsibleContent from '../collapsible/CollapsibleContent.vue'

import Separator from '../separator/Separator.vue'

import { Link } from '@inertiajs/vue3'

import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
} from '../dropdown-menu'

// manejo de sidebar
defineProps({
  sidebarOpen: Boolean
})
defineEmits(['toggle-sidebar'])

// Estados para menús colapsables
const crmOpen = ref(false)
const crudOpen = ref(false)

// Menús y rutas
const menus = [
  {
    id: 'crm',
    title: 'CRM',
    items: [
      { label: 'Dashboard', icon: LayoutDashboard, href: '/crm/dashboard' },
      { label: 'Lista', icon: List, href: '/crm/lista' },
      { label: 'Agregar', icon: Plus, href: '/crm/agregar' },
    ],
  },
  {
    id: 'crud',
    title: 'CRUD',
    items: [
      { label: 'Dashboard', icon: LayoutDashboard ,href: '/crud/dashboard' },
      { label: 'Lista', icon: List, href: '/crud/lista' },
      { label: 'Agregar', icon: Plus, href: '/crud/agregar' },
    ],
  },
]

// Usuario simulado
const user = {
  name: 'Killian Mauro',
  avatar: 'KM',
}


</script>

<template>
  <aside class=" min-h-full w-80 shrink-0 flex-col gap-0 border-r border-sidebar-border bg-sidebar text-sidebar-foreground lg:flex">
    <!-- Header -->
    <div class="flex items-center gap-3 border-b border-sidebar-border p-6 pb-4">
      <div class="grid h-12 w-12 place-items-center rounded-lg bg-orange-500 text-white font-bold text-lg">
        S
      </div>

        <Link href="/"
        view-transition
        replace
        class="text-lg font-semibold"
        >SafeSystem</Link>

    </div>

    <!-- Contenido - Menús colapsables -->
    <div class="flex-1 overflow-y-auto p-6">
      <div class="space-y-4">
        <template v-for="menu in menus" :key="menu.id">

          <Collapsible :default-open="true">

            <template #default="{ open }">

              <CollapsibleTrigger class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-semibold text-sidebar-foreground hover:bg-primary/10">
                <span>{{ menu.title }}</span>
                <ChevronDown :class="['h-4 w-4 transition-transform', open ? 'rotate-180' : '']" />
              </CollapsibleTrigger>

              <CollapsibleContent class="mt-2 space-y-1 pl-3">
                <Link
                    v-for="item in menu.items"
                    view-transition
                    replace
                    :class="{ 'bg-primary text-primary font-semibold': $page.url === item.href, 'hover:bg-primary/10': $page.url !== item.href }"
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-sidebar-foreground"
                    :key="item.label"
                    :href="item.href || '#'"
                >
                  <component :is="item.icon" class="h-4 w-4" />
                  <span>{{ item.label }}</span>
                </Link>
              </CollapsibleContent>

              <Separator v-if="menu.id !== menus[menus.length - 1].id" class="my-4" />

            </template>

          </Collapsible>

        </template>
      </div>
    </div>

    <!-- Footer - Usuario -->
    <div class="space-y-4 border-t border-sidebar-border p-6">
      <DropdownMenu>

        <DropdownMenuTrigger class="flex w-full items-center gap-3 rounded-lg px-3 py-2 hover:bg-primary/10">
          <div class="grid h-9 w-9 place-items-center rounded-full bg-primary text-primary-foreground text-xs font-semibold">
            {{ user.avatar }}
          </div>
          <div class="flex-1 text-left">
            <p class="text-sm font-semibold">{{ user.name }}</p>
          </div>
          <ChevronDown class="h-4 w-4" />
        </DropdownMenuTrigger>


        <DropdownMenuContent class="w-56">

          <DropdownMenuItem class="cursor-pointer">
            <User class="mr-2 h-4 w-4" />
            <span>Información</span>
          </DropdownMenuItem>

          <DropdownMenuItem class="cursor-pointer">
            <Settings class="mr-2 h-4 w-4" />
            <span>Ajustes</span>
          </DropdownMenuItem>

          <DropdownMenuSeparator />

          <DropdownMenuItem class="cursor-pointer text-red-600">
            <LogOut class="mr-2 h-4 w-4" />
            <span>Cerrar sesión</span>
          </DropdownMenuItem>

        </DropdownMenuContent>

      </DropdownMenu>
    </div>
  </aside>
</template>
