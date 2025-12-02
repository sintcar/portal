<template>
  <section class="stack">
    <header>
      <h2>Заявки</h2>
      <p class="muted">Отслеживайте статусы и создавайте новые.</p>
    </header>
    <div class="list">
      <OrderCard v-for="order in store.orders" :key="order.title" :order="order" />
    </div>
    <div class="new-request">
      <input type="text" placeholder="Опишите вашу заявку" />
      <button class="primary">Отправить</button>
    </div>
  </section>
</template>

<script setup>
import { usePortalStore } from '../store';
import OrderCard from '../components/OrderCard.vue';

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
  gap: 10px;
}

.new-request {
  display: flex;
  gap: 10px;
  align-items: center;
}

input {
  flex: 1;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #cbd5e1;
  background: #fff;
}

.primary {
  background: #16a34a;
  color: #fff;
  border: none;
  padding: 10px 14px;
  border-radius: 10px;
  font-weight: 700;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
