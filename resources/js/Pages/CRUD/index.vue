<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/Components/ui/input'
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue'
import { Link, router } from '@inertiajs/vue3'
import { reactive, watch } from 'vue'
import { useDebounceFn } from '@vueuse/core'

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    devices: Object,
    deviceTypes: Object,
    filters: Object,
})

const normalizeTypeId = (type) => {
    const typeId = type?.id ?? type

    return typeId ? Number(typeId) : null
}

const filters = reactive({
    search: props.filters.search ?? '',
    type: normalizeTypeId(props.filters.type),
})

const reload = useDebounceFn(() => {
    router.get(
        '/crud/lista',
        filters,
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        }
    )
}, 300)

watch(filters, reload, { deep: true })

const selectType = (type) => {
    const typeId = normalizeTypeId(type)

    filters.type = filters.type === typeId ? null : typeId
}
</script>

<template>
    <NotificationToast />

    <section class="flex flex-col justify-between gap-4 px-6 py-3 lg:flex-row">
        <div class="grid grid-cols-2 gap-2 md:grid-cols-4 lg:grid-cols-5">
            <button
                v-for="type in deviceTypes"
                :key="type.id"
                type="button"
                class="rounded-xl border px-3 py-1 text-xs shadow-sm transition-colors"
                :class="filters.type === type.id ? 'bg-primary text-primary-foreground' : 'hover:bg-primary/10'"
                @click="selectType(type)"
            >
                {{ type.type }}
            </button>
        </div>

        <div class="flex gap-2">
            <Input
                v-model="filters.search"
                type="text"
                placeholder="Buscar..."
                class="w-full rounded border px-3 py-2"
            />
        </div>
    </section>

    <div v-if="devices.length === 0" class="flex items-center justify-center">
        <p class="text-muted-foreground">No se encontraron dispositivos.</p>
    </div>

    <section
        v-for="device in devices"
        :key="device.id"
        class="mb-4 flex flex-col justify-between gap-4 rounded-lg border p-4 shadow-md md:flex-row md:items-center"
    >
        <div class="space-y-2">
            <h2 class="text-2xl font-semibold">{{ device.name }}</h2>
            <p class="text-sm text-muted-foreground">{{ device.type?.type }}</p>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="feature in device.features"
                    :key="feature.id"
                    class="rounded-md border px-2 py-1 text-xs"
                >
                    {{ feature.feature }}
                </span>
            </div>
        </div>

        <Link
            view-transition
            :href="`/crud/${device.id}`"
            class="flex items-center justify-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-white hover:bg-primary/10"
        >
            <span>Ver mas</span>
        </Link>
    </section>
</template>
