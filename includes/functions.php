<?php
/**
 * MTA:SA UCP - Yardımcı Fonksiyonlar
 */

/**
 * Para formatı
 */
function formatMoney($amount) {
    return '$' . number_format($amount, 0, ',', '.');
}

/**
 * Tarih formatı
 */
function formatDate($dateString, $format = 'd.m.Y H:i') {
    return date($format, strtotime($dateString));
}

/**
 * CSRF token oluştur
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF token doğrula
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    return true;
}

/**
 * CSRF token input alanı oluştur
 */
function csrfTokenField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCSRFToken() . '">';
}

/**
 * Alert mesajı göster
 */
function showAlert($message, $type = 'info') {
    $alertClass = 'alert-info-custom';
    $icon = 'bi-info-circle';
    
    switch ($type) {
        case 'success':
            $alertClass = 'alert-success-custom';
            $icon = 'bi-check-circle-fill';
            break;
        case 'danger':
        case 'error':
            $alertClass = 'alert-danger-custom';
            $icon = 'bi-exclamation-triangle-fill';
            break;
        case 'warning':
            $alertClass = 'alert-warning-custom';
            $icon = 'bi-exclamation-triangle';
            break;
    }
    
    return '<div class="alert ' . $alertClass . ' alert-custom fade-in">
        <i class="' . $icon . '"></i> ' . htmlspecialchars($message) . '
    </div>';
}

/**
 * Ticket durum badge'i
 */
function getTicketStatusBadge($status) {
    $badges = [
        'open' => ['class' => 'badge-warning-custom', 'text' => 'Açık'],
        'answered' => ['class' => 'badge-success-custom', 'text' => 'Yanıtlandı'],
        'closed' => ['class' => 'badge-primary-custom', 'text' => 'Kapatıldı']
    ];
    
    $badge = isset($badges[$status]) ? $badges[$status] : ['class' => 'badge-primary-custom', 'text' => 'Bilinmeyen'];
    
    return '<span class="badge ' . $badge['class'] . '">' . $badge['text'] . '</span>';
}

/**
 * Sayfalama HTML oluştur
 */
function generatePagination($currentPage, $totalPages, $baseUrl, $params = []) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $queryString = '';
    if (!empty($params)) {
        $queryString = '&' . http_build_query($params);
    }
    
    $html = '<nav aria-label="Sayfa navigasyonu"><ul class="pagination justify-content-center">';
    
    // Önceki sayfa
    if ($currentPage > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . $queryString . '">Önceki</a></li>';
    }
    
    // Sayfa numaraları
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=1' . $queryString . '">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $baseUrl . '?page=' . $i . $queryString . '">' . $i . '</a></li>';
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . $totalPages . $queryString . '">' . $totalPages . '</a></li>';
    }
    
    // Sonraki sayfa
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . $queryString . '">Sonraki</a></li>';
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Discord Webhook ile log gönder
 */
function discordLog($message, $embed = null, $color = null) {
    // Config dosyasını dahil et
    require_once dirname(__DIR__) . '/config.php';

    if (!DISCORD_WEBHOOK_ENABLED || empty(DISCORD_WEBHOOK_URL)) {
        return false;
    }

    $webhookUrl = DISCORD_WEBHOOK_URL; // Constant'ı değişkene çevir

    $data = [
        'username' => DISCORD_WEBHOOK_USERNAME,
        'avatar_url' => DISCORD_WEBHOOK_AVATAR,
        'content' => $message
    ];

    // Embed varsa ekle
    if ($embed !== null) {
        $embedData = [
            'title' => isset($embed['title']) ? $embed['title'] : 'Beldad UCP Log',
            'description' => isset($embed['description']) ? $embed['description'] : '',
            'color' => $color !== null ? $color : (isset($embed['color']) ? $embed['color'] : 3447003), // Varsayılan mavi
            'timestamp' => date('c'),
            'footer' => [
                'text' => 'Beldad Roleplay UCP',
                'icon_url' => 'https://i.imgur.com/XXXXXXX.png'
            ]
        ];

        // Alanlar varsa ekle
        if (isset($embed['fields']) && is_array($embed['fields'])) {
            $embedData['fields'] = $embed['fields'];
        }

        $data['embeds'] = [$embedData];
        $data['content'] = null; // Embed varsa content'i temizle
    }

    $jsonData = json_encode($data);

    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout'u 10 saniyeye çıkar

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    $curlErrorNo = curl_errno($ch);
    curl_close($ch);

    // Hata ayıklama için log dosyasına yaz
    $logMessage = "[" . date('Y-m-d H:i:s') . "] Discord Webhook - HTTP: $httpCode, cURL Error: $curlErrorNo ($curlError), Response: " . substr($response, 0, 200) . "\n";
    file_put_contents(dirname(__DIR__) . '/discord_debug.log', $logMessage, FILE_APPEND);

    return $httpCode === 204; // Discord webhook başarılı olursa 204 döner
}

/**
 * Admin işlem loglaması
 */
function logAdminAction($action, $details = '', $userId = null) {
    $userId = $userId !== null ? $userId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Unknown');
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';

    $embed = [
        'title' => '🔧 Admin İşlemi',
        'description' => "**İşlem:** {$action}\n**Admin:** {$username} (ID: {$userId})\n**Tarih:** " . date('d.m.Y H:i:s'),
        'color' => 15105570, // Kırmızımsı
        'fields' => []
    ];

    if (!empty($details)) {
        $embed['fields'][] = [
            'name' => 'Detaylar',
            'value' => $details,
            'inline' => false
        ];
    }

    discordLog('', $embed);
}

/**
 * Kullanıcı işlem loglaması
 */
function logUserAction($action, $userId = null, $details = '') {
    $userId = $userId !== null ? $userId : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Unknown');
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown';

    $embed = [
        'title' => '👤 Kullanıcı İşlemi',
        'description' => "**İşlem:** {$action}\n**Kullanıcı:** {$username} (ID: {$userId})\n**Tarih:** " . date('d.m.Y H:i:s'),
        'color' => 3066993, // Yeşil
        'fields' => []
    ];

    if (!empty($details)) {
        $embed['fields'][] = [
            'name' => 'Detaylar',
            'value' => $details,
            'inline' => false
        ];
    }

    discordLog('', $embed);
}

/**
 * Sistem loglaması
 */
function logSystemEvent($event, $details = '', $level = 'info') {
    $colors = [
        'info' => 3447003,     // Mavi
        'warning' => 16776960, // Sarı
        'error' => 15158332,   // Kırmızı
        'success' => 3066993   // Yeşil
    ];

    $embed = [
        'title' => '⚙️ Sistem Olayı',
        'description' => "**Olay:** {$event}\n**Seviye:** " . strtoupper($level) . "\n**Tarih:** " . date('d.m.Y H:i:s'),
        'color' => isset($colors[$level]) ? $colors[$level] : $colors['info'],
        'fields' => []
    ];

    if (!empty($details)) {
        $embed['fields'][] = [
            'name' => 'Detaylar',
            'value' => $details,
            'inline' => false
        ];
    }

    discordLog('', $embed);
}

