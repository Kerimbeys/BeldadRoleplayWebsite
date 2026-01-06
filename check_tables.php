<?php
require_once 'config.php';

try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS);
    echo "Veritabani baglantisi basarili\n";

    $tables = ['stocks', 'user_stocks', 'stock_transactions'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($result->rowCount() > 0) {
            echo "$table tablosu mevcut\n";
        } else {
            echo "$table tablosu mevcut degil\n";
        }
    }

} catch (Exception $e) {
    echo 'Veritabani hatasi: ' . $e->getMessage() . "\n";
}
?>