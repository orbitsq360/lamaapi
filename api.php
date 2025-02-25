<?php
header("Content-Type: application/json");

function fetchVerificationData($studentId) {
    $apiUrl = "http://software.diu.edu.bd:8006/bus/verify?studentId=" . urlencode($studentId);

    // Fetch data from external API
    $response = file_get_contents($apiUrl);
    if ($response === false) {
        http_response_code(500);
        return json_encode(["error" => "Failed to fetch data"]);
    }

    $data = json_decode($response, true);

    // Ensure the API returned valid data
    if (!isset($data["studentId"])) {
        http_response_code(404);
        return json_encode(["error" => "Student not found"]);
    }

    // Check if the API provides a valid photo
    if (!isset($data["photo"]) || empty($data["photo"])) {
        $defaultImagePath = __DIR__ . "/default.png"; // Make sure default.png exists

        if (file_exists($defaultImagePath)) {
            $defaultImage = base64_encode(file_get_contents($defaultImagePath));
            $data["photo"] = "data:image/png;base64," . $defaultImage;
        } else {
            $data["photo"] = "https://via.placeholder.com/150"; // Fallback to an online placeholder
        }
    }

    return json_encode($data);
}

// Check for studentId parameter
if (!isset($_GET['studentId'])) {
    http_response_code(400);
    echo json_encode(["error" => "studentId parameter is required"]);
    exit;
}

$studentId = $_GET['studentId'];
echo fetchVerificationData($studentId);
?>
