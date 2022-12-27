# Требования

* Установленный [node-js](https://nodejs.org/en/download/).
* Установленный [composer](https://getcomposer.org/download/).
* Установленный [Docker](https://docs.docker.com/engine/install/).

# Установка

* Клонировать репозиторий `git clone https://github.com/piece-of-me/cloud-storage-api.git`;
* Перейти в папку storage `cd cloud-storage-api`;
* Установить пакеты и зависимости с помощью `npm install` и `composer install`;
* Скопировать переменные окружения `cat .env.example > .env`;
* Запустить команду `php artisan sail:install`. Далее Вам предложат выбрать сервисы для установки. Необходимо выбрать `mysql` (Ввести `0` и нажать `Enter`);
* Запустить Sail в фоновом режиме с помощью команды `./vendor/bin/sail up -d`;
* Выполнить миграцию базы данных с помощью `./vendor/bin/sail artisan migrate`;
* Включить обработчик очереди с помощью `./vendor/bin/sail artisan queue:listen`.

# Дополнительно

* Вместо того чтобы многократно вводить `./vendor/bin/sail` вы можете создать синоним с помощью `alias sail='./vendor/bin/sail'`.

# Возможные проблемы

* Если при выполнении команды `sail` будут появляться ошибки вида: `./.env: line 6: $'\r': command not found`, вы можете их исправить с помощью `dos2unix .env` и `dos2unix .env.example`, конвертировав файлы в Unix-формат.
* Если при выполнении `./vendor/bin/sail artisan migrate` миграции не выполняются (отображается сообщение `Nothing to migrate.`), выполните `./vendor/bin/sail artisan migrate:reset`, чтобы откатить все миграции и запустите `./vendor/bin/sail artisan migrate` заново.