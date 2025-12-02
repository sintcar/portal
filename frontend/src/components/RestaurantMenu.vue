<template>
  <section class="menu">
    <header class="menu-head">
      <div>
        <h3>Меню ресторана</h3>
        <p class="muted">Лучшие предложения от шеф-повара</p>
      </div>
      <input v-model="search" type="search" placeholder="Поиск по меню" />
    </header>
    <div class="grid">
      <article v-for="item in filtered" :key="item.name" class="dish">
        <div>
          <h4>{{ item.name }}</h4>
          <p class="muted">{{ item.category }}</p>
        </div>
        <div class="price">{{ item.price }} ₽</div>
      </article>
    </div>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    default: () => []
  }
});

const search = ref('');

const filtered = computed(() =>
  props.items.filter((item) =>
    item.name.toLowerCase().includes(search.value.toLowerCase())
  )
);
</script>

<style scoped>
.menu {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 16px;
  border-radius: 18px;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
}

.menu-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}

input {
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #cbd5e1;
  background: #f8fafc;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 12px;
  margin-top: 12px;
}

.dish {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 12px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.muted {
  margin: 0;
  color: #64748b;
}

.price {
  font-weight: 700;
}
</style>
