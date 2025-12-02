<template>
  <section class="admin-section">
    <div class="admin-grid cols-2">
      <div class="admin-card">
        <div class="title">Общие настройки</div>
        <form class="form">
          <label class="label">Название отеля</label>
          <input class="input" placeholder="Sky Resort" />
          <label class="label">Телефон поддержки</label>
          <input class="input" placeholder="+7 (900) 000-00-00" />
          <label class="label">Часы работы</label>
          <input class="input" placeholder="24/7" />
          <button class="btn primary" type="button">Сохранить</button>
        </form>
      </div>

      <div class="admin-card">
        <div class="title">Уведомления</div>
        <div class="toggle-row" v-for="notice in notices" :key="notice.title">
          <div>
            <div class="title">{{ notice.title }}</div>
            <div class="muted">{{ notice.description }}</div>
          </div>
          <label class="switch">
            <input type="checkbox" :checked="notice.enabled" />
            <span class="slider" />
          </label>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const notices = [
  { title: 'Push гостям', description: 'промо, готовность номера', enabled: true },
  { title: 'Email аналитика', description: 'ежедневные сводки', enabled: false },
  { title: 'Системные аварии', description: 'только критичные', enabled: true }
];
</script>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.toggle-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #e2e8f0;
}

.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #cbd5e1;
  transition: 0.2s;
  border-radius: 30px;
}

.slider:before {
  position: absolute;
  content: '';
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.2s;
  border-radius: 50%;
}

input:checked + .slider {
  background: #2563eb;
}

input:checked + .slider:before {
  transform: translateX(18px);
}
</style>
