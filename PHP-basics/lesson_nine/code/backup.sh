#!/bin/bash

# Параметры базы данных
DB_CONTAINER_NAME="database"
DB_NAME="application1"
DB_USER="root"
BACKUP_PATH="/mnt/c/Users/kiselev/Desktop/Git/PHP/PHP-basics/lesson_nine/db/backup.sql"

# Создание резервной копии базы данных
echo "Создание резервной копии базы данных..."
docker exec -t $DB_CONTAINER_NAME pg_dump -U $DB_USER -d $DB_NAME > $BACKUP_PATH

if [ $? -eq 0 ]; then
    echo "Резервная копия успешно создана: $BACKUP_PATH"
else
    echo "Ошибка при создании резервной копии."
fi
