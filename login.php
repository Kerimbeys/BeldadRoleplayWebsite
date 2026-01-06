<?php
/**
 * MTA:SA UCP - Giriş Sayfası
 * Kullanıcı giriş işlemleri
 */

require_once 'session.php';
require_once 'includes/functions.php';

// Zaten giriş yapmışsa ana sayfaya yönlendir
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } else {
        // Geçici admin kontrolü
        if (defined('TEMP_ADMIN_ENABLED') && TEMP_ADMIN_ENABLED) {
            if ($username === TEMP_ADMIN_USERNAME && $password === TEMP_ADMIN_PASSWORD) {
                // Geçici admin girişi
                $_SESSION['user_id'] = TEMP_ADMIN_ID;
                $_SESSION['username'] = TEMP_ADMIN_USERNAME;
                $_SESSION['is_admin'] = 1;
                $_SESSION['temp_admin'] = true;
                $_SESSION['login_time'] = time();
                
                // Başarılı girişi logla
                logUserAction('Başarılı Giriş (Temp Admin)', TEMP_ADMIN_ID, 'Geçici admin hesabı ile giriş yapıldı');
                
                header('Location: index.php');
                exit();
            }
        }
        
        // Normal veritabanı girişi
        try {
            $db = Database::getInstance();
            
            if ($db->isConnected()) {
                // Kullanıcıyı veritabanından bul (SQL Injection korumalı)
                $user = $db->fetchOne(
                    "SELECT id, username, password, admin FROM accounts WHERE username = ? LIMIT 1",
                    [$username]
                );
                
                if ($user) {
                    // MTA:SA genellikle MD5 hash kullanır, ama modern sistemlerde bcrypt tercih edilir
                    // Burada hem MD5 hem de password_verify desteği ekleyelim
                    $passwordValid = false;
                    
                    // Önce MD5 kontrolü (MTA:SA'nın varsayılan hash yöntemi)
                    if (strlen($user['password']) === 32 && ctype_xdigit($user['password'])) {
                        // MD5 hash
                        $passwordValid = (md5($password) === $user['password']);
                    } else {
                        // Modern password hash (bcrypt/argon2)
                        $passwordValid = password_verify($password, $user['password']);
                    }
                    
                    if ($passwordValid) {
                        // Giriş başarılı
                        setUserSession($user);
                        
                        // Başarılı girişi logla
                        $isAdmin = $user['admin'] ? ' (Admin)' : '';
                        logUserAction('Başarılı Giriş' . $isAdmin, $user['id'], 'Kullanıcı sisteme giriş yaptı');
                        
                        header('Location: index.php');
                        exit();
                    } else {
                        $error = 'Kullanıcı adı veya şifre hatalı.';
                    }
                } else {
                    $error = 'Kullanıcı adı veya şifre hatalı.';
                }
            } else {
                $error = 'Veritabanı bağlantısı yok. Geçici admin ile giriş yapabilirsiniz.';
            }
        } catch (Exception $e) {
            $error = 'Veritabanı bağlantı hatası. Geçici admin ile giriş yapmayı deneyin.';
        }
    }
}

// Hata mesajı URL'den geliyorsa
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'session_expired':
            $error = 'Oturumunuz sona erdi. Lütfen tekrar giriş yapın.';
            break;
        case 'no_permission':
            $error = 'Bu sayfaya erişim yetkiniz yok.';
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-primary: #0a0e27;
            --bg-secondary: #1a1f3a;
            --bg-tertiary: #252b47;
            --accent-primary: #00d4ff;
            --accent-secondary: #ff006e;
            --text-primary: #ffffff;
            --text-secondary: #b8c5d6;
        }
        
        body {
            /* Background resmi */
            background-image: url('assets/images/background.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            /* Fallback gradient */
            background-color: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-primary);
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(26, 31, 58, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(0, 212, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: var(--accent-primary);
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }
        
        .login-header p {
            color: var(--text-secondary);
            margin: 0;
        }
        
        .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .form-control {
            background: var(--bg-tertiary);
            border: 1px solid rgba(0, 212, 255, 0.2);
            color: var(--text-primary);
            padding: 12px 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: var(--bg-tertiary);
            border-color: var(--accent-primary);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 255, 0.25);
        }
        
        .form-control::placeholder {
            color: rgba(184, 197, 214, 0.5);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--accent-primary) 0%, #0099cc 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 212, 255, 0.4);
            color: white;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
        }
        
        .alert-danger {
            background: rgba(255, 0, 110, 0.2);
            color: #ff6b9d;
            border-left: 3px solid var(--accent-secondary);
        }
        
        .alert-success {
            background: rgba(0, 212, 255, 0.2);
            color: var(--accent-primary);
            border-left: 3px solid var(--accent-primary);
        }
        
        .input-group-text {
            background: var(--bg-tertiary);
            border: 1px solid rgba(0, 212, 255, 0.2);
            border-right: none;
            color: var(--text-secondary);
        }
        
        .input-group .form-control {
            border-left: none;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--accent-primary);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="bi bi-controller"></i> MTA:SA UCP</h1>
                <p>Oyuncu Kontrol Paneli</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">
                        <i class="bi bi-person"></i> Kullanıcı Adı
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username" 
                            name="username" 
                            placeholder="Oyundaki kullanıcı adınız"
                            required 
                            autofocus
                            value="<?php echo htmlspecialchars(isset($_POST['username']) ? $_POST['username'] : ''); ?>"
                        >
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Şifre
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Şifrenizi girin"
                            required
                        >
                    </div>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Giriş Yap
                </button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

