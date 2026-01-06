<?php
/**
 * Beldad Roleplay - Admin İstatistikler
 */

require_once '../session.php';
requireAdmin();

$pageTitle = 'Sistem İstatistikleri - Admin Paneli';
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Genel istatistikler
$stats = [
    'total_users' => 0,
    'total_vehicles' => 0,
    'total_interiors' => 0,
    'total_companies' => 0,
    'total_tickets' => 0,
    'open_tickets' => 0,
    'total_money' => 0,
    'total_bankmoney' => 0
];

$richestUsers = array();
$mostVehicles = array();
$recentUsers = array();

if ($dbConnected) {
    try {
        $totalUsers = $db->fetchOne("SELECT COUNT(*) as count FROM accounts");
        $stats['total_users'] = isset($totalUsers['count']) ? $totalUsers['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $totalVehicles = $db->fetchOne("SELECT COUNT(*) as count FROM vehicles");
        $stats['total_vehicles'] = isset($totalVehicles['count']) ? $totalVehicles['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $totalInteriors = $db->fetchOne("SELECT COUNT(*) as count FROM interiors");
        $stats['total_interiors'] = isset($totalInteriors['count']) ? $totalInteriors['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $totalCompanies = $db->fetchOne("SELECT COUNT(*) as count FROM companies");
        $stats['total_companies'] = isset($totalCompanies['count']) ? $totalCompanies['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $totalTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets");
        $stats['total_tickets'] = isset($totalTickets['count']) ? $totalTickets['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $openTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets WHERE status = 'open'");
        $stats['open_tickets'] = isset($openTickets['count']) ? $openTickets['count'] : 0;
    } catch (Exception $e) {}
    
    // Toplam para istatistikleri
    try {
        $moneyStats = $db->fetchOne("SELECT SUM(money) as total_money, SUM(bankmoney) as total_bankmoney FROM accounts");
        $stats['total_money'] = isset($moneyStats['total_money']) ? $moneyStats['total_money'] : 0;
        $stats['total_bankmoney'] = isset($moneyStats['total_bankmoney']) ? $moneyStats['total_bankmoney'] : 0;
    } catch (Exception $e) {}

    // Borsa istatistikleri
    try {
        $stockStats = $db->fetchOne("SELECT COUNT(*) as total_stocks, SUM(volume) as total_volume FROM stocks WHERE is_active = 1");
        $stats['total_stocks'] = isset($stockStats['total_stocks']) ? $stockStats['total_stocks'] : 0;
        $stats['total_stock_volume'] = isset($stockStats['total_volume']) ? $stockStats['total_volume'] : 0;
    } catch (Exception $e) {
        // Demo modu için borsa istatistikleri
        $stats['total_stocks'] = 5;
        $stats['total_stock_volume'] = 4445;
    }

    // Aktif kullanıcı sayısı (son 24 saat)
    try {
        $activeUsers = $db->fetchOne("SELECT COUNT(*) as count FROM accounts WHERE lastlogin > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $stats['active_users_24h'] = isset($activeUsers['count']) ? $activeUsers['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        // En zengin kullanıcılar
        $richestUsers = $db->fetchAll(
            "SELECT id, username, (money + bankmoney) as total FROM accounts ORDER BY total DESC LIMIT 10"
        );
    } catch (Exception $e) {
        $richestUsers = array();
    }
    
    try {
        // En çok aracı olan kullanıcılar
        $mostVehicles = $db->fetchAll(
            "SELECT owner, COUNT(*) as count FROM vehicles GROUP BY owner ORDER BY count DESC LIMIT 10"
        );
    } catch (Exception $e) {
        $mostVehicles = array();
    }
    
    try {
        // Son kayıt olan kullanıcılar
        $recentUsers = $db->fetchAll(
            "SELECT id, username, money, bankmoney FROM accounts ORDER BY id DESC LIMIT 10"
        );
    } catch (Exception $e) {
        $recentUsers = array();
    }
}

include '../includes/header.php';

// Veritabanı bağlantısı uyarısı
if (!$dbConnected) {
    echo '<div class="container mt-4"><div class="alert alert-warning-custom alert-custom">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Uyarı:</strong> Veritabanı bağlantısı yok. İstatistikler görüntülenemiyor.
    </div></div>';
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="index.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Admin Paneli
            </a>
            <h2><i class="bi bi-graph-up"></i> Sistem İstatistikleri</h2>
        </div>
    </div>

    <!-- Genel İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
                <div class="stat-label">Toplam Kullanıcı</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-car-front"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_vehicles']); ?></div>
                <div class="stat-label">Toplam Araç</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-house-door"></i>
                </div>
                <div class="stat-value"><?php echo number_format($stats['total_interiors']); ?></div>
                <div class="stat-label">Toplam Ev</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stats['total_stocks']) ? $stats['total_stocks'] : 0); ?></div>
                <div class="stat-label">Borsa Hisse</div>
            </div>
        </div>
    </div>

    <!-- Para İstatistikleri -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-stack"></i> Toplam Para İstatistikleri
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Toplam Nakit:</span>
                            <span class="fw-bold text-success">$<?php echo number_format($stats['total_money'], 0, ',', '.'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Toplam Banka:</span>
                            <span class="fw-bold text-primary">$<?php echo number_format($stats['total_bankmoney'], 0, ',', '.'); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fw-bold">Toplam:</span>
                            <span class="fw-bold fs-4" style="color: var(--accent-primary);">
                                $<?php echo number_format($stats['total_money'] + $stats['total_bankmoney'], 0, ',', '.'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-circle"></i> Açık Ticketlar
                    </h5>
                </div>
                <div class="card-body-custom text-center py-4">
                    <h1 class="display-4" style="color: var(--accent-warning);">
                        <?php echo $stats['open_tickets']; ?>
                    </h1>
                    <p class="text-secondary">Yanıt bekleyen ticket</p>
                    <a href="tickets.php?status=open" class="btn btn-custom">
                        <i class="bi bi-arrow-right"></i> Görüntüle
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- En Zengin Kullanıcılar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-trophy"></i> En Zengin Kullanıcılar
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($richestUsers)): ?>
                        <p class="text-secondary text-center">Veri bulunamadı.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kullanıcı</th>
                                        <th>Toplam Para</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($richestUsers as $index => $user): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?> (ID: <?php echo $user['id']; ?>)</td>
                                            <td class="fw-bold text-success">$<?php echo number_format($user['total'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front"></i> En Çok Aracı Olanlar
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($mostVehicles)): ?>
                        <p class="text-secondary text-center">Veri bulunamadı.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kullanıcı ID</th>
                                        <th>Araç Sayısı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mostVehicles as $index => $vehicle): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>#<?php echo $vehicle['owner']; ?></td>
                                            <td class="fw-bold"><?php echo $vehicle['count']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Kayıt Olanlar -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Son Kayıt Olan Kullanıcılar
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($recentUsers)): ?>
                        <p class="text-secondary text-center">Veri bulunamadı.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kullanıcı Adı</th>
                                        <th>Nakit</th>
                                        <th>Banka</th>
                                        <th>Toplam</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentUsers as $user): ?>
                                        <tr>
                                            <td>#<?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td>$<?php echo number_format($user['money'], 0, ',', '.'); ?></td>
                                            <td>$<?php echo number_format($user['bankmoney'], 0, ',', '.'); ?></td>
                                            <td class="fw-bold">$<?php echo number_format($user['money'] + $user['bankmoney'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

