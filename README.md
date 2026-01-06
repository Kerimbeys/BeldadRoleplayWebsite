# 🎮 Beldad Roleplay

[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-success)]()

> **📚 Detaylı dokümantasyon için:** [`docs/`](docs/) klasörüne bakın.

## ✨ Özellikler

### 👤 Kullanıcı Özellikleri
- ✅ Güvenli giriş sistemi
- ✅ Profil yönetimi ve istatistikler
- ✅ Malvarlığı görüntüleme (Araçlar, Evler, Şirketler)
- ✅ Destek ticket sistemi
- ✅ Responsive tasarım (Mobil uyumlu)

### 🛡️ Admin Özellikleri
- ✅ Kullanıcı yönetimi
- ✅ Ticket yönetimi
- ✅ İstatistikler ve raporlar
- ✅ Güvenlik kontrolleri
- ✅ **🌙 Dark Mode Toggle** (LocalStorage ile kayıt)
- ✅ **Glass Morphism** tasarım
- ✅ **Smooth Scrolling** animasyonları
- ✅ **Real-time** sistem durumu göstergeleri
- ✅ **Interactive** grafikler ve istatistikler

### 🎨 Tasarım Özellikleri
- ✅ Modern dark mode tasarım
- ✅ **🌙 Admin Panel Dark Mode** (Toggle ile geçiş)
- ✅ Animasyonlu particles efekti
- ✅ Özelleştirilebilir background
- ✅ Chart.js grafikleri
- ✅ Toast bildirimleri
- ✅ Smooth animasyonlar
- ✅ **Glass Morphism** efekti
- ✅ **Responsive** admin paneli
- ✅ **Ultra Modern UI/UX**

## 📋 Gereksinimler

- **PHP:** 5.6 veya üzeri (PDO desteği ile)
- **MySQL:** 5.5 veya üzeri
- **Web Sunucusu:** Apache/Nginx
- **Tarayıcı:** Modern web tarayıcısı (Chrome, Firefox, Edge, Safari)

### 📦 PHP Eklentileri
- PDO
- PDO_MySQL
- Session
- JSON

## 🚀 Hızlı Kurulum

1. **Dosyaları indirin** ve web sunucunuzun root dizinine kopyalayın
2. **Veritabanı ayarlarını** `config.php` dosyasında güncelleyin
3. **Veritabanı tablolarını** oluşturun (bkz: [docs/VERITABANI_HAZIRLIK.md](docs/VERITABANI_HAZIRLIK.md))
4. **Tarayıcıda** projeyi açın

> **📖 Detaylı kurulum için:** [docs/KURULUM.md](docs/KURULUM.md)

## 🔒 Güvenlik

- ✅ Tüm SQL sorguları PDO prepared statements kullanır
- ✅ Session yönetimi güvenli şekilde yapılmaktadır
- ✅ XSS koruması için `htmlspecialchars()` kullanılmaktadır
- ✅ SQL Injection koruması PDO ile sağlanmaktadır

## 📁 Dosya Yapısı

```
├── assets/              # Statik dosyalar
│   ├── css/            # Stil dosyaları
│   ├── js/             # JavaScript dosyaları
│   └── images/         # Görseller ve favicon
├── includes/            # Ortak dosyalar
│   ├── header.php      # Üst kısım
│   ├── footer.php      # Alt kısım
│   └── functions.php   # Yardımcı fonksiyonlar
├── admin/               # Admin paneli
│   ├── index.php       # Admin ana sayfa
│   ├── users.php       # Kullanıcı yönetimi
│   ├── tickets.php     # Ticket yönetimi
│   └── ...
├── docs/                # Dokümantasyon
│   ├── README.md       # Dokümantasyon indeksi
│   ├── KURULUM.md      # Kurulum rehberi
│   └── ...
├── database/            # SQL dosyaları
│   └── tickets.sql     # Tickets tablosu
├── config.php          # Yapılandırma
├── db.php              # Veritabanı bağlantısı
├── session.php         # Session yönetimi
├── login.php           # Giriş sayfası
├── index.php           # Ana sayfa
├── README.md           # Bu dosya
└── .gitignore          # Git ignore dosyası
```

> **Detaylı dosya yapısı için:** [docs/PROJE_OZET.md](docs/PROJE_OZET.md)

## 📚 Dokümantasyon

Tüm dokümantasyon dosyaları [`docs/`](docs/) klasöründe bulunmaktadır:

### 🚀 Hızlı Başlangıç
- **[docs/KURULUM.md](docs/KURULUM.md)** - Detaylı kurulum rehberi
- **[docs/VERITABANI_HAZIRLIK.md](docs/VERITABANI_HAZIRLIK.md)** - Veritabanı hazırlama
- **[docs/GECICI_ADMIN.md](docs/GECICI_ADMIN.md)** - Geçici admin hesabı

### 🎨 Özelleştirme
- **[docs/BACKGROUND_HIZLI_DEGISTIRME.md](docs/BACKGROUND_HIZLI_DEGISTIRME.md)** - Background değiştirme
- **[docs/FAVICON_REHBERI.md](docs/FAVICON_REHBERI.md)** - Favicon değiştirme

### 📝 Proje Bilgileri
- **[docs/PROJE_OZET.md](docs/PROJE_OZET.md)** - Proje özeti ve özellikler
- **[docs/GELISTIRMELER.md](docs/GELISTIRMELER.md)** - Yapılan geliştirmeler
- **[docs/CHANGELOG.md](docs/CHANGELOG.md)** - Değişiklik geçmişi

**Tüm dokümantasyon için:** [`docs/README.md`](docs/README.md)

## 🎨 Tema

Dark mode gaming teması kullanılmaktadır:
- Ana renk: `#0a0e27`
- Vurgu rengi: `#00d4ff`
- Bootstrap 5 kullanılmaktadır
- Particles.js animasyonları
- Chart.js grafikleri

## �️ Admin Paneli Kullanım Kılavuzu

### 🌙 Dark Mode Özelliği
1. Admin paneline giriş yapın: `http://localhost:8000/admin/`
2. Sağ üst köşedeki **toggle butonu** ile tema değiştirin
3. Seçiminiz otomatik olarak kaydedilir (LocalStorage)

### 📊 Admin Paneli Özellikleri
- **Glass Morphism** tasarımı ile modern görünüm
- **Smooth scrolling** animasyonları
- **Real-time** istatistikler ve grafikler
- **Interactive** hızlı işlem kartları
- **System health** göstergeleri
- **Responsive** tasarım (tüm cihazlarda çalışır)

### 🎯 Hızlı İşlemler
- Kullanıcı yönetimi için **"Kullanıcıları Yönet"** butonu
- Ticket yönetimi için **"Ticketları Görüntüle"** butonu
- Sistem araçları için **"Araç Yönetimi"** butonu
- Mülk yönetimi için **"Ev Yönetimi"** butonu

## �📝 Notlar

- Şifreler MD5 veya bcrypt ile hash'lenmiş olabilir (her ikisi de desteklenir)
- Admin yetkisi `admin > 0` kontrolü ile yapılmaktadır
- Session süresi 1 saat olarak ayarlanmıştır

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/AmazingFeature`)
3. Commit yapın (`git commit -m 'Add some AmazingFeature'`)
4. Push yapın (`git push origin feature/AmazingFeature`)
5. Pull Request açın

## 📝 Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## 👨‍💻 Geliştirici

MTA:SA User Control Panel - Modern ve güvenli kullanıcı kontrol paneli

---

**⭐ Beğendiyseniz yıldız vermeyi unutmayın!**
