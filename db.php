<?php
/**
 * MTA:SA UCP - Veritabanı Bağlantı Dosyası
 * PDO kullanarak güvenli veritabanı bağlantısı
 */

require_once 'config.php';

class Database {
    private static $instance = null;
    private $pdo;
    
    /**
     * Singleton pattern ile veritabanı bağlantısı
     */
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, // Gerçek prepared statements
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Production'da detaylı hata gösterme
            error_log("Veritabanı bağlantı hatası: " . $e->getMessage());
            // Veritabanı bağlantısı başarısız - geçici mod için null döndür
            $this->pdo = null;
        }
    }
    
    /**
     * Veritabanı instance'ını döndürür
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * PDO nesnesini döndürür
     */
    public function getConnection() {
        if ($this->pdo === null) {
            throw new Exception("Veritabanı bağlantısı yok. Geçici admin modu aktif.");
        }
        return $this->pdo;
    }
    
    /**
     * Veritabanı bağlantısı var mı kontrol et
     */
    public function isConnected() {
        return $this->pdo !== null;
    }
    
    /**
     * Prepared statement ile güvenli sorgu çalıştırma
     */
    public function query($sql, $params = []) {
        if ($this->pdo === null) {
            throw new Exception("Veritabanı bağlantısı yok.");
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("SQL Hatası: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Tek satır veri çekme
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Tüm satırları çekme
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    // Clone ve unserialize engelleme (Singleton pattern için)
    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Global kullanım için helper fonksiyon
function getDB() {
    return Database::getInstance()->getConnection();
}

