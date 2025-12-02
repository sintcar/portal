# "Гостевой Центр" — архитектурная спецификация

## Технологический стек
- **Backend**: Laravel (REST/GraphQL API), PHP-FPM, Horizon/Queue worker, Redis cache/queue.
- **Frontend**: Vue 3 + Vite + Tailwind CSS, SSR/SPA гибрид, Pinia для состояния, Vue Router.
- **БД**: MySQL 8 (InnoDB), read replicas (опционально), миграции Laravel.
- **Инфраструктура**: Nginx, Redis, S3-совместимое хранилище вложений, CI/CD, контейнеризация.
- **Update Manager**: внутренний сервис автообновлений (см. ниже).

## Модульная структура
### Backend (Laravel)
- `App/Domain`
  - `Visitors`: регистрация визитов, гостевые пропуска, логирование входов/выходов.
  - `Organizations`: компании-хосты, отделы, контактные лица.
  - `Hosts`: сотрудники, принимающие гостей, графики присутствия.
  - `Appointments`: встречи, приглашения, расписание комнат.
  - `AccessControl`: правила доступа, интеграции с турникетами/СКУД, временные окна.
  - `Credentials`: выпуск QR/карточек, статусы пропусков, цифровые подписи.
  - `Notifications`: e-mail/SMS/Push/мессенджеры.
  - `Security`: аудит, журналы действий, полиси, права.
  - `Billing`: тарифные планы, лимиты гостей, платежные вебхуки.
  - `Admin`: панели управления, настройки, пользователи, роли.
  - `UpdateManager`: версии пакетов, задания обновлений, health-check.
  - `DeveloperConsole`: API-ключи, webhooks, sandbox.
- `App/Application`
  - Use-cases/Handlers, DTO, валидация, сервисные классы.
- `App/Infrastructure`
  - Репозитории (Eloquent/Redis), интеграции (SMTP, SMS, СКУД), очереди, кэш, storage.
- `App/Http`
  - REST/GraphQL контроллеры `Api`, `Admin`, `DeveloperConsole`, middleware (аутентификация, throttle, tenant), форм-реквесты.
- `Database`
  - Миграции, сиды, фабрики, `jobs` таблицы очередей, `audit_logs`.
- `Config`
  - Feature flags, rate limits, Update Manager endpoints, OAuth/SAML.
- `Routes`
  - `api.php`, `admin.php`, `devconsole.php`, `broadcasting.php`.

### Frontend (Vue/Tailwind)
- `src/app`
  - `layouts`, `pages` (`guest`, `host`, `admin`, `devconsole`), `components`, `widgets`, `composables`, `stores`, `router`.
- `src/modules`
  - `visitors`, `appointments`, `access-control`, `credentials`, `notifications`, `billing`, `admin`, `devconsole`, `update-manager`.
- `src/services`
  - API-клиент (Axios/fetch), interceptors, realtime (WebSocket/SSE), feature flags.
- `src/assets`
  - Tailwind config, дизайн-токены, локализация.

### Инфраструктура
- Контейнеры: `nginx`, `php-fpm`, `queue-worker`, `scheduler`, `redis`, `mysql`, `frontend` (Vite build/SSR), `update-manager`.
- CI/CD: линтеры, тесты, миграции, zero-downtime deploy (blue/green), автопроверка версий Update Manager.

## Доменная модель (ключевые сущности)
- **Visitor**: личные данные, документы, фото, согласия.
- **HostUser**: сотрудник/принимающий, принадлежит Organization/Department.
- **Organization**: компания-хост, настройки брендинга, политика безопасности.
- **Department**: подразделение Organization.
- **Appointment**: встреча (время, место, участники, комнаты, статус, повторяемость).
- **VisitPass**: пропуск/QR, срок действия, статус (draft/active/revoked/expired), тип (один раз/мульти/групповой).
- **AccessRule**: временные окна, зоны, связи с VisitPass/Appointment.
- **CheckinLog**: факты входа/выхода, устройство, источник.
- **Notification**: шаблоны, каналы, события (invite, check-in, update, revoke).
- **BillingPlan/Subscription**: лимиты, квоты, интеграции.
- **User**: аккаунты платформы (Admin/Operator/Host/Developer).
- **Role/Permission**: RBAC с многоорганизационной моделью.
- **ApiKey/Application**: для Developer Console API, scope, rate limit, webhook endpoints.
- **UpdatePackage/UpdateJob**: версии, зависимости, задания автообновления.

