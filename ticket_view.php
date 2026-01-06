<?php
/**
 * Beldad Roleplay - Ticket Detay Görüntüleme
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Ticket Detayı - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$ticket = null;

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Ticket ID kontrolü
$ticketId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($ticketId <= 0) {
    header('Location: tickets.php?error=invalid_ticket');
    exit();
}

if (!$dbConnected) {
    header('Location: tickets.php?error=db_connection');
    exit();
}

// Ticket'ı çek (sadece kendi ticket'ı)
try {
    $ticket = $db->fetchOne(
        "SELECT * FROM tickets WHERE id = ? AND user_id = ?",
        [$ticketId, $currentUser['id']]
    );
    
    if (!$ticket) {
        header('Location: tickets.php?error=ticket_not_found');
        exit();
    }
} catch (Exception $e) {
    header('Location: tickets.php?error=db_error');
    exit();
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="tickets.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
            <h2><i class="bi bi-ticket-perforated"></i> Ticket #<?php echo $ticket['id']; ?></h2>
        </div>
    </div>

    <!-- Ticket Bilgileri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo htmlspecialchars($ticket['subject']); ?></h5>
                    <span class="badge 
                        <?php 
                        echo $ticket['status'] === 'open' ? 'badge-warning-custom' : 
                            ($ticket['status'] === 'answered' ? 'badge-success-custom' : 'badge-primary-custom'); 
                        ?>
                    ">
                        <?php
                        echo $ticket['status'] === 'open' ? 'Açık' : 
                            ($ticket['status'] === 'answered' ? 'Yanıtlandı' : 'Kapatıldı');
                        ?>
                    </span>
                </div>
                <div class="card-body-custom">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Oluşturan:</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($ticket['username']); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Oluşturulma Tarihi:</span>
                            <span><?php echo date('d.m.Y H:i:s', strtotime($ticket['created_at'])); ?></span>
                        </div>
                        <?php if ($ticket['updated_at']): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Son Güncelleme:</span>
                            <span><?php echo date('d.m.Y H:i:s', strtotime($ticket['updated_at'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div>
                        <h6 class="text-secondary mb-2">Mesajınız:</h6>
                        <div class="p-3 bg-dark rounded" style="background: var(--bg-tertiary) !important;">
                            <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Yanıtı -->
    <?php if ($ticket['status'] === 'answered' && !empty($ticket['admin_reply'])): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-custom" style="border-color: var(--accent-primary);">
                    <div class="card-header-custom" style="background: rgba(0, 212, 255, 0.1);">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check"></i> Admin Yanıtı
                            <?php if ($ticket['admin_id']): ?>
                                <small class="text-secondary">(Admin ID: <?php echo $ticket['admin_id']; ?>)</small>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="p-3 bg-dark rounded" style="background: rgba(0, 212, 255, 0.05) !important;">
                            <?php echo nl2br(htmlspecialchars($ticket['admin_reply'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($ticket['status'] === 'open'): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info-custom alert-custom">
                    <i class="bi bi-info-circle"></i> 
                    Ticket'ınız henüz yanıtlanmadı. Lütfen bekleyin, en kısa sürede yanıtlanacaktır.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

