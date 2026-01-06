<?php
// Manuel Discord test
require_once 'includes/functions.php';

echo "Testing Discord webhook...\n";

$result = logUserAction('Manuel Test', 1, 'Bu manuel bir test mesajıdır.');
echo "Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Debug log kontrolü
if (file_exists('discord_debug.log')) {
    echo "\nDebug Log:\n";
    echo file_get_contents('discord_debug.log');
} else {
    echo "Debug log dosyası bulunamadı.\n";
}
?>