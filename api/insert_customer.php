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

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (
        !isset($data['first_name']) ||
        !isset($data['last_name']) ||
        !isset($data['phone']) ||
        !isset($data['email']) ||
        !isset($data['uid_type']) ||
        !isset($data['uid_number'])
    ) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO JPN_CUSTOMER (
            CUST_FNAME, CUST_LNAME, CUST_PHONE, CUST_EMAIL, CUST_UID_TYPE, CUST_UID_NO
        ) VALUES (
            :fname, :lname, :phone, :email, :uid_type, :uid_number
        )
    ");

    $stmt->execute([
        ':fname'      => $data['first_name'],
        ':lname'      => $data['last_name'],
        ':phone'      => $data['phone'],
        ':email'      => $data['email'],
        ':uid_type'   => $data['uid_type'],
        ':uid_number' => $data['uid_number']
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Customer inserted successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
