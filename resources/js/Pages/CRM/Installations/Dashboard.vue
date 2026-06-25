<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { getStateActive, getStateClasses } from '@/Constants/ui'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    title: String,
    installationStatistics: {
        type: Object,
        default: () => ({
            total: 0,
            states: [],
        }),
    },
})

defineOptions({
    layout: AppLayout,
})

const stateStatistics = computed(() => props.installationStatistics?.states ?? [])
const totalInstallations = computed(() => props.installationStatistics?.total ?? 0)

const percentageFormatter = new Intl.NumberFormat('es-AR', {
    maximumFractionDigits: 1,
})

const formatPercentage = (percentage) => percentageFormatter.format(percentage)
const getInstallationsHref = (state) => `/installations?state=${state.id}`
</script>

<template>
    <div class="space-y-6">
        <header class="flex flex-col justify-between gap-4 rounded-lg border bg-card p-5 shadow-sm sm:flex-row sm:items-center">
            <div class="space-y-1">
                <h1 class="text-2xl font-semibold tracking-normal text-foreground">{{ title }}</h1>
                <p class="text-sm text-muted-foreground">Resumen de instalaciones.</p>
            </div>

            <div class="rounded-lg border bg-background px-4 py-3 text-left sm:text-right">
                <p class="text-sm text-muted-foreground">Total de instalaciones</p>
                <p class="text-3xl font-semibold text-foreground">{{ totalInstallations }}</p>
            </div>
        </header>

        <section v-if="stateStatistics.length === 0" class="rounded-lg border bg-card p-6 text-sm text-muted-foreground">
            No hay estados configurados para calcular estadisticas.
        </section>

        <section v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <Link
                v-for="state in stateStatistics"
                :key="state.id"
                :href="getInstallationsHref(state)"
                view-transition
                prefetch
                class="block rounded-lg border bg-card p-5 shadow-sm transition-transform duration-200 ease-out hover:scale-[1.02] hover:shadow-md focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:outline-none"
            >
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm text-muted-foreground">Estado</p>
                        <h2 class="truncate text-lg font-semibold text-foreground">{{ state.name }}</h2>
                    </div>

                    <span
                        class="shrink-0 rounded-md border px-2 py-1 text-sm font-semibold"
                        :class="getStateClasses(state.color)"
                    >
                        {{ formatPercentage(state.percentage) }}%
                    </span>
                </div>

                <div class="mt-5 flex items-end justify-between gap-3">
                    <p class="text-3xl font-semibold text-foreground">{{ state.count }}</p>
                    <p class="pb-1 text-sm text-muted-foreground">instalaciones</p>
                </div>

                <div
                    class="mt-4 h-2 overflow-hidden rounded-full bg-muted"
                    role="progressbar"
                    :aria-valuenow="state.percentage"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    :aria-label="`Porcentaje de instalaciones en estado ${state.name}`"
                >
                    <div
                        class="h-full rounded-full transition-all"
                        :class="getStateActive(state.color) || 'bg-primary'"
                        :style="{ width: `${state.percentage}%` }"
                    />
                </div>
            </Link>
        </section>
    </div>
</template>
