#!/bin/bash

# Запуск скрипта:
# Вариант 1: deploy_poly
# Вариант 2: deploy_poly true

# Параметры
# $1 = true или ничего (Флаг на обновление ролей)

if [ "$EUID" -ne 0 ]; then
    echo "Ошибка: Этот скрипт должен запускаться от имени root. Используйте: sudo."
    exit 1
fi

# Переменные
export http_proxy="http://10.1.50.237:8080"
for v in HTTP_PROXY https_proxy HTTPS_PROXY ftp_proxy FTP_PROXY; do
    export "$v=$http_proxy"
done
SOURCE_DIR="/var/www/sklad-app"
TIMESTAMP=$(date +"%Y_%m_%d_%H:%M:%S")
PROJECT_NAME="poly_$TIMESTAMP"
PROJECT_DIR="/root/project_versions/$PROJECT_NAME"
IMAGES_DIR="storage/app/public"

# 0: Подготовительный этап: Дамп БД и скачиваем проект из GIT
mkdir -p /root/backups
pg_dump -h localhost \
    -U "$DB_USER" \
    -d "$DB_NAME" \
    >"/root/backups/db_backup_$(date +"%Y_%m_%d_%H:%M:%S").sql" &&
    echo "Бэкап БД успешно выполнен"

mkdir -p "$PROJECT_DIR" && echo "Создаем директорию для проекта: $PROJECT_DIR."

REPO_URL="https://github.com/daniil-avangard/sklad_prod.git"
BRANCH="main"

echo "Клонируем репозиторий в $PROJECT_DIR"
git clone -b "$BRANCH" "$REPO_URL" "$PROJECT_DIR"

if [ $? -eq 0 ]; then
    echo "Проект успешно склонирован в $PROJECT_DIR"
else
    echo "Ошибка: Не удалось клонировать репозиторий."
    exit 1
fi

# Проверка наличия директорий.
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Ошибка: Исходящая директория не существует: $SOURCE_DIR."
    exit 1
fi

if [ ! -d "$PROJECT_DIR" ]; then
    echo "Ошибка: Целевая директория не существует: $PROJECT_DIR."
    exit 1
fi

# Первый этап: Копируем данные из тек. проекта в новую версию
if [ -f "$SOURCE_DIR"/.env ]; then
    cp "$SOURCE_DIR"/.env "$PROJECT_DIR"/.env
    echo "Файл .env успешно скопирован."
else
    echo "Ошибка файл .env не найден в источнике."
    exit 1
fi

if [ ! -d "$SOURCE_DIR"/storage/app/public ]; then
    echo "Ошибка: Директория c картинками не существует: $SOURCE_DIR/storage/app/public."
    exit 1
fi

mkdir -p "/root/project_versions/$IMAGES_DIR"
cp -r "$SOURCE_DIR/$IMAGES_DIR"/* /root/project_versions/"$IMAGES_DIR"

if [ "$(ls -A /root/project_versions/$IMAGES_DIR)" ]; then
    echo "Картинки успешно скопированы в резервное хранилище /root/project_versions/$IMAGES_DIR."
else
    echo "Не найдено картинок для копирования."
    exit 1
fi

# Второй этап: Удаляем текущий проект и загружаем новую версию
rm -rf "$SOURCE_DIR" && echo "Исходящая директория $SOURCE_DIR успешно удалена."
mkdir -p "$SOURCE_DIR" && echo "Исходящая директория $SOURCE_DIR успешно создана."
cp -r "$PROJECT_DIR"/. "$SOURCE_DIR" && echo "Проект успешно скопирован. Из $PROJECT_DIR в $SOURCE_DIR"
cp -r /root/project_versions/"$IMAGES_DIR"/* "$SOURCE_DIR/$IMAGES_DIR" && echo "Картинки успешно скопированы из резервного хранилища"

# Третий этап: Устанавливаем зависимости проекта
cd "$SOURCE_DIR"
npm i
npm run build
composer i

# Четвертый этап: установка прав на директории
sudo chown -R www-data:www-data "$SOURCE_DIR"
chmod -Rf 0777 storage

# Пятый этап: Обновление конфига проекта
php artisan key:generate
composer dump-autoload --optimize

# Очищает старые кэши
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan cache:clear

# Устанавливает новый кэш (config, route, view, event)
php artisan storage:link
php artisan optimize

# Шестой этап: Выполняем миграции
PENDING_MIGRATIONS=$(php artisan migrate:status | grep "Pending" | wc -l)

if [ "$PENDING_MIGRATIONS" -gt 0 ]; then
    echo "Найдено ($PENDING_MIGRATIONS) незапущенных миграций. Выполняю миграции."
    php artisan migrate
else
    echo "Все миграции уже выполнены."
fi

# Седьмой этап: Обновление прав если были правки
DB_NAME="poly_second"
DB_USER="postgres"

if [ "$1" = "true" ]; then
    echo "Очищаем таблицу permissions..."
    echo "DELETE FROM permissions;" | psql -h localhost -U "$DB_USER" -d "$DB_NAME"
    php artisan permissions:create && echo "Права успешно установлены."
fi
