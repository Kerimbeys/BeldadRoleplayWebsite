# 🎨 Favicon (Site Simgesi) Değiştirme Rehberi

## 📍 Favicon Dosyalarının Konumu

Favicon dosyaları şu klasörde bulunmalıdır:
```
assets/images/
├── favicon.ico          (Ana favicon - 32x32 veya 16x16)
├── favicon-16x16.png    (16x16 PNG)
├── favicon-32x32.png    (32x32 PNG)
└── apple-touch-icon.png (180x180 - iOS için)
```

## 🔧 Nasıl Değiştirilir?

### Yöntem 1: Hazır Favicon Kullanma

1. **Favicon oluşturma siteleri:**
   - https://favicon.io/ (Ücretsiz, kolay)
   - https://realfavicongenerator.net/ (Detaylı seçenekler)
   - https://www.favicon-generator.org/

2. **Adımlar:**
   - Siteden favicon'unuzu oluşturun
   - İndirilen dosyaları `assets/images/` klasörüne kopyalayın
   - Dosya isimlerinin doğru olduğundan emin olun

### Yöntem 2: Kendi Resminizi Kullanma

1. **Resminizi hazırlayın:**
   - 512x512 veya 256x256 piksel (kare)
   - PNG veya ICO formatı
   - Şeffaf arka plan önerilir

2. **Favicon oluşturun:**
   - https://favicon.io/favicon-converter/ adresine gidin
   - Resminizi yükleyin
   - Tüm boyutları indirin

3. **Dosyaları yerleştirin:**
   ```
   assets/images/favicon.ico
   assets/images/favicon-16x16.png
   assets/images/favicon-32x32.png
   assets/images/apple-touch-icon.png
   ```

### Yöntem 3: MTA:SA Temalı Favicon

Gaming temasına uygun favicon örnekleri:
- 🎮 Oyun kontrolcüsü ikonu
- 🚗 Araba ikonu
- 💰 Para ikonu
- 🏆 Kupa ikonu

## 📝 Dosya Boyutları

- **favicon.ico**: 16x16 veya 32x32 (ICO formatı)
- **favicon-16x16.png**: 16x16 piksel
- **favicon-32x32.png**: 32x32 piksel
- **apple-touch-icon.png**: 180x180 piksel (iOS için)

## ✅ Kontrol Listesi

- [ ] `assets/images/favicon.ico` dosyası var mı?
- [ ] `assets/images/favicon-16x16.png` dosyası var mı?
- [ [ ] `assets/images/favicon-32x32.png` dosyası var mı?
- [ ] `assets/images/apple-touch-icon.png` dosyası var mı?
- [ ] Tarayıcıda test edildi mi? (Ctrl+F5 ile hard refresh)

## 🔄 Tarayıcı Önbelleğini Temizleme

Favicon değişikliği görünmüyorsa:

1. **Chrome/Edge:**
   - Ctrl + Shift + Delete
   - "Önbelleğe alınan resimler ve dosyalar" seçin
   - Temizle

2. **Firefox:**
   - Ctrl + Shift + Delete
   - "Önbellek" seçin
   - Temizle

3. **Hard Refresh:**
   - Ctrl + F5 (Windows)
   - Cmd + Shift + R (Mac)

## 🎨 Öneriler

- **Renk:** Dark mode temasına uygun (mavi, cyan tonları)
- **Tasarım:** Basit ve net (küçük boyutlarda okunabilir)
- **Format:** ICO ve PNG formatları kullanın
- **Boyut:** Minimum 16x16, ideal 32x32

## 📱 Mobil Uyumluluk

- iOS için `apple-touch-icon.png` (180x180) gerekli
- Android için `favicon-192x192.png` ve `favicon-512x512.png` eklenebilir

## 🔗 Ek Kaynaklar

- [Favicon.io](https://favicon.io/) - Ücretsiz favicon oluşturucu
- [RealFaviconGenerator](https://realfavicongenerator.net/) - Tüm platformlar için
- [Canva](https://www.canva.com/) - Favicon tasarımı için

---

**Not:** Favicon dosyalarını değiştirdikten sonra tarayıcı önbelleğini temizlemeyi unutmayın!

