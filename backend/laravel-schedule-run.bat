@echo off
cd /d D:\Mecsenzo_v3\backend
php artisan schedule:run >> NUL 2>&1
