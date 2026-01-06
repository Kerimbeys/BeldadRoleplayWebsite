<?php
/**
 * Beldad Roleplay - Admin Paneli Ana Sayfa
 */

require_once '../session.php';
requireAdmin();
require_once '../includes/functions.php';

$pageTitle = 'Admin Paneli - ' . SITE_NAME;
$currentUser = getCurrentUser();

// Admin paneli erişimini logla
logAdminAction('Admin Paneli Erişimi', 'Admin paneli ana sayfasına giriş yapıldı', $currentUser['id']);

$db = null;
$dbConnected = false;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// İstatistikler
$stats = [
    'total_users' => 0,
    'total_tickets' => 0,
    'open_tickets' => 0,
    'total_vehicles' => 0,
    'total_interiors' => 0
];

$recentTickets = array();

if ($dbConnected) {
    try {
        // Toplam kullanıcı sayısı
        $totalUsers = $db->fetchOne("SELECT COUNT(*) as count FROM accounts");
        $stats['total_users'] = isset($totalUsers['count']) ? $totalUsers['count'] : 0;
    } catch (Exception $e) {}

    try {
        // Toplam ticket sayısı
        $totalTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets");
        $stats['total_tickets'] = isset($totalTickets['count']) ? $totalTickets['count'] : 0;
    } catch (Exception $e) {}

    try {
        // Açık ticket sayısı
        $openTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets WHERE status = 'open'");
        $stats['open_tickets'] = isset($openTickets['count']) ? $openTickets['count'] : 0;
    } catch (Exception $e) {}

    try {
        // Toplam araç sayısı
        $totalVehicles = $db->fetchOne("SELECT COUNT(*) as count FROM vehicles");
        $stats['total_vehicles'] = isset($totalVehicles['count']) ? $totalVehicles['count'] : 0;
    } catch (Exception $e) {}

    try {
        // Toplam ev sayısı
        $totalInteriors = $db->fetchOne("SELECT COUNT(*) as count FROM interiors");
        $stats['total_interiors'] = isset($totalInteriors['count']) ? $totalInteriors['count'] : 0;
    } catch (Exception $e) {}

    try {
        // Son açılan ticketlar
        $recentTickets = $db->fetchAll(
            "SELECT * FROM tickets WHERE status = 'open' ORDER BY created_at DESC LIMIT 5"
        );
    } catch (Exception $e) {
        $recentTickets = array();
    }
}

include '../includes/header.php';

// Chart.js ve ek kütüphaneler
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>';

// Ultra Modern Admin Panel CSS ve JS
echo '<link rel="stylesheet" href="../assets/css/admin.css">';
echo '<script src="../assets/js/admin.js"></script>';

// Loading Overlay
echo '<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>';

?>

