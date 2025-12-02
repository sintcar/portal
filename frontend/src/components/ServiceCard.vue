<template>
  <article class="card">
    <div class="card-header">
      <div>
        <h3>{{ service.title }}</h3>
        <p class="meta">{{ service.category }}</p>
      </div>
      <span class="badge">{{ service.duration }} мин</span>
    </div>
    <p class="description">{{ service.description }}</p>
    <div class="card-footer">
      <div class="price">{{ service.price }} ₽</div>
      <button class="primary">Добавить</button>
    </div>
  </article>
</template>

<script setup>
defineProps({
  service: {
    type: Object,
    required: true
  }
});
</script>

<style scoped>
.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 16px;
  border-radius: 16px;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

h3 {
  margin: 0;
}

.meta {
  margin: 0;
  color: #475569;
}

.badge {
  background: #ecfeff;
  color: #0891b2;
  padding: 6px 10px;
  border-radius: 999px;
  font-weight: 700;
  font-size: 13px;
}

.description {
  margin: 0;
  color: #334155;
}

.card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.price {
  font-weight: 700;
  font-size: 18px;
}

.primary {
  background: linear-gradient(90deg, #22c55e, #16a34a);
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 12px;
  font-weight: 700;
  box-shadow: 0 10px 24px rgba(34, 197, 94, 0.2);
}
</style>
