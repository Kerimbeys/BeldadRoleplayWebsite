<?php
/**
 * MTA:SA UCP - Evlerim
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Evlerim - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$interiors = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
    
    if ($dbConnected) {
        // Evleri çek
        $interiors = $db->fetchAll(
            "SELECT * FROM interiors WHERE owner = ? ORDER BY id DESC",
            [$currentUser['id']]
        );
    }
} catch (Exception $e) {
    $dbConnected = false;
    $interiors = array();
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-house-door"></i> Evlerim</h2>
            <p class="text-secondary">Sahip olduğunuz tüm evlerin listesi</p>
        </div>
    </div>

    <?php if (empty($interiors)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-body-custom text-center py-5">
                        <i class="bi bi-house-door" style="font-size: 4rem; color: var(--text-secondary);"></i>
                        <h4 class="mt-3">Henüz eviniz yok</h4>
                        <p class="text-secondary">Oyunda ev satın alarak burada görüntüleyebilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($interiors as $interior): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-house-door-fill"></i> 
                                <?php echo htmlspecialchars(isset($interior['name']) ? $interior['name'] : 'Ev #' . $interior['id']); ?>
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="mb-3">
                                <?php if (isset($interior['type'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Tip:</span>
                                    <span class="fw-bold"><?php echo htmlspecialchars($interior['type']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($interior['price'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Değer:</span>
                                    <span class="fw-bold text-success">$<?php echo number_format($interior['price'], 0, ',', '.'); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($interior['x']) && isset($interior['y']) && isset($interior['z'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Konum:</span>
                                    <span class="text-secondary small">
                                        <?php echo number_format($interior['x'], 2); ?>, 
                                        <?php echo number_format($interior['y'], 2); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($interior['locked'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Durum:</span>
                                    <span>
                                        <?php if ($interior['locked'] == 1): ?>
                                            <span class="badge badge-warning-custom">Kilitli</span>
                                        <?php else: ?>
                                            <span class="badge badge-success-custom">Açık</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-center">
                                <span class="badge badge-primary-custom">
                                    ID: <?php echo $interior['id']; ?>
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
                            Toplam <strong><?php echo count($interiors); ?></strong> eviniz bulunmaktadır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

