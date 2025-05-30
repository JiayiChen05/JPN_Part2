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

// In a real implementation, you would get the author ID from the session
// For now, we'll accept it as a parameter (but in production, add proper validation)
$authorId = isset($_GET['author_id']) ? intval($_GET['author_id']) : 0;

if (!$authorId) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Author ID is required'
    ]);
    exit;
}

try {
    // Query to get all seminars the author is registered for
    $query = "
        SELECT 
            ase.INVITATION_ID,
            ase.A_ID,
            ase.E_ID,
            e.E_NAME AS seminar_name,
            e.E_STARTTIME AS start_time,
            e.E_ENDTIME AS end_time,
            e.E_TYPE,
            s.SPEAKER_FNAME,
            s.SPEAKER_LNAME,
            t.T_NAME AS topic_name
        FROM JPN_AUTHOR_SEMINAR ase
        JOIN JPN_EVENT e ON ase.E_ID = e.E_ID
        JOIN JPN_SEMINAR s ON e.E_ID = s.E_ID
        LEFT JOIN JPN_TOPIC t ON e.T_ID = t.T_ID
        WHERE ase.A_ID = :author_id
        ORDER BY e.E_STARTTIME ASC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
    $stmt->execute();
    
    $authorSeminars = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $authorSeminars
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
