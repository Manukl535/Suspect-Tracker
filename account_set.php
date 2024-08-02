<?php
session_start();
include('Includes/connection.php');

// Initialize variables
$newName = '';
$newEmail = '';
$newPhone = '';

// Initialize error variables
$nameError = $emailError = $phoneError = $passwordError = '';

// Check if the user is logged in as an admin
if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $newPassword = filter_var(trim($_POST['new_password']), FILTER_SANITIZE_STRING);
    $newName = filter_var(trim($_POST['new_name']), FILTER_SANITIZE_STRING);
    $newEmail = filter_var(trim($_POST['new_email']), FILTER_SANITIZE_EMAIL);
    $newPhone = filter_var(trim($_POST['new_phone']), FILTER_SANITIZE_STRING);

    // Validate email format and domain
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $newEmail)) {
        $emailError = 'Please enter a valid Gmail address!';
    }

    // Validate name (only allow alphabets, spaces, and hyphens)
    if (!preg_match("/^[a-zA-Z\s-]+$/", $newName)) {
        $nameError = 'Name can only contain letters, spaces, and hyphens!';
    }

    // Validate phone number format (+91 followed by 10 digits)
    if (!preg_match("/^\+91\d{10}$/", $newPhone)) {
        $phoneError = 'Phone number must be in the format +91 followed by 10 digits!';
    }

    // Check if there are any errors
    if (!$nameError && !$emailError && !$phoneError) {
        // Update the admin table (excluding password)
        $updateStmt = $conn->prepare("UPDATE admin SET name = ?, email = ?, phone = ? WHERE id = ?");
        if (!$updateStmt) {
            die('Error preparing update statement: ' . $conn->error);
        }

        $updateStmt->bind_param("ssss", $newName, $newEmail, $newPhone, $_SESSION['id']);
        if (!$updateStmt->execute()) {
            die('Error updating account: ' . $updateStmt->error);
        }

        // Update password if provided
        if (!empty($newPassword)) {
            $passwordStmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
            if (!$passwordStmt) {
                die('Error preparing password update statement: ' . $conn->error);
            }

            $passwordStmt->bind_param("ss", $newPassword, $_SESSION['id']);
            if (!$passwordStmt->execute()) {
                die('Error updating password: ' . $passwordStmt->error);
            }

            $passwordStmt->close();
        }

        echo '<p style="color: green;">Account updated successfully!</p>';
        echo '<script>alert("Account info updated!\\nLogging Out"); window.location.href = "logout.php";</script>';
        exit; // To prevent further execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            font-size: 0.875em;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function validateName() {
            const nameInput = document.getElementById('new_name');
            const errorSpan = document.getElementById('name_error');
            const nameValue = nameInput.value;
            if (!/^[a-zA-Z\s-]+$/.test(nameValue)) {
                errorSpan.textContent = 'Name can only contain letters, spaces, and hyphens!';
            } else {
                errorSpan.textContent = ''; // Clear the error message if valid
            }
        }

        function validateEmail() {
            const emailInput = document.getElementById('new_email');
            const errorSpan = document.getElementById('email_error');
            const emailValue = emailInput.value;
            if (!/^[\w-]+(\.[\w-]+)*@gmail\.com$/.test(emailValue)) {
                errorSpan.textContent = 'Please enter a valid Gmail address!';
            } else {
                errorSpan.textContent = ''; // Clear the error message if valid
            }
        }

        function validatePhone() {
            const phoneInput = document.getElementById('new_phone');
            const errorSpan = document.getElementById('phone_error');
            const phoneValue = phoneInput.value;
            if (!/^\+91\d{10}$/.test(phoneValue)) {
                errorSpan.textContent = 'Phone number must be in the format +91 followed by 10 digits!';
            } else {
                errorSpan.textContent = ''; // Clear the error message if valid
            }
        }

        function validateForm() {
            validateName();
            validateEmail();
            validatePhone();
            // Check if there are any errors displayed
            return !document.querySelector('.error').textContent;
        }
    </script>
</head>
<body>
<div class="container">
    <center>
        <a href="#" onclick="window.history.back(); return false;"><i style="font-size:24px" class="fa">&#xf190;</i></a>
        &nbsp;
        <a href="dashboard.php"><i style="font-size:24px;color:blue;" class="fa">&#xf015;</i></a>
        <br/>
    </center>
    <h2>Account Settings</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" placeholder="New Password">
        </div>
        <div class="form-group">
            <label for="new_name">New Name:</label>
            <input type="text" id="new_name" name="new_name" placeholder= "Srisha" value="<?php echo htmlspecialchars($newName); ?>" required oninput="validateName()">
            <div id="name_error" class="error"><?php echo $nameError; ?></div>
        </div>
        <div class="form-group">
            <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" placeholder= "srisha@gmail.com" value="<?php echo htmlspecialchars($newEmail); ?>" required oninput="validateEmail()">
            <div id="email_error" class="error"><?php echo $emailError; ?></div>
        </div>
        <div class="form-group">
            <label for="new_phone">New Phone:</label>
            <input type="text" id="new_phone" name="new_phone" placeholder= "+911234567890" value="<?php echo htmlspecialchars($newPhone); ?>" required oninput="validatePhone()">
            <div id="phone_error" class="error"><?php echo $phoneError; ?></div>
        </div>
        <button type="submit">Update Account</button>
    </form>
</div>
</body>
</html>
