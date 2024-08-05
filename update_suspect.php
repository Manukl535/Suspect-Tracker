<?php
include('./Includes/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suspect_id = $_POST['suspect_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $image = $_POST['image'];
    $prev_crimes = $_POST['prev_crimes'];

    $stmt = $conn->prepare("UPDATE suspect_details SET name = ?, age = ?, address = ?, image = ?, prev_crimes = ? WHERE suspect_id = ?");
    $stmt->bind_param('ssssss', $name, $age, $address, $image, $prev_crimes, $suspect_id);

    if ($stmt->execute()) {
        echo '<script>alert("Criminal data updated successfully");window.location.href = "suspects_data.php";</script>';
    } else {
        echo 'Failed to update suspect';
    }
    $stmt->close();
}
?>
