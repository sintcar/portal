<template>
  <section class="news">
    <header class="head">
      <h3>Новости отеля</h3>
      <p class="muted">Свежие события и рекомендации</p>
    </header>
    <div class="list">
      <article v-for="item in items" :key="item.title" class="card">
        <div>
          <h4>{{ item.title }}</h4>
          <p class="muted">{{ item.date }}</p>
          <p>{{ item.excerpt }}</p>
        </div>
        <a href="#" class="link">Читать</a>
      </article>
    </div>
  </section>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    default: () => []
  }
});
</script>

<style scoped>
.news {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.head h3 {
  margin: 0;
}

.muted {
  color: #64748b;
  margin: 4px 0;
}

.list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 12px;
}

.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  gap: 8px;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.link {
  align-self: flex-start;
  color: #6366f1;
  font-weight: 700;
}
</style>
