<script setup>
import { computed } from 'vue'
import {
  ChevronDown,
  LayoutDashboard,
  List,
  LogOut,
  Plus,
  Settings,
  User,
  ListIcon,
  Table2,
} from 'lucide-vue-next'
import { Link, usePage } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ui/application-logo/ApplicationLogo.vue'
import Collapsible from '../collapsible/Collapsible.vue'
import CollapsibleTrigger from '../collapsible/CollapsibleTrigger.vue'
import CollapsibleContent from '../collapsible/CollapsibleContent.vue'
import Separator from '../separator/Separator.vue'

import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuSeparator,
} from '../dropdown-menu'

defineProps({
  sidebarOpen: Boolean,
})

defineEmits(['toggle-sidebar'])

const page = usePage()

const menus = [
  {
    id: 'clients',
    title: 'Clientes',
    items: [
      { label: 'Dashboard', icon: LayoutDashboard, href: '/client/dashboard' },
      { label: 'Lista', icon: List, href: '/client/index' },
      { label: 'Agregar cliente', icon: Plus, href: '/client/add' },
    ],
  },{
    id: 'installations',
    title: 'Instalaciones',
    items: [
      { label: 'Dashboard', icon: LayoutDashboard, href: '/installation/dashboard' },
      { label: 'Lista', icon: List, href: '/installation/index' },
      { label: 'Agregar instalación', icon: Plus, href: '/installation/add' },
    ],
  },{
    id: 'crud',
    title: 'CRUD',
    items: [
      { label: 'Dashboard', icon: Table2, href: '/crud/dashboard' },
      { label: 'Lista', icon: List, href: '/crud/lista' },
      { label: 'Agregar', icon: Plus, href: '/crud/agregar' },
    ],
  },
]

const user = computed(() => page.props.auth?.user ?? { name: 'Usuario', email: '' , surname: '', username: '', phone: '' })
const userInitials = computed(() => {
  return user.value.name?.charAt(0).toUpperCase() +  user.value.surname?.charAt(0).toUpperCase() || 'U'
})
</script>

<template>
  <aside class="min-h-full w-80 shrink-0 flex-col gap-0  bg-background text-sidebar-foreground lg:flex">
    <Link href="/" view-transition replace class="flex items-center gap-3 border-b border-sidebar-border p-6 pb-3">
        <ApplicationLogo class="h-6 w-6" />
    </Link>

    <div class="flex-1 overflow-y-auto p-6 border-r border-sidebar-border">
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
                  :key="item.label"
                  view-transition
                  replace
                  :href="item.href || '#'"
                  :class="{
                    'bg-primary text-primary-foreground font-semibold': $page.url === item.href,
                    'hover:bg-primary/10': $page.url !== item.href,
                  }"
                  class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-sidebar-foreground"
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

    <div class="space-y-4 border-t border-sidebar-border p-6 border-r border-sidebar-border">
      <DropdownMenu>
        <DropdownMenuTrigger class="flex w-full items-center gap-3 rounded-lg px-3 py-2 hover:bg-primary/10">
          <div class="grid h-9 w-9 place-items-center rounded-full bg-primary text-xs font-semibold text-primary-foreground">
            {{ userInitials }}
          </div>
          <div class="min-w-0 flex-1 text-left">
            <p class="truncate text-sm font-semibold">{{ user.name + ' ' + user.surname }}</p>
            <p class="truncate text-xs text-muted-foreground">{{ user.email }}</p>
          </div>
          <ChevronDown class="h-4 w-4" />
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-56">
          <DropdownMenuItem class="cursor-pointer">
            <Link href="/profile" view-transition replace class="flex w-full items-center rounded-lg text-sm text-sidebar-foreground">
              <User class="mr-2 h-4 w-4" />
              <span>Informacion</span>
            </Link>
          </DropdownMenuItem>

          <DropdownMenuItem v-if="user.role_id === 1" class="cursor-pointer">
            <Link href="/users" view-transition replace class="flex w-full items-center rounded-lg text-sm text-sidebar-foreground">
              <ListIcon class="mr-2 h-4 w-4" />
              <span>Usuarios</span>
            </Link>
          </DropdownMenuItem>

          <DropdownMenuItem class="cursor-pointer">
            <Link href="/settings" view-transition replace class="flex w-full items-center rounded-lg text-sm text-sidebar-foreground">
                <Settings class="mr-2 h-4 w-4" />
                <span>Ajustes</span>
            </Link>
          </DropdownMenuItem>

          <DropdownMenuSeparator />

          <DropdownMenuItem as-child class="cursor-pointer">
            <Link href="/logout" method="post" as="button" class="w-full text-red-400 rounded-lg text-sm">
              <LogOut class="mr-2 h-4 w-4" />
              <span>Cerrar sesion</span>
            </Link>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  </aside>
</template>
