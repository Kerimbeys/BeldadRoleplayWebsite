<?php
/**
 * MTA:SA UCP - Yapılandırma Dosyası
 * Veritabanı ve genel ayarlar
 */

// Hata raporlama (production'da kapatılmalı)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Zaman dilimi
date_default_timezone_set('Europe/Istanbul');

// Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'beldadmta');
define('DB_USER', 'root');
define('DB_PASS', 'beldad34');
define('DB_CHARSET', 'utf8mb4');

// Site Ayarları
define('SITE_NAME', 'Beldad Roleplay');
define('SITE_URL', 'http://localhost/ucp');

// Session Ayarları
define('SESSION_LIFETIME', 3600); // 1 saat (saniye cinsinden)

// Güvenlik
define('HASH_ALGORITHM', 'sha256');

// Geçici Admin Hesabı (Veritabanı bağlantısı olmadığında kullanılır)
define('TEMP_ADMIN_ENABLED', true); // Geçici admin'i aktif etmek için true
define('TEMP_ADMIN_USERNAME', 'admin');
define('TEMP_ADMIN_PASSWORD', 'admin123'); // Şifre: admin123
define('TEMP_ADMIN_ID', 999);

// Discord Loglama Ayarları
define('DISCORD_WEBHOOK_ENABLED', true); // GEÇİCİ: Webhook URL'si geçersiz - Discord'da yeni webhook oluşturun
define('DISCORD_WEBHOOK_URL', 'https://discord.com/api/webhooks/1457911395654828045/g4cqAH9ZpdsavpwAr3jxU9gwW--m8DA-O8WXo3oNC5QAO3UmvSyIYlzMEWi5R-9uFeYI'); // Boş bırakın - yeni URL'yi buraya ekleyin
define('DISCORD_WEBHOOK_URL', 'https://discord.com/api/webhooks/1457911395654828045/g4cqAH9ZpdsavpwAr3jxU9gwW--m8DA-O8WXo3oNC5QAO3UmvSyIYlzMEWi5R-9uFeYI'); // Discord webhook URL'nizi buraya ekleyin
define('DISCORD_WEBHOOK_USERNAME', 'Beldad UCP Bot'); // Bot adı
define('DISCORD_WEBHOOK_AVATAR', 'https://i.imgur.com/lNqEeAJ.png'); // Bot avatar URL'si

