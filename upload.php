<?php
session_start();

include('Includes/connection.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: index.php');
    exit;
}

// Allowed video file extensions
$allowed_extensions = ['mp4', 'mkv', 'avi', 'mov', 'wmv'];

// Check if file has been uploaded
if (isset($_FILES['sourceFile'])) {
    $file_name = $_FILES['sourceFile']['name'];
    $file_tmp = $_FILES['sourceFile']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if file extension is allowed
    if (in_array($file_ext, $allowed_extensions)) {
        // Define the upload directory and file path
        $upload_dir = 'uploads/';
        $file_path = $upload_dir . basename($file_name);

        // Check if file is uploaded successfully
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert file name into the database
            $query = "INSERT INTO videos (file_name) VALUES ('$file_name')";

            if (mysqli_query($conn, $query)) {
                echo '<script>alert("File uploaded successfully");window.location.href = "dashboard.php";</script>';
            } else {
                echo '<script>alert("Error uploading file to database");window.location.href = "dashboard.php";</script>';
            }
        } else {
            echo '<script>alert("Error uploading file");window.location.href = "dashboard.php";</script>';
        }
    } else {
        echo '<script>alert("Error: File format doesn\'t match. Upload videos only (e.g., Video.mp4)");window.location.href = "dashboard.php";</script>';
    }
} else {
    echo '<script>alert("No file uploaded");window.location.href = "dashboard.php";</script>';
}
?>
