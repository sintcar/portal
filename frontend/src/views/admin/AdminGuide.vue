<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Маршруты гида</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Название</th>
                <th>Длительность</th>
                <th>Гиды</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="tour in tours" :key="tour.title">
                <td>{{ tour.title }}</td>
                <td>{{ tour.duration }}</td>
                <td>{{ tour.guides }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Новый маршрут</div>
        <form class="form">
          <label class="label">Название</label>
          <input class="input" placeholder="Архитектура центра" />
          <label class="label">Теги</label>
          <div class="chip">История</div>
          <div class="chip">Экскурсии</div>
          <label class="label">Описание</label>
          <textarea class="input" rows="3" placeholder="краткий обзор"></textarea>
          <button class="btn primary" type="button">Сохранить</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const tours = [
  { title: 'Закат на крыше', duration: '1.5 часа', guides: 'Полина, Роман' },
  { title: 'Старинный квартал', duration: '2 часа', guides: 'Роман' },
  { title: 'Сет ресторанов отеля', duration: '1 час', guides: 'Полина' }
];
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

textarea.input {
  resize: vertical;
}
</style>
