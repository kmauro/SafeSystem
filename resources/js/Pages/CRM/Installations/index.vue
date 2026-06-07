<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { getStateActive, getStateClasses } from '@/Constants/ui';
import { Input } from '@/Components/ui/input';
import NotificationToast from '@/Components/ui/notification-toast/NotificationToast.vue';
import { watch, reactive, ref } from 'vue';
import { useDebounceFn } from '@vueuse/core';

defineOptions({
    layout: AppLayout,
})

const props = defineProps({
    installations: Object,
    filters: Object,
    states: Object
})

const filters = reactive({
    search: props.filters.search ?? '',
    state: props.filters.state ?? null,
})

const reload = useDebounceFn(() => {
    router.get(
        '/installation/index',
        filters,
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        }
    )
}, 300)

watch(filters, reload, { deep: true })

const selectState = (state) => {
    filters.state = filters.state === state ? null : state
}

</script>

<template>
    <NotificationToast />

    <section class="flex justify-between gap-4 px-6 py-3">
        <div class=" grid grid-cols-5 gap-2">
            <button
            v-for="state in states"
            @click="selectState(state)"
            :key="state.id"
            class=" text-xs pl-3 pt-1 pb-1 pr-3 rounded-xl border-1 inset-shadow-sm/18"
            :class="
                filters.state === state ? getStateActive(state.color) : getStateClasses(state.color)"
            >
                {{ state.name }}
            </button>
        </div>
        <div class="flex gap-2">
            <Input
                v-model="filters.search"
                type="text"
                placeholder="Buscar..."
                class="border rounded px-3 py-2 w-full"
            />
        </div>
    </section>

    <div v-if="installations.length === 0" class="flex items-center justify-center">
        <p class="text-muted-foreground">No se encontraron instalaciones.</p>
    </div>

    <section v-for="installation in installations" class="border p-4 rounded-lg shadow-md flex justify-between mb-4" :key="installation.id" :class="getStateClasses(installation.color)">
            <div class="space-y-2">
                <h2 class="text-2xl font-semibold">{{ installation.address }}</h2>
            </div>
            <div>
                <Link
                view-transition
                :href="`/installation/${installation.id}`"
                class="flex items-center bg-primary text-white font-semibold hover:bg-primary/10 gap-2 rounded-lg px-3 py-2 text-sm text-sidebar-foreground"
            >
                <span>Ver mas</span>
            </Link>
            </div>
        </section>
</template>
