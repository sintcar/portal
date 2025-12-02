<template>
  <section class="admin-section">
    <div class="admin-grid cols-3">
      <div class="admin-card" v-for="metric in metrics" :key="metric.label">
        <div class="title">{{ metric.label }}</div>
        <div class="metric">{{ metric.value }}</div>
        <div class="muted">{{ metric.hint }}</div>
      </div>
    </div>

    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Активные заявки</div>
        <div class="table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Гость</th>
                <th>Услуга</th>
                <th>Статус</th>
                <th>Срок</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="request in requests" :key="request.id">
                <td>{{ request.id }}</td>
                <td>{{ request.guest }}</td>
                <td>{{ request.service }}</td>
                <td><span class="status-badge" :class="request.statusClass">{{ request.status }}</span></td>
                <td>{{ request.due }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="admin-card">
        <div class="title">Заполнение по корпусам</div>
        <div class="muted">Live occupancy</div>
        <div class="admin-grid cols-3" style="margin-top: 12px;">
          <div class="pill" v-for="tower in occupancy" :key="tower.label">
            {{ tower.label }} · {{ tower.load }}%
          </div>
        </div>
        <div class="chart-placeholder">Тут может быть график загрузки</div>
      </div>
    </div>

    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Задачи смены</div>
        <ul class="tasks">
          <li v-for="task in tasks" :key="task.title">
            <div>
              <div class="title">{{ task.title }}</div>
              <div class="muted">{{ task.owner }}</div>
            </div>
            <span class="status-badge" :class="task.statusClass">{{ task.status }}</span>
          </li>
        </ul>
      </div>

      <div class="admin-card">
        <div class="title">События дня</div>
        <ul class="timeline">
          <li v-for="event in events" :key="event.time">
            <div class="time">{{ event.time }}</div>
            <div>
              <div class="title">{{ event.title }}</div>
              <div class="muted">{{ event.details }}</div>
            </div>
            <span class="chip">{{ event.tag }}</span>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>

<script setup>
const metrics = [
  { label: 'Новые заявки', value: '24', hint: '+12% за сутки' },
  { label: 'Среднее время реакции', value: '08:14', hint: 'операторы 24/7' },
  { label: 'Заполнение номерного фонда', value: '87%', hint: 'высокий сезон' }
];

const requests = [
  { id: 341, guest: 'Е. Аникина', service: 'Поздний выезд', status: 'В работе', statusClass: 'status-open', due: 'до 12:30' },
  { id: 342, guest: 'А. Пушной', service: 'Уборка', status: 'Назначено', statusClass: 'status-pending', due: '14:00' },
  { id: 343, guest: 'В. Томпсон', service: 'Room Service', status: 'Выполнено', statusClass: 'status-done', due: '10:15' }
];

const occupancy = [
  { label: 'A', load: 92 },
  { label: 'B', load: 85 },
  { label: 'C', load: 76 }
];

const tasks = [
  { title: 'Подготовить конференц-зал к 15:00', owner: 'Мария, техслужба', status: 'В работе', statusClass: 'status-open' },
  { title: 'Обновить планшеты консьержей', owner: 'Андрей, IT', status: 'Запланировано', statusClass: 'status-pending' },
  { title: 'Подготовить welcome-набор для группы', owner: 'Ксения, PR', status: 'Готово', statusClass: 'status-done' }
];

const events = [
  { time: '10:00', title: 'Трансфер VIP', details: 'BMW 7, встреча у стойки', tag: 'Гости' },
  { time: '13:30', title: 'Смена меню ланча', details: 'добавить азиатский сет', tag: 'Ресторан' },
  { time: '17:00', title: 'Подготовка SPA-зоны', details: 'закрыть джакузи на сервис', tag: 'SPA' }
];
</script>

<style scoped>
.chart-placeholder {
  margin-top: 18px;
  padding: 16px;
  border: 1px dashed #cbd5e1;
  border-radius: 14px;
  color: #475569;
  text-align: center;
}

.tasks {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.tasks li {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}

.timeline {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.timeline li {
  display: grid;
  grid-template-columns: 80px 1fr auto;
  align-items: center;
  gap: 10px;
}

.time {
  font-weight: 700;
  color: #0f172a;
}
</style>
