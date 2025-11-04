<?php

echo "🧪 Testing Public Download Route\n";
echo "================================\n\n";

// Test 1: Check if route exists
echo "Test 1: Checking route...\n";
$ch = curl_init('http://localhost:8000/download/apk/3');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($httpCode == 200) {
    echo "✅ SUCCESS: Route is public and working!\n";
    echo "File will be downloaded without authentication.\n";
} elseif ($httpCode == 302 || $httpCode == 301) {
    echo "❌ REDIRECT DETECTED: Route redirects to: $redirectUrl\n";
    if (strpos($redirectUrl, 'login') !== false) {
        echo "⚠️  WARNING: Redirecting to login page!\n";
        echo "This means authentication is required.\n";
    }
} elseif ($httpCode == 404) {
    echo "❌ NOT FOUND: Route or file doesn't exist\n";
} else {
    echo "❌ ERROR: Unexpected HTTP code $httpCode\n";
}

echo "\n";

// Test 2: Check API response
echo "Test 2: Checking API response...\n";
$ch = curl_init('http://localhost:8000/api/latest-version');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Api-Key: 2yVrUCjQLo4S81l4cZkAaC5UaHbKLTaZ8fJ5p4eh0HLDckP19DNgAg3NHW6YC6Kt',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if (isset($data['data']['download_url'])) {
        echo "✅ API Response OK\n";
        echo "Download URL: " . $data['data']['download_url'] . "\n";
    } else {
        echo "⚠️  API response missing download_url\n";
    }
} else {
    echo "❌ API Error: HTTP $httpCode\n";
}

echo "\n================================\n";
echo "Testing complete!\n";
