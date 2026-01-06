<?php
/**
 * MTA:SA UCP - Session Yönetimi
 * Güvenli session kontrolü ve yönetimi
 */

require_once 'db.php';

// Session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kullanıcı giriş yapmış mı kontrol et
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

/**
 * Admin yetkisi kontrolü
 */
function isAdmin() {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Session'da admin bilgisi varsa onu kullan
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] > 0) {
        return true;
    }
    
    // Geçici admin kontrolü
    if (defined('TEMP_ADMIN_ENABLED') && TEMP_ADMIN_ENABLED) {
        if (isset($_SESSION['temp_admin']) && $_SESSION['temp_admin'] === true) {
            return true;
        }
    }
    
    // Veritabanından kontrol et (eğer bağlantı varsa)
    try {
        $db = Database::getInstance();
        if ($db->isConnected()) {
            $user = $db->fetchOne(
                "SELECT admin FROM accounts WHERE id = ?",
                [$_SESSION['user_id']]
            );
            
            if ($user && $user['admin'] > 0) {
                $_SESSION['is_admin'] = $user['admin'];
                return true;
            }
        }
    } catch (Exception $e) {
        // Veritabanı bağlantısı yok - geçici admin kontrolüne devam
    }
    
    return false;
}

/**
 * Kullanıcı bilgilerini session'a kaydet
 */
function setUserSession($userData) {
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['is_admin'] = isset($userData['admin']) ? $userData['admin'] : 0;
    $_SESSION['login_time'] = time();
}

/**
 * Kullanıcıyı çıkış yaptır
 */
function logout() {
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    session_destroy();
}

/**
 * Giriş yapmamış kullanıcıları yönlendir
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

/**
 * Admin yetkisi gerektiren sayfalar için kontrol
 */
function requireAdmin() {
    requireLogin();
    
    if (!isAdmin()) {
        header('Location: index.php?error=no_permission');
        exit();
    }
}

/**
 * Mevcut kullanıcının ID'sini döndür
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Rate limiting için login denemelerini kontrol et
 */
function checkLoginAttempts() {
    $maxAttempts = 5;
    $lockoutTime = 900; // 15 dakika
    
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = time();
    }
    
    // Lockout süresi geçmiş mi kontrol et
    if ($_SESSION['login_attempts'] >= $maxAttempts) {
        if (time() - $_SESSION['last_attempt'] < $lockoutTime) {
            return false; // Bloklanmış
        } else {
            // Süre geçmiş, resetle
            $_SESSION['login_attempts'] = 0;
        }
    }
    
    return true;
}

/**
 * Başarısız login denemesini kaydet
 */
function recordFailedLogin() {
    $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] + 1 : 1;
    $_SESSION['last_attempt'] = time();
}

/**
 * Başarılı login sonrası resetle
 */
function resetLoginAttempts() {
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['last_attempt']);
}

/**
 * Mevcut kullanıcının bilgilerini veritabanından çek
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    // Geçici admin için
    if (defined('TEMP_ADMIN_ENABLED') && TEMP_ADMIN_ENABLED) {
        if (isset($_SESSION['temp_admin']) && $_SESSION['temp_admin'] === true) {
            return array(
                'id' => TEMP_ADMIN_ID,
                'username' => TEMP_ADMIN_USERNAME,
                'money' => 0,
                'bankmoney' => 0,
                'skin' => 0,
                'job' => 0,
                'admin' => 1
            );
        }
    }
    
    // Veritabanından çek (eğer bağlantı varsa)
    try {
        $db = Database::getInstance();
        if ($db->isConnected()) {
            return $db->fetchOne(
                "SELECT id, username, money, bankmoney, skin, job, admin FROM accounts WHERE id = ?",
                [getCurrentUserId()]
            );
        }
    } catch (Exception $e) {
        // Veritabanı bağlantısı yok
    }
    
    return null;
}

