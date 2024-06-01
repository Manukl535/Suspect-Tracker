<?php
// Path to the directory containing live images
$liveImageDirectory = "images/";

// Check if there are any live images available
$liveImages = array_merge(glob($liveImageDirectory . "*.jpg"), glob($liveImageDirectory . "*.png"));

if (!empty($liveImages)) {
    // Get the most recent live image
    $latestLiveImage = end($liveImages);

    // Execute live.py with the latest live image path
    $command = 'python live.py ' . escapeshellarg($latestLiveImage) . ' 2>&1';
    echo "Command: " . $command . "<br>";

    $output = shell_exec($command);
    echo "Output: " . $output . "<br>"; // Output any errors or results

    // Display a link to navigate to suspects_details.php
    echo "<p>Click <a href='suspects_details.php'>here</a> to view suspect details.</p>";
} else {
    echo "No live images found in the 'images' directory.";
}
?>
