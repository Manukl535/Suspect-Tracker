<?php
include('./Includes/connection.php');

$suspect_id = $_GET['id'] ?? '';

if ($suspect_id) {
    $stmt = $conn->prepare("SELECT * FROM suspect_details WHERE suspect_id = ?");
    $stmt->bind_param('s', $suspect_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    
    header('Content-Type: application/json');
    echo json_encode($data);
}
?>
