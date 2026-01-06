<?php
/**
 * Beldad Roleplay - Hisse Satım
 */

require_once 'session.php';
require_once 'includes/functions.php';
requireLogin();

$pageTitle = 'Hisse Sat - ' . SITE_NAME;
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
$userStock = null;

if ($dbConnected) {
    try {
        $stock = $db->fetchOne("SELECT * FROM stocks WHERE symbol = ? AND is_active = 1", [$symbol]);
        if (!$stock) {
            header('Location: stock_market.php?error=stock_not_found');
            exit();
        }

        // Kullanıcının bu hisseyi kontrol et
        $userStock = $db->fetchOne(
            "SELECT * FROM user_stocks WHERE user_id = ? AND stock_symbol = ? AND quantity > 0",
            [$currentUser['id'], $symbol]
        );

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
    $userStock = ['quantity' => rand(0, 10), 'average_cost' => $stock['current_price'] * 0.95, 'total_invested' => rand(0, 10) * $stock['current_price'] * 0.95]; // Demo portföy
}

// Kullanıcı bu hisseye sahip değilse
if (!$userStock || $userStock['quantity'] <= 0) {
    $ownedShares = 0;
} else {
    $ownedShares = $userStock['quantity'];
}

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ownedShares > 0) {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Geçersiz istek.';
    } else {
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        $totalValue = $quantity * $stock['current_price'];
        $fee = $totalValue * 0.02; // %2 komisyon
        $netValue = $totalValue - $fee;

        if ($quantity <= 0) {
            $error = 'Geçerli bir miktar girin.';
        } elseif ($quantity > $ownedShares) {
            $error = 'Yeterli hisse senedi yok. Sahip olduğunuz: ' . $ownedShares . ' adet';
        } else {
            if ($dbConnected) {
                try {
                    $db->beginTransaction();

                    // Kullanıcının parasını artır
                    $db->query(
                        "UPDATE accounts SET money = money + ? WHERE id = ?",
                        [$netValue, $currentUser['id']]
                    );

                    // Kullanıcının hisse portföyünü güncelle
                    $newQuantity = $ownedShares - $quantity;
                    $soldValue = $quantity * $userStock['average_cost'];
                    $newTotalInvested = $userStock['total_invested'] - $soldValue;

                    if ($newQuantity > 0) {
                        // Hala hisse kaldıysa güncelle
                        $newAverageCost = $newTotalInvested / $newQuantity;
                        $db->query(
                            "UPDATE user_stocks SET quantity = ?, average_cost = ?, total_invested = ?, updated_at = NOW() WHERE id = ?",
                            [$newQuantity, $newAverageCost, $newTotalInvested, $userStock['id']]
                        );
                    } else {
                        // Tüm hisseler satıldıysa sil
                        $db->query("DELETE FROM user_stocks WHERE id = ?", [$userStock['id']]);
                    }

                    // İşlem geçmişine ekle
                    $db->query(
                        "INSERT INTO stock_transactions (user_id, stock_symbol, transaction_type, quantity, price_per_share, total_amount, fee, net_amount) VALUES (?, ?, 'sell', ?, ?, ?, ?, ?)",
                        [$currentUser['id'], $symbol, $quantity, $stock['current_price'], $totalValue, $fee, $netValue]
                    );

                    // Hisse hacmini güncelle
                    $db->query(
                        "UPDATE stocks SET volume = volume + ? WHERE symbol = ?",
                        [$quantity, $symbol]
                    );

                    $db->commit();
                    $success = $quantity . ' adet ' . $stock['name'] . ' hissesi başarıyla satıldı. Net gelir: $' . number_format($netValue, 0, ',', '.') . ' (Komisyon: $' . number_format($fee, 2) . ')';

                    // Güncel bilgileri yeniden çek
                    $ownedShares = $newQuantity;
                    $currentUser = getCurrentUser();

                } catch (Exception $e) {
                    $db->rollback();
                    $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                    error_log("Hisse satım hatası: " . $e->getMessage());
                }
            } else {
                // Demo modu - sadece başarı mesajı göster
                $success = $quantity . ' adet ' . $stock['name'] . ' hissesi başarıyla satıldı. Net gelir: $' . number_format($netValue, 0, ',', '.') . ' (Komisyon: $' . number_format($fee, 2) . ')';
                $ownedShares = max(0, $ownedShares - $quantity); // Demo için azalt
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
                        <i class="bi bi-dash-circle"></i> Hisse Sat: <?php echo htmlspecialchars($stock['name']); ?> (<?php echo htmlspecialchars($symbol); ?>)
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
                        <div class="col-md-4">
                            <div class="card-custom bg-secondary-custom">
                                <div class="card-body-custom text-center">
                                    <h6>Güncel Fiyat</h6>
                                    <h3 class="text-primary">$<?php echo number_format($stock['current_price'], 2); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-custom bg-secondary-custom">
                                <div class="card-body-custom text-center">
                                    <h6>Sahip Olduğunuz</h6>
                                    <h3 class="text-warning"><?php echo $ownedShares; ?> Adet</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-custom bg-secondary-custom">
                                <div class="card-body-custom text-center">
                                    <h6>Toplam Değer</h6>
                                    <h3 class="text-success">$<?php echo number_format($ownedShares * $stock['current_price'], 2); ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($ownedShares > 0): ?>
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
                                    max="<?php echo $ownedShares; ?>"
                                    placeholder="Kaç adet satmak istiyorsunuz?"
                                    required
                                    value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>"
                                >
                            </div>

                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label-custom">Birim Fiyat</label>
                                        <p class="form-control-plaintext">$<?php echo number_format($stock['current_price'], 2); ?></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label-custom">Toplam Tutar</label>
                                        <p class="form-control-plaintext" id="totalValue">$0.00</p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger-custom">
                                    <i class="bi bi-check-circle"></i> Sat
                                </button>
                                <a href="stock_market.php" class="btn btn-secondary-custom">
                                    <i class="bi bi-arrow-left"></i> Geri Dön
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning-custom">
                            <i class="bi bi-exclamation-triangle"></i> Bu hisse senedinden hiç sahip değilsiniz.
                        </div>
                        <a href="stock_market.php" class="btn btn-secondary-custom">
                            <i class="bi bi-arrow-left"></i> Borsaya Dön
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($ownedShares > 0): ?>
<script>
// Toplam tutarı hesapla
document.getElementById('quantity').addEventListener('input', function() {
    const quantity = parseInt(this.value) || 0;
    const price = <?php echo $stock['current_price']; ?>;
    const fee = (quantity * price) * 0.02;
    const total = (quantity * price) - fee;
    document.getElementById('totalValue').textContent = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
});
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>