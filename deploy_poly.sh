#!/bin/bash

# Запуск скрипта:
# deploy_poly token true

# Параметры
# $1 = PAT ключ пользователя от GIT
# $2 = true (Флаг на обновление ролей)

if [ "$EUID" -ne 0 ]; then
    echo "❌ Ошибка: Этот скрипт должен запускаться от имени root."
    echo "Используйте: sudo $0"
    exit 1
fi

# Переменные
GIT_PAT_TOKEN=$1
SOURCE_DIR="/var/www/sklad-app"
TIMESTAMP=$(date +"%Y_%m_%d_%H:%M:%S")
PROJECT_NAME="poly_$TIMESTAMP"
PROJECT_DIR="/root/project_versions/$PROJECT_NAME"

# Подготовительный этап: скачиваем проект из GIT
mkdir -p "$PROJECT_DIR" && echo "Создаем директорию $PROJECT_DIR для проекта."

REPO_URL="https://$GIT_PAT_TOKEN@github.com/daniil-avangard/sklad_prod.git"
BRANCH="main"

echo "Клонируем репозиторий в $PROJECT_DIR"
export http_proxy="http://10.1.50.237:8080"
for v in HTTP_PROXY https_proxy HTTPS_PROXY ftp_proxy FTP_PROXY; do
    export "$v=$http_proxy"
done
git clone -b "$BRANCH" "$REPO_URL" "$PROJECT_DIR"

if [ $? -eq 0 ]; then
    echo "✅ Проект успешно загружен в $PROJECT_DIR"
else
    echo "❌ Ошибка: Не удалось клонировать репозиторий."
    exit 1
fi

# Проверка наличия директорий.
if [ ! -d "$SOURCE_DIR" ]; then
    echo "Ошибка: Исходящая директория $SOURCE_DIR не существует."
    exit 1
fi

if [ ! -d "$PROJECT_DIR" ]; then
    echo "Ошибка: Целевая директория $PROJECT_DIR не существует."
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
    echo "Ошибка: Директория $SOURCE_DIR/storage/app/public не существует."
    exit 1
fi

mkdir -p "$PROJECT_DIR"/storage/app/public/
cp -r "$SOURCE_DIR"/storage/app/public/* "$PROJECT_DIR"/storage/app/public/
# rsync -a --delete "$SOURCE_DIR"/storage/app/public/* "$PROJECT_DIR"/storage/app/public/

if [ "$(ls -A $PROJECT_DIR/storage/app/public/)" ]; then
    echo "Директория: storage/app/public/* успешно скопирована"
else
    echo "Не найдено картинок при копировании директории."
    exit 1
fi

# Второй этап: Удаляем текущий проект и загружаем новую версию
rm -rf "$SOURCE_DIR" && echo "Исходящая директория $SOURCE_DIR успешно удалена."
mkdir -p "$SOURCE_DIR" && echo "Исходящая директория $SOURCE_DIR успешно создана."
cp -r "$PROJECT_DIR"/. "$SOURCE_DIR" && echo "Проект успешно скопирован. Из $PROJECT_DIR в $SOURCE_DIR"
# rsync -a --delete "$PROJECT_DIR"/ "$SOURCE_DIR"/ && echo "Проект успешно скопирован. Из $PROJECT_DIR в $SOURCE_DIR"

# Третий этап: Устанавливаем зависимости проекта
cd "$SOURCE_DIR"
npm i
npm run build
composer i

# Четвертый этап: установка прав на директории
sudo chown -R www-data:www-data $SOURCE_DIR
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
php artisan optimize

# Шестой этап: Обновление прав если были правки
DB_NAME="poly_second"
DB_USER="postgres"

if [ "$3" = "true" ]; then
    echo "Очищаем таблицу permissions..."
    echo "DELETE FROM permissions;" | psql -h localhost -U "$DB_USER" -d "$DB_NAME"
    php artisan permissions:create && echo "Права успешно установлены."
fi
