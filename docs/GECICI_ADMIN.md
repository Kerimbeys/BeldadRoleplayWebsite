# 🔐 Geçici Admin Hesabı

## Giriş Bilgileri

Veritabanı bağlantısı olmadığında kullanabileceğiniz geçici admin hesabı:

- **Kullanıcı Adı:** `admin`
- **Şifre:** `admin123`

## Nasıl Kullanılır?

1. Tarayıcıda `http://localhost:8000/login.php` adresine gidin
2. Yukarıdaki kullanıcı adı ve şifre ile giriş yapın
3. Admin paneli erişimi olacak

## Özellikler

✅ Admin paneline erişim
✅ Tüm sayfaları görüntüleme
⚠️ Veritabanı bağlantısı olmadığı için bazı özellikler çalışmayabilir

## Güvenlik Uyarısı

⚠️ **ÖNEMLİ:** Bu geçici admin hesabı sadece test amaçlıdır!

Production ortamında:
1. `config.php` dosyasında `TEMP_ADMIN_ENABLED` değerini `false` yapın
2. VEYA geçici admin şifresini değiştirin

## Şifre Değiştirme

`config.php` dosyasında şu satırları düzenleyin:

```php
define('TEMP_ADMIN_USERNAME', 'yeni_kullanici_adi');
define('TEMP_ADMIN_PASSWORD', 'yeni_sifre');
```

## Geçici Admin'i Kapatma

`config.php` dosyasında:

```php
define('TEMP_ADMIN_ENABLED', false);
```

---

**Not:** Veritabanı bağlantısı kurulduğunda normal giriş sistemi çalışacaktır.

