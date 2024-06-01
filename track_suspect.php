<?php
$uploadsDirectory = "uploads/";
$files = scandir($uploadsDirectory);

// Filter out directories and sort files by modification time (newest first)
$filePaths = array();
foreach ($files as $file) {
    $filePath = $uploadsDirectory . $file;
    if (is_file($filePath)) {
        $filePaths[$filePath] = filemtime($filePath);
    }
}
arsort($filePaths);

// Get the path of the most recent media file
$videoFilePath = null;
foreach ($filePaths as $filePath => $modificationTime) {
    // Check if the file is a media file (you may need to adjust the condition based on your specific file types)
    if (pathinfo($filePath, PATHINFO_EXTENSION) == "mp4" || pathinfo($filePath, PATHINFO_EXTENSION) == "avi" || pathinfo($filePath, PATHINFO_EXTENSION) == "mov") {
        $videoFilePath = $filePath;
        break; // Stop loop after finding the most recent media file
    }
}

if ($videoFilePath) {
    // Execute main.py with the selected video file path
    $output = shell_exec('python main.py ' . escapeshellarg($videoFilePath) . ' 2>&1');
    echo $output; // Output any errors or results
    
    // Display a link to navigate to suspects_details.php
    echo "<p>Click <a href='suspects_details.php'>here</a> to view suspect details.</p>";
    exit(); // Ensure that subsequent code is not executed after displaying the link
} else {
    echo "No recent media file found in the 'uploads' directory.";
}
?>
