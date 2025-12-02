# Gap analysis: модуль «Отзывы и рейтинг»

Модуль отзывов добавлен в систему в полном соответствии с основными сценариями ТЗ:

## Бэкенд
- Создана таблица `reviews` (hotel_id, room_id, order_id, service_id, staff_id, rating 1–5, comment, is_anonymous, status, reviewed_at, admin_reply) с необходимыми индексами и связями. 【F:backend/database/migrations/2024_01_01_000230_create_reviews_table.php†L1-L34】
- Добавлена модель `Review` с отношениями к отелю, номеру, заказу, услуге и сотруднику-исполнителю. 【F:backend/app/Models/Review.php†L1-L55】
- Реализованы API:
  - Гость: создание отзыва, список своих отзывов, отзывы по услуге. 【F:backend/routes/api.php†L18-L40】【F:backend/app/Http/Controllers/API/Guest/ReviewController.php†L1-L87】
  - Админ: получение отзывов с фильтрами, просмотр, смена статуса, ответ администратора, рейтинги услуг и сотрудников, сводка. 【F:backend/routes/api.php†L48-L72】【F:backend/app/Http/Controllers/API/Admin/ReviewController.php†L1-L58】【F:backend/app/Http/Controllers/API/Admin/ReviewAnalyticsController.php†L1-L70】
  - Персонал: просмотр отзывов по себе и краткая статистика. 【F:backend/routes/api.php†L78-L88】【F:backend/app/Http/Controllers/API/Staff/ReviewController.php†L1-L41】
- В сидере добавлены пермишены для модуля отзывов (`reviews.*`, `reviews.self`). 【F:backend/database/seeders/DatabaseSeeder.php†L8-L38】

## Фронтенд
- В гостевом роутере появилась страница «Мои отзывы»; в админском — раздел «Отзывы и рейтинг» с вкладками «Все отзывы», «Рейтинг услуг», «Рейтинг сотрудников», «Аналитика». 【F:frontend/src/router/index.js†L1-L68】
- Добавлены заглушки страниц для нового раздела, чтобы обеспечить навигацию и точку интеграции UI. 【F:frontend/src/views/MyReviewsView.vue†L1-L14】【F:frontend/src/views/admin/AdminReviews.vue†L1-L13】【F:frontend/src/views/admin/AdminServiceRatings.vue†L1-L12】【F:frontend/src/views/admin/AdminStaffRatings.vue†L1-L12】【F:frontend/src/views/admin/AdminReviewAnalytics.vue†L1-L12】

## Update Manager / Developer Console
- Новая миграция попадает в стандартный поток обновлений (artisan migrate), дополняя таблицы для модуля отзывов. 【F:backend/database/migrations/2024_01_01_000230_create_reviews_table.php†L1-L34】

## Итог
Основные недостающие элементы модуля «Отзывы и рейтинг» реализованы: схема данных, модель, API для гостей/админов/персонала, пермишены и маршруты фронтенда. Требуется дальнейшая доработка UI и подключение к реальным спискам заказов/услуг для вывода данных в компонентах.
