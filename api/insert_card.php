<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once '../config/db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['INV_ID']) ||
    !isset($data['PAY_DATE']) ||
    !isset($data['PAY_AMOUNT']) ||
    !isset($data['CARD_HOLDER_NAME']) ||
    !isset($data['CARD_NUMBER']) ||
    !isset($data['CARD_TYPE'])
) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit();
}

try {
    
    $stmt = $pdo->prepare("CALL SP_INSERT_JPN_PAYMENT_CARD(?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['INV_ID'],
        $data['PAY_DATE'],
        $data['PAY_AMOUNT'],
        $data['CARD_HOLDER_NAME'],
        $data['CARD_NUMBER'],
        $data['CARD_TYPE']
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Card payment processed successfully"
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
