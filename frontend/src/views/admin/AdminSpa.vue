<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Записи SPA</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Гость</th>
                <th>Услуга</th>
                <th>Специалист</th>
                <th>Время</th>
                <th>Статус</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="visit in visits" :key="visit.time">
                <td>{{ visit.guest }}</td>
                <td>{{ visit.service }}</td>
                <td>{{ visit.specialist }}</td>
                <td>{{ visit.time }}</td>
                <td><span class="status-badge" :class="visit.statusClass">{{ visit.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Управление слотами</div>
        <form class="form">
          <label class="label">Дата</label>
          <input class="input" type="date" />
          <label class="label">Время</label>
          <input class="input" type="time" />
          <label class="label">Кабинет</label>
          <select class="input">
            <option>Thai</option>
            <option>Beauty</option>
            <option>Соляная комната</option>
          </select>
          <button class="btn primary" type="button">Добавить слот</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const visits = [
  { guest: 'Д. Дени', service: 'Балийский массаж', specialist: 'Кира', time: '10:00', status: 'Подтверждено', statusClass: 'status-done' },
  { guest: 'В. Зотова', service: 'SPA-день', specialist: 'Марк', time: '12:00', status: 'Ожидание', statusClass: 'status-pending' },
  { guest: 'А. Холмс', service: 'Сауна + массаж', specialist: 'Кира', time: '15:30', status: 'В работе', statusClass: 'status-open' }
];
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>
