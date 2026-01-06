<?php
/**
 * MTA:SA UCP - Profil Sayfası
 * Detaylı kullanıcı profil bilgileri
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Profilim - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Kullanıcının detaylı istatistiklerini çek
$stats = [
    'vehicles_count' => 0,
    'interiors_count' => 0,
    'companies_count' => 0,
    'total_tickets' => 0,
    'open_tickets' => 0
];

if ($dbConnected) {
    try {
        $vehiclesCount = $db->fetchOne("SELECT COUNT(*) as count FROM vehicles WHERE owner = ?", [$currentUser['id']]);
        $stats['vehicles_count'] = isset($vehiclesCount['count']) ? $vehiclesCount['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $interiorsCount = $db->fetchOne("SELECT COUNT(*) as count FROM interiors WHERE owner = ?", [$currentUser['id']]);
        $stats['interiors_count'] = isset($interiorsCount['count']) ? $interiorsCount['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $companiesCount = $db->fetchOne("SELECT COUNT(*) as count FROM companies WHERE owner = ?", [$currentUser['id']]);
        $stats['companies_count'] = isset($companiesCount['count']) ? $companiesCount['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $totalTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets WHERE user_id = ?", [$currentUser['id']]);
        $stats['total_tickets'] = isset($totalTickets['count']) ? $totalTickets['count'] : 0;
    } catch (Exception $e) {}
    
    try {
        $openTickets = $db->fetchOne("SELECT COUNT(*) as count FROM tickets WHERE user_id = ? AND status = 'open'", [$currentUser['id']]);
        $stats['open_tickets'] = isset($openTickets['count']) ? $openTickets['count'] : 0;
    } catch (Exception $e) {}
}

// Meslek bilgisi
$jobName = 'İşsiz';
if ($dbConnected && !empty($currentUser['job'])) {
    try {
        $job = $db->fetchOne("SELECT name FROM jobs WHERE id = ? LIMIT 1", [$currentUser['job']]);
        if ($job) {
            $jobName = $job['name'];
        }
    } catch (Exception $e) {}
}

// Son ticketlar
$recentTickets = array();
if ($dbConnected) {
    try {
        $recentTickets = $db->fetchAll(
            "SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC LIMIT 5",
            [$currentUser['id']]
        );
    } catch (Exception $e) {
        $recentTickets = array();
    }
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-person-circle"></i> Profilim</h2>
            <p class="text-secondary">Detaylı hesap bilgileriniz ve istatistikleriniz</p>
        </div>
    </div>

    <!-- Kullanıcı Bilgileri -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge"></i> Hesap Bilgileri
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-fill me-3 text-primary" style="font-size: 2rem;"></i>
                                <div>
                                    <div class="text-secondary small">Kullanıcı Adı</div>
                                    <div class="fw-bold fs-5"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-123 me-3 text-primary" style="font-size: 2rem;"></i>
                                <div>
                                    <div class="text-secondary small">Kullanıcı ID</div>
                                    <div class="fw-bold fs-5">#<?php echo $currentUser['id']; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-palette-fill me-3 text-primary" style="font-size: 2rem;"></i>
                                <div>
                                    <div class="text-secondary small">Skin ID</div>
                                    <div class="fw-bold fs-5"><?php echo isset($currentUser['skin']) ? $currentUser['skin'] : 'Belirtilmemiş'; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-briefcase me-3 text-primary" style="font-size: 2rem;"></i>
                                <div>
                                    <div class="text-secondary small">Meslek</div>
                                    <div class="fw-bold fs-5"><?php echo htmlspecialchars($jobName); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-shield-check me-3 text-primary" style="font-size: 2rem;"></i>
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
        <div class="col-md-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-cash-stack"></i> Finansal Durum
                    </h5>
                </div>
                <div class="card-body-custom text-center">
                    <div class="mb-3">
                        <div class="text-secondary small">Nakit Para</div>
                        <div class="fw-bold fs-4 text-success">
                            $<?php echo number_format($currentUser['money'], 0, ',', '.'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="text-secondary small">Banka Parası</div>
                        <div class="fw-bold fs-4 text-primary">
                            $<?php echo number_format($currentUser['bankmoney'], 0, ',', '.'); ?>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="text-secondary small">Toplam Para</div>
                        <div class="fw-bold fs-3" style="color: var(--accent-primary);">
                            $<?php echo number_format($currentUser['money'] + $currentUser['bankmoney'], 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up"></i> İstatistiklerim
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="text-center p-3" style="background: var(--bg-tertiary); border-radius: 10px;">
                                <i class="bi bi-car-front" style="font-size: 2rem; color: var(--accent-primary);"></i>
                                <div class="fw-bold fs-4 mt-2"><?php echo $stats['vehicles_count']; ?></div>
                                <div class="text-secondary small">Araç</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="text-center p-3" style="background: var(--bg-tertiary); border-radius: 10px;">
                                <i class="bi bi-house-door" style="font-size: 2rem; color: var(--accent-primary);"></i>
                                <div class="fw-bold fs-4 mt-2"><?php echo $stats['interiors_count']; ?></div>
                                <div class="text-secondary small">Ev</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="text-center p-3" style="background: var(--bg-tertiary); border-radius: 10px;">
                                <i class="bi bi-building" style="font-size: 2rem; color: var(--accent-primary);"></i>
                                <div class="fw-bold fs-4 mt-2"><?php echo $stats['companies_count']; ?></div>
                                <div class="text-secondary small">Şirket</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="text-center p-3" style="background: var(--bg-tertiary); border-radius: 10px;">
                                <i class="bi bi-ticket-perforated" style="font-size: 2rem; color: var(--accent-primary);"></i>
                                <div class="fw-bold fs-4 mt-2"><?php echo $stats['total_tickets']; ?></div>
                                <div class="text-secondary small">Ticket</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Ticketlar -->
    <?php if (!empty($recentTickets)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Son Destek Taleplerim
                    </h5>
                </div>
                <div class="card-body-custom">
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Konu</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentTickets as $ticket): ?>
                                    <tr>
                                        <td>#<?php echo $ticket['id']; ?></td>
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
                                            <a href="ticket_view.php?id=<?php echo $ticket['id']; ?>" class="btn btn-custom btn-sm">
                                                <i class="bi bi-eye"></i> Görüntüle
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="tickets.php" class="btn btn-custom">
                            <i class="bi bi-arrow-right"></i> Tüm Ticketları Görüntüle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

