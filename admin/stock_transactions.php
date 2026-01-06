<?php
/**
 * Beldad Roleplay - Admin Borsa İşlem Geçmişi
 */

require_once '../session.php';
requireAdmin();

$pageTitle = 'Borsa İşlem Geçmişi - Admin Paneli';
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$transactions = array();
$stats = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Filtreler
$symbolFilter = isset($_GET['symbol']) ? $_GET['symbol'] : 'all';
$typeFilter = isset($_GET['type']) ? $_GET['type'] : 'all';
$dateFilter = isset($_GET['date']) ? $_GET['date'] : 'all';

// Sayfalama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 50;
$offset = ($page - 1) * $perPage;

$whereClause = '';
$params = [];

if ($symbolFilter !== 'all') {
    $whereClause .= " AND st.stock_symbol = ?";
    $params[] = $symbolFilter;
}

if ($typeFilter !== 'all') {
    $whereClause .= " AND st.transaction_type = ?";
    $params[] = $typeFilter;
}

if ($dateFilter !== 'all') {
    switch ($dateFilter) {
        case 'today':
            $whereClause .= " AND DATE(st.transaction_date) = CURDATE()";
            break;
        case 'week':
            $whereClause .= " AND st.transaction_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $whereClause .= " AND st.transaction_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
            break;
    }
}

