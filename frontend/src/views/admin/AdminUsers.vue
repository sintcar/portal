<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Пользователи</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Имя</th>
                <th>Роль</th>
                <th>Отдел</th>
                <th>Статус</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in users" :key="user.name">
                <td>{{ user.name }}</td>
                <td>{{ user.role }}</td>
                <td>{{ user.team }}</td>
                <td><span class="status-badge" :class="user.statusClass">{{ user.status }}</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Роли и права</div>
        <div class="permission" v-for="role in roles" :key="role.name">
          <div>
            <div class="title">{{ role.name }}</div>
            <div class="muted">{{ role.description }}</div>
          </div>
          <div class="pill">{{ role.scopes.join(', ') }}</div>
        </div>
        <form class="form">
          <label class="label">Добавить пользователя</label>
          <input class="input" placeholder="Имя сотрудника" />
          <select class="input">
            <option>Администратор</option>
            <option>Оператор</option>
            <option>Housekeeping</option>
          </select>
          <button class="btn primary" type="button">Пригласить</button>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
const users = [
  { name: 'Елена Админ', role: 'Администратор', team: 'Back Office', status: 'Активен', statusClass: 'status-done' },
  { name: 'Павел Оператор', role: 'Оператор', team: 'Front Desk', status: 'В смене', statusClass: 'status-open' },
  { name: 'Света Хаускипинг', role: 'Housekeeping', team: 'HK', status: 'Оффлайн', statusClass: 'status-error' }
];

const roles = [
  { name: 'Администратор', description: 'доступ ко всем разделам', scopes: ['CRUD', 'Права'] },
  { name: 'Оператор', description: 'работа с заявками', scopes: ['Чтение', 'Изменение'] },
  { name: 'Housekeeping', description: 'чистота и сервис', scopes: ['Чтение'] }
];
</script>

<style scoped>
.permission {
  padding: 12px 0;
  border-bottom: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  gap: 10px;
  align-items: center;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 12px;
}
</style>
