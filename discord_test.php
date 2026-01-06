<?php
/**
 * Discord Webhook Test Dosyası
 * Admin panelinden Discord loglama testleri için
 */

require_once 'session.php';
require_once 'includes/functions.php';
requireAdmin();

header('Content-Type: application/json');

$type = isset($_GET['type']) ? $_GET['type'] : '';

$response = ['success' => false, 'message' => 'Geçersiz istek'];

switch ($type) {
    case 'user_action':
        $result = logUserAction('Test Kullanıcı İşlemi', 1, 'Bu bir test mesajıdır. Discord webhook çalışıyor!');
        $response = [
            'success' => $result,
            'message' => $result ? 'Kullanıcı log testi başarılı' : 'Kullanıcı log testi başarısız'
        ];
        break;

    case 'admin_action':
        $result = logAdminAction('Test Admin İşlemi', 'Bu bir test mesajıdır. Admin webhook çalışıyor!');
        $response = [
            'success' => $result,
            'message' => $result ? 'Admin log testi başarılı' : 'Admin log testi başarısız'
        ];
        break;

    case 'system_event':
        $result = logSystemEvent('Test Sistem Olayı', 'Bu bir test mesajıdır. Sistem webhook çalışıyor!', 'info');
        $response = [
            'success' => $result,
            'message' => $result ? 'Sistem log testi başarılı' : 'Sistem log testi başarısız'
        ];
        break;

    default:
        $response = ['success' => false, 'message' => 'Geçersiz test türü'];
}

// Debug log dosyasını kontrol et
$debugFile = 'discord_debug.log';
if (file_exists($debugFile)) {
    $debugContent = file_get_contents($debugFile);
    $response['debug'] = substr($debugContent, -500); // Son 500 karakter
}

echo json_encode($response);
?>