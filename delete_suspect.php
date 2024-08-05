<?php
include('./Includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $suspect_id = $_POST['id'];
    $response = array('status' => 'error', 'message' => 'Failed to delete suspect');

    $stmt = $conn->prepare("DELETE FROM suspect_details WHERE suspect_id = ?");
    $stmt->bind_param('s', $suspect_id);

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Criminal deleted successfully');
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No suspect ID provided'));
}
?>
