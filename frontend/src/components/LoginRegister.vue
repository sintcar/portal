<template>
  <section class="auth">
    <div class="tabs">
      <button :class="{ active: mode === 'login' }" @click="mode = 'login'">Вход</button>
      <button :class="{ active: mode === 'register' }" @click="mode = 'register'">Регистрация</button>
    </div>
    <form class="form" @submit.prevent>
      <label>Электронная почта</label>
      <input type="email" placeholder="guest@mail.com" required />
      <label>Пароль</label>
      <input type="password" required />
      <label v-if="mode === 'register'">Подтверждение пароля</label>
      <input v-if="mode === 'register'" type="password" required />
      <button type="submit" class="primary">{{ submitText }}</button>
    </form>
  </section>
</template>

<script setup>
import { computed, ref } from 'vue';

const mode = ref('login');
const submitText = computed(() => (mode.value === 'login' ? 'Войти' : 'Создать аккаунт'));
</script>

<style scoped>
.auth {
  background: #fff;
  border: 1px solid #e2e8f0;
  padding: 18px;
  border-radius: 16px;
  box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
  max-width: 440px;
  margin: 0 auto;
}

.tabs {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 8px;
}

.tabs button {
  padding: 10px;
  background: #e2e8f0;
  border: none;
  border-radius: 10px;
  font-weight: 700;
}

.tabs button.active {
  background: linear-gradient(90deg, #6366f1, #22d3ee);
  color: #fff;
}

.form {
  display: grid;
  gap: 10px;
  margin-top: 14px;
}

label {
  font-weight: 600;
}

input {
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #cbd5e1;
  background: #f8fafc;
}

.primary {
  background: #0ea5e9;
  color: #fff;
  border: none;
  padding: 12px;
  border-radius: 12px;
  font-weight: 700;
}
</style>
