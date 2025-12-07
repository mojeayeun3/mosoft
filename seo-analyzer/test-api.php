<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>API 테스트</title>
</head>
<body>
    <h1>SEO API 테스트</h1>
    
    <form method="POST" action="seo-api.php">
        <input type="url" name="url" value="https://mosoft.ca" required>
        <select name="analysisType">
            <option value="quick">빠른 분석</option>
            <option value="standard">표준 분석</option>
            <option value="full">전체 분석</option>
        </select>
        <button type="submit">테스트</button>
    </form>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo '<h2>결과:</h2>';
        echo '<pre>';
        
        // 직접 API 호출 테스트
        $url = $_POST['url'];
        $analysisType = $_POST['analysisType'];
        
        $VULTR_API = 'http://155.138.140.60:8080/api/analyze';
        
        $requestData = [
            'url' => $url,
            'include_pagespeed' => false
        ];
        
        $ch = curl_init($VULTR_API);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        echo "HTTP Code: $httpCode\n";
        echo "cURL Error: $error\n";
        echo "Response:\n";
        echo htmlspecialchars($response);
        
        echo '</pre>';
    }
    ?>
</body>
</html>