<template>
  <component :is="layoutComponent">
    <RouterView />
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import AdminShell from './layouts/AdminShell.vue';
import MainLayout from './layouts/MainLayout.vue';

const route = useRoute();
const layoutComponent = computed(() => (route.meta.layout === 'admin' ? AdminShell : MainLayout));
</script>
