-- MTA:SA UCP - Borsa Sistemi Tabloları
-- Bu SQL dosyasını veritabanınızda çalıştırarak borsa tablolarını oluşturun

-- Hisse senetleri tablosu
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `symbol` varchar(10) NOT NULL UNIQUE,
  `name` varchar(100) NOT NULL,
  `description` text,
  `current_price` decimal(10,2) NOT NULL DEFAULT 100.00,
  `previous_price` decimal(10,2) NOT NULL DEFAULT 100.00,
  `change_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `volume` int(11) NOT NULL DEFAULT 0,
  `market_cap` bigint(20) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `symbol` (`symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kullanıcı hisse portföyü tablosu
CREATE TABLE IF NOT EXISTS `user_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stock_symbol` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `average_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_invested` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_stock` (`user_id`,`stock_symbol`),
  KEY `user_id` (`user_id`),
  KEY `stock_symbol` (`stock_symbol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hisse işlem geçmişi tablosu
CREATE TABLE IF NOT EXISTS `stock_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `stock_symbol` varchar(10) NOT NULL,
  `transaction_type` enum('buy','sell') NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_share` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `stock_symbol` (`stock_symbol`),
  KEY `transaction_date` (`transaction_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Varsayılan hisse senetlerini ekle
INSERT IGNORE INTO `stocks` (`symbol`, `name`, `description`, `current_price`, `previous_price`) VALUES
('BELDAD', 'Beldad Corporation', 'Beldad Roleplay ana şirketi', 250.00, 245.00),
('MTA', 'Multi Theft Auto Inc.', 'Oyun motoru sağlayıcısı', 450.00, 460.00),
('RP', 'Roleplay Ventures', 'Roleplay oyunları yatırım şirketi', 125.00, 120.00),
('GAME', 'Gaming Solutions Ltd.', 'Oyun geliştirme şirketi', 375.00, 380.00),
('TECH', 'Tech Innovations', 'Teknoloji yenilikleri şirketi', 650.00, 640.00),
('AUTO', 'Auto Empire', 'Otomotiv sektöründe faaliyet gösteren şirket', 320.00, 315.00),
('BANK', 'Secure Bank Corp.', 'Güvenli bankacılık hizmetleri', 580.00, 575.00),
('REAL', 'Real Estate Plus', 'Gayrimenkul yatırım şirketi', 290.00, 295.00);