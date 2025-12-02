<template>
  <form class="booking" @submit.prevent>
    <div>
      <label>Выбор процедуры</label>
      <select v-model="form.service">
        <option v-for="option in services" :key="option" :value="option">{{ option }}</option>
      </select>
    </div>
    <div class="grid">
      <div>
        <label>Дата</label>
        <input type="date" v-model="form.date" />
      </div>
      <div>
        <label>Время</label>
        <input type="time" v-model="form.time" />
      </div>
    </div>
    <button type="submit" class="primary">Забронировать</button>
  </form>
</template>

<script setup>
import { reactive } from 'vue';

const props = defineProps({
  services: {
    type: Array,
    default: () => []
  }
});

const form = reactive({
  service: props.services[0] || '',
  date: '',
  time: ''
});
</script>

<style scoped>
.booking {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 18px;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 4px;
}

select,
input {
  width: 100%;
  padding: 10px;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  background: #f8fafc;
}

.grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 12px;
}

.primary {
  background: linear-gradient(90deg, #ec4899, #8b5cf6);
  border: none;
  color: #fff;
  padding: 12px 16px;
  border-radius: 12px;
  font-weight: 700;
}
</style>
