<?php
// Discord webhook test
$url = 'https://discord.com/api/webhooks/1457911395654828045/g4cqAH9ZpdsavpwAr3jxU9gwW--m8DA-O8WXo3oNC5QAO3UmvSyIYlzMEWi5R-9uFeYI';

$data = [
    'content' => '🧪 Test mesajı - Beldad UCP',
    'username' => 'Test Bot',
    'embeds' => [[
        'title' => 'Test Embed',
        'description' => 'Bu bir test mesajıdır.',
        'color' => 3447003
    ]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";
echo "cURL Error: $curlError\n";
?>