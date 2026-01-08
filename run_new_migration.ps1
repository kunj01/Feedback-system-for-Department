Set-Location "d:\UGSF sem 6\Main\training-placement"
php artisan migrate --force
Write-Host "`n✓ Migration completed successfully!" -ForegroundColor Green
Read-Host "`nPress Enter to continue"
