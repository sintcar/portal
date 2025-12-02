<template>
  <main class="installer">
    <header class="hero">
      <div>
        <p class="eyebrow">Начало работы</p>
        <h1>Мастер установки</h1>
        <p class="muted">
          Пройдите шаги последовательно: подготовка сервера, настройка окружения, база данных,
          миграции, ключи, первый супер-админ и первичное заполнение данных.
        </p>
      </div>
      <div class="status-grid">
        <div class="status-card" v-for="(value, key) in steps" :key="key">
          <p class="label">{{ labels[key] }}</p>
          <span :class="['pill', pillClass(value)]">{{ pillText(value) }}</span>
        </div>
      </div>
    </header>

    <section class="grid">
      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 1</p>
            <h3>Проверка сервера</h3>
            <p class="muted">PHP, расширения и права на запись.</p>
          </div>
          <button class="btn" type="button" @click="checkServer">Проверить</button>
        </div>
        <div v-if="serverReport" class="list">
          <div class="list__row">
            <span>PHP</span>
            <span class="mono">{{ serverReport.php_version }}</span>
          </div>
          <div class="list__row" v-for="(isEnabled, extension) in serverReport.extensions" :key="extension">
            <span>{{ extension }}</span>
            <span :class="['pill', pillClass(isEnabled)]">{{ isEnabled ? 'OK' : 'Нет' }}</span>
          </div>
          <div class="list__row">
            <span>storage</span>
            <span :class="['pill', pillClass(serverReport.storage_writable)]">{{ readableFlag(serverReport.storage_writable) }}</span>
          </div>
          <div class="list__row">
            <span>bootstrap/cache</span>
            <span :class="['pill', pillClass(serverReport.bootstrap_writable)]">{{ readableFlag(serverReport.bootstrap_writable) }}</span>
          </div>
          <div class="list__row">
            <span>.env</span>
            <span :class="['pill', pillClass(serverReport.env_exists)]">{{ serverReport.env_exists ? 'Создан' : 'Нет файла' }}</span>
          </div>
        </div>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 2</p>
            <h3>Создание .env</h3>
            <p class="muted">Сохраните базовые переменные окружения.</p>
          </div>
          <button class="btn" type="button" @click="createEnv">Создать</button>
        </div>
        <form class="form" @submit.prevent="createEnv">
          <label class="field">
            <span>APP_NAME</span>
            <input v-model="envForm.app_name" type="text" placeholder="Portal" />
          </label>
          <label class="field">
            <span>APP_URL</span>
            <input v-model="envForm.app_url" type="text" placeholder="http://localhost" required />
          </label>
          <label class="field">
            <span>DB_HOST</span>
            <input v-model="envForm.db_host" type="text" placeholder="127.0.0.1" required />
          </label>
          <label class="field inline">
            <span>DB_PORT</span>
            <input v-model.number="envForm.db_port" type="number" min="1" required />
          </label>
          <label class="field">
            <span>DB_DATABASE</span>
            <input v-model="envForm.db_database" type="text" placeholder="portal" required />
          </label>
          <label class="field">
            <span>DB_USERNAME</span>
            <input v-model="envForm.db_username" type="text" placeholder="root" required />
          </label>
          <label class="field">
            <span>DB_PASSWORD</span>
            <input v-model="envForm.db_password" type="password" placeholder="••••" />
          </label>
          <label class="checkbox">
            <input v-model="envForm.overwrite" type="checkbox" />
            <span>Перезаписать существующий .env</span>
          </label>
        </form>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 3</p>
            <h3>Установка базы данных</h3>
            <p class="muted">Проверка подключения с указанными реквизитами.</p>
          </div>
          <button class="btn" type="button" @click="setupDatabase">Проверить подключение</button>
        </div>
        <p class="hint">Используются значения из формы .env.</p>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 4</p>
            <h3>Миграции</h3>
            <p class="muted">Запуск всех миграций схемы.</p>
          </div>
          <button class="btn" type="button" @click="runMigrations">Запустить миграции</button>
        </div>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 5</p>
            <h3>Генерация ключей</h3>
            <p class="muted">APP_KEY для шифрования и токенов.</p>
          </div>
          <button class="btn" type="button" @click="generateKey">Сгенерировать ключ</button>
        </div>
        <p class="hint" v-if="generatedKey">Сохранённый ключ: <span class="mono">{{ generatedKey }}</span></p>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 6</p>
            <h3>Первый супер-админ</h3>
            <p class="muted">Создание пользователя со статусом администратора.</p>
          </div>
          <button class="btn" type="button" @click="createAdmin">Создать</button>
        </div>
        <form class="form" @submit.prevent="createAdmin">
          <label class="field">
            <span>Имя</span>
            <input v-model="adminForm.name" type="text" placeholder="Иван Иванов" required />
          </label>
          <label class="field">
            <span>Email</span>
            <input v-model="adminForm.email" type="email" placeholder="admin@example.com" required />
          </label>
          <label class="field">
            <span>Пароль</span>
            <input v-model="adminForm.password" type="password" placeholder="••••••••" required />
          </label>
          <label class="field">
            <span>Телефон</span>
            <input v-model="adminForm.phone" type="text" placeholder="+7" />
          </label>
        </form>
      </article>

      <article class="card">
        <div class="card__header">
          <div>
            <p class="label">Шаг 7</p>
            <h3>Первичный сид</h3>
            <p class="muted">Заполнение базовых ролей и разрешений.</p>
          </div>
          <button class="btn" type="button" @click="runSeed">Запустить сид</button>
        </div>
      </article>
    </section>

    <section class="log" v-if="messages.length">
      <h3>Лог установки</h3>
      <ul>
        <li v-for="(item, index) in messages" :key="index" :class="item.type">{{ item.text }}</li>
      </ul>
    </section>
  </main>
