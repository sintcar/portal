<template>
  <section class="admin-section">
    <div class="admin-card">
      <div class="title">Заявки гостей</div>
      <div class="filters">
        <select class="input" v-model="filter.status">
          <option value="all">Все статусы</option>
          <option value="open">В работе</option>
          <option value="pending">Назначено</option>
          <option value="done">Выполнено</option>
        </select>
        <select class="input" v-model="filter.channel">
          <option value="all">Все каналы</option>
          <option>Web</option>
          <option>Мобильное приложение</option>
          <option>Стойка ресепшн</option>
        </select>
        <button class="btn">Экспорт</button>
      </div>
      <div class="table-wrapper">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Гость</th>
              <th>Услуга</th>
              <th>Канал</th>
              <th>Статус</th>
              <th>Исполнитель</th>
              <th>ETA</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="request in filtered" :key="request.id">
              <td>{{ request.id }}</td>
              <td>{{ request.guest }}</td>
              <td>{{ request.service }}</td>
              <td>{{ request.channel }}</td>
              <td><span class="status-badge" :class="request.statusClass">{{ request.status }}</span></td>
              <td>{{ request.owner }}</td>
              <td>{{ request.eta }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, reactive } from 'vue';

const filter = reactive({ status: 'all', channel: 'all' });

const requests = [
  { id: 781, guest: 'И. Котов', service: 'Доставка еды', channel: 'Мобильное приложение', status: 'В работе', statusClass: 'status-open', owner: 'Room Service', eta: '15 мин' },
  { id: 782, guest: 'Ю. Самойлова', service: 'Замена полотенец', channel: 'Web', status: 'Назначено', statusClass: 'status-pending', owner: 'Housekeeping', eta: '30 мин' },
  { id: 783, guest: 'О. Кузнецова', service: 'Ранняя уборка', channel: 'Стойка ресепшн', status: 'Выполнено', statusClass: 'status-done', owner: 'Housekeeping', eta: '—' },
  { id: 784, guest: 'К. Ли', service: 'Трансфер в аэропорт', channel: 'Web', status: 'В работе', statusClass: 'status-open', owner: 'Транспорт', eta: '40 мин' }
];

const filtered = computed(() =>
  requests.filter((item) => {
    const statusOk = filter.status === 'all' || item.statusClass === `status-${filter.status}`;
    const channelOk = filter.channel === 'all' || item.channel === filter.channel;
    return statusOk && channelOk;
  })
);
</script>

<style scoped>
.filters {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 12px 0;
}
</style>
