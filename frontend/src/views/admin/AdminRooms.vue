<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Номера и статусы</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Тип</th>
                <th>Гость</th>
                <th>Статус</th>
                <th>Уборка</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="room in rooms" :key="room.number">
                <td>{{ room.number }}</td>
                <td>{{ room.type }}</td>
                <td>{{ room.guest }}</td>
                <td><span class="status-badge" :class="room.statusClass">{{ room.status }}</span></td>
                <td>{{ room.cleaning }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Настройка категории</div>
        <form class="form">
          <label class="label">Категория</label>
          <select class="input">
            <option>Standard</option>
            <option>Deluxe</option>
            <option>Suite</option>
          </select>

          <label class="label">Базовая цена</label>
          <input class="input" placeholder="от 4900 ₽" />

          <label class="label">Пакеты</label>
          <div class="pill">Завтрак включен</div>
          <div class="pill">SPA доступ</div>
          <button class="btn primary" type="button">Сохранить</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const rooms = [
  { number: '1204', type: 'Deluxe', guest: 'Семья Беловых', status: 'Занят', statusClass: 'status-open', cleaning: '16:00' },
  { number: '903', type: 'Standard', guest: 'Свободен', status: 'Доступен', statusClass: 'status-done', cleaning: 'Готов' },
  { number: '1411', type: 'Suite', guest: 'М. Клейн', status: 'Late check-out', statusClass: 'status-pending', cleaning: '18:00' }
];
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>
