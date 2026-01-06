<?php
/**
 * Dropdown Test Page
 * For testing dropdown menu functionality
 */

// Bypass login for testing
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'TestUser';

$pageTitle = 'Dropdown Test - Beldad Roleplay';
?>

<?php include 'includes/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card-custom">
                <div class="card-header">
                    <h2><i class="bi bi-controller"></i> Dropdown Test</h2>
                </div>
                <div class="card-body">
                    <p>Bu sayfa dropdown menülerinin çalışıp çalışmadığını test etmek için oluşturulmuştur.</p>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Test Talimatları:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Üst menüdeki "Malvarlığım" butonuna tıklayın</li>
                            <li>Dropdown menüsünün açılması gerekiyor</li>
                            <li>Menü öğelerine tıklayabiliyor olmalısınız</li>
                            <li>Mobil cihazda hamburger menüye dokunun</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4>Desktop Test</h4>
                            <p>Ekran genişliği > 991.98px ise Bootstrap'in native dropdown davranışı kullanılır.</p>
                        </div>
                        <div class="col-md-6">
                            <h4>Mobile Test</h4>
                            <p>Ekran genişliği ≤ 991.98px ise özel mobil davranış kullanılır.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>