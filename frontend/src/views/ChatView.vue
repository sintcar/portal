<template>
  <section class="stack">
    <header>
      <h2>Чат-консьерж</h2>
      <p class="muted">Общайтесь с персоналом в реальном времени.</p>
    </header>
    <div class="chat">
      <div class="messages">
        <div class="bubble guest">Здравствуйте! Можно заказать поздний выезд?</div>
        <div class="bubble staff">Здравствуйте! Да, отметила запрос, подтвердим в течение 10 минут.</div>
      </div>
      <div class="input">
        <input type="text" placeholder="Ваше сообщение" />
        <button class="primary">Отправить</button>
      </div>
    </div>
  </section>
</template>

<script setup>
// Заглушка интерфейса чата
</script>

<style scoped>
.stack {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.chat {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.messages {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.bubble {
  padding: 10px 12px;
  border-radius: 12px;
  max-width: 560px;
}

.bubble.guest {
  background: #e0f2fe;
  align-self: flex-start;
}

.bubble.staff {
  background: #dcfce7;
  align-self: flex-end;
}

.input {
  display: flex;
  gap: 10px;
}

input {
  flex: 1;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #cbd5e1;
}

.primary {
  background: #0ea5e9;
  border: none;
  color: #fff;
  padding: 10px 14px;
  border-radius: 10px;
  font-weight: 700;
}

.muted {
  margin: 0;
  color: #64748b;
}
</style>
