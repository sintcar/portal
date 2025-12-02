<template>
  <section class="stack">
    <header>
      <h2>Мой номер</h2>
      <p class="muted">Информация о проживании и активных запросах.</p>
    </header>
    <div class="room-card">
      <div>
        <p class="muted">Номер</p>
        <h3>{{ store.guest.room }}</h3>
        <p class="muted">Период: {{ store.guest.stay }}</p>
      </div>
      <div class="info">
        <div>
          <p class="muted">Баланс</p>
          <strong>{{ store.guest.balance }} ₽</strong>
        </div>
        <div>
          <p class="muted">Бонусы</p>
          <strong>{{ store.guest.bonus }} ₽</strong>
        </div>
      </div>
    </div>
    <div>
      <h4>Последние заявки</h4>
      <div class="list">
        <OrderCard v-for="order in store.orders" :key="order.title" :order="order" />
      </div>
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
  gap: 16px;
}

.room-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 16px;
  border-radius: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
}

.info {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.list {
  display: grid;
  gap: 10px;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
