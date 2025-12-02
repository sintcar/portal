<template>
  <section class="stack">
    <header>
      <h2>SPA</h2>
      <p class="muted">Расслабьтесь и выберите удобное время посещения.</p>
    </header>
    <div class="grid">
      <SpaBooking :services="store.spa" />
      <div class="info">
        <h4>Популярные пакеты</h4>
        <ul>
          <li v-for="service in store.spa" :key="service">{{ service }}</li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup>
import SpaBooking from '../components/SpaBooking.vue';
import { usePortalStore } from '../store';

const store = usePortalStore();
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 14px;
  align-items: start;
}

.info {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 14px;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
}

ul {
  margin: 0;
  padding-left: 18px;
  color: #475569;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
