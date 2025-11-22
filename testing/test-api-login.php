<?php

// Test API Login untuk debugging
// Usage: php test-api-login.php

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$baseUrl = 'https://dev.mydeposys.com'; // Ganti dengan URL server yang ingin ditest
$email = $_ENV['EXTERNAL_API_EMAIL'] ?? 'admin@example.com';
$password = $_ENV['EXTERNAL_API_PASSWORD'] ?? '';

echo "Testing API Login...\n";
echo "Base URL: {$baseUrl}\n";
echo "Email: {$email}\n";
echo "Password: " . str_repeat('*', strlen($password)) . "\n\n";

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => "{$baseUrl}/api/auth/login",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([
        'email' => $email,
        'password' => $password,
    ]),
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'X-Requested-With: XMLHttpRequest',
    ],
    CURLOPT_SSL_VERIFYPEER => false, // Untuk testing saja
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "HTTP Code: {$httpCode}\n";

if ($error) {
    echo "CURL Error: {$error}\n";
    exit(1);
}

echo "\nResponse:\n";
echo $response . "\n\n";

$data = json_decode($response, true);

if ($data) {
    echo "Parsed JSON:\n";
    print_r($data);
    
    echo "\n\nChecking for token...\n";
    if (isset($data['access_token'])) {
        echo "✓ Found: access_token\n";
    } elseif (isset($data['token'])) {
        echo "✓ Found: token\n";
    } elseif (isset($data['data']['access_token'])) {
        echo "✓ Found: data.access_token\n";
    } elseif (isset($data['data']['token'])) {
        echo "✓ Found: data.token\n";
    } else {
        echo "✗ Token not found in any expected location\n";
        echo "Available keys: " . implode(', ', array_keys($data)) . "\n";
    }
} else {
    echo "Failed to parse JSON response\n";
}
