<?php
session_start();

include('Includes/connection.php');

if (!isset($_SESSION['logged-in'])) {
    header('Location: index.php');
    exit;
}

// Directory where uploaded files will be moved
$uploadDirectory = "images/";

// Allowed image file extensions
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Add other image types if needed

// Check if file has been uploaded
if (isset($_FILES['suspectImage'])) {
    $file_name = $_FILES['suspectImage']['name'];
    $file_tmp = $_FILES['suspectImage']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Check if file extension is allowed
    if (in_array($file_ext, $allowedExtensions)) {
        // Define the upload path
        $file_path = $uploadDirectory . basename($file_name);

        // Check if file is uploaded successfully
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Insert file name into the database
            $query = "INSERT INTO images (file_name) VALUES ('$file_name')";

            if (mysqli_query($conn, $query)) {
                echo '<script>alert("Image uploaded successfully");window.location.href = "dashboard.php";</script>';
            } else {
                echo '<script>alert("Error uploading image to database");window.location.href = "dashboard.php";</script>';
            }
        } else {
            echo '<script>alert("Error uploading image");window.location.href = "dashboard.php";</script>';
        }
    } else {
        echo '<script>alert("Error: File format doesn\'t match. Upload images only (e.g., image.jpg)");window.location.href = "dashboard.php";</script>';
    }
} else {
    echo '<script>alert("No file uploaded");window.location.href = "dashboard.php";</script>';
}
?>
