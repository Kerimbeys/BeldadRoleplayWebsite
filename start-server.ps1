# Beldad Roleplay - Yerel Sunucu Başlatma Scripti (PowerShell)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Beldad Roleplay - Yerel Sunucu Başlatılıyor" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Sunucu başlatıldı!" -ForegroundColor Green
Write-Host "Tarayıcıda açmak için: http://localhost:8000" -ForegroundColor Yellow
Write-Host ""
Write-Host "Durdurmak için: Ctrl+C" -ForegroundColor Red
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Mevcut dizine geç
Set-Location $PSScriptRoot

# PHP sunucusunu başlat
php -S localhost:8000

