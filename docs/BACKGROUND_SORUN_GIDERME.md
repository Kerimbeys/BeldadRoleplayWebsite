# 🔧 Background Resmi Görünmüyor - Sorun Giderme

## ❌ Sorun: Background resmi görünmüyor

### Çözüm 1: Particles Background'unu Düzenle

`assets/css/style.css` dosyasında **satır 54-75** arası `#particles-js` stilini bulun:

**Seçenek A: Particles background'u şeffaf yapın (Önerilen)**
```css
#particles-js {
    background: transparent;
}
```

**Seçenek B: Particles background'u body ile aynı resmi kullanın**
```css
#particles-js {
    background-image: url('../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
```

**Seçenek C: Koyu overlay ekleyin (resim + koyu katman)**
```css
#particles-js {
    background: linear-gradient(135deg, rgba(10, 14, 39, 0.7) 0%, rgba(26, 31, 58, 0.7) 100%),
                url('../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    background-blend-mode: overlay;
}
```

### Çözüm 2: Dosya Yolunu Kontrol Edin

1. Resmin `assets/images/background.jpg` konumunda olduğundan emin olun
2. Dosya adının tam olarak `background.jpg` olduğundan emin olun (büyük/küçük harf duyarlı)
3. Dosya boyutunun çok büyük olmadığından emin olun (max 2MB önerilir)

### Çözüm 3: Tarayıcı Önbelleğini Temizleyin

- **Ctrl + F5** (Hard refresh)
- Veya **Ctrl + Shift + Delete** ile önbelleği temizleyin

### Çözüm 4: CSS Dosya Yolunu Kontrol Edin

CSS'deki yol doğru mu kontrol edin:

```css
/* assets/css/style.css dosyasından */
background-image: url('../images/background.jpg');
/* Bu yol: assets/images/background.jpg'yi işaret eder */
```

Eğer hala çalışmıyorsa, mutlak yol deneyin:

```css
background-image: url('/assets/images/background.jpg');
/* veya */
background-image: url('assets/images/background.jpg');
```

### Çözüm 5: Particles'ı Geçici Olarak Kapatın

Test için particles'ı kapatın:

1. `includes/header.php` dosyasında **satır 35**'i yorum satırı yapın:
```html
<!-- <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script> -->
```

2. `includes/header.php` dosyasında **satır 41**'i yorum satırı yapın:
```html
<!-- <div id="particles-js"></div> -->
```

Eğer bu şekilde görünüyorsa, sorun particles background'unda.

---

## ✅ Hızlı Çözüm (Önerilen)

`assets/css/style.css` dosyasında **satır 62**'yi şu şekilde değiştirin:

```css
#particles-js {
    /* ... diğer stiller ... */
    background: transparent; /* Particles şeffaf, body background görünür */
}
```

Bu şekilde particles efekti görünür ama background resmi de görünür.

---

## 🎨 Alternatif: Overlay Efekti

Resmin üzerine koyu bir katman eklemek isterseniz:

```css
#particles-js {
    background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(26, 31, 58, 0.6) 100%),
                url('../images/background.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
```

Bu şekilde resim görünür ama biraz koyulaşır (particles daha belirgin olur).

---

## 📝 Kontrol Listesi

- [ ] `assets/images/background.jpg` dosyası var mı?
- [ ] Dosya adı doğru mu? (büyük/küçük harf)
- [ ] CSS'deki yol doğru mu?
- [ ] Particles background'u şeffaf mı?
- [ ] Tarayıcı önbelleği temizlendi mi?
- [ ] Dosya boyutu çok büyük değil mi?

---

**Hala çalışmıyorsa:** Tarayıcı konsolunu açın (F12) ve hataları kontrol edin!

