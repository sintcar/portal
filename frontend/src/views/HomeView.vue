<template>
  <section class="stack">
    <GuestDashboard :guest="store.guest" :open-requests="store.openRequestCount" />
    <div class="split">
      <div class="column">
        <h3>Быстрые сервисы</h3>
        <div class="grid">
          <ServiceCard v-for="service in store.services" :key="service.title" :service="service" />
        </div>
      </div>
      <div class="column">
        <NewsBlock :items="store.news" />
      </div>
    </div>
  </section>
</template>

<script setup>
import GuestDashboard from '../components/GuestDashboard.vue';
import ServiceCard from '../components/ServiceCard.vue';
import NewsBlock from '../components/NewsBlock.vue';
import { usePortalStore } from '../store';

const store = usePortalStore();
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.split {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 14px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 12px;
}

.column h3 {
  margin-bottom: 8px;
}
</style>
