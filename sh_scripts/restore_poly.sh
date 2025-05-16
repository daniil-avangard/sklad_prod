#!/bin/bash

# Запуск скрипта:
# Вариант: restore_poly project_path

# Параметры
# $1 = путь до проекта

if [ "$EUID" -ne 0 ]; then
    echo "Ошибка: Этот скрипт должен запускаться от имени root. Используйте: sudo."
    exit 1
fi

# Переменные
export http_proxy="http://10.1.50.237:8080"
for v in HTTP_PROXY https_proxy HTTPS_PROXY ftp_proxy FTP_PROXY; do
    export "$v=$http_proxy"
done

if [ -z "$1" ]; then
    echo "Ошибка: Не передан путь до проекта."
    echo "Использование: $0 <project_path>"
    exit 1
fi

PROJECT_PATH="$1"
SOURCE_DIR="/var/www/sklad-app"
PROJECT_VERSION_DIR="/root/project_versions"
FULL_PROJECT_PATH="$PROJECT_VERSION_DIR"/"$PROJECT_PATH"

if [ ! -d "$SOURCE_DIR" ]; then
    echo "Ошибка: Исходящая директория не существует: $SOURCE_DIR."
    exit 1
fi

if [ ! -d "$FULL_PROJECT_PATH" ]; then
    echo "Ошибка: Директория проекта для восстановления не существует: $FULL_PROJECT_PATH."
    exit 1
fi

# Первый этап: копирует проект
rm -rf "$SOURCE_DIR" && echo "Исходящая директория успешно удалена."
mkdir -p "$SOURCE_DIR" && echo "Исходящая директория успешно создана."
cp -r "$FULL_PROJECT_PATH"/. "$SOURCE_DIR" && echo "Проект успешно скопирован. Из $FULL_PROJECT_PATH в $SOURCE_DIR"

# Второй этап: Устанавливаем зависимости проекта
cd "$SOURCE_DIR"
npm i
npm run build
composer i

# Третий этап: установка прав на директории
sudo chown -R www-data:www-data "$SOURCE_DIR"
chmod -Rf 0777 storage

# Четвертый этап: Обновление конфига проекта
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



# SOURCE_MIGRATION_FILES_LENGHT=$(find $SOURCE_DIR/$MIGRATION_DIR -maxdepth 1 -type f -name "*_*.php" | wc -l)
# PROJECT_MIGRATION_FILES_LENGHT=$(find $PROJECT_DIR/$MIGRATION_DIR -maxdepth 1 -type f -name "*_*.php" | wc -l)


# # Шестой этап: Делаем миграции
# if [ $PROJECT_MIGRATION_FILES_LENGHT -gt $SOURCE_MIGRATION_FILES_LENGHT ]; then
#     php artisan migrate
#     echo "Новые миграции успешно выполнены."
# else
#     echo "Миграции не требуются."
# fi


# psql -U username -d database_name -f backup_2025-04-05_12:00:00.sql
