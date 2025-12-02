<template>
  <section class="stack">
    <header>
      <h2>Новости</h2>
      <p class="muted">Все обновления отеля в одном месте.</p>
    </header>
    <NewsBlock :items="store.news" />
  </section>
</template>

<script setup>
import NewsBlock from '../components/NewsBlock.vue';
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
