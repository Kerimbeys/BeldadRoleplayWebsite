<?php
/**
 * Beldad Roleplay - Ticket/Destek Sistemi
 */

require_once 'session.php';
requireLogin();

$pageTitle = 'Destek Talepleri - ' . SITE_NAME;
$currentUser = getCurrentUser();

$db = null;
$dbConnected = false;
$error = '';
$success = '';
$tickets = array();

try {
    $db = Database::getInstance();
    $dbConnected = $db->isConnected();
} catch (Exception $e) {
    $dbConnected = false;
}

// Yeni ticket oluşturma
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_ticket') {
    $subject = trim(isset($_POST['subject']) ? $_POST['subject'] : '');
    $message = trim(isset($_POST['message']) ? $_POST['message'] : '');
    
    if (empty($subject) || empty($message)) {
        $error = 'Konu ve mesaj alanları zorunludur.';
    } elseif (!$dbConnected) {
        $error = 'Veritabanı bağlantısı yok. Ticket oluşturulamıyor.';
    } else {
        try {
            $db->query(
                "INSERT INTO tickets (user_id, username, subject, message, status) VALUES (?, ?, ?, ?, 'open')",
                [$currentUser['id'], $currentUser['username'], $subject, $message]
            );
            $success = 'Destek talebiniz başarıyla oluşturuldu. En kısa sürede yanıtlanacaktır.';
        } catch (Exception $e) {
            $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
            error_log("Ticket oluşturma hatası: " . $e->getMessage());
        }
    }
}

// Kullanıcının ticketlarını çek
if ($dbConnected) {
    try {
        $tickets = $db->fetchAll(
            "SELECT * FROM tickets WHERE user_id = ? ORDER BY created_at DESC",
            [$currentUser['id']]
        );
    } catch (Exception $e) {
        $tickets = array();
    }
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-ticket-perforated"></i> Destek Talepleri</h2>
            <p class="text-secondary">Yeni destek talebi oluşturabilir veya mevcut taleplerinizi görüntüleyebilirsiniz.</p>
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

    <!-- Yeni Ticket Oluşturma Formu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Yeni Destek Talebi Oluştur
                    </h5>
                </div>
                <div class="card-body-custom">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="create_ticket">
                        
                        <div class="mb-3">
                            <label for="subject" class="form-label-custom">
                                <i class="bi bi-tag"></i> Konu Başlığı
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-custom" 
                                id="subject" 
                                name="subject" 
                                placeholder="Destek talebinizin konusunu yazın"
                                required
                                maxlength="255"
                                value="<?php echo htmlspecialchars(isset($_POST['subject']) ? $_POST['subject'] : ''); ?>"
                            >
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label-custom">
                                <i class="bi bi-chat-text"></i> Mesaj
                            </label>
                            <textarea 
                                class="form-control form-control-custom" 
                                id="message" 
                                name="message" 
                                rows="5"
                                placeholder="Sorununuzu veya talebinizi detaylı bir şekilde açıklayın"
                                required
                            ><?php echo htmlspecialchars(isset($_POST['message']) ? $_POST['message'] : ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-custom">
                            <i class="bi bi-send"></i> Talebi Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mevcut Ticketlar -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul"></i> Mevcut Destek Taleplerim
                    </h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($tickets)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Henüz destek talebiniz bulunmamaktadır.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Konu</th>
                                        <th>Durum</th>
                                        <th>Oluşturulma</th>
                                        <th>Güncelleme</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
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
                                                <?php if ($ticket['updated_at']): ?>
                                                    <?php echo date('d.m.Y H:i', strtotime($ticket['updated_at'])); ?>
                                                <?php else: ?>
                                                    <span class="text-secondary">-</span>
                                                <?php endif; ?>
                                            </td>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

