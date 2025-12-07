<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PHP 테스트</h1>";

// 1. PHP 작동 확인
echo "<p>✅ PHP 작동 중</p>";

// 2. cURL 사용 가능 확인
if (function_exists('curl_version')) {
    echo "<p>✅ cURL 사용 가능</p>";
    $version = curl_version();
    echo "<p>cURL 버전: " . $version['version'] . "</p>";
} else {
    echo "<p>❌ cURL 사용 불가능</p>";
}

// 3. Vultr API 연결 테스트
echo "<h2>API 연결 테스트</h2>";

$url = 'http://155.138.140.60:8080/health';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

echo "<p>연결 시도 중: $url</p>";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

if ($error) {
    echo "<p>❌ cURL 에러: $error</p>";
} else {
    echo "<p>✅ HTTP 코드: $httpCode</p>";
    echo "<p>응답: <pre>" . htmlspecialchars($response) . "</pre></p>";
}

curl_close($ch);

// 4. 실제 API 테스트
echo "<h2>SEO API 테스트</h2>";

$apiUrl = 'http://155.138.140.60:8080/api/analyze';
$data = json_encode([
    'url' => 'https://mosoft.ca',
    'include_pagespeed' => false
]);

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

echo "<p>POST 요청: $apiUrl</p>";
echo "<p>데이터: <pre>$data</pre></p>";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "<p>❌ 에러: $error</p>";
} else {
    echo "<p>✅ HTTP 코드: $httpCode</p>";
    echo "<p>응답 길이: " . strlen($response) . " bytes</p>";
    echo "<p>응답 (처음 500자):</p>";
    echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
}
?>