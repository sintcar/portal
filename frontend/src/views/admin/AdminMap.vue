<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Точки на карте отеля</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Зона</th>
                <th>Тип</th>
                <th>Описание</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="point in mapPoints" :key="point.name">
                <td>{{ point.name }}</td>
                <td>{{ point.type }}</td>
                <td>{{ point.description }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Новая точка</div>
        <form class="form">
          <label class="label">Название</label>
          <input class="input" placeholder="Конференц-холл" />
          <label class="label">Тип</label>
          <select class="input">
            <option>Инфраструктура</option>
            <option>Сервис</option>
            <option>Развлечения</option>
          </select>
          <label class="label">Описание</label>
          <textarea class="input" rows="3" placeholder="Что найдёт гость"></textarea>
          <button class="btn primary" type="button">Добавить на карту</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const mapPoints = [
  { name: 'Сцена у озера', type: 'Развлечения', description: 'вечерний концерт, кино на открытом воздухе' },
  { name: 'Детская', type: 'Сервис', description: 'няня, квесты, VR' },
  { name: 'Бизнес лаунж', type: 'Инфраструктура', description: 'кабинеты, принтеры, кофе' }
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
