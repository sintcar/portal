# Portal

## Структура проекта
- `backend/` — Laravel API; включает необходимые каталоги `storage/` и `bootstrap/cache/` для корректной работы artisan-команд и кеша.
- `frontend/` — клиентское приложение на Vite/Vue 3.
- `developer-console/` — сервис Node.js для управления релизами и лицензиями; устанавливает зависимости через `npm install` (каталог `node_modules` не хранится в репозитории).
- `update-manager/` — отдельный Laravel-компонент для управления обновлениями.
- `modules/` — описания подключаемых модулей.

После клонирования с GitHub структура повторяет серверное расположение каталогов: служебные директории приложения создаются заранее и снабжены `.gitkeep`, а временные и окруженческие файлы исключены через `.gitignore`.

## Развертывание на чистый сервер (пример s2.v-altay.ru)
Ниже — подробная пошаговая инструкция с конкретными командами для установки проекта из репозитория `https://github.com/sintcar/portal` в каталог `/var/www/www-root/data/www/s2.v-altay.ru` и настройки MySQL-базы `db3` (пароль `YraF2015`). Все команды предполагают пользователя с sudo-доступом.

1. **Установите системные зависимости**
   ```bash
   sudo apt update
   sudo apt install -y php php-cli php-fpm php-mysql php-xml php-mbstring php-zip php-curl php-bcmath php-gd \
       mysql-server redis-server nodejs npm git unzip supervisor
   sudo npm install -g npm@latest
   curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
   ```

2. **Клонируйте проект в целевой путь**
   ```bash
   sudo mkdir -p /var/www/www-root/data/www/s2.v-altay.ru
   sudo chown -R "$USER":"$USER" /var/www/www-root/data/www/s2.v-altay.ru
   git clone https://github.com/sintcar/portal.git /var/www/www-root/data/www/s2.v-altay.ru
   cd /var/www/www-root/data/www/s2.v-altay.ru
   ```

3. **Настройте переменные окружения backend (Laravel) и установите зависимости**
   ```bash
   cd backend
   cp .env.example .env
   composer install --no-dev --prefer-dist --optimize-autoloader
   php artisan key:generate
   ```
   Обновите параметры подключения к базе в `backend/.env`:
   ```bash
   sed -i 's/^DB_DATABASE=.*/DB_DATABASE=db3/' .env
   sed -i 's/^DB_USERNAME=.*/DB_USERNAME=db3/' .env
   sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=YraF2015/' .env
   ```

4. **Создайте базу данных и пользователя**
   ```bash
   sudo mysql -e "CREATE DATABASE IF NOT EXISTS db3 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   sudo mysql -e "CREATE USER IF NOT EXISTS 'db3'@'localhost' IDENTIFIED BY 'YraF2015';"
   sudo mysql -e "GRANT ALL PRIVILEGES ON db3.* TO 'db3'@'localhost'; FLUSH PRIVILEGES;"
   ```

5. **Выполните миграции и соберите кеши**
   ```bash
   php artisan migrate --force
   php artisan config:clear && php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```

6. **Опционально загрузите базовые данные**
   ```bash
   php artisan db:seed --force
   ```

7. **Соберите frontend**
   ```bash
   cd ../frontend
   npm ci
   npm run build
   cd ..
   ```

8. **Запустите и проверьте**
   - Настройте веб-сервер (Nginx/Apache) так, чтобы корнем сайта был `/var/www/www-root/data/www/s2.v-altay.ru/backend/public`.
   - Статические файлы фронтенда доступны в `/var/www/www-root/data/www/s2.v-altay.ru/frontend/dist`.
   - После настройки убедитесь, что API отвечает на `/api/install/status`.
