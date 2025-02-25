<?php

header("Content-Type: application/json");

function fetchVerificationData($studentId) {
    $apiUrl = "http://software.diu.edu.bd:8006/bus/verify?studentId=" . urlencode($studentId);
    
    $response = file_get_contents($apiUrl);
    if ($response === false) {
        http_response_code(500);
        return json_encode(["error" => "Failed to fetch data"]);
    }

    return $response;
}

if (!isset($_GET['studentId'])) {
    http_response_code(400);
    echo json_encode(["error" => "studentId parameter is required"]);
    exit;
}

$studentId = $_GET['studentId'];
echo fetchVerificationData($studentId);

?>
