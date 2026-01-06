# 🗄️ Veritabanı Hazırlık Rehberi

## Gerekli Tablolar

UCP sisteminin çalışması için aşağıdaki tabloların veritabanınızda mevcut olması gerekir:

### 1. `accounts` Tablosu (Zorunlu)
Kullanıcı hesapları için temel tablo.

```sql
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `money` int(11) DEFAULT 0,
  `bankmoney` int(11) DEFAULT 0,
  `skin` int(11) DEFAULT 0,
  `job` int(11) DEFAULT 0,
  `admin` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Önemli Alanlar:**
- `id` - Kullanıcı ID'si
- `username` - Kullanıcı adı (benzersiz)
- `password` - Şifre (MD5 veya bcrypt hash)
- `money` - Nakit para
- `bankmoney` - Banka parası
- `skin` - Skin ID
- `job` - Meslek ID
- `admin` - Admin seviyesi (0 = Oyuncu, 1+ = Admin)

### 2. `vehicles` Tablosu (Opsiyonel)
Araçlar için tablo.

```sql
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `plate` varchar(20) DEFAULT NULL,
  `color1` int(11) DEFAULT NULL,
  `color2` int(11) DEFAULT NULL,
  `fuel` int(11) DEFAULT 100,
  `health` int(11) DEFAULT 1000,
  `x` float DEFAULT NULL,
  `y` float DEFAULT NULL,
  `z` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. `interiors` Tablosu (Opsiyonel)
Evler için tablo.

```sql
CREATE TABLE IF NOT EXISTS `interiors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT 0,
  `x` float DEFAULT NULL,
  `y` float DEFAULT NULL,
  `z` float DEFAULT NULL,
  `locked` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 4. `companies` Tablosu (Opsiyonel)
Şirketler için tablo.

```sql
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `profit` int(11) DEFAULT 0,
  `employees` int(11) DEFAULT 0,
  `x` float DEFAULT NULL,
  `y` float DEFAULT NULL,
  `z` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 5. `tickets` Tablosu (Zorunlu - Ticket Sistemi İçin)
Destek talepleri için tablo.

**Bu tablo için SQL dosyası mevcut:** `database/tickets.sql`

```sql
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','answered','closed') NOT NULL DEFAULT 'open',
  `admin_id` int(11) DEFAULT NULL,
  `admin_reply` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 6. `jobs` Tablosu (Opsiyonel)
Meslekler için tablo (meslek isimlerini göstermek için).

```sql
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## Kurulum Adımları

### 1. Veritabanı Oluşturma

phpMyAdmin veya MySQL komut satırından:

```sql
CREATE DATABASE beldadmta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Tabloları Oluşturma

**Yöntem 1: phpMyAdmin**
1. Veritabanınızı seçin
2. SQL sekmesine gidin
3. Yukarıdaki SQL komutlarını çalıştırın

**Yöntem 2: SQL Dosyası**
```bash
mysql -u root -p beldadmta < database/tickets.sql
```

### 3. Test Kullanıcısı Oluşturma

```sql
INSERT INTO `accounts` (`username`, `password`, `money`, `bankmoney`, `admin`) 
VALUES ('testuser', MD5('test123'), 10000, 50000, 0);

-- Admin kullanıcı
INSERT INTO `accounts` (`username`, `password`, `money`, `bankmoney`, `admin`) 
VALUES ('admin', MD5('admin123'), 0, 0, 1);
```

### 4. Config.php Ayarları

`config.php` dosyasında veritabanı bilgilerinizi kontrol edin:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'beldadmta');
define('DB_USER', 'root');
define('DB_PASS', 'beldad34');
```

---

## Kontrol Listesi

- [ ] Veritabanı oluşturuldu
- [ ] `accounts` tablosu oluşturuldu
- [ ] `tickets` tablosu oluşturuldu (`database/tickets.sql`)
- [ ] `vehicles` tablosu oluşturuldu (opsiyonel)
- [ ] `interiors` tablosu oluşturuldu (opsiyonel)
- [ ] `companies` tablosu oluşturuldu (opsiyonel)
- [ ] `jobs` tablosu oluşturuldu (opsiyonel)
- [ ] Test kullanıcısı oluşturuldu
- [ ] `config.php` ayarları yapıldı
- [ ] Bağlantı test edildi

---

## Sorun Giderme

### "Table doesn't exist" Hatası
- Tabloların oluşturulduğundan emin olun
- Veritabanı adının doğru olduğunu kontrol edin

### "Access denied" Hatası
- MySQL kullanıcı adı ve şifresini kontrol edin
- Kullanıcının veritabanına erişim yetkisi olduğundan emin olun

### "Connection refused" Hatası
- MySQL servisinin çalıştığından emin olun
- Port numarasını kontrol edin (varsayılan: 3306)

---

**Başarılar! 🎮**

