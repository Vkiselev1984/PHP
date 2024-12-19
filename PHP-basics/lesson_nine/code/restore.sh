#!/bin/bash

# Параметры базы данных
DB_CONTAINER_NAME="database"
DB_NAME="application1"
DB_USER="root"
BACKUP_PATH="/backup.sql"

# Восстановление базы данных из резервной копии
echo "Восстановление базы данных из резервной копии..."
cat $BACKUP_PATH | psql -U $DB_USER -d DB_NAME -f /backup.sql

if [ $? -eq 0 ]; then
    echo "База данных успешно восстановлена."
else
    echo "Ошибка при восстановлении базы данных."
fi
