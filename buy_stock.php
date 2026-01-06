<?php
/**
 * Beldad Roleplay - Hisse Alım
 */

require_once 'session.php';
require_once 'includes/functions.php';
requireLogin();

$pageTitle = 'Hisse Al - ' . SITE_NAME;
$currentUser = getCurrentUser();

$error = '';
$success = '';

$db = null;
$dbConnected = false;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Hisse sembolü kontrolü
$symbol = isset($_GET['symbol']) ? strtoupper(trim($_GET['symbol'])) : '';
if (empty($symbol)) {
    header('Location: stock_market.php?error=invalid_symbol');
    exit();
}

$stock = null;
if ($dbConnected) {
    try {
        $stock = $db->fetchOne("SELECT * FROM stocks WHERE symbol = ? AND is_active = 1", [$symbol]);
        if (!$stock) {
            header('Location: stock_market.php?error=stock_not_found');
            exit();
        }
    } catch (Exception $e) {
        header('Location: stock_market.php?error=db_error');
        exit();
    }
} else {
    // Demo verisi
    $demoStocks = [
        'BELDAD' => ['id' => 1, 'symbol' => 'BELDAD', 'name' => 'Beldad Corporation', 'current_price' => 250.00, 'is_active' => 1],
        'MTA' => ['id' => 2, 'symbol' => 'MTA', 'name' => 'Multi Theft Auto Inc.', 'current_price' => 450.00, 'is_active' => 1],
        'RP' => ['id' => 3, 'symbol' => 'RP', 'name' => 'Roleplay Ventures', 'current_price' => 125.00, 'is_active' => 1],
        'GAME' => ['id' => 4, 'symbol' => 'GAME', 'name' => 'Gaming Solutions Ltd.', 'current_price' => 375.00, 'is_active' => 1],
        'TECH' => ['id' => 5, 'symbol' => 'TECH', 'name' => 'Tech Innovations', 'current_price' => 650.00, 'is_active' => 1],
    ];
    
    if (!isset($demoStocks[$symbol])) {
        header('Location: stock_market.php?error=stock_not_found');
        exit();
    }
    $stock = $demoStocks[$symbol];
}

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Geçersiz istek.';
    } else {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $totalCost = $quantity * $stock['current_price'];
        $fee = $totalCost * 0.02; // %2 komisyon
        $netCost = $totalCost + $fee;

        if ($quantity <= 0) {
            $error = 'Geçerli bir miktar girin.';
        } elseif ($netCost > $currentUser['money']) {
            $error = 'Yeterli nakit paranız yok. Gerekli: $' . number_format($netCost, 0, ',', '.') . ', Mevcut: $' . number_format($currentUser['money'], 0, ',', '.');
        } elseif (!$dbConnected) {
            $error = 'Veritabanı bağlantısı yok. İşlem gerçekleştirilemiyor.';
        } else {
            try {
                $db->beginTransaction();

                // Kullanıcının parasını düş
                $db->query(
                    "UPDATE accounts SET money = money - ? WHERE id = ?",
                    [$netCost, $currentUser['id']]
                );

                // Kullanıcının hisse portföyünü güncelle
                $existingStock = $db->fetchOne(
                    "SELECT * FROM user_stocks WHERE user_id = ? AND stock_symbol = ?",
                    [$currentUser['id'], $symbol]
                );

                if ($existingStock) {
                    // Mevcut hisseyi güncelle
                    $newQuantity = $existingStock['quantity'] + $quantity;
                    $newTotalInvested = $existingStock['total_invested'] + $totalCost;
                    $newAverageCost = $newTotalInvested / $newQuantity;

                    $db->query(
                        "UPDATE user_stocks SET quantity = ?, average_cost = ?, total_invested = ?, updated_at = NOW() WHERE id = ?",
                        [$newQuantity, $newAverageCost, $newTotalInvested, $existingStock['id']]
                    );
                } else {
                    // Yeni hisse ekle
                    $db->query(
                        "INSERT INTO user_stocks (user_id, stock_symbol, quantity, average_cost, total_invested) VALUES (?, ?, ?, ?, ?)",
                        [$currentUser['id'], $symbol, $quantity, $stock['current_price'], $totalCost]
                    );
                }

                // İşlem geçmişine ekle
                $db->query(
                    "INSERT INTO stock_transactions (user_id, stock_symbol, transaction_type, quantity, price_per_share, total_amount, fee, net_amount) VALUES (?, ?, 'buy', ?, ?, ?, ?, ?)",
                    [$currentUser['id'], $symbol, $quantity, $stock['current_price'], $totalCost, $fee, $netCost]
                );

                // Hisse hacmini güncelle
                $db->query(
                    "UPDATE stocks SET volume = volume + ? WHERE symbol = ?",
                    [$quantity, $symbol]
                );

                $db->commit();
                $success = $quantity . ' adet ' . $stock['name'] . ' hissesi başarıyla satın alındı. Toplam maliyet: $' . number_format($netCost, 0, ',', '.') . ' (Komisyon: $' . number_format($fee, 2) . ')';

                // Güncel kullanıcı bilgilerini yeniden çek
                $currentUser = getCurrentUser();

            } catch (Exception $e) {
                $db->rollback();
                $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                error_log("Hisse alım hatası: " . $e->getMessage());
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Hisse Al: <?php echo htmlspecialchars($stock['name']); ?> (<?php echo htmlspecialchars($symbol); ?>)
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if ($error): ?>
                        <div class="alert alert-danger-custom">
                            <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success-custom">
                            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card-custom bg-secondary-custom">
                                <div class="card-body-custom text-center">
                                    <h6>Güncel Fiyat</h6>
                                    <h3 class="text-primary">$<?php echo number_format($stock['current_price'], 2); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-custom bg-secondary-custom">
                                <div class="card-body-custom text-center">
                                    <h6>Mevcut Nakit</h6>
                                    <h3 class="text-success">$<?php echo number_format($currentUser['money'], 0, ',', '.'); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="">
                        <?php echo csrfTokenField(); ?>

                        <div class="mb-3">
                            <label for="quantity" class="form-label-custom">
                                <i class="bi bi-hash"></i> Miktar
                            </label>
                            <input
                                type="number"
                                class="form-control form-control-custom"
                                id="quantity"
                                name="quantity"
                                min="1"
                                max="<?php echo floor($currentUser['money'] / ($stock['current_price'] * 1.02)); ?>"
                                placeholder="Kaç adet almak istiyorsunuz?"
                                required
                                value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>"
                            >
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="form-label-custom">Birim Fiyat</label>
                                    <p class="form-control-plaintext">$<?php echo number_format($stock['price'], 2); ?></p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label-custom">Toplam Tutar</label>
                                    <p class="form-control-plaintext" id="totalCost">$0.00</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success-custom">
                                <i class="bi bi-check-circle"></i> Satın Al
                            </button>
                            <a href="stock_market.php" class="btn btn-secondary-custom">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toplam tutarı hesapla
document.getElementById('quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 0;
    const price = <?php echo $stock['current_price']; ?>;
    const fee = (quantity * price) * 0.02;
    const total = (quantity * price) + fee;
    document.getElementById('totalCost').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
});
</script>

<?php include 'includes/footer.php'; ?>