## Update Manager
- Компонент Laravel (`App\Domain\UpdateManager`) + фоновые задания.
- Функции: проверка доступных версий (registry), скачивание пакетов, миграции/сеансы, pre-flight health, поэтапное раскатывание (canary), откат.
- Планировщик: cron/`php artisan schedule` — nightly check + on-demand через Admin/CLI.
- Хранение: таблицы `update_packages`, `update_jobs`, логи (`update_logs`).
- API/Admin: действия "Проверить", "Обновить", "Отложить", отображение статуса, нотификации.
- Интеграция с CI: публикация пакета в registry, подписи checksum.

## Developer Console API
- Управление API-ключами, приложениями, вебхуками, версиями.
- Маршруты `devconsole.php`: CRUD приложений, выдача/ротация ключей, просмотр квот/логов, тестовые вызовы.
- SandBox: отдельный ключ + ограниченные пермишены, throttle.
- Вебхуки: события `appointment.created`, `visitpass.issued`, `visitpass.checked_in`, `update.completed`.
- Документация: OpenAPI/GraphQL schema, примеры.

## Автообновления (поток)
1. Update Manager запрашивает registry → получает manifest.
2. Планировщик создаёт `UpdateJob` (статус `pending`).
3. Очередь скачивает пакет, проверяет checksum/подпись.
4. Применяются миграции, очистка кэша, перезапуск Horizon/queues.
5. Canary rollout: часть инстансов (feature flag), сбор метрик.
6. После успешной валидации — rollout всем инстансам, статус `completed`; при ошибке — rollback (`failed` + уведомления).

## Очереди и фоновые задания
- Redis в роли брокера/кэша, Laravel Horizon для мониторинга.
- Очереди: `notifications`, `access-control`, `sync-external`, `update-manager`, `webhooks`.
- Dead-letter: отдельный список/таблица `failed_jobs` с ретраями и алертами.

## Структура БД (основные таблицы)
- `users` (id, organization_id, name, email, phone, role, status, auth_provider).
- `roles`, `permissions`, `role_permission`, `user_role` (RBAC, scoping per org).
- `organizations` (id, name, code, settings, timezone, branding).
- `departments` (id, organization_id, name, parent_id).
- `hosts` (id, user_id, department_id, position, presence_schedule).
- `visitors` (id, org_id, personal data, documents, photo_path, consent_flags).
- `appointments` (id, org_id, host_id, room, starts_at, ends_at, recurrence, status).
- `visit_passes` (id, visitor_id, appointment_id, access_rule_id, type, valid_from, valid_to, status, qr_token, revoked_by, revoked_reason).
- `access_rules` (id, org_id, zones, weekdays, time_windows, multi_use, escort_required).
- `checkin_logs` (id, visit_pass_id, gate, direction, device_id, occurred_at, source).
- `notifications` (id, org_id, channel, template, payload, status, sent_at, error_message).
- `billing_plans`, `subscriptions` (org_id, plan_id, quota_guests, quota_api, renewal_at, status).
- `api_applications` (id, org_id, name, description, webhook_url, is_sandbox).
- `api_keys` (id, api_application_id, key_hash, scopes, rate_limit, last_used_at, revoked_at).
- `webhook_logs` (id, api_application_id, event, payload, status, response_code, retried_at).
- `update_packages` (id, version, channel, checksum, url, notes, released_at).
- `update_jobs` (id, package_id, status, started_at, completed_at, instance, strategy, created_by).
- `update_logs` (id, job_id, level, message, created_at).
- `audit_logs` (id, user_id, org_id, action, entity_type, entity_id, ip, user_agent, created_at).
- `feature_flags` (id, key, value, scope, expires_at).

