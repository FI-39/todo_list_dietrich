#!/bin/bash

TIMESTAMP=$(date +%Y-%m-%d_%H-%M-%S)
BACKUP_DIR="backups"
DB_NAME="todo_list"
DB_USER="dando"
BACKUP_FILE="$BACKUP_DIR/${DB_NAME}_backup_$TIMESTAMP.sql"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p $DB_NAME > $BACKUP_FILE

echo "Backup erstellt: $BACKUP_FILE"
