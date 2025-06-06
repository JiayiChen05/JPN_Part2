<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

// Check required fields
if (
    !isset($data['USER_FNAME']) ||
    !isset($data['USER_LNAME']) ||
    !isset($data['USER_EMAIL']) ||
    !isset($data['USER_PHONE']) ||
    !isset($data['USER_STREET']) ||
    !isset($data['USER_CITY']) ||
    !isset($data['USER_STATE']) ||
    !isset($data['USER_COUNTRY']) ||
    !isset($data['USER_ZIPCODE']) ||
    !isset($data['USER_PASSWORD'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit();
}

try {
    // First, check if email already exists
    $checkStmt = $pdo->prepare("SELECT U_ID FROM JPN_USER WHERE U_EMAIL = ?");
    $checkStmt->execute([$data['USER_EMAIL']]);
    if ($checkStmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            "status" => "error",
            "message" => "Email already registered"
        ]);
        exit();
    }

    // Use the stored procedure to register an author
    $stmt = $pdo->prepare("
        CALL SP_INSERT_JPN_USER_AUTHOR(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['USER_FNAME'],
        $data['USER_LNAME'],
        $data['USER_PHONE'],
        $data['USER_EMAIL'],
        $data['USER_PASSWORD'],
        $data['USER_STREET'],
        $data['USER_CITY'],
        $data['USER_STATE'],
        $data['USER_COUNTRY'],
        $data['USER_ZIPCODE']
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Author registered successfully",
        "redirect" => "login.html"
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?> 