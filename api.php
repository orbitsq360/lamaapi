<?php

header('Content-Type: application/json; charset=utf-8');

// Validate input parameters
if (!isset($_GET['studentId']) || !is_numeric($_GET['studentId']) || empty(trim($_GET['studentId']))) {
    echo json_encode(['error' => 'Please provide a valid studentId (numeric).'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Sanitize input
$studentId = htmlspecialchars(trim($_GET['studentId']), ENT_QUOTES, 'UTF-8');

// Get API URL from environment variable or fallback to default
$apiUrl = getenv('API_URL') ?: "http://software.diu.edu.bd:8006/bus/verify?studentId=" . urlencode($studentId);

// Function to fetch data from an API
function fetchData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Skip SSL verification
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:104.0) Gecko/20100101 Firefox/104.0");

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => "cURL Error: $error"];
    }

    curl_close($ch);

    $decodedResponse = json_decode($response, true);
    if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => "Error decoding JSON: " . json_last_error_msg()];
    }

    return $decodedResponse;
}

// Fetch bus pass details
$busPassData = fetchData($apiUrl);

// Check if API returned an error
if (isset($busPassData['error'])) {
    echo json_encode($busPassData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// Output the response
echo json_encode($busPassData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>
