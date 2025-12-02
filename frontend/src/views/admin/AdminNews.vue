<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Новости</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Заголовок</th>
                <th>Дата</th>
                <th>Автор</th>
                <th>Статус</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in news" :key="item.title">
                <td>{{ item.title }}</td>
                <td>{{ item.date }}</td>
                <td>{{ item.author }}</td>
                <td><span class="status-badge" :class="item.statusClass">{{ item.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Новый пост</div>
        <form class="form">
          <label class="label">Заголовок</label>
          <input class="input" placeholder="Запуск летней сцены" />
          <label class="label">Категория</label>
          <select class="input">
            <option>Новости</option>
            <option>Акции</option>
            <option>Гид</option>
          </select>
          <label class="label">Текст</label>
          <textarea class="input" rows="4" placeholder="краткий текст"></textarea>
          <button class="btn primary" type="button">Опубликовать</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const news = [
  { title: 'Обновили SPA-зону', date: '12.05', author: 'Анна', status: 'Опубликовано', statusClass: 'status-done' },
  { title: 'Премия Travellers Choice', date: '10.05', author: 'PR', status: 'Черновик', statusClass: 'status-pending' },
  { title: 'Летнее меню', date: '09.05', author: 'Ресторан', status: 'Запланировано', statusClass: 'status-open' }
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
