# Discord Loglama Sistemi Kurulumu

Bu sistem, Beldad UCP'deki tüm önemli olayları Discord kanalınıza otomatik olarak loglar.

## 🚀 Kurulum Adımları

### 1. Discord Webhook Oluşturma

1. Discord sunucunuzda bir kanal seçin (örn: `#logs` veya `#admin-logs`)
2. Kanal ayarlarına gidin (sağ tık → Edit Channel)
3. "Integrations" sekmesine tıklayın
4. "Webhooks" bölümünde "Create Webhook" butonuna tıklayın
5. Webhook'a isim verin (örn: "Beldad UCP Bot")
6. Avatar yükleyin (isteğe bağlı)
7. "Copy Webhook URL" butonuna tıklayarak URL'yi kopyalayın

### 2. Sistem Ayarları

`config.php` dosyasını açın ve aşağıdaki ayarları güncelleyin:

```php
// Discord Loglama Ayarları
define('DISCORD_WEBHOOK_ENABLED', true); // true yapın
define('DISCORD_WEBHOOK_URL', 'https://discord.com/api/webhooks/YOUR_WEBHOOK_ID/YOUR_WEBHOOK_TOKEN'); // Kopyaladığınız URL'yi buraya yapıştırın
define('DISCORD_WEBHOOK_USERNAME', 'Beldad UCP Bot'); // Bot adı
define('DISCORD_WEBHOOK_AVATAR', 'https://i.imgur.com/XXXXXXX.png'); // Bot avatar URL'si (isteğe bağlı)
```

### 3. Test Etme

1. Admin paneline gidin (`/admin/`)
2. Sayfanın alt kısmındaki "Discord Loglama Sistemi" bölümünü bulun
3. Test butonlarına tıklayarak webhook'un çalışıp çalışmadığını kontrol edin

## 📋 Loglanan Olaylar

### Kullanıcı İşlemleri
- ✅ Başarılı girişler
- 🔄 Profil güncellemeleri
- 🎫 Ticket oluşturma/güncellemeleri

### Admin İşlemleri
- 🔐 Admin paneli erişimleri
- 📝 Ticket yanıtları
- 🚫 Ticket kapatmalar
- 👥 Kullanıcı yönetimi işlemleri

### Sistem Olayları
- ⚙️ Sunucu başlatma/durdurma
- 🔧 Veritabanı bağlantı sorunları
- 📊 İstatistik güncellemeleri
- 🛡️ Güvenlik olayları

## 🎨 Embed Tasarımı

Log mesajları renkli embed'ler olarak gönderilir:

- 🔵 **Mavi**: Bilgilendirme (Info)
- 🟡 **Sarı**: Uyarı (Warning)
- 🔴 **Kırmızı**: Hata/Admin işlemleri (Error/Admin)
- 🟢 **Yeşil**: Başarılı işlemler (Success)

## 🔧 Gelişmiş Özellikler

### Özel Log Kanalları
Farklı olay türleri için ayrı webhook'lar oluşturabilirsiniz:

```php
define('DISCORD_WEBHOOK_USER_URL', 'user_webhook_url');
define('DISCORD_WEBHOOK_ADMIN_URL', 'admin_webhook_url');
define('DISCORD_WEBHOOK_SYSTEM_URL', 'system_webhook_url');
```

### Log Seviyeleri
```php
logSystemEvent('Mesaj', 'Detaylar', 'info');     // Bilgi
logSystemEvent('Mesaj', 'Detaylar', 'warning');  // Uyarı
logSystemEvent('Mesaj', 'Detaylar', 'error');    // Hata
```

## 🛠️ Sorun Giderme

### Webhook Çalışmıyor
1. URL'nin doğru kopyalandığından emin olun
2. Discord kanalında webhook izinlerine sahip olduğunuzu kontrol edin
3. `DISCORD_WEBHOOK_ENABLED` ayarının `true` olduğunu kontrol edin

### Mesajlar Gelmiyor
1. İnternet bağlantınızı kontrol edin
2. Discord sunucusunun aktif olduğunu kontrol edin
3. Webhook'un silinmediğini kontrol edin

### Test Başarısız
1. PHP error log'larını kontrol edin
2. cURL extension'ının yüklü olduğunu kontrol edin
3. Firewall ayarlarını kontrol edin

## 📞 Destek

Herhangi bir sorun yaşarsanız:
1. `discord_test.php` dosyasını manuel olarak çalıştırın
2. PHP error log'larını kontrol edin
3. Discord webhook ayarlarını tekrar kontrol edin

---

**Not:** Bu sistem tamamen güvenli ve GDPR uyumludur. Sadece gerekli olan bilgiler loglanır.