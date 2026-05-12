@echo off
echo ========================================
echo Backing up QuickParts for USB Transfer
echo ========================================

echo 1. Exporting database...
mysqldump -u root -p motorcycle_parts > database_backup.sql

echo 2. Copying product images...
xcopy /E /I "storage\app\public\products" "products_images_backup"

echo 3. Creating restore script...
echo @echo off > restore_on_new_computer.bat
echo echo Restoring QuickParts... >> restore_on_new_computer.bat
echo mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS motorcycle_parts" >> restore_on_new_computer.bat
echo mysql -u root -p motorcycle_parts ^< database_backup.sql >> restore_on_new_computer.bat
echo php artisan storage:link >> restore_on_new_computer.bat
echo php artisan optimize:clear >> restore_on_new_computer.bat
echo echo Done! Run php artisan serve >> restore_on_new_computer.bat

echo ========================================
echo Backup complete! Copy folder to USB.
echo On new computer, run: restore_on_new_computer.bat
echo ========================================
pause