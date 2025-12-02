<template>
  <section class="stack">
    <header>
      <h2>Гид</h2>
      <p class="muted">Подборки маршрутов и советов для гостей.</p>
    </header>
    <div class="list">
      <article v-for="item in store.guide" :key="item.title" class="card">
        <h4>{{ item.title }}</h4>
        <p>{{ item.description }}</p>
        <button class="primary">Подробнее</button>
      </article>
    </div>
  </section>
</template>

<script setup>
import { usePortalStore } from '../store';

const store = usePortalStore();
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 12px;
}

.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.primary {
  background: #2563eb;
  color: #fff;
  border: none;
  padding: 10px 12px;
  border-radius: 10px;
  font-weight: 700;
  align-self: flex-start;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
