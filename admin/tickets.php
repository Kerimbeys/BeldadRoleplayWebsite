<?php
/**
 * Beldad Roleplay - Admin Ticket Yönetimi
 */

require_once '../session.php';
requireAdmin();

$pageTitle = 'Ticket Yönetimi - Admin Paneli';
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$tickets = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Tüm ticketları çek
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
$whereClause = '';
$params = [];

if ($statusFilter !== 'all') {
    $whereClause = "WHERE status = ?";
    $params[] = $statusFilter;
}

if ($dbConnected) {
    try {
        $tickets = $db->fetchAll(
            "SELECT * FROM tickets " . $whereClause . " ORDER BY created_at DESC",
            $params
        );
    } catch (Exception $e) {
        $tickets = array();
    }
}

include '../includes/header.php';

// Veritabanı bağlantısı uyarısı
if (!$dbConnected) {
    echo '<div class="container mt-4"><div class="alert alert-warning-custom alert-custom">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Uyarı:</strong> Veritabanı bağlantısı yok. Ticketlar görüntülenemiyor.
    </div></div>';
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="index.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Admin Paneli
            </a>
            <h2><i class="bi bi-ticket-perforated"></i> Ticket Yönetimi</h2>
        </div>
    </div>

    <!-- Filtreler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body-custom">
                    <div class="btn-group" role="group">
                        <?php
                        $allCount = 0;
                        $openCount = 0;
                        $answeredCount = 0;
                        $closedCount = 0;
                        
                        if ($dbConnected) {
                            try {
                                $allCount = count($db->fetchAll("SELECT id FROM tickets"));
                                $openCount = count($db->fetchAll("SELECT id FROM tickets WHERE status = 'open'"));
                                $answeredCount = count($db->fetchAll("SELECT id FROM tickets WHERE status = 'answered'"));
                                $closedCount = count($db->fetchAll("SELECT id FROM tickets WHERE status = 'closed'"));
                            } catch (Exception $e) {}
                        }
                        ?>
                        <a href="?status=all" class="btn <?php echo $statusFilter === 'all' ? 'btn-custom' : 'btn-outline-primary'; ?>">
                            Tümü (<?php echo $allCount; ?>)
                        </a>
                        <a href="?status=open" class="btn <?php echo $statusFilter === 'open' ? 'btn-custom' : 'btn-outline-warning'; ?>">
                            Açık (<?php echo $openCount; ?>)
                        </a>
                        <a href="?status=answered" class="btn <?php echo $statusFilter === 'answered' ? 'btn-custom' : 'btn-outline-success'; ?>">
                            Yanıtlandı (<?php echo $answeredCount; ?>)
                        </a>
                        <a href="?status=closed" class="btn <?php echo $statusFilter === 'closed' ? 'btn-custom' : 'btn-outline-secondary'; ?>">
                            Kapatıldı (<?php echo $closedCount; ?>)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">Ticket Listesi</h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($tickets)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Bu kategoride ticket bulunmamaktadır.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kullanıcı</th>
                                        <th>Konu</th>
                                        <th>Durum</th>
                                        <th>Oluşturulma</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
                                        <tr>
                                            <td>#<?php echo $ticket['id']; ?></td>
                                            <td><?php echo htmlspecialchars($ticket['username']); ?></td>
                                            <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                $statusText = '';
                                                switch ($ticket['status']) {
                                                    case 'open':
                                                        $statusClass = 'badge-warning-custom';
                                                        $statusText = 'Açık';
                                                        break;
                                                    case 'answered':
                                                        $statusClass = 'badge-success-custom';
                                                        $statusText = 'Yanıtlandı';
                                                        break;
                                                    case 'closed':
                                                        $statusClass = 'badge-primary-custom';
                                                        $statusText = 'Kapatıldı';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                            </td>
                                            <td><?php echo date('d.m.Y H:i', strtotime($ticket['created_at'])); ?></td>
                                            <td>
                                                <a href="ticket_manage.php?id=<?php echo $ticket['id']; ?>" class="btn btn-custom btn-sm">
                                                    <i class="bi bi-eye"></i> Yönet
                                                </a>
                                            </td>
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

