@echo off
cd /d "%~dp0"
php artisan migrate --force
if %ERRORLEVEL% EQU 0 (
    echo.
    echo ===================================
    echo Migration completed successfully!
    echo ===================================
) else (
    echo.
    echo ===================================
    echo Migration failed with error code %ERRORLEVEL%
    echo ===================================
)
echo.
pause
