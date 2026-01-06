<?php
/**
 * Beldad Roleplay - Admin Kullanıcı Yönetimi
 */

require_once '../session.php';
requireAdmin();

$pageTitle = 'Kullanıcı Yönetimi - Admin Paneli';
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

// Kullanıcı düzenleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_user') {
    if (!$dbConnected) {
        $error = 'Veritabanı bağlantısı yok. Kullanıcı güncellenemiyor.';
    } else {
        $userId = (int)(isset($_POST['user_id']) ? $_POST['user_id'] : 0);
        $money = (int)(isset($_POST['money']) ? $_POST['money'] : 0);
        $bankmoney = (int)(isset($_POST['bankmoney']) ? $_POST['bankmoney'] : 0);
        $admin = (int)(isset($_POST['admin']) ? $_POST['admin'] : 0);
        
        if ($userId > 0) {
            try {
                $db->query(
                    "UPDATE accounts SET money = ?, bankmoney = ?, admin = ? WHERE id = ?",
                    [$money, $bankmoney, $admin, $userId]
                );
                $success = 'Kullanıcı bilgileri başarıyla güncellendi.';
            } catch (Exception $e) {
                $error = 'Bir hata oluştu. Lütfen tekrar deneyin.';
                error_log("Kullanıcı güncelleme hatası: " . $e->getMessage());
            }
        }
    }
}

// Arama
$search = isset($_GET['search']) ? $_GET['search'] : '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = "WHERE username LIKE ? OR id = ?";
    $params = ["%$search%", (int)$search];
}

// Sayfalama
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 20;
$offset = ($page - 1) * $perPage;

// Toplam kayıt sayısı
$totalCount = $db->fetchOne(
    "SELECT COUNT(*) as count FROM accounts " . $whereClause,
    $params
);
$totalPages = ceil((isset($totalCount['count']) ? $totalCount['count'] : 0) / $perPage);

// Kullanıcıları çek
$users = $db->fetchAll(
    "SELECT id, username, money, bankmoney, skin, job, admin FROM accounts " . $whereClause . " ORDER BY id DESC LIMIT ? OFFSET ?",
    array_merge($params, [$perPage, $offset])
);

include '../includes/header.php';

// Veritabanı bağlantısı uyarısı
if (!$dbConnected) {
    echo '<div class="container mt-4"><div class="alert alert-warning-custom alert-custom">
        <i class="bi bi-exclamation-triangle"></i> 
        <strong>Uyarı:</strong> Veritabanı bağlantısı yok. Kullanıcılar görüntülenemiyor.
    </div></div>';
}
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="index.php" class="btn btn-custom btn-sm mb-3">
                <i class="bi bi-arrow-left"></i> Admin Paneli
            </a>
            <h2><i class="bi bi-people"></i> Kullanıcı Yönetimi</h2>
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

    <!-- Arama -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-body-custom">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input 
                                type="text" 
                                class="form-control form-control-custom" 
                                name="search" 
                                placeholder="Kullanıcı adı veya ID ile ara..."
                                value="<?php echo htmlspecialchars($search); ?>"
                            >
                            <button class="btn btn-custom" type="submit">
                                <i class="bi bi-search"></i> Ara
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="users.php" class="btn btn-danger-custom">
                                    <i class="bi bi-x"></i> Temizle
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Kullanıcı Listesi -->
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0">Kullanıcı Listesi (Toplam: <?php echo number_format(isset($totalCount['count']) ? $totalCount['count'] : 0); ?>)</h5>
                </div>
                <div class="card-body-custom">
                    <?php if (empty($users)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-person-x" style="font-size: 3rem; color: var(--text-secondary);"></i>
                            <p class="mt-3 text-secondary">Kullanıcı bulunamadı.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kullanıcı Adı</th>
                                        <th>Nakit</th>
                                        <th>Banka</th>
                                        <th>Skin</th>
                                        <th>Admin</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td>#<?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td>$<?php echo number_format($user['money'], 0, ',', '.'); ?></td>
                                            <td>$<?php echo number_format($user['bankmoney'], 0, ',', '.'); ?></td>
                                            <td><?php echo isset($user['skin']) ? $user['skin'] : '-'; ?></td>
                                            <td>
                                                <?php if ($user['admin'] > 0): ?>
                                                    <span class="badge badge-success-custom">Admin (<?php echo $user['admin']; ?>)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-primary-custom">Oyuncu</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-custom btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editModal<?php echo $user['id']; ?>"
                                                >
                                                    <i class="bi bi-pencil"></i> Düzenle
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Düzenleme Modal -->
                                        <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                                                    <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                                                        <h5 class="modal-title">Kullanıcı Düzenle: <?php echo htmlspecialchars($user['username']); ?></h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="action" value="edit_user">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Nakit Para</label>
                                                                <input 
                                                                    type="number" 
                                                                    class="form-control form-control-custom" 
                                                                    name="money" 
                                                                    value="<?php echo $user['money']; ?>"
                                                                    required
                                                                >
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Banka Parası</label>
                                                                <input 
                                                                    type="number" 
                                                                    class="form-control form-control-custom" 
                                                                    name="bankmoney" 
                                                                    value="<?php echo $user['bankmoney']; ?>"
                                                                    required
                                                                >
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label-custom">Admin Seviyesi</label>
                                                                <input 
                                                                    type="number" 
                                                                    class="form-control form-control-custom" 
                                                                    name="admin" 
                                                                    value="<?php echo $user['admin']; ?>"
                                                                    min="0"
                                                                    required
                                                                >
                                                                <small class="text-secondary">0 = Oyuncu, 1+ = Admin</small>
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

                        <!-- Sayfalama -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Sayfa navigasyonu" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Önceki</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Sonraki</a>
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

