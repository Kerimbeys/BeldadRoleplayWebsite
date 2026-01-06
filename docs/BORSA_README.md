# Beldad Roleplay - Borsa Sistemi

## Genel Bakış

Bu sistem, oyuncuların oyun içi paralarını kullanarak hisse senedi alıp satabilecekleri kapsamlı bir borsa platformu sağlar.

## Özellikler

### Oyuncu Özellikleri
- **Hisse Senetleri Görüntüleme**: Güncel fiyatlar, değişim oranları ve hacim bilgileri
- **Hisse Alım/Satım**: Kolay alım-satım işlemleri (%2 komisyon)
- **Portföy Yönetimi**: Kişisel portföy takibi, kar/zarar hesaplaması
- **İşlem Geçmişi**: Tüm işlemlerin detaylı kayıtları

### Admin Özellikleri
- **Hisse Yönetimi**: Yeni hisse ekleme, fiyat güncelleme, aktif/pasif yapma
- **İşlem Takibi**: Tüm borsa işlemlerinin detaylı izlenmesi
- **İstatistikler**: Borsa hacmi, aktif trader sayısı, toplam işlem sayısı
- **Filtreleme**: İşleme göre, tarihe göre, hisseye göre filtreleme

## Veritabanı Tabloları

### `stocks`
Hisse senetleri tablosu
- `id`: Birincil anahtar
- `symbol`: Hisse sembolü (Beldad, MTA, vb.)
- `name`: Şirket adı
- `current_price`: Güncel fiyat
- `previous_price`: Önceki fiyat
- `change_percent`: Değişim yüzdesi
- `volume`: İşlem hacmi
- `is_active`: Aktif mi?

### `user_stocks`
Kullanıcı portföyü tablosu
- `user_id`: Kullanıcı ID
- `stock_symbol`: Hisse sembolü
- `quantity`: Miktar
- `average_cost`: Ortalama maliyet
- `total_invested`: Toplam yatırılan

### `stock_transactions`
İşlem geçmişi tablosu
- `user_id`: Kullanıcı ID
- `stock_symbol`: Hisse sembolü
- `transaction_type`: 'buy' veya 'sell'
- `quantity`: İşlem miktarı
- `price_per_share`: Birim fiyat
- `total_amount`: Toplam tutar
- `fee`: Komisyon
- `net_amount`: Net tutar

## Kurulum

1. `database/stocks_fixed.sql` dosyasını veritabanınızda çalıştırın
2. Sistem otomatik olarak çalışacaktır

## Kullanım

### Oyuncu Olarak
1. Ana sayfadan "Malvarlığım > Borsa" bölümüne gidin
2. Hisse senetlerini inceleyin
3. "Al" butonuna tıklayarak hisse satın alın
4. Portföyünüzü "Portföyüm" bölümünden takip edin
5. "Sat" butonuna tıklayarak hisse satabilirsiniz

### Admin Olarak
1. Admin paneline giriş yapın
2. "Borsa Yönetimi" bölümünden hisse senetlerini yönetin
3. "İşlem Geçmişi" bölümünden tüm işlemleri takip edin
4. İstatistikleri görüntüleyin

## Komisyon Sistemi

- Alım/Satım işlemleri için %2 komisyon uygulanır
- Komisyon toplam işlem tutarından hesaplanır
- Alımda: (adet × fiyat × 1.02) toplam maliyet
- Satımda: (adet × fiyat × 0.98) net gelir

## Güvenlik

- CSRF koruması aktif
- SQL injection koruması
- Session tabanlı giriş kontrolü
- Admin yetki kontrolü

## Destek

Herhangi bir sorun yaşarsanız, ticket sistemi üzerinden destek talebi oluşturabilirsiniz.