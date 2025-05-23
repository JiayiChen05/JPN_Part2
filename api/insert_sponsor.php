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

    if (!isset($data['sp_fname']) || !isset($data['sp_type'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required fields: sp_fname or sp_type'
        ]);
        exit;
    }

    // For organization type (O), sp_lname should be NULL
    $sp_lname = null;
    if ($data['sp_type'] === 'I' && (!isset($data['sp_lname']) || empty($data['sp_lname']))) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Last name is required for Individual sponsor type'
        ]);
        exit;
    } else if (isset($data['sp_lname']) && $data['sp_lname'] !== null) {
        $sp_lname = $data['sp_lname'];
    }

    $stmt = $pdo->prepare("
        INSERT INTO JPN_SPONSOR (SP_FNAME, SP_LNAME, SP_TYPE)
        VALUES (:sp_fname, :sp_lname, :sp_type)
    ");

    $stmt->execute([
        ':sp_fname' => $data['sp_fname'],
        ':sp_lname' => $sp_lname,
        ':sp_type' => $data['sp_type']
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Sponsor inserted successfully',
        'sponsor_id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
