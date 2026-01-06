<?php
/**
 * Beldad Roleplay - Admin Borsa Yönetimi
 */

require_once '../session.php';
requireAdmin();

$pageTitle = 'Borsa Yönetimi - Admin Paneli';
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$error = '';
$success = '';

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Hisse güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_stock') {
    if (!$dbConnected) {
        $error = 'Veritabanı bağlantısı yok.';
    } else {
        $stockId = (int)(isset($_POST['stock_id']) ? $_POST['stock_id'] : 0);
        $price = (float)(isset($_POST['price']) ? $_POST['price'] : 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if ($stockId > 0 && $price > 0) {
            try {
                $db->query(
                    "UPDATE stocks SET current_price = ?, previous_price = current_price, updated_at = NOW() WHERE id = ?",
                    [$price, $stockId]
                );

                if ($isActive != 1) {
                    $db->query("UPDATE stocks SET is_active = ? WHERE id = ?", [$isActive, $stockId]);
                }

                $success = 'Hisse bilgileri başarıyla güncellendi.';
                logAdminAction('Hisse Güncelleme', "Stock ID: {$stockId}, Yeni Fiyat: {$price}", $currentUser['id']);
            } catch (Exception $e) {
                $error = 'Bir hata oluştu.';
                error_log("Hisse güncelleme hatası: " . $e->getMessage());
            }
        }
    }
}

// Yeni hisse ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_stock') {
    if (!$dbConnected) {
        $error = 'Veritabanı bağlantısı yok.';
    } else {
        $symbol = strtoupper(trim(isset($_POST['symbol']) ? $_POST['symbol'] : ''));
        $name = trim(isset($_POST['name']) ? $_POST['name'] : '');
        $price = (float)(isset($_POST['price']) ? $_POST['price'] : 0);

        if (!empty($symbol) && !empty($name) && $price > 0) {
            try {
                $db->query(
                    "INSERT INTO stocks (symbol, name, current_price, previous_price) VALUES (?, ?, ?, ?)",
                    [$symbol, $name, $price, $price]
                );
                $success = 'Yeni hisse başarıyla eklendi.';
                logAdminAction('Yeni Hisse Ekleme', "Symbol: {$symbol}, Name: {$name}", $currentUser['id']);
            } catch (Exception $e) {
                $error = 'Bir hata oluştu. Sembol zaten mevcut olabilir.';
                error_log("Hisse ekleme hatası: " . $e->getMessage());
            }
        } else {
            $error = 'Tüm alanları doldurun.';
        }
    }
}

// Hisse senetlerini çek
$stocks = array();
$stockStats = array();