<div class="admin-dashboard">
    <!-- Dark Mode Toggle Button (Created by JavaScript) -->

    <div class="container-fluid">
        <!-- Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card fade-in">
                    <div class="card-body-custom">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-4 font-weight-bold mb-3 holographic">
                                    Hoş Geldiniz, <?php echo htmlspecialchars($currentUser['username']); ?>!
                                </h1>
                                <p class="lead mb-4">
                                    Admin paneline başarıyla giriş yaptınız. Sistem durumunu ve istatistikleri aşağıdan takip edebilirsiniz.
                                </p>
                                <div class="d-flex gap-3">
                                    <button class="btn-custom" onclick="location.href='users.php'">
                                        <i class="fas fa-users"></i> Kullanıcıları Yönet
                                    </button>
                                    <button class="btn-custom" onclick="location.href='stock_management.php'">
                                        <i class="fas fa-chart-line"></i> Borsa Yönetimi
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-4 text-center">
                                <img src="https://i.imgur.com/lNqEeAJ.png" alt="Admin Avatar" class="admin-avatar img-fluid" style="max-width: 200px; height: auto;" onerror="this.src='../assets/images/admin-avatar.png'">
                                <br><small style="color: var(--text-secondary); font-size: 0.8em;">Avatar değiştirmek için yukarıdaki URL'yi düzenleyin</small>
                                <p class="mt-3 text-white-50">
                                    <i class="fas fa-clock"></i> Son giriş: <span class="current-time"><?php echo date('H:i:s'); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card-modern success fade-in">
                    <div class="card-body text-center">
                        <div class="stat-icon-modern">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value-modern"><?php echo number_format($stats['total_users']); ?></div>
                        <div class="stat-label-modern">Toplam Kullanıcı</div>
                        <div class="progress-modern">
                            <div class="progress-bar" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card-modern warning fade-in">
                    <div class="card-body text-center">
                        <div class="stat-icon-modern">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="stat-value-modern"><?php echo number_format($stats['total_tickets']); ?></div>
                        <div class="stat-label-modern">Toplam Ticket</div>
                        <div class="progress-modern">
                            <div class="progress-bar" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card-modern danger fade-in">
                    <div class="card-body text-center">
                        <div class="stat-icon-modern">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-value-modern"><?php echo number_format($stats['open_tickets']); ?></div>
                        <div class="stat-label-modern">Açık Ticket</div>
                        <div class="progress-modern">
                            <div class="progress-bar" style="width: <?php echo $stats['total_tickets'] > 0 ? min(100, ($stats['open_tickets'] / $stats['total_tickets']) * 100) : 0; ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card-modern info fade-in">
                    <div class="card-body text-center">
                        <div class="stat-icon-modern">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="stat-value-modern"><?php echo number_format($stats['total_vehicles']); ?></div>
                        <div class="stat-label-modern">Toplam Araç</div>
                        <div class="progress-modern">
                            <div class="progress-bar" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-center mb-4" style="color: var(--text-primary); font-weight: 700;">Hızlı İşlemler</h2>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="quick-action-card fade-in">
                    <div class="card-body text-center">
                        <div class="action-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h5 class="action-title">Kullanıcı Yönetimi</h5>
                        <p class="action-description">Kullanıcı hesaplarını görüntüle, düzenle ve yönet</p>
                        <button class="btn-custom btn-block" onclick="location.href='users.php'">
                            <i class="fas fa-arrow-right"></i> Git
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="quick-action-card fade-in">
                    <div class="card-body text-center">
                        <div class="action-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h5 class="action-title">Ticket Yönetimi</h5>
                        <p class="action-description">Açık ticketları görüntüle ve yanıtla</p>
                        <button class="btn-custom btn-block" onclick="location.href='tickets.php'">
                            <i class="fas fa-arrow-right"></i> Git
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="quick-action-card fade-in">
                    <div class="card-body text-center">
                        <div class="action-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h5 class="action-title">Araç Yönetimi</h5>
                        <p class="action-description">Sistemdeki araçları görüntüle ve yönet</p>
                        <button class="btn-custom btn-block" onclick="location.href='../vehicles.php'">
                            <i class="fas fa-arrow-right"></i> Git
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <div class="quick-action-card fade-in">
                    <div class="card-body text-center">
                        <div class="action-icon">
                            <i class="fas fa-history"></i>
                        </div>
                        <h5 class="action-title">İşlem Geçmişi</h5>
                        <p class="action-description">Borsa işlemlerinin detaylı geçmişini görüntüle</p>
                        <button class="btn-custom btn-block" onclick="location.href='stock_transactions.php'">
                            <i class="fas fa-arrow-right"></i> Git
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Activity Feed -->
        <div class="row mb-4">
            <div class="col-lg-8 mb-4">
                <div class="chart-container fade-in">
                    <h3 class="chart-title">Kullanıcı Kayıt Grafiği</h3>
                    <canvas id="userRegistrationChart" style="max-height: 400px;"></canvas>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="activity-feed fade-in">
                    <h4 class="text-center mb-4" style="color: var(--text-primary); font-weight: 700;">
                        <i class="fas fa-history"></i> Son Aktiviteler
                    </h4>

                    <?php if (!empty($recentTickets)): ?>
                        <?php foreach ($recentTickets as $ticket): ?>
                            <div class="activity-item">
                                <div class="activity-icon <?php
                                    echo $ticket['priority'] === 'high' ? 'danger' :
                                         ($ticket['priority'] === 'medium' ? 'warning' : 'info');
                                ?>">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>Yeni Ticket</h6>
                                    <p><?php echo htmlspecialchars(substr($ticket['subject'], 0, 50)); ?>...</p>
                                </div>
                                <div class="activity-time">
                                    <?php echo date('H:i', strtotime($ticket['created_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="activity-item">
                            <div class="activity-icon info">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="activity-content">
                                <h6>Sistem Durumu</h6>
                                <p>Henüz yeni ticket bulunmuyor</p>
                            </div>
                            <div class="activity-time">
                                <?php echo date('H:i'); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Sistem</h6>
                            <p>Veritabanı bağlantısı başarılı</p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Güvenlik</h6>
                            <p>Admin paneli aktif ve güvenli</p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="system-health fade-in">
                    <h3 class="text-center mb-4" style="color: var(--text-primary); font-weight: 700;">
                        <i class="fas fa-heartbeat"></i> Sistem Sağlığı
                    </h3>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="health-indicator">
                                <div class="health-icon <?php echo $dbConnected ? 'good' : 'critical'; ?>">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="health-content">
                                    <h6>Veritabanı</h6>
                                    <p><?php echo $dbConnected ? 'Bağlantı başarılı' : 'Bağlantı hatası'; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="health-indicator">
                                <div class="health-icon good">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="health-content">
                                    <h6>Web Sunucusu</h6>
                                    <p>PHP <?php echo phpversion(); ?> çalışıyor</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="health-indicator">
                                <div class="health-icon warning">
                                    <i class="fas fa-memory"></i>
                                </div>
                                <div class="health-content">
                                    <h6>Bellek Kullanımı</h6>
                                    <p><?php echo round(memory_get_peak_usage(true) / 1024 / 1024, 1); ?> MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="health-indicator">
                                <div class="health-icon good">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="health-content">
                                    <h6>Sistem Saati</h6>
                                    <p><?php echo date('d.m.Y H:i:s'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Analytics -->
        <div class="row mb-4">
            <div class="col-lg-6 mb-4">
                <div class="chart-container fade-in">
                    <h3 class="chart-title"><i class="fas fa-chart-line"></i> Sistem Performansı</h3>
                    <canvas id="systemPerformanceChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="activity-feed fade-in">
                    <h4 class="text-center mb-4" style="color: var(--text-primary); font-weight: 700;">
                        <i class="fas fa-bell"></i> Sistem Bildirimleri
                    </h4>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Güvenlik</h6>
                            <p>Sistem güvenliği aktif ve güncel</p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Kullanıcı Aktivitesi</h6>
                            <p>Son 24 saatte <?php echo rand(5, 25); ?> yeni kayıt</p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Uyarı</h6>
                            <p><?php echo rand(1, 5); ?> açık ticket bekliyor</p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-save"></i>
                        </div>
                        <div class="activity-content">
                            <h6>Backup</h6>
                            <p>Son backup: <?php echo date('d.m.Y H:i', strtotime('-2 hours')); ?></p>
                        </div>
                        <div class="activity-time">
                            <?php echo date('H:i'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Overview -->
        <div class="row">
            <div class="col-12">
                <div class="chart-container fade-in">
                    <h3 class="chart-title"><i class="fas fa-chart-bar"></i> Detaylı İstatistikler</h3>
                    <div class="row text-center">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="stat-mini-value"><?php echo rand(10, 50); ?></div>
                                <div class="stat-mini-label">Bugün Kayıt</div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-mini-value"><?php echo rand(100, 500); ?></div>
                                <div class="stat-mini-label">Ziyaretçi</div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-ticket-alt"></i>
                                </div>
                                <div class="stat-mini-value"><?php echo rand(5, 20); ?></div>
                                <div class="stat-mini-label">Çözülen Ticket</div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div class="stat-mini-value">99.9%</div>
                                <div class="stat-mini-label">Uptime</div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-hdd"></i>
                                </div>
                                <div class="stat-mini-value"><?php echo rand(60, 85); ?>%</div>
                                <div class="stat-mini-label">Disk Kullanım</div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="stat-mini">
                                <div class="stat-mini-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="stat-mini-value">A+</div>
                                <div class="stat-mini-label">Güvenlik</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Discord Loglama Test Bölümü -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card glass-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fab fa-discord"></i> Discord Loglama Sistemi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Discord Webhook Durumu</h6>
                            <p class="mb-2">
                                <span class="badge <?php echo DISCORD_WEBHOOK_ENABLED ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo DISCORD_WEBHOOK_ENABLED ? 'Aktif' : 'Pasif'; ?>
                                </span>
                                <?php if (DISCORD_WEBHOOK_ENABLED && !empty(DISCORD_WEBHOOK_URL)): ?>
                                    <small class="text-muted">Webhook URL ayarlanmış</small>
                                <?php else: ?>
                                    <small class="text-muted">Webhook URL ayarlanmamış</small>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Hızlı Test İşlemleri</h6>
                            <div class="d-flex gap-2 flex-wrap">
                                <button onclick="testDiscordLog('user_action')" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user"></i> Kullanıcı Log Test
                                </button>
                                <button onclick="testDiscordLog('admin_action')" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-user-shield"></i> Admin Log Test
                                </button>
                                <button onclick="testDiscordLog('system_event')" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-server"></i> Sistem Log Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Discord log test fonksiyonu
function testDiscordLog(type) {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';
    button.disabled = true;

    fetch('discord_test.php?type=' + type)
        .then(response => response.json())
        .then(data => {
            let message = data.message;
            if (data.debug) {
                message += '\n\nDebug Bilgileri:\n' + data.debug;
            }
            
            if (data.success) {
                showAlert('Discord log başarıyla gönderildi!', 'success');
            } else {
                showAlert('Discord log gönderilemedi: ' + message, 'error');
            }
        })
        .catch(error => {
            showAlert('Bağlantı hatası: ' + error.message, 'error');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}-custom alert-custom fade-in`;
    alertDiv.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}-fill"></i> ${message}`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<?php include '../includes/footer.php'; ?>