## ER-диаграмма (текст)
- Organization 1—N Department; Department 1—N Host (via users).
- Organization 1—N User; User N—M Role via UserRole; Role N—M Permission via RolePermission.
- Host (User) 1—N Appointment; Appointment 1—N VisitPass; Appointment N—M Visitor via VisitPass.
- VisitPass 1—1 AccessRule; VisitPass 1—N CheckinLog.
- Organization 1—N Subscription (активная) → BillingPlan.
- Organization 1—N ApiApplication; ApiApplication 1—N ApiKey; ApiApplication 1—N WebhookLog.
- UpdatePackage 1—N UpdateJob; UpdateJob 1—N UpdateLog.
- User 1—N AuditLog; Organization 1—N AuditLog.

## Потоки взаимодействия (gost-front → API → Admin → Developer Console)
1. **Гость (gost-front)**: 
   - SPA/SSR получает публичные настройки организации и страницу приглашения.
   - Авторизация гостя через magic-link/OTP → запрос на `/api/guest/auth`.
   - Получение/активация пропуска `/api/guest/passes`, отображение QR, уведомления о расписании.
2. **Host/Оператор (Admin UI)**:
   - Панель `/admin` (Vue) → `/api/admin/*` для CRUD встреч, пропусков, правил доступа, уведомлений.
   - Реальное время: WebSocket/SSE (статусы проходов, очереди входа).
   - Управление Update Manager: запросы `/api/admin/update-manager` (check/download/apply, логи).
3. **Developer Console**:
   - Отдельный SPA `/devconsole` → `/api/devconsole/*`.
   - Операции: создание приложений, выпуск/ротация ключей, просмотр квот, тестовые вызовы, управление вебхуками.
   - Доступ к логам вебхуков, перезапуск доставки (очередь `webhooks`).

## Схема прав и ролей (RBAC)
- **Глобальные принципы**: все сущности с `organization_id`; супер-админ (platform) с доступом ко всем.
- **Роли (пример)**:
  - `PlatformAdmin`: управление организациями, биллингом, Update Manager registry.
  - `OrgAdmin`: управление пользователями, отделами, политикой доступа, биллингом org.
  - `SecurityOfficer`: аудит, access rules, отклонение/ревокация пропусков.
  - `Operator`: создание/подтверждение встреч, выпуск/печать пропусков, мониторинг очередей.
  - `Host`: приглашения для своих встреч, просмотр статусов гостей.
  - `Guest`: ограниченный доступ к своим пропускам.
  - `Developer`: управление приложениями/ключами, чтение логов вебхуков.
- **Механизмы**: Laravel Gates/Policies; middleware `role`, `permission`, `org-scope`; атрибуты ресурсов фильтруются по org/department; feature flags по планам; audit trail для всех админ/опасных действий.

## Нефункциональные требования
- Безопасность: JWT/OAuth2/SAML, rate limiting, WAF, защита QR (одноразовые токены), шифрование PII, DLP.
- Масштабируемость: горизонтальное масштабирование фронтов и API; Redis cluster; MySQL read replicas.
- Наблюдаемость: logs + traces (OpenTelemetry), метрики (Prometheus), алерты, дашборды Update Manager/Horizon.
- Доступность: HA для Redis/MySQL, бэкапы, миграции с lock timeout, blue/green.
- Производительность: кэширование справочников, ETag/HTTP-кэш, CDN для статичных ассетов.

## Конфигурация очередей и событий
- Jobs: `SendNotification`, `IssueVisitPass`, `SyncAccessControl`, `DispatchWebhook`, `ProcessUpdatePackage`.
- Events: `AppointmentCreated`, `VisitPassIssued`, `CheckinRegistered`, `UpdateJobCompleted`.
- Слушатели: триггер уведомлений, обновление статистики, запись аудита.

## API контракты (уровень ресурсов)
- `/api/guest`: auth, passes, appointments (read-only), check-in подтверждение.
- `/api/host`: приглашения, гости, комнаты, календарь.
- `/api/admin`: users/roles, organizations, access-rules, passes, appointments, billing, notifications, update-manager.
- `/api/devconsole`: applications, api-keys, webhooks, sandbox, usage stats.
- `/api/internal`: health, metrics, readiness, version (используется Update Manager/CI).

