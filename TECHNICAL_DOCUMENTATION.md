# Техническая документация портала

## API reference
### Публичные/гостевые маршруты (`/api/guest`)
- `GET /api/guest/hotels` — список отелей/локаций.
- `GET /api/guest/hotels/{hotel}/rooms` — список комнат/номеров.
- `GET /api/guest/hotels/{hotel}/services` — доступные сервисы.
- `GET /api/guest/hotels/{hotel}/spa` — SPA-услуги.
- `GET /api/guest/hotels/{hotel}/restaurants` — рестораны.
- `GET /api/guest/restaurants/{restaurant}/menu` — меню выбранного ресторана.
- `GET /api/guest/map` — карта территории.
- `GET /api/guest/hotels/{hotel}/guide` — гайд/путеводитель.
- `GET /api/guest/hotels/{hotel}/news` — новости локации.
- `POST /api/guest/hotels/{hotel}/orders` — оформление заказа.

### Установочный мастер (`/api/install`)
- `GET /api/install/status` — проверка системных требований и наличия `.env`.
- `POST /api/install/env` — создание файла окружения на основе параметров.
- `POST /api/install/database` — настройка соединения с БД и проверка доступности.
- `POST /api/install/migrate` — запуск обязательных миграций.
- `POST /api/install/key` — генерация `APP_KEY`.
- `POST /api/install/admin` — создание учётной записи администратора по умолчанию.
- `POST /api/install/seed` — выполнение сидов демо/базовых данных.

### Админ-маршруты (`/api/admin`, auth + роль `admin`)
- Сервисы: `GET/POST/PUT/DELETE` `/api/admin/hotels/{hotel}/services` и `/api/admin/services/{service}`.
- Заказы: `GET /api/admin/hotels/{hotel}/orders`, `PATCH /api/admin/orders/{order}/status`.
- Новости: `GET/POST/PUT` `/api/admin/hotels/{hotel}/news` и `/api/admin/news/{news}`.
- Роли/права: `GET/POST /api/admin/roles`, `PUT /api/admin/roles/{role}/permissions`, `PATCH /api/admin/users/{user}/role`.
- Загрузка файлов: `POST /api/admin/uploads`.

### Персонал (`/api/staff`, роль `staff`)
- `GET /api/staff/requests` — входящие заявки.
- `PATCH /api/staff/requests/{order}` — изменение статуса заявки.

### Сетевой админ (`/api/network`, роль `network-admin`)
- `GET/PUT /api/network/hotels` и `/api/network/hotels/{hotel}` — управление отелями.
- `GET /api/network/modules` — список модулей.
- `PATCH /api/network/modules/{module}/toggle` — включение/отключение модуля.
- `POST /api/network/modules/{module}/licenses` — выдача лицензий.

### Developer Console API (`/api/dev-console`, роль `developer`)
- `GET /api/dev-console/modules` — перечень модулей/пакетов.
- `POST /api/dev-console/modules/{module}/versions` — публикация новой версии.
- `POST /api/dev-console/versions/{moduleVersion}/logs` — добавление лога релиза/деплоя.

## Структура БД
- Пользователи и роли: `users`, `roles`, `permissions`, `role_permission`, `user_role` (многоорганизационная RBAC модель).
- Организации и структуры: `organizations`, `departments`, `hosts`.
- Гости и встречи: `visitors`, `appointments`, `visit_passes`, `access_rules`, `checkin_logs`.
- Уведомления и биллинг: `notifications`, `billing_plans`, `subscriptions`.
- Интеграции разработчика: `api_applications`, `api_keys`, `webhook_logs`.
- Обновления: `update_packages`, `update_jobs`, `update_logs`.
- Аудит и флаги: `audit_logs`, `feature_flags`.
- Дополнительные связи: текстовая ER-диаграмма описывает отношения Organization ↔ Department ↔ Host, User ↔ Role/Permission, Appointment ↔ VisitPass/Visitor, UpdatePackage ↔ UpdateJob/UpdateLog и т.д.

## Update Manager
- Реализован как доменный компонент Laravel с фоновыми заданиями; хранение в таблицах `update_packages`, `update_jobs`, `update_logs`.
- Возможности: проверка реестра версий, скачивание пакетов, прогоны миграций и health-check, canary rollout, автоматический откат при неуспехе.
- Планировщик: регулярные проверки через `php artisan schedule`, плюс ручные запуски из админки/CLI.
- Поток автообновления: запрос manifest → создание `UpdateJob (pending)` → скачивание + проверка checksum → миграции и очистка кеша → canary раскатка → полное развертывание или rollback.
- Интеграция с CI: публикация пакетов и подписи checksum; админ-API предоставляет действия проверки, обновления и отложенного запуска.

## Developer Console API
- Функциональность: управление приложениями и ключами, публикация версий модулей, просмотр логов/квот, работа с вебхуками и sandbox-режимом.
- Базовые маршруты: `devconsole.php` в backend содержит CRUD для приложений и ключей, а `/api/dev-console` (auth Sanctum + роль `developer`) обрабатывает модули/версии и логи деплоев.
- Вебхуки: события `appointment.created`, `visitpass.issued`, `visitpass.checked_in`, `update.completed` доступны для интеграции внешних приложений.
- Документация предполагается в формате OpenAPI/GraphQL со примерами запросов.

## Инструкции по установке
1. **Проверка окружения**: `GET /api/install/status` возвращает информацию о PHP-версии, расширениях и доступности каталогов, а также наличие `.env`.
2. **Создание `.env`**: `POST /api/install/env` с параметрами приложения и БД формирует файл окружения.
3. **Настройка базы данных**: `POST /api/install/database` обновляет конфиг соединения (`mysql` по умолчанию) и проверяет подключение.
4. **Миграции**: `POST /api/install/migrate` выполняет миграции с `--force`.
5. **Ключ приложения**: `POST /api/install/key` генерирует `APP_KEY` и возвращает актуальное значение.
6. **Администратор**: `POST /api/install/admin` создаёт активного пользователя-админа и дефолтную роль `admin`.
7. **Начальные данные**: `POST /api/install/seed` запускает сиды.
8. **Деплой в продакшн** (скрипт `deploy.sh`):
   - Остановить воркеры очередей (если используются supervisor-юниты).
   - Включить режим обслуживания `php artisan down`.
   - Установить PHP зависимости `composer install --no-dev --prefer-dist --optimize-autoloader`.
   - Прогнать миграции `php artisan migrate --force` и прогреть кеши (`config`, `route`, `view`, `event`).
   - Собрать frontend (`npm ci` + `npm run build`).
   - Перезапустить очереди (`php artisan queue:restart`) и выключить режим обслуживания `php artisan up`.
   - Перечитать/запустить supervisor группы воркеров.
