<?php
header('Content-Type: application/json');

// Vultr API 설정
$VULTR_API = 'http://155.138.140.60:8080/api';

// POST 데이터 받기
$url = $_POST['url'] ?? '';
$analysisType = $_POST['analysisType'] ?? 'quick';

if (empty($url)) {
    echo json_encode(['error' => 'URL을 입력해주세요.']);
    exit;
}

// 분석 유형에 따라 설정
$fullSite = false;
$includePageSpeed = false;
$maxPages = 1;  // 기본값
$timeout = 120; // 기본 2분

switch ($analysisType) {
    case 'quick':
        $fullSite = false;
        $includePageSpeed = false;
        $maxPages = 1;
        $timeout = 30;
        break;
    case 'standard':
        $fullSite = false;
        $includePageSpeed = true;
        $maxPages = 1;
        $timeout = 90;
        break;
    case 'full':
        $fullSite = true;
        $includePageSpeed = false;
        $maxPages = 200;  // 전체 사이트 - 최대 200페이지
        $timeout = 600;   // 10분
        break;
    case 'complete':
        $fullSite = true;
        $includePageSpeed = true;
        $maxPages = 200;  // 완전 분석 - 최대 200페이지
        $timeout = 900;   // 15분
        break;
    case 'ultra':
        $fullSite = true;
        $includePageSpeed = true;
        $maxPages = 200;  // 슈퍼 완전 - 최대 200페이지
        $timeout = 1800;  // 30분
        break;
}

// API 엔드포인트 선택
$endpoint = $fullSite 
    ? $VULTR_API . '/analyze/full-site?max_pages=' . $maxPages
    : $VULTR_API . '/analyze';

// API 요청 데이터
$requestData = [
    'url' => $url,
    'include_pagespeed' => $includePageSpeed
];

// cURL로 API 호출
$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// 디버깅: 응답 크기 확인
error_log("API Response Size: " . strlen($response) . " bytes");
error_log("HTTP Code: " . $httpCode);

if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    error_log("cURL Error: " . $error);
    echo json_encode([
        'error' => 'API 연결 실패: ' . $error
    ]);
    exit;
}

curl_close($ch);

if ($httpCode !== 200) {
    // 400 에러 상세 정보 출력
    $errorDetail = '';
    if (!empty($response)) {
        $errorData = json_decode($response, true);
        if (isset($errorData['detail'])) {
            $errorDetail = $errorData['detail'];
        }
    }
    
    echo json_encode([
        'error' => 'API 오류: HTTP ' . $httpCode,
        'detail' => $errorDetail,
        'raw_response' => $response
    ]);
    exit;
}

// 빈 응답 체크
if (empty($response)) {
    echo json_encode([
        'error' => 'API에서 빈 응답을 받았습니다.'
    ]);
    exit;
}

// 성공 - 결과 그대로 전달 (이미 JSON임)
echo $response;
?>