<?php
/**
 * Admin Yetki Test Sayfası
 */

require_once 'session.php';

$pageTitle = 'Admin Yetki Testi';
$currentUser = getCurrentUser();

echo "<h1>Admin Yetki Testi</h1>";
echo "<pre>";

echo "Giriş Durumu: " . (isLoggedIn() ? "Giriş yapılmış" : "Giriş yapılmamış") . "\n";
echo "Admin Durumu: " . (isAdmin() ? "Admin yetkisi var" : "Admin yetkisi yok") . "\n\n";

echo "Session Bilgileri:\n";
echo "user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'YOK') . "\n";
echo "username: " . (isset($_SESSION['username']) ? $_SESSION['username'] : 'YOK') . "\n";
echo "is_admin: " . (isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 'YOK') . "\n";
echo "temp_admin: " . (isset($_SESSION['temp_admin']) ? $_SESSION['temp_admin'] : 'YOK') . "\n\n";

echo "Config Bilgileri:\n";
echo "TEMP_ADMIN_ENABLED: " . (defined('TEMP_ADMIN_ENABLED') ? TEMP_ADMIN_ENABLED : 'TANIMSIZ') . "\n";
echo "TEMP_ADMIN_USERNAME: " . (defined('TEMP_ADMIN_USERNAME') ? TEMP_ADMIN_USERNAME : 'TANIMSIZ') . "\n";

echo "</pre>";

if (isAdmin()) {
    echo "<p><a href='admin/index.php'>Admin Paneline Git</a></p>";
} else {
    echo "<p>Admin yetkiniz yok. Geçici admin ile giriş yapmak için:</p>";
    echo "<p>Kullanıcı adı: admin</p>";
    echo "<p>Şifre: admin123</p>";
    echo "<p><a href='login.php'>Giriş Sayfası</a></p>";
}
?>