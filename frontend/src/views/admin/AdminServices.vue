<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Каталог услуг</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Статус</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="service in services" :key="service.name">
                <td>{{ service.name }}</td>
                <td>{{ service.category }}</td>
                <td>{{ service.price }}</td>
                <td><span class="status-badge" :class="service.statusClass">{{ service.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Новая услуга</div>
        <form class="form" @submit.prevent="addService">
          <label class="label">Название</label>
          <input v-model="form.name" class="input" placeholder="Например, Трансфер" />

          <label class="label">Категория</label>
          <select v-model="form.category" class="input">
            <option>Room Service</option>
            <option>Трансфер</option>
            <option>Технические</option>
            <option>SPA</option>
          </select>

          <label class="label">Цена</label>
          <input v-model="form.price" class="input" placeholder="от 900 ₽" />

          <label class="label">Статус</label>
          <select v-model="form.status" class="input">
            <option>Активна</option>
            <option>Черновик</option>
          </select>

          <button class="btn primary" type="submit">Сохранить</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { reactive, ref } from 'vue';

const services = ref([
  { name: 'Room Service', category: 'Ресторан', price: 'от 900 ₽', status: 'Активна', statusClass: 'status-open' },
  { name: 'Поздний выезд', category: 'Номера', price: '1500 ₽', status: 'Активна', statusClass: 'status-open' },
  { name: 'Трансфер', category: 'Транспорт', price: 'от 2500 ₽', status: 'Черновик', statusClass: 'status-pending' }
]);

const form = reactive({
  name: '',
  category: 'Room Service',
  price: '',
  status: 'Активна'
});

const addService = () => {
  if (!form.name || !form.price) return;
  services.value.unshift({
    name: form.name,
    category: form.category,
    price: form.price,
    status: form.status,
    statusClass: form.status === 'Активна' ? 'status-open' : 'status-pending'
  });
  form.name = '';
  form.price = '';
};
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>
