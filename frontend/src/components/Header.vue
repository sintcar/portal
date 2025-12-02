<template>
  <header class="header">
    <div class="brand">Portal Frontdesk</div>
    <nav class="nav">
      <RouterLink v-for="item in navItems" :key="item.to" :to="item.to" class="nav-link">
        {{ item.label }}
      </RouterLink>
    </nav>
    <RouterLink to="/login" class="auth-link">Войти / Регистрация</RouterLink>
  </header>
</template>

<script setup>
import { RouterLink } from 'vue-router';

const navItems = [
  { to: '/', label: 'Главная гостя' },
  { to: '/catalog', label: 'Каталог услуг' },
  { to: '/room', label: 'Мой номер' },
  { to: '/requests', label: 'Заявки' },
  { to: '/restaurant', label: 'Ресторан' },
  { to: '/spa', label: 'SPA' },
  { to: '/map', label: 'Карта' },
  { to: '/guide', label: 'Гид' },
  { to: '/news', label: 'Новости' },
  { to: '/chat', label: 'Чат-консьерж' }
];
</script>

<style scoped>
.header {
  position: sticky;
  top: 0;
  z-index: 10;
  display: flex;
  align-items: center;
  gap: 16px;
  justify-content: space-between;
  padding: 12px 20px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid #e2e8f0;
  box-shadow: 0 2px 12px rgba(15, 23, 42, 0.05);
}

.brand {
  font-weight: 700;
  letter-spacing: 0.3px;
}

.nav {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
}

.nav-link {
  padding: 8px 12px;
  border-radius: 10px;
  transition: all 0.2s ease;
  color: #0f172a;
  font-weight: 500;
}

.nav-link:hover {
  background: #0f172a;
  color: #f8fafc;
}

.auth-link {
  padding: 8px 12px;
  border-radius: 10px;
  background: linear-gradient(90deg, #6366f1, #8b5cf6);
  color: #fff;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2);
}
</style>
