<script setup>
import Header from '@/Components/Header.vue'
import Sidebar from '@/Components/Sidebar.vue'
import Footer from '@/Components/Footer.vue'
</script>

<template>
    <div class="app-container">

        <Header />

        <div class="main-container">

            <Sidebar />

            <main class="content">
                <slot />
            </main>

        </div>

        <Footer />

    </div>
</template>

<style scoped>
.app-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.main-container {
    display: flex;
    flex: 1;
}

.content {
    flex: 1;
    padding: 1rem;
}
</style>
