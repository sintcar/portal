<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Акции</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Название</th>
                <th>Срок</th>
                <th>Канал</th>
                <th>Статус</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="promo in promotions" :key="promo.title">
                <td>{{ promo.title }}</td>
                <td>{{ promo.period }}</td>
                <td>{{ promo.channel }}</td>
                <td><span class="status-badge" :class="promo.statusClass">{{ promo.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Создать акцию</div>
        <form class="form">
          <label class="label">Название</label>
          <input class="input" placeholder="-15% на SPA" />
          <label class="label">Канал</label>
          <select class="input">
            <option>Email</option>
            <option>Push</option>
            <option>Соцсети</option>
          </select>
          <label class="label">Срок действия</label>
          <input class="input" type="date" />
          <button class="btn primary" type="button">Опубликовать</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const promotions = [
  { title: '3=2 на выходные', period: '01-15 мая', channel: 'Email', status: 'Запущено', statusClass: 'status-done' },
  { title: 'SPA -20% утром', period: 'весь май', channel: 'Push', status: 'Черновик', statusClass: 'status-pending' },
  { title: 'Kids club бесплатно', period: 'каждые выходные', channel: 'Соцсети', status: 'Активно', statusClass: 'status-open' }
];
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>
