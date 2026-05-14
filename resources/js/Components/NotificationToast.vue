<script setup>
const props = defineProps({
    show: Boolean,
    success: [String, Boolean, null],
    error: [String, Boolean, null],
})
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
            v-if="show"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg"
            :class="{'bg-green-600' : success, 'bg-red-600' : error}"
        >
            {{ success ? success : error }}
        </div>
    </Transition>
</template>
