# 🎨 Background Hızlı Değiştirme Rehberi

## ⚡ Hızlı Yöntem

### 1. CSS Dosyasını Açın
`assets/css/style.css` dosyasını açın.

### 2. Background'u Değiştirin

**Satır 27-45 arası** `body` stilini bulun ve değiştirin:

#### Seçenek 1: Gradient (Varsayılan)
```css
background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
```

#### Seçenek 2: Tek Renk
```css
background: #0a0e27;
```

#### Seçenek 3: Farklı Gradient
```css
background: linear-gradient(135deg, #1a0e27 0%, #3a1f5a 50%, #0f0929 100%);
```

#### Seçenek 4: Resim Background
```css
background-image: url('../images/background.jpg');
background-size: cover;
background-position: center;
background-attachment: fixed;
```

### 3. Particles Background'unu Değiştirin

**Satır 37-50 arası** `#particles-js` stilini bulun:

```css
#particles-js {
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1629 100%);
}
```

Aynı şekilde burayı da değiştirebilirsiniz.

---

## 🎨 Hazır Tema Örnekleri

### Tema 1: Koyu Mavi (Varsayılan)
```css
background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
```

### Tema 2: Mor Tonları
```css
background: linear-gradient(135deg, #1a0e27 0%, #3a1f5a 100%);
```

### Tema 3: Koyu Yeşil
```css
background: linear-gradient(135deg, #0a1e17 0%, #1a3a2a 100%);
```

### Tema 4: Koyu Kırmızı
```css
background: linear-gradient(135deg, #1e0a0a 0%, #3a1a1a 100%);
```

### Tema 5: Siyah
```css
background: #000000;
```

---

## ✨ Particles Ayarları

### Particles Rengini Değiştirme
`assets/js/particles-config.js` dosyasında **satır 33**:

```javascript
color: {
    value: '#00d4ff' // Burayı değiştirin
}
```

**Renk Örnekleri:**
- Mavi: `#00d4ff`
- Yeşil: `#00ff88`
- Mor: `#9d4edd`
- Turuncu: `#ffaa00`
- Kırmızı: `#ff006e`
- Beyaz: `#ffffff`

### Particle Sayısını Değiştirme
**Satır 25**:

```javascript
value: 80, // 50-150 arası
```

### Çizgileri Kapatma
**Satır 66**:

```javascript
line_linked: {
    enable: false, // true/false
}
```

### Hareket Hızı
**Satır 74**:

```javascript
speed: 2, // 1-5 arası
```

---

## 🖼️ Resim Background Kullanma

1. Resminizi `assets/images/` klasörüne koyun
2. `assets/css/style.css` dosyasında:

```css
body {
    background-image: url('../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}

#particles-js {
    background-image: url('../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
```

**Önerilen Resim Özellikleri:**
- Boyut: 1920x1080 veya daha büyük
- Format: JPG (daha küçük dosya) veya PNG
- Ağırlık: 500KB'den küçük
- Tema: Koyu, gaming temalı

---

## 🔧 Particles'ı Tamamen Kapatma

Eğer particles efektini istemiyorsanız:

1. `includes/header.php` dosyasında **satır 35**'i yorum satırı yapın:
```html
<!-- <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script> -->
```

2. `includes/header.php` dosyasında **satır 41**'i yorum satırı yapın:
```html
<!-- <div id="particles-js"></div> -->
```

---

## 📝 Örnek: Beldad Teması

```css
/* Body Background */
body {
    background: linear-gradient(135deg, #0a0e27 0%, #1a2a3a 100%);
}

/* Particles Background */
#particles-js {
    background: linear-gradient(135deg, #0a0e27 0%, #1a2a3a 100%);
}

/* Particles Rengi */
color: {
    value: '#00d4ff'
}
```

---

**Not:** Değişikliklerden sonra tarayıcı önbelleğini temizleyin (Ctrl+F5)!

