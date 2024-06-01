<!-- Designed and Developed by Manu and Srisha -->


<?php
session_start();

include('Includes/connection.php');

if (!isset($_SESSION['logged-in'])) {
    header('location:index.php');
    exit;
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suspect Tracker</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="script.js"></script>

    <style>
       
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

.container {
    display: flex;
    justify-content: space-between;
    padding: 20px;
    height: calc(100vh - 50px);
}

.left-side, .right-side {
    flex: 1;
    background-color: #fff;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: auto;
    min-height: calc(100vh - 50px);
}

.menu {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.menu li {
    display: inline;
    margin-right: 20px;
}

.menu li a {
    text-decoration: none;
    color: #333;
}

h2 {
    color: #333;
}

p {
    color: #666;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background-color: #45a049;
}

.choice-options {
    display: inline-block;
    margin-right: 20px;
}

input[type="file"] {
    display: block;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
    background-color: #fff;
}

.center-text {
  display: block;
  text-align: center;
  font-family: Arial, sans-serif;
  padding-top: 10px;
}

        
    </style>
</head>
<body class="w3-light-grey">

<div class="w3-bar w3-top w3-black w3-large" style="z-index:4; height: 99px;">

    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i> &nbsp;Menu</button>
    
        <span class="center-text"><img src="logo.png" alt="logo" style="width: 100px; height: 50px;"></span>
        <span class="center-text">Karnataka State Police, Govt Of Karnataka</span>
        
</div>

<nav class="w3-sidebar w3-collapse w3-white w3-animate-left" style="z-index:3;width:300px;padding-top:25px" id="mySidebar"><br>
    <div class="w3-container w3-row">
        <div class="w3-col s8 w3-bar">  
            <span style="padding-top:0">Welcome Officer, <?php if(isset($_SESSION['name'])) { echo ucfirst($_SESSION['name']); } ?> </header><br>
            <span id="greeting"></span><br>
            <span id="real-time"></span> 
        </div>
    </div>
    <hr>
    <div class="w3-container" style="padding-top:0">
        <header class="w3-container" style="padding-top:0">
            <h5><b><i class="fa fa-dashboard"></i> Dashboard</b></h5>
        </header>
    </div>
    <div class="w3-bar-block">
        <div style="margin-top: 10px;"></div>
        <a href="suspects_data.php" class="w3-bar-item w3-button w3-padding w3-light-blue"><i class="fa fa-database"></i>&nbsp; Suspects Data</a>
    </div>
    <div class="w3-bar-block">
        <div style="margin-top: 10px;"></div>
        <a href="account_set.php" class="w3-bar-item w3-button w3-padding w3-red"><i class="fa fa-cog fa-fw"></i>&nbsp; Account Settings</a>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:310px;margin-top:50px;">
    
    <div class="right-side">
    <center style="font-family: Arial, sans-serif; padding-top: 16px;">

    <h2 style="font-family: Georgia, serif; font-weight: bold;">Suspect Tracker</h2>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    
    <span><a href="logout.php" 
    style="text-decoration:none; padding-left: 950px;padding-bottom:40px    display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s;">Logout</a></span>

    
</center>

        <p>Select Your Choice:</p>
        <div class="choice-options">
            <input type="radio" name="choice" value="pre-recorded" onclick="toggleUploadOptions('pre-recorded')" checked> Pre-recorded media
        </div>

        <div class="choice-options">
            <input type="radio" name="choice" value="live-stream" onclick="toggleUploadOptions('live-stream')"> Live stream
        </div>
        <div id="upload-options">
            <!-- Upload options for pre-recorded media -->
            <form action="upload.php" method="post" enctype="multipart/form-data">
    
                <p id="sourceFileLabel">Source File:</p>
                <input type="file" name="sourceFile" id="fileInput">
                <button type="submit" id="uploadButton">Upload</button>
                <div id="result" style="display: none;"></div>
                
            </form>

            
            <!-- Upload options for suspect image -->
            <p id="suspectImageLabel">Suspect Image:</p>
            <input type="file" id="suspectImageInput">
            <button id="suspectImageUpload">Upload</button>
            <div id="suspectImageResult"></div>

        </div>
        
<center>
<button id="trackSuspectButton">Track Suspect</button>

    <hr style="border-color: grey; width: 80%;">
</center>



<center><p>2024 &#169; All Rights Reserved <br/>Designed and Maintained by <b>Manu </b>and <b>Srisha</b></p></center>

</div>

    
    <script>
   // Function to toggle upload options based on the selected choice
   function toggleUploadOptions(choice) {
    var sourceFileLabel = document.getElementById("sourceFileLabel");
    var fileInput = document.getElementById("fileInput");
    var uploadButton = document.getElementById("uploadButton");
    var result = document.getElementById("result");
    var suspectImageLabel = document.getElementById("suspectImageLabel"); 
    var suspectImageInput = document.getElementById("suspectImageInput");
    var suspectImageUpload = document.getElementById("suspectImageUpload");
    var suspectImageResult = document.getElementById("suspectImageResult");

    if (choice === "pre-recorded") {
        sourceFileLabel.style.display = "block";
        fileInput.style.display = "block";
        uploadButton.style.display = "block";
        result.style.display = "block";
        
        // Hide suspect image upload options
        suspectImageLabel.style.display = "none";
        suspectImageInput.style.display = "none";
        suspectImageUpload.style.display = "none";
        suspectImageResult.style.display = "none";
    } else {
        sourceFileLabel.style.display = "none";
        fileInput.style.display = "none";
        uploadButton.style.display = "none";
        result.style.display = "none";
        
        // Show suspect image upload options
        suspectImageLabel.style.display = "block";
        suspectImageInput.style.display = "block";
        suspectImageUpload.style.display = "block";
        suspectImageResult.style.display = "block";
    }
}


// Call toggleUploadOptions function when the page is loaded to show/hide upload options based on the checked radio button
window.onload = function() {
    toggleUploadOptions('pre-recorded');

    document.getElementById("trackSuspectButton").addEventListener("click", function() {
    // Make an AJAX request to your PHP script
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "track_suspect.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Response from PHP script (optional)
            console.log(xhr.responseText);
        }
    };
    xhr.send();
});

};  
    </script>
    
    </body>
    </html>