<?php
session_start();

include('Includes/connection.php');

if (!isset($_SESSION['logged-in'])) {
    header('location:index.php');
    exit;
}

// Directory where uploaded files will be moved
$uploadDirectory = "images/";

// Check if the directory exists, if not, create it
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true); // Creates the directory recursively with full permissions
}

// Check if file has been uploaded
if(isset($_FILES['suspectImage'])) {
    $file_name = $_FILES['suspectImage']['name'];
    $file_tmp = $_FILES['suspectImage']['tmp_name'];
    
    // Move file to the upload directory
    if(move_uploaded_file($file_tmp, $uploadDirectory.$file_name)) {
        // Insert file name into the database
        $query = "INSERT INTO images (file_name) VALUES ('$file_name')";

        if(mysqli_query($conn, $query)) {
            echo '<script>alert("Image uploaded successfully");window.location.href = "dashboard.php"; </script>';
        } else {
            echo '<script>alert("Error Uploading Image"); window.location.href = "dashboard.php"; </script>';mysqli_error($conn);
        }
    } else {
        echo '<script>alert("Error Uploading Image");window.location.href = "dashboard.php"; </script>';
    }
}
?>
