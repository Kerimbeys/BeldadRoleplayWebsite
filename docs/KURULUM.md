# 🚀 MTA:SA UCP - Tarayıcıda Görüntüleme Rehberi

## Yerel Sunucu ile Çalıştırma

### Yöntem 1: PHP Built-in Server (Önerilen)

#### Windows için:
1. **Çift tıklayın:** `start-server.bat` dosyasına çift tıklayın
   - VEYA
2. **Komut satırından:**
   ```cmd
   cd "C:\Users\root\Desktop\Beldad Website"
   php -S localhost:8000
   ```

#### PowerShell için:
```powershell
cd "C:\Users\root\Desktop\Beldad Website"
.\start-server.ps1
```

#### Linux/Mac için:
```bash
cd "/path/to/Beldad Website"
chmod +x start-server.sh
./start-server.sh
```

### Tarayıcıda Açma:
Sunucu başladıktan sonra tarayıcınızda şu adresi açın:
```
http://localhost:8000
```

---

## Yöntem 2: XAMPP/WAMP/MAMP

### XAMPP Kullanımı:

1. **XAMPP'ı indirin ve kurun:**
   - https://www.apachefriends.org/

2. **Dosyaları kopyalayın:**
   - Tüm proje dosyalarını `C:\xampp\htdocs\ucp\` klasörüne kopyalayın

3. **Apache'yi başlatın:**
   - XAMPP Control Panel'den Apache'yi Start edin

4. **Tarayıcıda açın:**
   ```
   http://localhost/ucp
   ```

### WAMP Kullanımı:

1. **WAMP'ı indirin ve kurun:**
   - https://www.wampserver.com/

2. **Dosyaları kopyalayın:**
   - Tüm proje dosyalarını `C:\wamp64\www\ucp\` klasörüne kopyalayın

3. **WAMP'ı başlatın:**
   - WAMP ikonuna tıklayın ve "Start All Services" seçin

4. **Tarayıcıda açın:**
   ```
   http://localhost/ucp
   ```

---

## ⚠️ ÖNEMLİ: Veritabanı Ayarları

Sunucuyu başlatmadan önce **mutlaka** `config.php` dosyasındaki veritabanı bilgilerini kontrol edin:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'beldadmta');        // Veritabanı adınız
define('DB_USER', 'root');             // Kullanıcı adınız
define('DB_PASS', 'beldad34');         // Şifreniz
```

### Veritabanı Tablosunu Oluşturma:

1. **phpMyAdmin'e gidin:**
   - XAMPP: http://localhost/phpmyadmin
   - WAMP: http://localhost/phpmyadmin

2. **Veritabanınızı seçin** (örn: beldadmta)

3. **SQL sekmesine gidin**

4. **`database/tickets.sql` dosyasının içeriğini kopyalayıp yapıştırın**

5. **"Go" butonuna tıklayın**

---

## 🎯 İlk Giriş

1. Tarayıcıda `http://localhost:8000/login.php` adresine gidin

2. **Oyundaki kullanıcı adı ve şifrenizle** giriş yapın

3. Ana dashboard'u görüntüleyin!

---

## 🔧 Sorun Giderme

### "PHP is not recognized" Hatası:
- PHP'nin PATH'e ekli olduğundan emin olun
- VEYA XAMPP/WAMP kullanın

### "Connection refused" Hatası:
- Port 8000 başka bir program tarafından kullanılıyor olabilir
- Farklı bir port deneyin: `php -S localhost:8080`

### Veritabanı Bağlantı Hatası:
- `config.php` dosyasındaki bilgileri kontrol edin
- MySQL/MariaDB servisinin çalıştığından emin olun
- Veritabanının var olduğundan emin olun

### "Page not found" Hatası:
- Dosyaların doğru dizinde olduğundan emin olun
- `.htaccess` dosyasının mevcut olduğundan emin olun

---

## 📝 Notlar

- **Development modunda** hata mesajları görünebilir (normal)
- **Production'a** geçmeden önce `config.php` içinde `display_errors` ayarını `Off` yapın
- Şifre hash formatınızı kontrol edin (MD5 veya bcrypt)

---

**Başarılar! 🎮**

