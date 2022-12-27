# Требования

* Установленный [node-js](https://nodejs.org/en/download/).
* Установленный [composer](https://getcomposer.org/download/).
* Установленный [Docker](https://docs.docker.com/engine/install/).

# Установка

### Клонирование репозитория
* Клонировать репозиторий `git clone https://github.com/piece-of-me/cloud-storage-api.git`;
* Перейти в папку storage `cd cloud-storage-api`;

### Установка пакетов и зависимостей
<!--* Установить пакеты и зависимости с помощью `npm install`;-->
* Если установлен php ниже версии 8.0.2, необходимо выполнить `docker run --rm -v $(pwd):/app composer/composer:latest install`;
* Если версия php выше 8.0.2, то `composer install`;

### Запуск приложения
- Скопировать переменные окружения `cat .env.example > .env`;
- Запустить Sail в фоновом режиме с помощью `./vendor/bin/sail up -d`;
- Выполнить миграцию базы данных с помощью `./vendor/bin/sail artisan migrate`;
- Включить обработчик очереди с помощью `./vendor/bin/sail artisan queue:listen`.

# Дополнительно

* Вместо того чтобы многократно вводить `./vendor/bin/sail` вы можете создать синоним с помощью `alias sail='./vendor/bin/sail'`.

# Postman

* Коллекция postman находится в папке `postman-collection`;
* Перед использованием, необходимо изменить переменную `url` в корне коллекции на корректный.

# Возможные проблемы

* Если при выполнении команды `sail` будут появляться ошибки вида: `./.env: line 6: $'\r': command not found`, вы можете их исправить с помощью `dos2unix .env` и `dos2unix .env.example`, конвертировав файлы в Unix-формат.
* Если при выполнении `./vendor/bin/sail artisan migrate` миграции не выполняются (отображается сообщение `Nothing to migrate.`), выполните `./vendor/bin/sail artisan migrate:reset`, чтобы откатить все миграции и запустите `./vendor/bin/sail artisan migrate` заново.

### Реализованный дополнительный функционал

* Получение размера файлов внутри директории;
* Получение размеров всех файлов на диске;
* Генерация уникальной публичной ссылки на файл;
* Возможность при загрузке указывать срок хранения файла, после которого он сам удаляется;