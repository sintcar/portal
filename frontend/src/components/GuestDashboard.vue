<template>
  <section class="dashboard">
    <div>
      <p class="muted">Добро пожаловать</p>
      <h2>{{ guest.name }}</h2>
      <p class="muted">Номер {{ guest.room }} · {{ guest.stay }}</p>
    </div>
    <div class="widgets">
      <div class="widget">
        <span class="label">Баланс</span>
        <strong>{{ guest.balance }} ₽</strong>
      </div>
      <div class="widget">
        <span class="label">Бонусы</span>
        <strong>{{ guest.bonus }} ₽</strong>
      </div>
      <div class="widget">
        <span class="label">Заявки</span>
        <strong>{{ openRequests }}</strong>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';

defineProps({
  guest: {
    type: Object,
    required: true
  },
  openRequests: {
    type: Number,
    default: 0
  }
});
</script>

<style scoped>
.dashboard {
  background: linear-gradient(135deg, #0ea5e9, #6366f1);
  color: #fff;
  padding: 18px;
  border-radius: 18px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
  box-shadow: 0 16px 40px rgba(99, 102, 241, 0.35);
}

.muted {
  margin: 0;
  color: rgba(255, 255, 255, 0.78);
}

.widgets {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 12px;
}

.widget {
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.2);
  padding: 12px;
  border-radius: 12px;
  text-align: center;
}

.label {
  display: block;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.8px;
}
</style>