</template>

<script setup>
import { reactive, ref } from 'vue';

const steps = reactive({
  server: null,
  env: null,
  database: null,
  migrations: null,
  key: null,
  admin: null,
  seed: null
});

const labels = {
  server: 'Сервер',
  env: '.env',
  database: 'База данных',
  migrations: 'Миграции',
  key: 'Ключи',
  admin: 'Супер-админ',
  seed: 'Initial seed'
};

const serverReport = ref(null);
const generatedKey = ref('');
const messages = ref([]);

const envForm = reactive({
  app_name: 'Portal',
  app_url: 'http://localhost',
  db_host: '127.0.0.1',
  db_port: 3306,
  db_database: 'portal',
  db_username: 'root',
  db_password: '',
  overwrite: false
});

const adminForm = reactive({
  name: 'Super Admin',
  email: 'admin@example.com',
  password: 'password',
  phone: ''
});

const pillClass = (value) => {
  if (value === true || value === 'done') return 'success';
  if (value === false || value === 'error') return 'danger';
  return 'muted-pill';
};

const pillText = (value) => {
  if (value === true || value === 'done') return 'Готово';
  if (value === false || value === 'error') return 'Ошибка';
  return 'Ожидает';
};

const readableFlag = (flag) => (flag ? 'Доступно' : 'Недоступно');

const pushMessage = (text, type = 'info') => {
  messages.value.unshift({ text, type });
};

const handleResponse = async (response) => {
  const payload = await response.json().catch(() => ({}));
  if (!response.ok) {
    const error = payload.message || 'Ошибка запроса';
    pushMessage(error, 'error');
    throw new Error(error);
  }
  return payload;
};

const checkServer = async () => {
  const response = await fetch('/api/install/status');
  const payload = await handleResponse(response);
  serverReport.value = payload;
  steps.server = 'done';
  pushMessage('Серверные требования проверены.');
};

const createEnv = async () => {
  const response = await fetch('/api/install/env', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(envForm)
  });
  await handleResponse(response);
  steps.env = 'done';
  pushMessage('.env файл создан.');
};

const setupDatabase = async () => {
  const payload = {
    db_host: envForm.db_host,
    db_port: envForm.db_port,
    db_database: envForm.db_database,
    db_username: envForm.db_username,
    db_password: envForm.db_password
  };

  const response = await fetch('/api/install/database', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
  await handleResponse(response);
  steps.database = 'done';
  pushMessage('Подключение к базе данных установлено.');
};

const runMigrations = async () => {
  const response = await fetch('/api/install/migrate', { method: 'POST' });
  await handleResponse(response);
  steps.migrations = 'done';
  pushMessage('Миграции успешно выполнены.');
};

const generateKey = async () => {
  const response = await fetch('/api/install/key', { method: 'POST' });
  const payload = await handleResponse(response);
  generatedKey.value = payload.app_key || '';
  steps.key = 'done';
  pushMessage('APP_KEY сгенерирован.');
};

const createAdmin = async () => {
  const response = await fetch('/api/install/admin', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(adminForm)
  });
  await handleResponse(response);
  steps.admin = 'done';
  pushMessage('Создан первый супер-админ.');
};

const runSeed = async () => {
  const response = await fetch('/api/install/seed', { method: 'POST' });
  await handleResponse(response);
  steps.seed = 'done';
  pushMessage('Initial seed завершён.');
};
</script>

<style scoped>
.installer {
  display: flex;
  flex-direction: column;
  gap: 24px;
  padding: 24px;
}

.hero {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 24px;
  background: #0f172a;
  color: #e2e8f0;
  border-radius: 16px;
  padding: 24px;
}

.eyebrow {
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 12px;
  margin: 0 0 4px;
  color: #38bdf8;
}

.hero h1 {
  margin: 0 0 8px;
}

.muted {
  color: #cbd5e1;
  margin: 0;
}

.status-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 12px;
  min-width: 320px;
}

.status-card {
  background: #0b1220;
  border: 1px solid #1f2937;
  border-radius: 10px;
  padding: 12px;
}

.label {
  font-size: 12px;
  color: #94a3b8;
  margin: 0 0 4px;
}

.pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
}

.success {
  background: #10b98133;
  color: #10b981;
}

.danger {
  background: #ef444433;
  color: #ef4444;
}

.muted-pill {
  background: #1e293b;
  color: #cbd5e1;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 16px;
}

.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.card__header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
}

.btn {
  background: #2563eb;
  color: #fff;
  border: none;
  padding: 10px 16px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 600;
}

.btn:hover {
  background: #1d4ed8;
}

.form {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 10px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  font-size: 14px;
}

.field input {
  padding: 10px 12px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
}

.checkbox {
  display: flex;
  gap: 8px;
  align-items: center;
}

.list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.list__row {
  display: flex;
  justify-content: space-between;
  padding: 8px 10px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  align-items: center;
}

.hint {
  margin: 0;
  color: #475569;
  font-size: 14px;
}

.mono {
  font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
}

.log {
  background: #0f172a;
  color: #e2e8f0;
  border-radius: 12px;
  padding: 16px;
}

.log ul {
  list-style: none;
  padding: 0;
  margin: 8px 0 0;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.log li {
  padding: 8px 10px;
  border-radius: 8px;
}

.log li.info {
  background: #1e293b;
}

.log li.error {
  background: #7f1d1d;
}
</style>
