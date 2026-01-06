<?php
/**
 * MTA:SA UCP - Hata Sayfası
 */

$errorCode = isset($_GET['code']) ? $_GET['code'] : '404';
$errorMessage = isset($_GET['message']) ? $_GET['message'] : 'Sayfa bulunamadı';

$errorMessages = [
    '404' => 'Sayfa Bulunamadı',
    '403' => 'Erişim Reddedildi',
    '500' => 'Sunucu Hatası',
    'session_expired' => 'Oturum Süresi Doldu'
];

$errorTitle = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $errorMessage;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $errorTitle; ?> - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-custom text-center">
                    <div class="card-body-custom py-5">
                        <i class="bi bi-exclamation-triangle" style="font-size: 5rem; color: var(--accent-warning);"></i>
                        <h1 class="mt-4" style="color: var(--accent-primary);"><?php echo $errorCode; ?></h1>
                        <h3 class="mt-3"><?php echo htmlspecialchars($errorTitle); ?></h3>
                        <p class="text-secondary mt-3"><?php echo htmlspecialchars($errorMessage); ?></p>
                        <div class="mt-4">
                            <a href="index.php" class="btn btn-custom me-2">
                                <i class="bi bi-house-door"></i> Ana Sayfa
                            </a>
                            <a href="javascript:history.back()" class="btn btn-danger-custom">
                                <i class="bi bi-arrow-left"></i> Geri Dön
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

