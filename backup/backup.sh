#!/bin/bash
echo "📦 Sauvegarde des bases de données..."
mysqldump -u root -prootpassword --all-databases > /backup/mysql_backup.sql
echo "✅ Sauvegarde terminée."
