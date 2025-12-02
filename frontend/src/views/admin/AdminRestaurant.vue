<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Резервы столов</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Стол</th>
                <th>Гость</th>
                <th>Время</th>
                <th>Гостей</th>
                <th>Заметки</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="reservation in reservations" :key="reservation.table">
                <td>{{ reservation.table }}</td>
                <td>{{ reservation.guest }}</td>
                <td>{{ reservation.time }}</td>
                <td>{{ reservation.guests }}</td>
                <td>{{ reservation.notes }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Меню дня</div>
        <ul class="list">
          <li v-for="item in menu" :key="item.title">
            <div>
              <div class="title">{{ item.title }}</div>
              <div class="muted">{{ item.description }}</div>
            </div>
            <span class="pill">{{ item.price }}</span>
          </li>
        </ul>
        <button class="btn primary" type="button">Обновить</button>
      </div>
    </div>
  </section>
</template>

<script setup>
const reservations = [
  { table: 'A3', guest: 'Н. Рыбаков', time: '12:30', guests: 2, notes: 'Без орехов' },
  { table: 'B1', guest: 'С. Твердов', time: '13:00', guests: 4, notes: 'День рождения' },
  { table: 'VIP', guest: 'Е. Уайт', time: '19:30', guests: 6, notes: 'Сомелье' }
];

const menu = [
  { title: 'Боул с лососем', description: 'маринованный лосось, киноа, томаты', price: '690 ₽' },
  { title: 'Паста с трюфелем', description: 'сливочный соус, пармезан, масло трюфеля', price: '840 ₽' },
  { title: 'Чай Матча', description: 'взбитое молоко, сироп юдзу', price: '290 ₽' }
];
</script>

<style scoped>
.list {
  list-style: none;
  padding: 0;
  margin: 12px 0 20px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.list li {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
</style>
