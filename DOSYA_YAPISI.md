# 📁 MTA:SA UCP - Dosya Yapısı

Bu dosya projenin tam dosya yapısını gösterir.

## 📂 Klasör Yapısı

```
Beldad Website/
│
├── 📁 admin/                    # Admin paneli
│   ├── index.php               # Admin ana sayfa (İstatistikler)
│   ├── tickets.php             # Ticket yönetimi (Filtreleme)
│   ├── ticket_manage.php       # Ticket yanıtlama ve kapatma
│   ├── users.php               # Kullanıcı yönetimi (Arama, Düzenleme)
│   └── stats.php               # Detaylı sistem istatistikleri
│
├── 📁 assets/                   # Statik dosyalar
│   ├── 📁 css/                 # Stil dosyaları
│   │   └── style.css           # Ana CSS dosyası (Dark mode gaming teması)
│   │
│   ├── 📁 images/              # Görseller
│   │   ├── favicon.ico         # Ana favicon
│   │   ├── favicon-16x16.png   # 16x16 favicon
│   │   ├── favicon-32x32.png   # 32x32 favicon
│   │   ├── apple-touch-icon.png # iOS favicon
│   │   ├── background.jpg      # Background resmi
│   │   └── README.md           # Images klasörü açıklaması
│   │
│   └── 📁 js/                  # JavaScript dosyaları
│       ├── main.js             # Ana JavaScript dosyası
│       ├── charts.js           # Chart.js helper fonksiyonları
│       └── particles-config.js # Particles.js yapılandırması
│
├── 📁 database/                 # Veritabanı dosyaları
│   └── tickets.sql             # Tickets tablosu SQL scripti
│
├── 📁 docs/                     # Dokümantasyon (YENİ!)
│   ├── README.md               # Dokümantasyon indeksi
│   ├── KURULUM.md              # Kurulum rehberi
│   ├── VERITABANI_HAZIRLIK.md  # Veritabanı hazırlama
│   ├── GECICI_ADMIN.md         # Geçici admin hesabı
│   ├── BACKGROUND_REHBERI.md   # Background detaylı rehberi
│   ├── BACKGROUND_HIZLI_DEGISTIRME.md # Background hızlı değiştirme
│   ├── BACKGROUND_SORUN_GIDERME.md    # Background sorun giderme
│   ├── FAVICON_REHBERI.md      # Favicon değiştirme rehberi
│   ├── PROJE_OZET.md           # Proje özeti ve özellikler
│   ├── GELISTIRMELER.md        # Yapılan geliştirmeler
│   └── CHANGELOG.md            # Değişiklik geçmişi
│
├── 📁 includes/                 # Ortak dosyalar
│   ├── header.php              # Ortak header (Navigation)
│   ├── footer.php              # Ortak footer
│   └── functions.php          # Yardımcı fonksiyonlar
│
├── 📄 config.php                # Yapılandırma dosyası
├── 📄 db.php                    # Veritabanı bağlantısı (Singleton)
├── 📄 session.php               # Session yönetimi
│
├── 📄 login.php                 # Giriş sayfası
├── 📄 logout.php                # Çıkış işlemi
├── 📄 index.php                 # Ana dashboard
├── 📄 profile.php               # Detaylı profil sayfası
│
├── 📄 vehicles.php              # Araçlarım listesi
├── 📄 interiors.php              # Evlerim listesi
├── 📄 companies.php              # Şirketlerim listesi
│
├── 📄 tickets.php                # Ticket listesi
├── 📄 ticket_view.php            # Ticket detay sayfası
│
├── 📄 error.php                  # Hata sayfası
│
├── 📄 README.md                  # Ana README dosyası
├── 📄 DOSYA_YAPISI.md            # Bu dosya
├── 📄 .gitignore                 # Git ignore dosyası
│
└── 📄 start-server.*            # Sunucu başlatma scriptleri
    ├── start-server.bat         # Windows batch
    ├── start-server.ps1         # PowerShell
    └── start-server.sh          # Linux/Mac shell
```

## 📋 Dosya Açıklamaları

### 🔧 Yapılandırma Dosyaları
- **config.php** - Veritabanı ayarları, site bilgileri, güvenlik ayarları
- **db.php** - PDO veritabanı bağlantısı (Singleton pattern)
- **session.php** - Session yönetimi, login kontrolü, admin yetkisi

### 🎨 Frontend Dosyaları
- **assets/css/style.css** - Ana stil dosyası (Dark mode, animasyonlar, particles)
- **assets/js/main.js** - JavaScript fonksiyonları (animasyonlar, toast, form validation)
- **assets/js/charts.js** - Chart.js helper fonksiyonları
- **assets/js/particles-config.js** - Particles.js yapılandırması

### 📄 Sayfa Dosyaları
- **login.php** - Kullanıcı giriş sayfası
- **index.php** - Ana dashboard (istatistikler, özet bilgiler)
- **profile.php** - Detaylı profil sayfası
- **vehicles.php** - Araç listesi
- **interiors.php** - Ev listesi
- **companies.php** - Şirket listesi
- **tickets.php** - Ticket listesi
- **ticket_view.php** - Ticket detay ve yanıtlama

### 🛡️ Admin Dosyaları
- **admin/index.php** - Admin ana sayfa (istatistikler, hızlı erişim)
- **admin/tickets.php** - Ticket yönetimi (filtreleme, arama)
- **admin/ticket_manage.php** - Ticket yanıtlama ve kapatma
- **admin/users.php** - Kullanıcı yönetimi (arama, düzenleme)
- **admin/stats.php** - Detaylı sistem istatistikleri

### 📚 Dokümantasyon
- **docs/** - Tüm dokümantasyon dosyaları bu klasörde
- **README.md** - Ana README dosyası
- **DOSYA_YAPISI.md** - Bu dosya

## 🔍 Dosya Boyutları

- **CSS:** ~700 satır (style.css)
- **JavaScript:** ~260 satır (main.js) + ~130 satır (particles-config.js) + ~100 satır (charts.js)
- **PHP:** ~50-350 satır arası (sayfa başına)

## 📝 Notlar

- Tüm dokümantasyon dosyaları `docs/` klasöründe toplanmıştır
- Gereksiz dosyalar temizlenmiştir (`background-custom.css` silindi)
- `.gitignore` dosyası eklendi
- Favicon ve background resimleri `assets/images/` klasöründe

---

**Son Güncelleme:** Dosya yapısı düzenlendi ve dokümantasyon organize edildi.

