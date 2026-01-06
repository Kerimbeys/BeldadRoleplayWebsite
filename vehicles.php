<?php
/**
 * MTA:SA UCP - Araçlarım
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Araçlarım - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$vehicles = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
    
    if ($dbConnected) {
        // Araçları çek
        $vehicles = $db->fetchAll(
            "SELECT * FROM vehicles WHERE owner = ? ORDER BY id DESC",
            [$currentUser['id']]
        );
    }
} catch (Exception $e) {
    $dbConnected = false;
    $vehicles = array();
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-car-front"></i> Araçlarım</h2>
            <p class="text-secondary">Sahip olduğunuz tüm araçların listesi</p>
        </div>
    </div>

    <?php if (empty($vehicles)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-body-custom text-center py-5">
                        <i class="bi bi-car-front" style="font-size: 4rem; color: var(--text-secondary);"></i>
                        <h4 class="mt-3">Henüz aracınız yok</h4>
                        <p class="text-secondary">Oyunda araç satın alarak burada görüntüleyebilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-custom">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-car-front-fill"></i> 
                                <?php echo htmlspecialchars(isset($vehicle['model']) ? $vehicle['model'] : 'Bilinmeyen Model'); ?>
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Plaka:</span>
                                    <span class="fw-bold"><?php echo htmlspecialchars(isset($vehicle['plate']) ? $vehicle['plate'] : 'Yok'); ?></span>
                                </div>
                                <?php if (isset($vehicle['color1']) || isset($vehicle['color2'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Renk:</span>
                                    <span>
                                        <?php if (isset($vehicle['color1'])): ?>
                                            <span class="badge badge-primary-custom"><?php echo $vehicle['color1']; ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($vehicle['color2'])): ?>
                                            <span class="badge badge-primary-custom"><?php echo $vehicle['color2']; ?></span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($vehicle['fuel'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Yakıt:</span>
                                    <span class="fw-bold">%<?php echo $vehicle['fuel']; ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($vehicle['health'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Sağlık:</span>
                                    <span class="fw-bold"><?php echo $vehicle['health']; ?> HP</span>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($vehicle['x']) && isset($vehicle['y']) && isset($vehicle['z'])): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Konum:</span>
                                    <span class="text-secondary small">
                                        <?php echo number_format($vehicle['x'], 2); ?>, 
                                        <?php echo number_format($vehicle['y'], 2); ?>
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-center">
                                <span class="badge badge-primary-custom">
                                    ID: <?php echo $vehicle['id']; ?>
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
                            Toplam <strong><?php echo count($vehicles); ?></strong> aracınız bulunmaktadır.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

