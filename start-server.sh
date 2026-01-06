#!/bin/bash
# Beldad Roleplay - Yerel Sunucu Başlatma Scripti (Linux/Mac)

echo "========================================"
echo "Beldad Roleplay - Yerel Sunucu Başlatılıyor"
echo "========================================"
echo ""
echo "Sunucu başlatıldı!"
echo "Tarayıcıda açmak için: http://localhost:8000"
echo ""
echo "Durdurmak için: Ctrl+C"
echo "========================================"
echo ""

# Mevcut dizine geç
cd "$(dirname "$0")"

# PHP sunucusunu başlat
php -S localhost:8000

