<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Suspect Details</title>
<style>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
table {
    border-collapse: collapse;
    width: 80%; 
}
table, th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
}
img {
    display: block;
    margin: auto;
}
</style>
</head>
<body>

<div class="container">

<?php
include('Includes/connection.php');

// Read the detected names from the file
$detected_names_file = "Detected_faces.txt";
$detected_names = file($detected_names_file, FILE_IGNORE_NEW_LINES);

// Prepare SQL query to retrieve suspect details
$sql = "SELECT * FROM suspect_details WHERE name IN ('" . implode("','", $detected_names) . "')";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    // Output table header
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Age</th><th>Address</th><th>Image</th><th>Previous Crimes</th></tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
       
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["age"] . "</td>";
        echo "<td>" . $row["address"] . "</td>";
        echo "<td><img src='suspects/" . $row["image"] . "' width='100'></td>";
        echo "<td>" . $row["prev_crimes"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No matching suspects found.";
}

$conn->close();
?>
</div>

</body>
</html>
