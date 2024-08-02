<?php
session_start();
include('Includes/connection.php');

if(isset($_SESSION['logged-in'])){
	header('location:dashboard.php');
	exit;
}
if(isset($_POST['login-btn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id,name,email,password FROM admin WHERE email=? AND password=? LIMIT 1");
    $stmt->bind_param('ss',$email,$password);
     
    if($stmt->execute()){
        $stmt->bind_result($id,$name,$email,$password);
        $stmt->store_result();

        if($stmt->num_rows() == 1){
          $stmt->fetch();
          
          $_SESSION['id']=$id;
          $_SESSION['name']=$name;
          $_SESSION['email']=$email;
          $_SESSION['phone']=$phone;
          $_SESSION['logged-in']=true;

          header('location:dashboard.php?messages=Logged in Successfully');
        }
        else{
            header('location:index.php?error=Invalid Email or Password');
        }
    }else{
        header('location:index.php?error=Something Went Wrong');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<style>
    body {
        color: #999;
		background: #f5f5f5;
		font-family: 'Varela Round', sans-serif;
        background: #63738a;
	}
	.form-control {
		box-shadow: none;
		border-color: #ddd;
	}
	.form-control:focus {
		border-color: #4aba70; 
	}
	.login-form {
        width: 400px;
		margin: 0 auto;
		padding: 30px 0;
	}
    .login-form form {
        color: #434343;
		border-radius: 1px;
    	margin-bottom: 15px;
        background: #fff;
		border: 1px solid #f3f3f3;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
	}
	.login-form h4 {
		text-align: center;
		font-size: 22px;
        margin-bottom: 20px;
	}
    .login-form .avatar {
        color: #fff;
		margin: 0 auto 30px;
        text-align: center;
		width: 100px;
		height: 100px;
		border-radius: 50%;
		z-index: 9;
		background:#5cb85c;
		padding: 15px;
		box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
	}
    .login-form .avatar i {
        font-size: 62px;
    }
    .login-form .form-group {
        margin-bottom: 20px;
    }
	.login-form .form-control, .login-form .btn {
		min-height: 40px;
		border-radius: 2px; 
        transition: all 0.5s;
	}
	.login-form .close {
        position: absolute;
		top: 15px;
		right: 15px;
	}
	.login-form .btn {
		background: #5cb85c;
		border: none;
		line-height: normal;
	}
	.login-form .btn:hover, .login-form .btn:focus {
		background: #42ae68;
	}
    .login-form .checkbox-inline {
        float: left;
    }
    .login-form input[type="checkbox"] {
        margin-top: 2px;
    }
    .login-form .forgot-link {
        float: right;
    }
    .login-form .small1 {
        font-size: 13px;
        color: #fff;
    }
    .login-form .small {
        font-size: 13px;
        
    }
    .login-form a {
        color: #4aba70;
    }
</style>

</head>
<body>
<div class="login-form">    
    <form id="login-form" action="index.php" method="POST">
		<div class="avatar"><i class="material-icons">&#xE7FF;</i></div>
    	<h4 class="modal-title">Login to Your Account</h4>
        <center><p style="color:red;"><?php if(isset($_GET['error'])) { echo $_GET['error']; } ?></p></center>
        <div class="form-group">
    
           
        </div> 
        <div class="form-group">
            <input type="email" class="form-control" placeholder="Email" id="Email" name="email" required="required">
        </div>
        <div class="form-group">
            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required="required">
        </div>
        <div class="form-group small clearfix">
            <label class="checkbox-inline"><input type="checkbox"> Remember me</label>
            
            <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
        </div> 
        <div class="form-group">
            
        </div>
        
        <input type="submit" class="btn btn-primary btn-block btn-lg" name="login-btn" value="Login">              
    </form>			
    
</div><br/>
<style>
    .copyright{
        color: #fff;
    }
</style>
<center>
    <footer>
        <div class="copyright">
            <p>2023 &#169; All Rights Reserved</p><p>Designed and Maintained by <b>Manu </b>and <b>Srisha</b></p>
        </div>
    
    </footer></center>
    <?php include('ama.php'); ?>
    <?php

if (isset($_SESSION['login_message'])) {
    echo "<script>alert('{$_SESSION['login_message']}');</script>";
    unset($_SESSION['login_message']); // Clear the message after displaying it
}
?>


<script>
    function toggleLoginFields() {
        var loginOption = document.querySelector('input[name="login-option"]:checked').value;
        var credentialsFields = document.getElementById("credentialsFields");
        
        if (loginOption === "credentials") {
            credentialsFields.style.display = "block";
        } else if (loginOption === "face-unlock") {
            credentialsFields.style.display = "none";
        }
    }
    
    function openFaceUnlockPage() {
        sessionStorage.setItem("selectedOption", "face-unlock"); // Store selected option in sessionStorage
        alert("Please make sure the background is clear and remove any spectacles.");
        window.location.href = "face_unlock.html"; 
    }

    // Function to set the selected radio button based on sessionStorage value
    window.onload = function() {
        var selectedOption = sessionStorage.getItem("selectedOption");
        if (selectedOption === "face-unlock") {
            document.getElementById("face-unlock-radio").checked = true;
        } else {
            document.getElementById("credentials-radio").checked = true;
        }
    }
</script>

</body>
</html>