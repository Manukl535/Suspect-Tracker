<?php
session_start();
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}

// Include the database connection
include('./Includes/connection.php');

// Function to get suspects from the database
function getSuspects($conn)
{
    $suspectStmt = $conn->prepare("SELECT * FROM suspect_details");
    $suspectStmt->execute();
    $suspects = $suspectStmt->get_result();
    $suspectStmt->close();

    return $suspects;
}

// Check if the add suspect form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_suspect'])) {
    // Extract form data
    
    $suspect_id = $_POST['suspect_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $image = $_POST['image'];
    $prev_crimes = $_POST['prev_crimes'];

    // Prepare and execute the INSERT query
    $addSuspectStmt = $conn->prepare("INSERT INTO suspect_details (suspect_id, name, age, address, image, prev_crimes) 
                                    VALUES (?, ?, ?, ?, ?, ?)");
    $addSuspectStmt->bind_param('ssssss',$suspect_id, $name, $age, $address, $image, $prev_crimes);
    $addSuspectStmt->execute();
    $addSuspectStmt->close();
}

// Fetch suspects using the getSuspects function
$suspects = getSuspects($conn);


?>

<!DOCTYPE html>
<html lang="en">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<head>
    <title>Control Panel - Manage Suspects</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .main-content {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h2, h3 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        form div {
            flex: 0 0 48%;
            padding: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 50px;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #d9534f;
            text-decoration: none;
            cursor: pointer;
        }

        a:hover {
            text-decoration: underline;
        }

        .export-btn {
            background-color: #337ab7;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .export-btn:hover {
            background-color: #286090;
        }
    </style>
</head>

<body>

    <div class="main-content">
    <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px; color:blue;" class="fa">&#xf190;</i></a>

&nbsp;

<a href="dashboard.php"><i style="font-size:24px;color:blue;" class="fa">&#xf015;</i></a>
        <h2>Manage Suspects</h2>

        <form method="post" action="" style="border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
            <div>


                <label for="suspect_id">Suspect ID:</label>
                <input type="text" name="suspect_id" required>

                <label for="name">Name:</label>
                <input type="text" name="name" required>

                <label for="address">Address:</label>
                <input name="address" style="resize: none;">
            </div>

            <div>
                <label for="age">Image:</label>
                <input type="text" name="image">



                <label for="image">Age:</label>
                <input type="text" name="age" required>
        
                <label for="prev_crimes">Commited Crimes:</label>
                <input type="text" name="prev_crimes" required>
            </div>

            <div style="text-align: center; margin: 5px auto;">
                <button type="submit" name="add_suspect" onclick="return confirm('Are you sure about adding the suspect?')">Add Suspect</button>
            </div>
        </form>

        <div>
            <h2>Suspects List</h2>

            <!-- Export to Excel button -->
            <button class="export-btn" onclick="exportToExcel('suspect-table')">Export to Excel</button>

            <table id="suspect-table">
                <thead>
                    <tr>
                        
                        <th>Suspect ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Image</th>
                        <th>Previous Crimes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $suspects->fetch_assoc()) { ?>
                        <tr>
                            
                            <td><?php echo $row['suspect_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['age']; ?></td>
                            <td><?php echo $row['address']; ?></td>
                            <td><img src="Suspects/<?php echo $row['image']; ?>" alt="Image" style="max-width: 100px; max-height: 100px;"></td>
                            <td><?php echo $row['prev_crimes']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Function to export table data to Excel
        function exportToExcel(tableId) {
            var tab_text = "<table border='2px'><tr>";
            var textRange; var j = 0;
            tab = document.getElementById(tableId); // id of table

            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
            }
            else {
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            }

            return (sa);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var updateButtons = document.querySelectorAll('.update-btn');
            updateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var productId = this.getAttribute('data-product-id');
                    var updateRow = document.querySelector('.update-row[data-product-id="' + productId + '"]');
                    updateRow.style.display = 'table-row';

                    // Add click event listener for the "Save" button in the update row
                    var saveButton = updateRow.querySelector('.update-btn');
                    saveButton.addEventListener('click', function () {
                        var newQuantityInput = updateRow.querySelector('input[name="available_qty"]');
                        var newQuantity = newQuantityInput.value;

                        // Send the updated quantity to the server using AJAX
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_quantity.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                // Handle the response from the server if needed
                                alert(xhr.responseText);
                                // Reload the page after updating the quantity
                                location.reload();
                            }
                        };
                        xhr.send('product_id=' + productId + '&new_quantity=' + newQuantity);
                    });
                });
            });
        });
    </script>
</body>

</html>
