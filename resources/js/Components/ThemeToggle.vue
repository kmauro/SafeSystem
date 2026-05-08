<script setup>
import { Button } from '@/Components/ui/button'
import { useTheme } from '@/Composables/useTheme'

const { mode } = useTheme()

function toggleTheme() {
    mode.value = mode.value === 'dark'
        ? 'light'
        : 'dark'
}
</script>

<template>
    <Button @click="toggleTheme" variant="outline" size="icon">

        {{ mode === 'dark'
            ? '☀️'
            : '🌙'
        }}

    </Button>
</template>
