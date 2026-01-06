<?php
/**
 * MTA:SA UCP - Ana Dashboard
 * Kullanıcı profil bilgileri ve istatistikler
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Ana Sayfa - ' . SITE_NAME;
$currentUser = getCurrentUser();

// Kullanıcı bilgilerini çek
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
    'total_money' => (isset($currentUser['money']) ? $currentUser['money'] : 0) + (isset($currentUser['bankmoney']) ? $currentUser['bankmoney'] : 0),
    'cash' => isset($currentUser['money']) ? $currentUser['money'] : 0,
    'bank' => isset($currentUser['bankmoney']) ? $currentUser['bankmoney'] : 0,
    'vehicles_count' => 0,
    'interiors_count' => 0,
    'companies_count' => 0
];

// Araç sayısı
if ($dbConnected) {
    try {
        $vehiclesCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM vehicles WHERE owner = ?",
            [$currentUser['id']]
        );
        $stats['vehicles_count'] = isset($vehiclesCount['count']) ? $vehiclesCount['count'] : 0;
    } catch (Exception $e) {
        $stats['vehicles_count'] = 0;
    }
} else {
    $stats['vehicles_count'] = 0;
}

// Ev sayısı
if ($dbConnected) {
    try {
        $interiorsCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM interiors WHERE owner = ?",
            [$currentUser['id']]
        );
        $stats['interiors_count'] = isset($interiorsCount['count']) ? $interiorsCount['count'] : 0;
    } catch (Exception $e) {
        $stats['interiors_count'] = 0;
    }
} else {
    $stats['interiors_count'] = 0;
}

// Şirket sayısı (eğer companies tablosu varsa)
if ($dbConnected) {
    try {
        $companiesCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM companies WHERE owner = ?",
            [$currentUser['id']]
        );
        $stats['companies_count'] = isset($companiesCount['count']) ? $companiesCount['count'] : 0;
    } catch (Exception $e) {
        $stats['companies_count'] = 0;
    }
} else {
    $stats['companies_count'] = 0;
}

// Meslek ismini al (eğer jobs tablosu varsa)
$jobName = 'İşsiz';
if ($dbConnected && !empty($currentUser['job'])) {
    try {
        $job = $db->fetchOne(
            "SELECT name FROM jobs WHERE id = ? LIMIT 1",
            [$currentUser['job']]
        );
        if ($job) {
            $jobName = $job['name'];
        }
    } catch (Exception $e) {
        // Hata durumunda varsayılan değer
    }
}

include 'includes/header.php';

// Veritabanı bağlantısı yoksa uyarı göster
if (!$dbConnected && isset($_SESSION['temp_admin'])) {
    echo '<div class="container mt-4"><div class="alert alert-warning-custom alert-custom">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Geçici Admin Modu:</strong> Veritabanı bağlantısı yok. Bazı özellikler çalışmayabilir.
    </div></div>';
}
?>

<div class="container mt-4">
    <!-- Hoş Geldin Mesajı -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom fade-in">
                <div class="card-header-custom">
                    <h3 class="mb-0 holographic">
                        <i class="bi bi-person-circle"></i> Hoş Geldin, <?php echo htmlspecialchars($currentUser['username']); ?>!
                    </h3>
                </div>
                <div class="card-body-custom">
                    <p class="mb-0 text-secondary">
                        <i class="bi bi-calendar3"></i> Son giriş: <?php echo date('d.m.Y H:i'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card float">
                <div class="stat-icon pulse">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-value counter">$<?php echo number_format($stats['total_money'], 0, ',', '.'); ?></div>
                <div class="stat-label">Toplam Para</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card float" style="animation-delay: 0.2s;">
                <div class="stat-icon pulse">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-value counter">$<?php echo number_format($stats['cash'], 0, ',', '.'); ?></div>
                <div class="stat-label">Nakit</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card float" style="animation-delay: 0.4s;">
                <div class="stat-icon pulse">
                    <i class="bi bi-bank"></i>
                </div>
                <div class="stat-value counter">$<?php echo number_format($stats['bank'], 0, ',', '.'); ?></div>
                <div class="stat-label">Banka</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card float" style="animation-delay: 0.6s;">
                <div class="stat-icon pulse">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div class="stat-value"><?php echo htmlspecialchars($jobName); ?></div>
                <div class="stat-label">Meslek</div>
            </div>
        </div>
    </div>

    <!-- Malvarlığı Özeti -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card-custom glow">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front"></i> Araçlarım
                    </h5>
                </div>
                <div class="card-body-custom text-center">
                    <h2 class="text-primary counter"><?php echo $stats['vehicles_count']; ?></h2>
                    <p class="text-secondary mb-3">Toplam Araç</p>
                    <div class="progress-custom mb-3" style="max-width: 200px; margin: 0 auto;">
                        <div class="progress-bar-custom" style="width: <?php echo min(100, ($stats['vehicles_count'] / 10) * 100); ?>%;"></div>
                    </div>
                    <a href="vehicles.php" class="btn btn-custom btn-sm ripple">
                        <i class="bi bi-arrow-right"></i> Detayları Gör
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-custom glow">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-house-door"></i> Evlerim
                    </h5>
                </div>
                <div class="card-body-custom text-center">
                    <h2 class="text-primary counter"><?php echo $stats['interiors_count']; ?></h2>
                    <p class="text-secondary mb-3">Toplam Ev</p>
                    <div class="progress-custom mb-3" style="max-width: 200px; margin: 0 auto;">
                        <div class="progress-bar-custom" style="width: <?php echo min(100, ($stats['interiors_count'] / 5) * 100); ?>%;"></div>
                    </div>
                    <a href="interiors.php" class="btn btn-custom btn-sm ripple">
                        <i class="bi bi-arrow-right"></i> Detayları Gör
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-custom glow">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-building"></i> Şirketlerim
                    </h5>
                </div>
                <div class="card-body-custom text-center">
                    <h2 class="text-primary counter"><?php echo $stats['companies_count']; ?></h2>
                    <p class="text-secondary mb-3">Toplam Şirket</p>
                    <div class="progress-custom mb-3" style="max-width: 200px; margin: 0 auto;">
                        <div class="progress-bar-custom" style="width: <?php echo min(100, ($stats['companies_count'] / 3) * 100); ?>%;"></div>
                    </div>
                    <a href="companies.php" class="btn btn-custom btn-sm ripple">
                        <i class="bi bi-arrow-right"></i> Detayları Gör
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Para Dağılımı Grafiği -->
    <?php if ($stats['total_money'] > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart"></i> Para Dağılımı
                    </h5>
                </div>
                <div class="card-body-custom">
                    <canvas id="moneyChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Profil Bilgileri -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge"></i> Profil Bilgileri
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-fill me-3 text-primary" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-secondary small">Kullanıcı Adı</div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-123 me-3 text-primary" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-secondary small">Kullanıcı ID</div>
                                    <div class="fw-bold">#<?php echo $currentUser['id']; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-palette-fill me-3 text-primary" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-secondary small">Skin ID</div>
                                    <div class="fw-bold"><?php echo isset($currentUser['skin']) ? $currentUser['skin'] : 'Belirtilmemiş'; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-shield-check me-3 text-primary" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-secondary small">Yetki Seviyesi</div>
                                    <div class="fw-bold">
                                        <?php if ($currentUser['admin'] > 0): ?>
                                            <span class="badge badge-success-custom">Admin (Seviye <?php echo $currentUser['admin']; ?>)</span>
                                        <?php else: ?>
                                            <span class="badge badge-primary-custom">Oyuncu</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php if ($stats['total_money'] > 0): ?>
<script>
// Para Dağılımı Grafiği
const ctx = document.getElementById('moneyChart');
if (ctx) {
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Nakit Para', 'Banka Parası'],
            datasets: [{
                data: [<?php echo $stats['cash']; ?>, <?php echo $stats['bank']; ?>],
                backgroundColor: [
                    'rgba(0, 212, 255, 0.8)',
                    'rgba(0, 255, 136, 0.8)'
                ],
                borderColor: [
                    'rgba(0, 212, 255, 1)',
                    'rgba(0, 255, 136, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#b8c5d6',
                        font: {
                            size: 14
                        },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += '$' + context.parsed.toLocaleString('tr-TR');
                            return label;
                        }
                    }
                }
            }
        }
    });
}
</script>
<?php endif; ?>

