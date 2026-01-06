# 🎨 Background ve Particles Ayarlama Rehberi

## 🖼️ Background Değiştirme

### Yöntem 1: CSS ile Gradient Background

`assets/css/style.css` dosyasında `body` stilini düzenleyin:

```css
body {
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1629 100%);
}
```

**Örnek Gradient'ler:**

```css
/* Mavi Tonları */
background: linear-gradient(135deg, #0a0e27 0%, #1a3a5c 50%, #0f1629 100%);

/* Mor Tonları */
background: linear-gradient(135deg, #1a0e27 0%, #3a1f5a 50%, #0f0929 100%);

/* Koyu Tek Renk */
background: #0a0e27;

/* Radial Gradient */
background: radial-gradient(ellipse at center, #1a1f3a 0%, #0a0e27 100%);
```

### Yöntem 2: Resim Background

1. Resminizi `assets/images/` klasörüne koyun (örn: `background.jpg`)
2. `assets/css/style.css` dosyasında:

```css
body {
    background-image: url('assets/images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-repeat: no-repeat;
}
```

**Önerilen Resim Özellikleri:**
- Boyut: 1920x1080 veya daha büyük
- Format: JPG veya PNG
- Ağırlık: 500KB'den küçük (performans için)
- Tema: Koyu, gaming temalı

### Yöntem 3: Particles Background'u Değiştirme

`assets/js/particles-config.js` dosyasında `#particles-js` background'unu değiştirin:

```javascript
// CSS'de veya particles-config.js'de
#particles-js {
    background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
}
```

---

## ✨ Particles Ayarları

### Particle Sayısı

`assets/js/particles-config.js` dosyasında:

```javascript
number: {
    value: 80, // 50-150 arası önerilir
}
```

- **Düşük performanslı bilgisayarlar için:** 50-60
- **Normal kullanım:** 80-100
- **Güçlü bilgisayarlar için:** 120-150

### Particle Rengi

```javascript
color: {
    value: '#00d4ff' // Cyan (varsayılan)
}
```

**Renk Örnekleri:**
- Mavi: `#00d4ff`
- Yeşil: `#00ff88`
- Mor: `#9d4edd`
- Turuncu: `#ffaa00`
- Kırmızı: `#ff006e`

### Particle Boyutu

```javascript
size: {
    value: 3, // 1-5 arası önerilir
}
```

### Çizgiler (Line Linked)

```javascript
line_linked: {
    enable: true, // true/false
    distance: 150, // Mesafe (100-200)
    color: '#00d4ff',
    opacity: 0.4, // 0-1 arası
    width: 1 // Kalınlık
}
```

### Hareket Hızı

```javascript
move: {
    speed: 2, // 1-5 arası
    direction: 'none' // 'none', 'top', 'bottom', 'left', 'right'
}
```

### İnteraktivite

**Mouse Hover:**
```javascript
onhover: {
    enable: true,
    mode: 'repulse' // 'repulse', 'grab', 'bubble'
}
```

**Tıklama:**
```javascript
onclick: {
    enable: true,
    mode: 'push' // 'push', 'remove', 'bubble', 'repulse'
}
```

---

## 🎯 Hazır Tema Örnekleri

### Tema 1: Minimal (Az Particle)
```javascript
number: { value: 50 },
size: { value: 2 },
line_linked: { enable: true, opacity: 0.2 }
```

### Tema 2: Orta (Varsayılan)
```javascript
number: { value: 80 },
size: { value: 3 },
line_linked: { enable: true, opacity: 0.4 }
```

### Tema 3: Yoğun (Çok Particle)
```javascript
number: { value: 120 },
size: { value: 4 },
line_linked: { enable: true, opacity: 0.6 }
```

### Tema 4: Hızlı Hareket
```javascript
move: { speed: 4 },
onhover: { mode: 'grab' }
```

### Tema 5: Yavaş ve Yumuşak
```javascript
move: { speed: 1 },
opacity: { value: 0.3 },
line_linked: { opacity: 0.2 }
```

---

## 🔧 Hızlı Ayarlar

### Particles'ı Kapatmak
`includes/header.php` dosyasında particles.js satırını yorum satırı yapın:
```html
<!-- <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script> -->
```

### Background'u Değiştirmek
`assets/css/style.css` dosyasında `body` ve `#particles-js` stillerini düzenleyin.

### Performans İyileştirmesi
- Particle sayısını azaltın (50-60)
- Çizgileri kapatın (`line_linked: { enable: false }`)
- Opacity'yi düşürün (0.2-0.3)

---

## 📝 Örnek Kullanımlar

### Gaming Teması (Koyu + Cyan)
```css
background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 100%);
color: '#00d4ff'
```

### Futuristik (Mor Tonları)
```css
background: linear-gradient(135deg, #1a0e27 0%, #3a1f5a 100%);
color: '#9d4edd'
```

### Minimal (Sade)
```css
background: #0a0e27;
number: { value: 40 }
```

---

**Not:** Değişikliklerden sonra tarayıcı önbelleğini temizleyin (Ctrl+F5)!

