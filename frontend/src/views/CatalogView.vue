<template>
  <section>
    <header class="head">
      <h2>Каталог услуг</h2>
      <p>Все сервисы отеля в одном месте.</p>
    </header>
    <div class="grid">
      <ServiceCard v-for="service in store.services" :key="service.title" :service="service" />
    </div>
  </section>
</template>

<script setup>
import ServiceCard from '../components/ServiceCard.vue';
import { usePortalStore } from '../store';

const store = usePortalStore();
</script>

<style scoped>
.head h2 {
  margin: 0;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 12px;
  margin-top: 12px;
}
</style>
