<template>
  <section class="stack">
    <header>
      <h2>Ресторан</h2>
      <p class="muted">Закажите блюда в номер или резерв стол.</p>
    </header>
    <RestaurantMenu :items="store.menu" />
  </section>
</template>

<script setup>
import RestaurantMenu from '../components/RestaurantMenu.vue';
import { usePortalStore } from '../store';

const store = usePortalStore();
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
