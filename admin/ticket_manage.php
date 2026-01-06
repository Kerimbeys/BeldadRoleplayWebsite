<?php
/**
 * Beldad Roleplay - Admin Ticket Yönetimi (Yanıtlama)
 */

require_once '../session.php';
require_once '../includes/functions.php';
requireAdmin();

$pageTitle = 'Ticket Yönetimi - Admin Paneli';
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

// Ticket'ı çek
$ticket = null;
try {
    $ticket = $db->fetchOne(
        "SELECT * FROM tickets WHERE id = ?",
        [$ticketId]
    );
    
    if (!$ticket) {
        header('Location: tickets.php?error=ticket_not_found');
        exit();
    }
} catch (Exception $e) {
    header('Location: tickets.php?error=db_error');
    exit();
}

// Ticket yanıtlama veya kapatma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || !validateCSRFToken($_POST['csrf_token'])) {
        $error = 'Geçersiz istek. Lütfen sayfayı yenileyip tekrar deneyin.';
    } else {
        $action = isset($_POST['action']) ? $_POST['action'] : '';
        
        if ($action === 'reply') {
        $adminReply = trim(isset($_POST['admin_reply']) ? $_POST['admin_reply'] : '');
        
        if (empty($adminReply)) {
            $error = 'Yanıt mesajı boş olamaz.';
        } else {
            try {
                $db->query(
                    "UPDATE tickets SET admin_id = ?, admin_reply = ?, status = 'answered', updated_at = NOW() WHERE id = ?",
                    [$currentUser['id'], $adminReply, $ticketId]
                );
                $success = 'Ticket başarıyla yanıtlandı.';
                
                // Ticket yanıtını logla
                logAdminAction('Ticket Yanıtlandı', "Ticket ID: {$ticketId}, Kullanıcı: {$ticket['username']}", $currentUser['id']);
                
                // Ticket'ı yeniden çek
                $ticket = $db->fetchOne("SELECT * FROM tickets WHERE id = ?", [$ticketId]);
            } catch (Exception $e) {
                $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                error_log("Ticket yanıtlama hatası: " . $e->getMessage());
            }
        }
    } elseif ($action === 'close') {
        try {
            $db->query(
                "UPDATE tickets SET status = 'closed', updated_at = NOW() WHERE id = ?",
                [$ticketId]
            );
            $success = 'Ticket başarıyla kapatıldı.';
            
            // Ticket kapatmayı logla
            logAdminAction('Ticket Kapatıldı', "Ticket ID: {$ticketId}, Kullanıcı: {$ticket['username']}", $currentUser['id']);
            
            // Ticket'ı yeniden çek
            $ticket = $db->fetchOne("SELECT * FROM tickets WHERE id = ?", [$ticketId]);
        } catch (Exception $e) {
            $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
            error_log("Ticket kapatma hatası: " . $e->getMessage());
        }
    }
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="tickets.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
            <h2><i class="bi bi-ticket-perforated"></i> Ticket Yönetimi #<?php echo $ticket['id']; ?></h2>
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
                            <span class="text-secondary">Kullanıcı:</span>
                            <span class="fw-bold"><?php echo htmlspecialchars($ticket['username']); ?> (ID: <?php echo $ticket['user_id']; ?>)</span>
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
                        <h6 class="text-secondary mb-2">Kullanıcı Mesajı:</h6>
                        <div class="p-3 bg-dark rounded" style="background: var(--bg-tertiary) !important;">
                            <?php echo nl2br(htmlspecialchars($ticket['message'])); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Yanıtı (Eğer varsa) -->
    <?php if (!empty($ticket['admin_reply'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card-custom" style="border-color: var(--accent-primary);">
                    <div class="card-header-custom" style="background: rgba(0, 212, 255, 0.1);">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check"></i> Admin Yanıtı
                            <small class="text-secondary">(Admin ID: <?php echo $ticket['admin_id']; ?>)</small>
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
    <?php endif; ?>

    <!-- Yanıtlama Formu (Sadece açık ticketlar için) -->
    <?php if ($ticket['status'] === 'open'): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-custom">
                    <div class="card-header-custom">
                        <h5 class="mb-0">
                            <i class="bi bi-reply"></i> Ticket Yanıtla
                        </h5>
                    </div>
                    <div class="card-body-custom">
                        <form method="POST" action="">
                            <?php echo csrfTokenField(); ?>
                            <input type="hidden" name="action" value="reply">
                            
                            <div class="mb-3">
                                <label for="admin_reply" class="form-label-custom">
                                    <i class="bi bi-chat-text"></i> Yanıt Mesajı
                                </label>
                                <textarea 
                                    class="form-control form-control-custom" 
                                    id="admin_reply" 
                                    name="admin_reply" 
                                    rows="6"
                                    placeholder="Kullanıcıya yanıtınızı yazın"
                                    required
                                ><?php echo htmlspecialchars(isset($_POST['admin_reply']) ? $_POST['admin_reply'] : ''); ?></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success-custom">
                                    <i class="bi bi-send"></i> Yanıtla
                                </button>
                            </div>
                        </form>
                        
                        <form method="POST" action="" class="mt-2" onsubmit="return confirm('Ticket\'ı kapatmak istediğinize emin misiniz?');">
                            <?php echo csrfTokenField(); ?>
                            <input type="hidden" name="action" value="close">
                            <button type="submit" class="btn btn-danger-custom">
                                <i class="bi bi-x-circle"></i> Ticket'ı Kapat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($ticket['status'] === 'answered'): ?>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info-custom alert-custom">
                    <i class="bi bi-info-circle"></i> 
                    Bu ticket zaten yanıtlanmış. Gerekirse tekrar düzenleyebilirsiniz.
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