if ($dbConnected) {
    try {
        $stocks = $db->fetchAll("SELECT * FROM stocks ORDER BY symbol");

        // Borsa istatistikleri
        $stockStats = [
            'total_stocks' => count($stocks),
            'active_stocks' => count(array_filter($stocks, function($s) { return $s['is_active']; })),
            'total_transactions' => isset($db->fetchOne("SELECT COUNT(*) as count FROM stock_transactions")['count']) ? $db->fetchOne("SELECT COUNT(*) as count FROM stock_transactions")['count'] : 0,
            'total_volume' => isset($db->fetchOne("SELECT SUM(volume) as total FROM stocks")['total']) ? $db->fetchOne("SELECT SUM(volume) as total FROM stocks")['total'] : 0
        ];
    } catch (Exception $e) {
        $stocks = array();
    }
} else {
    // Demo verisi
    $stocks = [
        ['id' => 1, 'symbol' => 'BELDAD', 'name' => 'Beldad Corporation', 'current_price' => 250.00, 'previous_price' => 245.00, 'volume' => 1250, 'is_active' => 1, 'created_at' => '2024-01-01', 'updated_at' => '2024-01-15'],
        ['id' => 2, 'symbol' => 'MTA', 'name' => 'Multi Theft Auto Inc.', 'current_price' => 450.00, 'previous_price' => 455.00, 'volume' => 890, 'is_active' => 1, 'created_at' => '2024-01-01', 'updated_at' => '2024-01-15'],
        ['id' => 3, 'symbol' => 'RP', 'name' => 'Roleplay Ventures', 'current_price' => 125.00, 'previous_price' => 120.00, 'volume' => 2100, 'is_active' => 1, 'created_at' => '2024-01-01', 'updated_at' => '2024-01-15'],
        ['id' => 4, 'symbol' => 'GAME', 'name' => 'Gaming Solutions Ltd.', 'current_price' => 375.00, 'previous_price' => 380.00, 'volume' => 675, 'is_active' => 1, 'created_at' => '2024-01-01', 'updated_at' => '2024-01-15'],
        ['id' => 5, 'symbol' => 'TECH', 'name' => 'Tech Innovations', 'current_price' => 650.00, 'previous_price' => 645.00, 'volume' => 430, 'is_active' => 1, 'created_at' => '2024-01-01', 'updated_at' => '2024-01-15'],
    ];

    $stockStats = [
        'total_stocks' => count($stocks),
        'active_stocks' => count(array_filter($stocks, function($s) { return $s['is_active']; })),
        'total_transactions' => 0,
        'total_volume' => array_sum(array_column($stocks, 'volume'))
    ];
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="index.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Admin Paneli
            </a>
            <h2><i class="bi bi-graph-up"></i> Borsa Yönetimi</h2>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger-custom alert-custom fade-in">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success-custom alert-custom fade-in">
            <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <!-- Borsa İstatistikleri -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stockStats['total_stocks']) ? $stockStats['total_stocks'] : 0); ?></div>
                <div class="stat-label">Toplam Hisse</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stockStats['active_stocks']) ? $stockStats['active_stocks'] : 0); ?></div>
                <div class="stat-label">Aktif Hisse</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stockStats['total_transactions']) ? $stockStats['total_transactions'] : 0); ?></div>
                <div class="stat-label">Toplam İşlem</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stockStats['total_volume']) ? $stockStats['total_volume'] : 0); ?></div>
                <div class="stat-label">Toplam Hacim</div>
            </div>
        </div>
    </div>

    <!-- Yeni Hisse Ekle -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Yeni Hisse Ekle
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_stock">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label-custom">Sembol</label>
                                <input type="text" class="form-control form-control-custom" name="symbol" placeholder="BELDAD" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-custom">Şirket Adı</label>
                                <input type="text" class="form-control form-control-custom" name="name" placeholder="Beldad Corporation" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label-custom">Başlangıç Fiyatı</label>
                                <input type="number" class="form-control form-control-custom" name="price" step="0.01" min="0.01" placeholder="100.00" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label-custom">&nbsp;</label>
                                <button type="submit" class="btn btn-success-custom w-100">
                                    <i class="bi bi-plus"></i> Ekle
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Hisse Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">Hisse Senetleri</h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($stocks)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Henüz hiç hisse senedi bulunmuyor.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sembol</th>
                                        <th>Şirket</th>
                                        <th>Güncel Fiyat</th>
                                        <th>Önceki Fiyat</th>
                                        <th>Değişim</th>
                                        <th>Hacim</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stocks as $stock): ?>
                                        <tr>
                                            <td><?php echo $stock['id']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($stock['symbol']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($stock['name']); ?></td>
                                            <td>$<?php echo number_format($stock['current_price'], 2); ?></td>
                                            <td>$<?php echo number_format($stock['previous_price'], 2); ?></td>
                                            <td>
                                                <?php
                                                $change = $stock['current_price'] - $stock['previous_price'];
                                                $changePercent = $stock['previous_price'] > 0 ? ($change / $stock['previous_price']) * 100 : 0;
                                                ?>
                                                <span class="<?php echo $change >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    <i class="bi <?php echo $change >= 0 ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                                    <?php echo ($change >= 0 ? '+' : '') . number_format($change, 2); ?>
                                                    (<?php echo ($change >= 0 ? '+' : '') . number_format($changePercent, 2); ?>%)
                                                </span>
                                            </td>
                                            <td><?php echo number_format($stock['volume']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $stock['is_active'] ? 'badge-success-custom' : 'badge-danger-custom'; ?>">
                                                    <?php echo $stock['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-custom btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $stock['id']; ?>">
                                                    <i class="bi bi-pencil"></i> Düzenle
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Düzenleme Modal -->
                                        <div class="modal fade" id="editModal<?php echo $stock['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                                                    <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                                                        <h5 class="modal-title"><?php echo htmlspecialchars($stock['symbol']); ?> - Düzenle</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="action" value="update_stock">
                                                            <input type="hidden" name="stock_id" value="<?php echo $stock['id']; ?>">

                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Güncel Fiyat</label>
                                                                <input type="number" class="form-control form-control-custom" name="price" step="0.01" min="0.01" value="<?php echo $stock['current_price']; ?>" required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active<?php echo $stock['id']; ?>" <?php echo $stock['is_active'] ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label" for="active<?php echo $stock['id']; ?>">
                                                                        Aktif
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                                            <button type="submit" class="btn btn-custom">Kaydet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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