if ($dbConnected) {
    try {
        // İşlem geçmişi
        $transactions = $db->fetchAll(
            "SELECT st.*, a.username, s.name as stock_name
             FROM stock_transactions st
             JOIN accounts a ON st.user_id = a.id
             JOIN stocks s ON st.stock_symbol = s.symbol
             WHERE 1=1 {$whereClause}
             ORDER BY st.transaction_date DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [$perPage, $offset])
        );

        // Toplam kayıt sayısı
        $totalCount = $db->fetchOne(
            "SELECT COUNT(*) as count FROM stock_transactions st WHERE 1=1 {$whereClause}",
            $params
        );
        $totalPages = ceil((isset($totalCount['count']) ? $totalCount['count'] : 0) / $perPage);

        // İstatistikler
        $stats = $db->fetchOne(
            "SELECT
                COUNT(*) as total_transactions,
                SUM(CASE WHEN transaction_type = 'buy' THEN total_amount ELSE 0 END) as total_buy_volume,
                SUM(CASE WHEN transaction_type = 'sell' THEN total_amount ELSE 0 END) as total_sell_volume,
                SUM(fee) as total_fees,
                COUNT(DISTINCT user_id) as active_traders
             FROM stock_transactions st WHERE 1=1 {$whereClause}",
            $params
        );

        // Benzersiz hisse senetleri
        $uniqueStocks = $db->fetchAll("SELECT symbol, name FROM stocks WHERE is_active = 1 ORDER BY symbol");

    } catch (Exception $e) {
        $transactions = array();
        $uniqueStocks = array();
    }
} else {
    // Demo verisi
    $demoStocks = [
        'BELDAD' => 'Beldad Corporation',
        'MTA' => 'Multi Theft Auto Inc.',
        'RP' => 'Roleplay Ventures',
        'GAME' => 'Gaming Solutions Ltd.',
        'TECH' => 'Tech Innovations'
    ];

    $uniqueStocks = array_map(function($symbol, $name) {
        return ['symbol' => $symbol, 'name' => $name];
    }, array_keys($demoStocks), $demoStocks);

    // Demo işlemler
    $demoTransactions = [
        ['id' => 1, 'user_id' => 1, 'username' => 'DemoUser1', 'stock_symbol' => 'BELDAD', 'stock_name' => 'Beldad Corporation', 'transaction_type' => 'buy', 'quantity' => 10, 'price_per_share' => 245.00, 'total_amount' => 2450.00, 'fee' => 49.00, 'net_amount' => 2401.00, 'transaction_date' => '2024-01-15 10:30:00'],
        ['id' => 2, 'user_id' => 2, 'username' => 'DemoUser2', 'stock_symbol' => 'MTA', 'stock_name' => 'Multi Theft Auto Inc.', 'transaction_type' => 'buy', 'quantity' => 5, 'price_per_share' => 455.00, 'total_amount' => 2275.00, 'fee' => 45.50, 'net_amount' => 2229.50, 'transaction_date' => '2024-01-15 11:15:00'],
        ['id' => 3, 'user_id' => 1, 'username' => 'DemoUser1', 'stock_symbol' => 'RP', 'stock_name' => 'Roleplay Ventures', 'transaction_type' => 'sell', 'quantity' => 8, 'price_per_share' => 120.00, 'total_amount' => 960.00, 'fee' => 19.20, 'net_amount' => 940.80, 'transaction_date' => '2024-01-15 14:20:00'],
        ['id' => 4, 'user_id' => 3, 'username' => 'DemoUser3', 'stock_symbol' => 'GAME', 'stock_name' => 'Gaming Solutions Ltd.', 'transaction_type' => 'buy', 'quantity' => 15, 'price_per_share' => 380.00, 'total_amount' => 5700.00, 'fee' => 114.00, 'net_amount' => 5586.00, 'transaction_date' => '2024-01-15 16:45:00'],
        ['id' => 5, 'user_id' => 2, 'username' => 'DemoUser2', 'stock_symbol' => 'TECH', 'stock_name' => 'Tech Innovations', 'transaction_type' => 'buy', 'quantity' => 3, 'price_per_share' => 645.00, 'total_amount' => 1935.00, 'fee' => 38.70, 'net_amount' => 1896.30, 'transaction_date' => '2024-01-16 09:10:00'],
    ];

    // Filtreleme uygula
    $filteredTransactions = $demoTransactions;
    if ($symbolFilter !== 'all') {
        $filteredTransactions = array_filter($filteredTransactions, function($t) use ($symbolFilter) {
            return $t['stock_symbol'] === $symbolFilter;
        });
    }
    if ($typeFilter !== 'all') {
        $filteredTransactions = array_filter($filteredTransactions, function($t) use ($typeFilter) {
            return $t['transaction_type'] === $typeFilter;
        });
    }

    // Sayfalama
    $totalPages = ceil(count($filteredTransactions) / $perPage);
    $transactions = array_slice($filteredTransactions, $offset, $perPage);

    // Demo istatistikleri
    $stats = [
        'total_transactions' => count($filteredTransactions),
        'total_buy_volume' => array_sum(array_column(array_filter($filteredTransactions, function($t) { return $t['transaction_type'] === 'buy'; }), 'total_amount')),
        'total_sell_volume' => array_sum(array_column(array_filter($filteredTransactions, function($t) { return $t['transaction_type'] === 'sell'; }), 'total_amount')),
        'total_fees' => array_sum(array_column($filteredTransactions, 'fee')),
        'active_traders' => count(array_unique(array_column($filteredTransactions, 'user_id')))
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
            <h2><i class="bi bi-receipt"></i> Borsa İşlem Geçmişi</h2>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stats['total_transactions']) ? $stats['total_transactions'] : 0); ?></div>
                <div class="stat-label">Toplam İşlem</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-value">$<?php echo number_format(isset($stats['total_buy_volume']) ? $stats['total_buy_volume'] : 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Alım Hacmi</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-graph-down"></i>
                </div>
                <div class="stat-value">$<?php echo number_format(isset($stats['total_sell_volume']) ? $stats['total_sell_volume'] : 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Satım Hacmi</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-value"><?php echo number_format(isset($stats['active_traders']) ? $stats['active_traders'] : 0); ?></div>
                <div class="stat-label">Aktif Trader</div>
            </div>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-funnel"></i> Filtreler
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form method="GET" action="" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label-custom">Hisse Senedi</label>
                            <select name="symbol" class="form-control form-control-custom">
                                <option value="all">Tümü</option>
                                <?php foreach (isset($uniqueStocks) ? $uniqueStocks : [] as $stock): ?>
                                    <option value="<?php echo $stock['symbol']; ?>" <?php echo $symbolFilter === $stock['symbol'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($stock['symbol'] . ' - ' . $stock['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">İşlem Tipi</label>
                            <select name="type" class="form-control form-control-custom">
                                <option value="all">Tümü</option>
                                <option value="buy" <?php echo $typeFilter === 'buy' ? 'selected' : ''; ?>>Alım</option>
                                <option value="sell" <?php echo $typeFilter === 'sell' ? 'selected' : ''; ?>>Satım</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Tarih</label>
                            <select name="date" class="form-control form-control-custom">
                                <option value="all">Tümü</option>
                                <option value="today" <?php echo $dateFilter === 'today' ? 'selected' : ''; ?>>Bugün</option>
                                <option value="week" <?php echo $dateFilter === 'week' ? 'selected' : ''; ?>>Son 7 Gün</option>
                                <option value="month" <?php echo $dateFilter === 'month' ? 'selected' : ''; ?>>Son 30 Gün</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">&nbsp;</label>
                            <button type="submit" class="btn btn-custom w-100">
                                <i class="bi bi-search"></i> Filtrele
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- İşlem Geçmişi -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">İşlem Geçmişi (<?php echo number_format(isset($totalCount['count']) ? $totalCount['count'] : 0); ?> kayıt)</h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($transactions)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-receipt" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Bu kriterlere uygun işlem bulunamadı.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kullanıcı</th>
                                        <th>Hisse</th>
                                        <th>Tip</th>
                                        <th>Adet</th>
                                        <th>Birim Fiyat</th>
                                        <th>Toplam</th>
                                        <th>Komisyon</th>
                                        <th>Net Tutar</th>
                                        <th>Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td>#<?php echo $transaction['id']; ?></td>
                                            <td><?php echo htmlspecialchars($transaction['username']); ?> (ID: <?php echo $transaction['user_id']; ?>)</td>
                                            <td><?php echo htmlspecialchars($transaction['stock_symbol'] . ' - ' . $transaction['stock_name']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $transaction['transaction_type'] === 'buy' ? 'badge-success-custom' : 'badge-danger-custom'; ?>">
                                                    <?php echo $transaction['transaction_type'] === 'buy' ? 'Alım' : 'Satım'; ?>
                                                </span>
                                            </td>
                                            <td><?php echo number_format($transaction['quantity']); ?></td>
                                            <td>$<?php echo number_format($transaction['price_per_share'], 2); ?></td>
                                            <td>$<?php echo number_format($transaction['total_amount'], 2); ?></td>
                                            <td>$<?php echo number_format($transaction['fee'], 2); ?></td>
                                            <td class="fw-bold">$<?php echo number_format($transaction['net_amount'], 2); ?></td>
                                            <td><?php echo date('d.m.Y H:i', strtotime($transaction['transaction_date'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Sayfalama -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Sayfa navigasyonu" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&symbol=<?php echo $symbolFilter; ?>&type=<?php echo $typeFilter; ?>&date=<?php echo $dateFilter; ?>">Önceki</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>&symbol=<?php echo $symbolFilter; ?>&type=<?php echo $typeFilter; ?>&date=<?php echo $dateFilter; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&symbol=<?php echo $symbolFilter; ?>&type=<?php echo $typeFilter; ?>&date=<?php echo $dateFilter; ?>">Sonraki</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>