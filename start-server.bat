@echo off
echo ========================================
echo Beldad Roleplay - Yerel Sunucu Baslatiliyor
echo ========================================
echo.
echo Sunucu baslatildi!
echo Tarayicida acmak icin: http://localhost:8000
echo.
echo Durdurmak icin: Ctrl+C
echo ========================================
echo.

cd /d "%~dp0"
php -S localhost:8000

pause

