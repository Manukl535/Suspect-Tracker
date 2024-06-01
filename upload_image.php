<?php
session_start();

include('Includes/connection.php');

if (!isset($_SESSION['logged-in'])) {
    header('location:index.php');
    exit;
}

// Check if file has been uploaded
if(isset($_FILES['sourceFile'])) {
    $file_name = $_FILES['sourceFile']['name'];
    $file_tmp = $_FILES['sourceFile']['tmp_name'];
    
    // Check if file is uploaded successfully
    if(move_uploaded_file($file_tmp, "uploads/".$file_name)) {
        // Insert file name into the database
        $query = "INSERT INTO images (file_name) VALUES ('$file_name')";

        if(mysqli_query($conn, $query)) {
            echo '<script>alert("File uploaded successfully");window.location.href = "dashboard.php"; </script>';
        } else {
            echo '<script>alert("Error Uploading File"); window.location.href = "dashboard.php"; </script>';mysqli_error($conn);
        }
    } else {
        echo '<script>alert("Error Uploading File");window.location.href = "dashboard.php"; </script>';
    }
}
?>
