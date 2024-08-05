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

    try {
        // Prepare and execute the INSERT query
        $addSuspectStmt = $conn->prepare("INSERT INTO suspect_details (suspect_id, name, age, address, image, prev_crimes) 
                                        VALUES (?, ?, ?, ?, ?, ?)");
        $addSuspectStmt->bind_param('ssssss', $suspect_id, $name, $age, $address, $image, $prev_crimes);
        $addSuspectStmt->execute();
        $addSuspectStmt->close();

        // Set a session variable for the success message
        $_SESSION['suspect_added'] = true;

        // Redirect to the same page to avoid resubmission on refresh
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (mysqli_sql_exception $e) {
        // Check if it's a duplicate entry error
        if ($e->getCode() === 1062) {
            $_SESSION['error_message'] = 'Criminal ID is not available';
        } else {
            $_SESSION['error_message'] = 'An error occurred while adding the suspect';
        }

        // Redirect to the same page to avoid resubmission on refresh
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Check if there's a success message to show
$suspect_added = isset($_SESSION['suspect_added']) ? $_SESSION['suspect_added'] : false;
if ($suspect_added) {
    unset($_SESSION['suspect_added']);
}

// Check if there's an error message to show
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : false;
if ($error_message) {
    unset($_SESSION['error_message']);
}

// Fetch suspects using the getSuspects function
$suspects = getSuspects($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Panel - Manage Suspects</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
        }

        .modal-form input {
            margin-bottom: 15px;
            width: 100%;
        }

        .modal-form button {
            align-self: center;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .modal-form button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($suspect_added): ?>
                alert('New criminal added');
            <?php endif; ?>

            <?php if ($error_message): ?>
                alert('<?php echo addslashes($error_message); ?>');
            <?php endif; ?>
        });

        function exportToExcel(tableId) {
            var tab_text = "<table border='2px'><tr>";
            var textRange; var j = 0;
            var tab = document.getElementById(tableId); // id of table

            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); // remove if you want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if you want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // removes input params

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) { // If Internet Explorer
                var txtArea1 = document.createElement('iframe');
                txtArea1.style.display = 'none';
                document.body.appendChild(txtArea1);
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                txtArea1.document.execCommand("SaveAs", true, "Criminals_List.xls");
            } else {
                var link = document.createElement('a');
                link.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tab_text);
                link.download = 'SuspectsList.xls';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }

        function openEditModal(suspectId) {
            fetch('get_suspect.php?id=' + suspectId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-suspect_id').value = data.suspect_id;
                    document.getElementById('edit-name').value = data.name;
                    document.getElementById('edit-age').value = data.age;
                    document.getElementById('edit-address').value = data.address;
                    document.getElementById('edit-image').value = data.image;
                    document.getElementById('edit-prev_crimes').value = data.prev_crimes;
                    document.getElementById('edit-modal').style.display = 'block'; // Show edit modal
                });
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none'; // Hide edit modal
        }

        function deleteSuspect(suspectId) {
            if (confirm('Are you sure you want to delete this criminal?')) {
                fetch('delete_suspect.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(suspectId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = 'suspects_data.php';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the suspect.');
                });
            }
        }
    </script>
</head>
<body>
    <div class="main-content">
        <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px; color:blue;" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="dashboard.php"><i style="font-size:24px;color:blue;" class="fa">&#xf015;</i></a>
        <h2>Manage Criminals</h2>

        <form method="post" action="" style="border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
            <div>
                <label for="suspect_id">Criminal ID:</label>
                <input type="text" name="suspect_id" placeholder="Enter criminal ID" required>

                <label for="name">Name:</label>
                <input type="text" name="name" placeholder="Enter name" required>

                <label for="address">Address:</label>
                <input type="text" name="address" placeholder="Enter address" required>
            </div>

            <div>
                <label for="image">Image:</label>
                <input type="text" name="image" placeholder="Enter image file path" required>

                <label for="age">Age:</label>
                <input type="text" name="age" placeholder="Enter age" required>
        
                <label for="prev_crimes">Previous Crimes:</label>
                <input type="text" name="prev_crimes" placeholder="Enter previous crimes" required>
            </div>

            <div style="text-align: center; margin: 5px auto;">
                <button type="submit" name="add_suspect" onclick="return confirm('Are you sure about adding the criminal?')">Add Criminal</button>
            </div>
        </form>

        <!-- Edit Modal -->
        <div id="edit-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Criminals</h2>
                <form id="edit-suspect-form" method="post" action="update_suspect.php" class="modal-form">
                    <input type="hidden" id="edit-suspect_id" name="suspect_id">
                    <label for="edit-name">Name:</label>
                    <input type="text" id="edit-name" name="name" required>

                    <label for="edit-age">Age:</label>
                    <input type="text" id="edit-age" name="age" required>
                    
                    <label for="edit-address">Address:</label>
                    <input type="text" id="edit-address" name="address" required>
                    
                    <label for="edit-image">Image:</label>
                    <input type="text" id="edit-image" name="image" required>

                    <label for="edit-prev_crimes">Previous Crimes:</label>
                    <input type="text" id="edit-prev_crimes" name="prev_crimes" required>
                    
                    <button type="submit" style="background-color: #007bff; color: #fff; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">Save</button>
                </form>
            </div>
        </div>

        <div>
            <h2>Criminals List</h2>

            <!-- Export to Excel button -->
            <button class="export-btn" onclick="exportToExcel('suspect-table')">Export to Excel</button>

            <table id="suspect-table">
                <thead>
                    <tr>
                        <th>Criminal ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Image</th>
                        <th>Previous Crimes</th>
                        <th>Actions</th> <!-- Added Actions Column -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $suspects->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['suspect_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                                <?php
                                $imagePath = 'Suspects/' . $row['image'];
                                if (file_exists($imagePath)) {
                                    echo '<img src="' . htmlspecialchars($imagePath) . '" alt="Image" style="max-width: 100px; max-height: 100px;">';
                                } else {
                                    $parentImagePath = './' . $row['image']; // Images in parent directory
                                    if (file_exists($parentImagePath)) {
                                        echo '<img src="' . htmlspecialchars($parentImagePath) . '" alt="Image" style="max-width: 100px; max-height: 100px;">';
                                    } else {
                                        echo 'Image not found';
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['prev_crimes']); ?></td>
                                <td>
                                <button style="background-color: #007bff; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;" onclick="openEditModal('<?php echo htmlspecialchars($row['suspect_id']); ?>')">Edit</button>
                                <button style="background-color: #dc3545; color: #fff; padding: 5px 10px; border: none; border-radius: 5px; cursor: pointer;" onclick="deleteSuspect('<?php echo htmlspecialchars($row['suspect_id']); ?>')">Delete</button>
                            </td>
                            </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</body>
</html>
