<?php
session_start();
include('Includes/connection.php');

function function_alert($message, $redirectUrl) {
    echo "<script>alert('$message');</script>";
    echo "<script>window.location.href = '$redirectUrl';</script>";
    exit();
}

require_once 'C:\xampp\htdocs\twilio-php-main\src\Twilio\autoload.php';
use Twilio\Rest\Client;

$sid = "ACc52a9ad7912313f3a2fac31a004cb494";
$token = "e75fd4d8f76310fc8cf0f4160707822b";
$twilioPhoneNumber = '+15169798118';
$twilio = new Client($sid, $token);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit']) && isset($_POST['phone'])) {
        $otp = rand(100000, 999999);
        $phone = $_POST['phone'];

        // Check if the phone number starts with +91
        if (strpos($phone, '+91') !== 0) {
            function_alert("Please enter a valid phone number starting with +91.", "forgot_password.php");
        }

        // Update the database with the generated OTP
        $updateQuery = "UPDATE admin SET otp = '$otp' WHERE phone = '$phone'";
        $conn->query($updateQuery);

        // Set the phone number in the session
        $_SESSION['phone'] = $phone;

        // Send OTP to the registered mobile number using Twilio
        $message = $twilio->messages
            ->create($phone, [
                'from' => $twilioPhoneNumber,
                'body' => "Your OTP is: $otp",
            ]);

        // Redirect to OTP verification page
        header("Location: forgot_password.php?verify=true&phone=" . urlencode($phone));
        exit();
    } elseif (isset($_POST['verify']) && isset($_POST['otp']) && isset($_POST['phone'])) {
        $enteredOtp = $_POST['otp'];
        $phone = $_POST['phone'];

        // Retrieve stored OTP from the database
        $getStoredOtpQuery = "SELECT otp FROM admin WHERE phone = '$phone'";
        $result = $conn->query($getStoredOtpQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedOtp = $row['otp'];

            // Check if the entered OTP matches the stored OTP in the database
            if ($enteredOtp == $storedOtp) {
                // OTP is valid, proceed to change password
                // Redirect to change password page
                header("Location: forgot_password.php?change=true&phone=" . urlencode($phone));
                exit();
            } else {
                function_alert("Invalid OTP. Please try again.", "forgot_password.php");
            }
        } else {
            function_alert("User not found!.", "index.php");
        }
    }

    if (isset($_POST['Change_Password'])) {
        if (!isset($_SESSION['phone'])) {
            function_alert("Session phone not set. Please try again.", "forgot_password.php");
        }

        $password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $phone = $_SESSION['phone'];

        if (strlen($password) < 6) {
            function_alert("Password must have at least 6 characters", "forgot_password.php");
        } elseif ($password !== $confirm_password) {
            function_alert("Passwords didn't match. Try Again", "forgot_password.php");
        } else {
            $stmt = $conn->prepare("UPDATE admin SET password=? WHERE phone=?");
            $stmt->bind_param('ss', $password, $phone);
            if ($stmt->execute()) {
                function_alert("Password changed successfully!\\nRedirecting to Login page", "index.php");
            } else {
                function_alert("Couldn't update the password", "index.php");
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Forgot Password</title>
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px; /* Increased border-radius for a softer look */
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Increased box-shadow for depth */
    width: 300px; /* Set a fixed width for better readability */
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 15px; /* Increased margin for better spacing */
    font-size: 16px; /* Adjusted font size for better visibility */
    font-weight: bold; /* Added bold font weight for emphasis */
}

input {
    padding: 12px; /* Increased padding for better input field appearance */
    margin-bottom: 20px; /* Increased margin for better spacing */
    border: 1px solid #ccc; /* Added a subtle border for input fields */
    border-radius: 5px; /* Added border-radius for rounded corners */
    transition: border-color 0.3s ease; /* Smooth transition for better interactivity */
}

input:focus {
    outline: none; /* Remove default focus outline */
    border-color: #4caf50; /* Change border color on focus */
}

button {
    background-color: #4caf50;
    color: #fff;
    padding: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease; /* Smooth transition for better interactivity */
}

button:hover {
    background-color: #45a049;
}
</style>
</head>
<body>
    <div class="container">
        <?php if (isset($_GET['verify']) && $_GET['verify'] == 'true'): ?>
            <form action="forgot_password.php" method="post">
                <h2>Verify OTP</h2>
                <label for="otp">Enter OTP:</label>
                <input type="text" name="otp" required>
                <input type="hidden" name="phone" value="<?php echo htmlspecialchars($_GET['phone']); ?>">
                <button type="submit" name="verify">Verify OTP</button>
            </form>

        <?php elseif (isset($_GET['change']) && $_GET['change'] == 'true'): ?>

            <div class="profile-section" style="flex: 1;">
                <center>
                    <h3>Change Password</h3>
                    <p style="color:red;"><?php if (isset($_GET['error'])) { echo $_GET['error']; } ?></p>
                    <p style="color:green;"><?php if (isset($_GET['message'])) { echo $_GET['message']; } ?></p>
                </center>
                <form id="account-form" action="forgot_password.php" method="POST">
                    <label for="new_password">New Password</label> <input type="password" id="new_password"
                        name="new_password" required="">
                    <label for="confirm_password">Confirm New Password</label> <input type="password"
                        id="confirm_password" name="confirm_password" required="">
                    <center>
                        <button type="submit" name="Change_Password" style='border-radius: 50px;'>Change Password</button>
                    </center>
                </form>
                <script>
                    
                    if (window.location.hash === '#changePassword') {
                        document.getElementById('account-form').style.display = 'block';
                    }
                </script>
            </div>
        </div>
    </div>

        <?php else: ?>
            <form action="forgot_password.php" method="post">
                <h2>Forgot Password?</h2>
                <label for="phone">Mobile Number (With +91):</label>
                <input type="text" name="phone" placeholder="+917022015320" required>
                <button type="submit" name="submit">Send OTP</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
