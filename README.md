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

## Пример конфигурации Nginx
Ниже приведён исправленный вариант конфигурации с одинаковым корнем для HTTP/HTTPS, корректным порядком объявления `$root_path` и актуальными настройками TLS. Обратите внимание, что директиву `set` нужно размещать внутри блоков `server` — если файл подключается сразу в основную секцию `nginx.conf`, вынесение `set` в начало файла приведёт к ошибке `"set" directive is not allowed here`.

```nginx
server {
    set $root_path /var/www/www-root/data/www/s2.v-altay.ru/backend/public;
    server_name s2.v-altay.ru www.s2.v-altay.ru;
    listen 5.35.126.252:80;

    charset off;
    index index.php index.html;
    root $root_path;
    disable_symlinks if_not_owner from=$root_path;

    include /etc/nginx/vhosts-includes/*.conf;
    include /etc/nginx/vhosts-resources/s2.v-altay.ru/*.conf;

    access_log /var/www/httpd-logs/s2.v-altay.ru.access.log;
    error_log  /var/www/httpd-logs/s2.v-altay.ru.error.log notice;

    ssi on;

    gzip on;
    gzip_comp_level 5;
    gzip_disable "msie6";
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript image/svg+xml;

    location / {
        location ~ [^/]\.ph(p\d*|tml)$ {
            try_files /does_not_exists @php;
        }

        location ~* ^.+\.(jpg|jpeg|gif|png|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf|webp|woff|woff2)$ {
            expires 24h;
        }
    }

    location @php {
        include /etc/nginx/vhosts-resources/s2.v-altay.ru/dynamic/*.conf;
        fastcgi_index index.php;
        fastcgi_param PHP_ADMIN_VALUE "sendmail_path = /usr/sbin/sendmail -t -i -f webmaster@s2.v-altay.ru";
        fastcgi_pass unix:/var/www/php-fpm/2.sock;
        fastcgi_split_path_info ^((?U).+\.ph(?:p\d*|tml))(/?.+)$;
        try_files $uri =404;
        include fastcgi_params;
    }
}

server {
    set $root_path /var/www/www-root/data/www/s2.v-altay.ru/backend/public;
    server_name s2.v-altay.ru www.s2.v-altay.ru;
    listen 5.35.126.252:443 ssl;

    ssl_certificate     "/var/www/httpd-cert/www-root/s2.v-altay.ru_le1.crtca"; # файл должен содержать полный chain
    ssl_certificate_key "/var/www/httpd-cert/www-root/s2.v-altay.ru_le1.key";
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'EECDH+AESGCM:EECDH+CHACHA20:!aNULL:!MD5:!DSS';
    ssl_prefer_server_ciphers on;
    ssl_dhparam /etc/ssl/certs/dhparam4096.pem;

    charset off;
    index index.php index.html;
    root $root_path;
    disable_symlinks if_not_owner from=$root_path;

    include /etc/nginx/vhosts-includes/*.conf;
    include /etc/nginx/vhosts-resources/s2.v-altay.ru/*.conf;

    access_log /var/www/httpd-logs/s2.v-altay.ru.access.log;
    error_log  /var/www/httpd-logs/s2.v-altay.ru.error.log notice;

    ssi on;

    gzip on;
    gzip_comp_level 5;
    gzip_disable "msie6";
    gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript image/svg+xml;

    location / {
        location ~ [^/]\.ph(p\d*|tml)$ {
            try_files /does_not_exists @php;
        }

        location ~* ^.+\.(jpg|jpeg|gif|png|svg|js|css|mp3|ogg|mpe?g|avi|zip|gz|bz2?|rar|swf|webp|woff|woff2)$ {
            expires 24h;
        }
    }

    location @php {
        include /etc/nginx/vhosts-resources/s2.v-altay.ru/dynamic/*.conf;
        fastcgi_index index.php;
        fastcgi_param PHP_ADMIN_VALUE "sendmail_path = /usr/sbin/sendmail -t -i -f webmaster@s2.v-altay.ru";
        fastcgi_pass unix:/var/www/php-fpm/2.sock;
        fastcgi_split_path_info ^((?U).+\.ph(?:p\d*|tml))(/?.+)$;
        try_files $uri =404;
        include fastcgi_params;
    }
}
```

Перед применением убедитесь, что файл сертификата содержит полную цепочку (fullchain), а путь к нему корректен.
