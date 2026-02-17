<?php

/**
 * Simple BOG API test script
 * Run with: php test-bog-api.php
 */

echo "Testing BOG API Connection\n";
echo "==========================\n\n";

// BOG API credentials
$baseUrl = 'https://api.bog.ge';
$clientId = '10003598';
$clientSecret = 'BgE7o9MC4zzf';

echo "Configuration:\n";
echo "  Base URL: $baseUrl\n";
echo "  Client ID: $clientId\n";
echo "  Client Secret: " . substr($clientSecret, 0, 5) . "...\n\n";

// Test 1: Check if API is reachable
echo "Test 1: Checking if API is reachable...\n";
$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode > 0) {
    echo "  ✓ API is reachable (HTTP $httpCode)\n\n";
} else {
    echo "  ✗ Cannot reach API\n";
    echo "  Check your internet connection or firewall settings\n\n";
    exit(1);
}

// Test 2: Test OAuth2 authentication
echo "Test 2: Testing OAuth2 authentication...\n";
$authUrl = "https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token";
$data = [
    'grant_type' => 'client_credentials',
];

// Create Basic Auth header
$basicAuth = base64_encode($clientId . ':' . $clientSecret);

$ch = curl_init($authUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . $basicAuth,
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_VERBOSE, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "  Request URL: $authUrl\n";
echo "  HTTP Status: $httpCode\n";
echo "  Response: $response\n\n";

if ($curlError) {
    echo "  ✗ CURL Error: $curlError\n\n";
    exit(1);
}

if ($httpCode == 200) {
    $json = json_decode($response, true);
    if (isset($json['access_token'])) {
        echo "  ✓ Authentication successful!\n";
        echo "  Access Token: " . substr($json['access_token'], 0, 30) . "...\n";
        echo "  Token Type: " . ($json['token_type'] ?? 'N/A') . "\n";
        echo "  Expires In: " . ($json['expires_in'] ?? 'N/A') . " seconds\n\n";
        echo "SUCCESS: BOG API is working correctly!\n";
        exit(0);
    } else {
        echo "  ✗ No access_token in response\n";
        echo "  Response: " . print_r($json, true) . "\n\n";
        exit(1);
    }
} else {
    echo "  ✗ Authentication failed\n";
    echo "  HTTP Status: $httpCode\n";
    echo "  Response: $response\n\n";
    
    if ($httpCode == 401) {
        echo "  Possible causes:\n";
        echo "  - Invalid client_id or client_secret\n";
        echo "  - Credentials not activated by BOG\n";
        echo "  - Wrong environment (test vs production)\n\n";
    }
    
    exit(1);
}

