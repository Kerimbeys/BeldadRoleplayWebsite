<?php
/**
 * Beldad Roleplay - Borsa Sistemi
 * Hisse senedi alım-satım platformu
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Borsa - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$stocks = array();
$userPortfolio = array();
$portfolioValue = 0;
$totalInvested = 0;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

if ($dbConnected) {
    try {
        // Hisse senetlerini çek
        $stocks = $db->fetchAll("SELECT * FROM stocks WHERE is_active = 1 ORDER BY symbol");

        // Kullanıcının portföyünü çek
        $userPortfolio = $db->fetchAll(
            "SELECT us.*, s.name, s.current_price,
                    (us.quantity * s.current_price) as current_value,
                    ((us.quantity * s.current_price) - us.total_invested) as profit_loss
             FROM user_stocks us
             JOIN stocks s ON us.stock_symbol = s.symbol
             WHERE us.user_id = ? AND us.quantity > 0
             ORDER BY s.symbol",
            [$currentUser['id']]
        );

        // Portföy değerini hesapla
        foreach ($userPortfolio as $stock) {
            $portfolioValue += $stock['current_value'];
            $totalInvested += $stock['total_invested'];
        }

    } catch (Exception $e) {
        $stocks = array();
        $userPortfolio = array();
        $portfolioValue = 0;
        $totalInvested = 0;
    }
} else {
    // Veritabanı bağlantısı yoksa demo verileri göster
    $stocks = [
        ['id' => 1, 'symbol' => 'BELDAD', 'name' => 'Beldad Corporation', 'current_price' => 250.00, 'previous_price' => 245.00, 'change_percent' => 2.04, 'volume' => 1250, 'market_cap' => 2500000, 'is_active' => 1],
        ['id' => 2, 'symbol' => 'MTA', 'name' => 'Multi Theft Auto Inc.', 'current_price' => 450.00, 'previous_price' => 460.00, 'change_percent' => -2.17, 'volume' => 890, 'market_cap' => 4500000, 'is_active' => 1],
        ['id' => 3, 'symbol' => 'RP', 'name' => 'Roleplay Ventures', 'current_price' => 125.00, 'previous_price' => 120.00, 'change_percent' => 4.17, 'volume' => 2100, 'market_cap' => 1250000, 'is_active' => 1],
        ['id' => 4, 'symbol' => 'GAME', 'name' => 'Gaming Solutions Ltd.', 'current_price' => 375.00, 'previous_price' => 380.00, 'change_percent' => -1.32, 'volume' => 675, 'market_cap' => 3750000, 'is_active' => 1],
        ['id' => 5, 'symbol' => 'TECH', 'name' => 'Tech Innovations', 'current_price' => 650.00, 'previous_price' => 640.00, 'change_percent' => 1.56, 'volume' => 432, 'market_cap' => 6500000, 'is_active' => 1],
    ];
    $userPortfolio = array();
    $portfolioValue = 0;
    $totalInvested = 0;
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4 holographic">
                <i class="bi bi-graph-up"></i> Borsa Sistemi
            </h2>
            <p class="text-center text-secondary">
                Hisse senedi alım-satım işlemlerinizi buradan yönetebilirsiniz.
            </p>
        </div>
    </div>

    <!-- Portföy Özeti -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <h5 class="card-title-custom">
                        <i class="bi bi-wallet2"></i> Portföy Değeri
                    </h5>
                    <h3 class="text-success">$<?php echo number_format($portfolioValue, 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <h5 class="card-title-custom">
                        <i class="bi bi-cash"></i> Nakit
                    </h5>
                    <h3 class="text-primary">$<?php echo number_format($currentUser['money'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <h5 class="card-title-custom">
                        <i class="bi bi-bank"></i> Banka
                    </h5>
                    <h3 class="text-info">$<?php echo number_format($currentUser['bankmoney'], 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom">
                <div class="card-body-custom text-center">
                    <h5 class="card-title-custom">
                        <i class="bi bi-graph-up"></i> Toplam Varlık
                    </h5>
                    <h3 class="text-warning">$<?php echo number_format($currentUser['money'] + $currentUser['bankmoney'] + $portfolioValue, 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Hisse Senetleri Tablosu -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-bar-chart"></i> Hisse Senetleri
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Sembol</th>
                                    <th>Şirket Adı</th>
                                    <th>Fiyat</th>
                                    <th>Değişim</th>
                                    <th>Hacim</th>
                                    <th>Piyasa Değeri</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stocks as $stock): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($stock['symbol']); ?></strong>
                                        </td>
                                        <td><?php echo htmlspecialchars($stock['name']); ?></td>
                                        <td>$<?php echo number_format($stock['current_price'], 2); ?></td>
                                        <td>
                                            <span class="<?php echo $stock['change_percent'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                <i class="bi <?php echo $stock['change_percent'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                                <?php echo ($stock['change_percent'] >= 0 ? '+' : '') . number_format($stock['change_percent'], 2); ?>%
                                            </span>
                                        </td>
                                        <td><?php echo number_format($stock['volume']); ?></td>
                                        <td>$<?php echo number_format($stock['market_cap'], 0, ',', '.'); ?>M</td>
                                        <td>
                                            <a href="buy_stock.php?symbol=<?php echo urlencode($stock['symbol']); ?>" class="btn btn-success-custom btn-sm">
                                                <i class="bi bi-plus-circle"></i> Al
                                            </a>
                                            <a href="sell_stock.php?symbol=<?php echo urlencode($stock['symbol']); ?>" class="btn btn-danger-custom btn-sm">
                                                <i class="bi bi-dash-circle"></i> Sat
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portföyüm -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart"></i> Portföyüm
                        <?php if (!empty($userPortfolio)): ?>
                            <span class="badge bg-primary ms-2"><?php echo count($userPortfolio); ?> Hisse</span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($userPortfolio)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-pie-chart" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Henüz hiç hisse senedi satın almadınız.</p>
                            <p class="text-muted">Yukarıdaki listeden hisse alarak portföyünüzü oluşturmaya başlayın.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Sembol</th>
                                        <th>Şirket</th>
                                        <th>Adet</th>
                                        <th>Ortalama Maliyet</th>
                                        <th>Güncel Fiyat</th>
                                        <th>Güncel Değer</th>
                                        <th>Kar/Zarar</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($userPortfolio as $stock): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($stock['stock_symbol']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($stock['name']); ?></td>
                                            <td><?php echo number_format($stock['quantity']); ?></td>
                                            <td>$<?php echo number_format($stock['average_cost'], 2); ?></td>
                                            <td>$<?php echo number_format($stock['current_price'], 2); ?></td>
                                            <td class="fw-bold">$<?php echo number_format($stock['current_value'], 2); ?></td>
                                            <td>
                                                <span class="<?php echo $stock['profit_loss'] >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    <i class="bi <?php echo $stock['profit_loss'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down'; ?>"></i>
                                                    $<?php echo number_format(abs($stock['profit_loss']), 2); ?>
                                                    (<?php echo $stock['profit_loss'] >= 0 ? '+' : ''; ?><?php echo number_format(($stock['profit_loss'] / $stock['total_invested']) * 100, 2); ?>%)
                                                </span>
                                            </td>
                                            <td>
                                                <a href="sell_stock.php?symbol=<?php echo urlencode($stock['stock_symbol']); ?>" class="btn btn-danger-custom btn-sm">
                                                    <i class="bi bi-dash-circle"></i> Sat
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <th colspan="5">TOPLAM</th>
                                        <th>$<?php echo number_format($portfolioValue, 2); ?></th>
                                        <th class="<?php echo ($portfolioValue - $totalInvested) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            $<?php echo number_format(abs($portfolioValue - $totalInvested), 2); ?>
                                            (<?php echo ($portfolioValue - $totalInvested) >= 0 ? '+' : ''; ?><?php echo $totalInvested > 0 ? number_format((($portfolioValue - $totalInvested) / $totalInvested) * 100, 2) : '0.00'; ?>%)
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>