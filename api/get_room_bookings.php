<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/db_connect.php';

try {
    $query = "SELECT 
            r.RES_ID,
            r.RES_STARTTIME,
            r.RES_ENDTIME,
            r.RES_DESC,
            r.RES_COUNT,
            r.CUST_ID,
            CONCAT(u.U_FNAME, ' ', COALESCE(u.U_LNAME, '')) AS customer_name,
            r.ROOM_ID,
            ro.ROOM_CAPACITY
          FROM JPN_RESERVATION r
          JOIN JPN_CUSTOMER c ON r.CUST_ID = c.CUST_ID
          JOIN JPN_USER u ON c.CUST_ID = u.U_ID
          JOIN JPN_ROOM ro ON r.ROOM_ID = ro.ROOM_ID
          ORDER BY r.RES_STARTTIME DESC";


    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $reservations
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
