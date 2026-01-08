@echo off
echo ==========================================
echo    RUNNING DATABASE MIGRATION
echo ==========================================
echo.
cd /d "%~dp0"
php run_migration.php
echo.
echo ==========================================
