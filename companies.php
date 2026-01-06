<?php
/**
 * MTA:SA UCP - Şirketlerim
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Şirketlerim - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$companies = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
    
    if ($dbConnected) {
        // Şirketleri çek
        $companies = $db->fetchAll(
            "SELECT * FROM companies WHERE owner = ? ORDER BY id DESC",
            [$currentUser['id']]
        );
    }
} catch (Exception $e) {
    $dbConnected = false;
    $companies = array();
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-building"></i> Şirketlerim</h2>
            <p class="text-secondary">Sahip olduğunuz tüm şirketlerin listesi</p>
        </div>
    </div>

    <?php if (empty($companies)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-body-custom text-center py-5">
                        <i class="bi bi-building" style="font-size: 4rem; color: var(--text-secondary);"></i>
                        <h4 class="mt-3">Henüz şirketiniz yok</h4>
                        <p class="text-secondary">Oyunda şirket satın alarak burada görüntüleyebilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($companies as $company): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-building-fill"></i> 
                                <?php echo htmlspecialchars(isset($company['name']) ? $company['name'] : 'Şirket #' . $company['id']); ?>
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="mb-3">
                                <?php if (isset($company['type'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Tip:</span>
                                    <span class="fw-bold"><?php echo htmlspecialchars($company['type']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($company['profit'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Kâr:</span>
                                    <span class="fw-bold text-success">$<?php echo number_format($company['profit'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($company['x']) && isset($company['y']) && isset($company['z'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Konum:</span>
                                    <span class="text-secondary small">
                                        <?php echo number_format($company['x'], 2); ?>, 
                                        <?php echo number_format($company['y'], 2); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($company['employees'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Çalışan:</span>
                                    <span class="fw-bold"><?php echo $company['employees']; ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-center">
                                <span class="badge badge-primary-custom">
                                    ID: <?php echo $company['id']; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <p class="mb-0 text-center text-secondary">
                            <i class="bi bi-info-circle"></i> 
                            Toplam <strong><?php echo count($companies); ?></strong> şirketiniz bulunmaktadır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

