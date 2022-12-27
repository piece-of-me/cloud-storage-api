# Требования

* Установленный [node-js](https://nodejs.org/en/download/).
* Установленный [composer](https://getcomposer.org/download/).
* Установленный [Docker](https://docs.docker.com/engine/install/).

# Установка

* Клонировать репозиторий `git clone https://github.com/piece-of-me/cloud-storage-api.git`;
* Перейти в папку storage `cd storage`;
* Установить пакеты и зависимости с помощью `npm install` и `composer install`;
* Скопировать переменные окружения `cat .env.example > .env`;
* Запустить команду `php artisan sail:install`. Далее Вам предложат выбрать сервисы для установки. Необходимо выбрать `mysql` (Ввести `0` и нажать `Enter`);
* Запустить Sail в фоновом режиме с помощью команды `./vendor/bin sail up -d`;
* Выполнить миграцию базы данных с помощью `php artisan migrate`;
* Включить обработчик очереди с помощью `php artisan queue:listen`.