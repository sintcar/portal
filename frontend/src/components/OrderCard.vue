<template>
  <article class="order">
    <div>
      <h4>{{ order.title }}</h4>
      <p class="muted">{{ order.date }} · {{ order.room }}</p>
    </div>
    <div class="status" :class="order.status">{{ statusText }}</div>
  </article>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  order: {
    type: Object,
    required: true
  }
});

const statusText = computed(() => {
  switch (props.order.status) {
    case 'done':
      return 'Выполнено';
    case 'in-progress':
      return 'В работе';
    default:
      return 'Новая';
  }
});
</script>

<style scoped>
.order {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 12px 16px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
}

h4 {
  margin: 0 0 4px;
}

.muted {
  margin: 0;
  color: #64748b;
}

.status {
  padding: 6px 12px;
  border-radius: 999px;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 12px;
}

.status.done {
  background: #ecfdf3;
  color: #16a34a;
}

.status.in-progress {
  background: #fff7ed;
  color: #ea580c;
}

.status.new {
  background: #e0f2fe;
  color: #0284c7;
}
</style>
