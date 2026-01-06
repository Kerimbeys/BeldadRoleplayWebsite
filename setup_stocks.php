<?php
require_once 'config.php';

try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $sql = file_get_contents('database/stocks_fixed.sql');
    $pdo->exec($sql);

    echo 'Borsa tablolari basariyla olusturuldu!';
} catch (Exception $e) {
    echo 'Hata: ' . $e->getMessage();
}
?>