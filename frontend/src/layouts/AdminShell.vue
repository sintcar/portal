<template>
  <div class="admin-layout">
    <aside class="sidebar">
      <div class="brand">Portal Admin</div>
      <nav>
        <RouterLink
          v-for="item in navItems"
          :key="item.to"
          :to="item.to"
          class="nav-link"
          :class="{ active: route.path === item.to }"
        >
          <span class="icon">{{ item.icon }}</span>
          <span>{{ item.label }}</span>
        </RouterLink>
      </nav>
    </aside>

    <div class="workspace">
      <header class="topbar">
        <div class="breadcrumbs">
          <span class="crumb">Админ-панель</span>
          <span class="separator">/</span>
          <span class="crumb">{{ activeTitle }}</span>
        </div>
        <div class="actions">
          <input class="search" type="search" placeholder="Поиск по панели" />
          <button class="primary">Новая запись</button>
          <div class="avatar">EA</div>
        </div>
      </header>

      <main class="content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';

const navItems = [
  { label: 'Dashboard', to: '/admin/dashboard', icon: '📊' },
  { label: 'Услуги', to: '/admin/services', icon: '🛎️' },
  { label: 'Заявки', to: '/admin/requests', icon: '📥' },
  { label: 'Номера', to: '/admin/rooms', icon: '🛏️' },
  { label: 'Ресторан', to: '/admin/restaurant', icon: '🍽️' },
  { label: 'SPA', to: '/admin/spa', icon: '💆' },
  { label: 'Карта', to: '/admin/map', icon: '🗺️' },
  { label: 'Гид', to: '/admin/guide', icon: '🧭' },
  { label: 'Акции', to: '/admin/promotions', icon: '🎁' },
  { label: 'Новости', to: '/admin/news', icon: '📰' },
  { label: 'Настройки', to: '/admin/settings', icon: '⚙️' },
  { label: 'Пользователи и роли', to: '/admin/users', icon: '🛡️' }
];

const route = useRoute();
const activeTitle = computed(() => navItems.find((item) => item.to === route.path)?.label || '');
</script>

<style scoped>
.admin-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  min-height: 100vh;
  background: #f8fafc;
  color: #0f172a;
}

.sidebar {
  background: #0f172a;
  color: #e2e8f0;
  padding: 20px 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
  box-shadow: 4px 0 18px rgba(15, 23, 42, 0.15);
}

.brand {
  font-weight: 700;
  font-size: 18px;
  letter-spacing: 0.5px;
  padding: 10px 12px;
  border-radius: 12px;
  background: linear-gradient(120deg, #0ea5e9, #6366f1);
  color: white;
  text-align: center;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 10px;
  font-weight: 600;
  color: #cbd5e1;
  transition: all 0.2s ease;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.06);
  color: white;
}

.nav-link.active {
  background: rgba(255, 255, 255, 0.12);
  color: white;
}

.icon {
  width: 20px;
  text-align: center;
}

.workspace {
  display: flex;
  flex-direction: column;
}

.topbar {
  height: 72px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: white;
  box-shadow: 0 1px 0 #e2e8f0;
  position: sticky;
  top: 0;
  z-index: 10;
}

.breadcrumbs {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #475569;
}

.crumb:first-of-type {
  font-weight: 600;
}

.separator {
  color: #cbd5e1;
}

.actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.search {
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  padding: 8px 12px;
  border-radius: 999px;
  min-width: 240px;
}

.primary {
  background: linear-gradient(120deg, #2563eb, #0ea5e9);
  color: white;
  border: none;
  padding: 10px 14px;
  border-radius: 12px;
  font-weight: 700;
  letter-spacing: 0.2px;
}

.avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: grid;
  place-items: center;
  background: #0f172a;
  color: #e2e8f0;
  font-weight: 700;
}

.content {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

:global(.admin-section) {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

:global(.admin-section h1),
:global(.admin-section h2) {
  margin: 0;
  color: #0f172a;
}

:global(.admin-section h1) {
  font-size: 26px;
}

:global(.admin-section h2) {
  font-size: 18px;
}

:global(.admin-grid) {
  display: grid;
  gap: 12px;
}

:global(.admin-grid.cols-3) {
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

:global(.admin-grid.cols-2) {
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
}

:global(.admin-card) {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 14px;
  box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}

:global(.admin-card .title) {
  font-weight: 700;
  color: #0f172a;
}

:global(.muted) {
  color: #64748b;
  font-size: 14px;
}

:global(.metric) {
  font-size: 28px;
  font-weight: 800;
  color: #0f172a;
}

:global(.pill) {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 999px;
  background: #e2e8f0;
  font-size: 12px;
  color: #0f172a;
}

:global(.table-wrapper) {
  overflow: auto;
}

:global(.admin-table) {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

:global(.admin-table th),
:global(.admin-table td) {
  padding: 10px 8px;
  border-bottom: 1px solid #e2e8f0;
  text-align: left;
}

:global(.admin-table th) {
  color: #475569;
  font-weight: 700;
}

:global(.btn) {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 12px;
  border-radius: 10px;
  border: 1px solid #cbd5e1;
  background: white;
  font-weight: 600;
}

:global(.btn.primary) {
  background: #2563eb;
  color: white;
  border: none;
}

:global(.input) {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  background: #f8fafc;
}

:global(.label) {
  font-size: 13px;
  color: #475569;
  font-weight: 600;
}

:global(.chip) {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 10px;
  border-radius: 999px;
  background: #eff6ff;
  color: #1d4ed8;
  font-weight: 600;
}

:global(.status-badge) {
  padding: 4px 10px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
}

:global(.status-open) {
  background: #ecfeff;
  color: #0e7490;
}

:global(.status-pending) {
  background: #fff7ed;
  color: #c2410c;
}

:global(.status-done) {
  background: #ecfdf3;
  color: #15803d;
}

:global(.status-error) {
  background: #fef2f2;
  color: #b91c1c;
}
</style>
