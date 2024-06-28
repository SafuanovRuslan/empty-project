# Начало работы #

## XDebug ##

### Настройки проекта ###
1. В первую очередь в настройках файервола разрешить обращения
   на 9000 и 9003 порты.
2. В файле `docker-compose.yml` для сервиса `php-fpm` отредактировать 
   `environment`, установив название `PHP_IDE_CONFIG=serverName=<имя_сервера>`.

### Настройки PhpStorm ###
1. Переходим в `Build, Execution, Deployment -> Docker` и устанавливаем
   соединение через Unix socket (стандартные настройки).
2. Переходим в `Language -> PHP` и добавляем CLI Interpreter (выбрать Docker
   из списка, настройки оставить по-умолчанию).
3. Переходим в `Language -> PHP -> Servers` и добавляем новый сервер.
   В качестве имени сервера указываем `serverName` указанный при настройках
   проекта в пункте 2. Также включаем `path mappings`.
4. В файле `/docker/php-fpm/conf.d/xdebug.ini` в качестве `xdebug.client_host`
   указываем ip-адрес сети docker0 (ip-адрес можно узнать при помощи
   команды `ifconfig`).
5. Перезапустить контейнер
6. Запустить PHP Remote Debug

### Настройки браузера ###
1. Установить расширение для дебага Xdebug helper

https://www.youtube.com/watch?v=XszBIW4sPHk
   