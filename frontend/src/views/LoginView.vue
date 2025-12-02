<template>
  <section class="stack">
    <header class="center">
      <h2>Вход / Регистрация</h2>
      <p class="muted">Получите доступ к персональному кабинету гостя.</p>
    </header>
    <LoginRegister />
  </section>
</template>

<script setup>
import LoginRegister from '../components/LoginRegister.vue';
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.center {
  text-align: center;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
