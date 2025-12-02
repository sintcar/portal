<template>
  <div class="layout">
    <AppHeader />
    <main class="page-content">
      <slot />
    </main>
    <AppFooter />
  </div>
</template>

<script setup>
import AppHeader from '../components/Header.vue';
import AppFooter from '../components/Footer.vue';
</script>

<style scoped>
.layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  color: #0f172a;
}

.page-content {
  width: min(1200px, 100%);
  margin: 24px auto;
  padding: 0 16px;
  flex: 1;
}
</style>
