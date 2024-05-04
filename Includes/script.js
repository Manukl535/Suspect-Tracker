function updateTimeAndGreeting() {
    // Get current time
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();

    // Format hours, minutes, and seconds to have leading zeros if needed
    hours = (hours < 10 ? "0" : "") + hours;
    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    // Display the time in the format "10:10:00"
    document.getElementById("real-time").textContent = "Time: " + hours + ":" + minutes + ":" + seconds;

    // Determine the greeting based on the current hour
    var greeting;
    if (hours < 12) {
        greeting = "Good Morning!";
    } else if (hours >= 12 && hours < 18) {
        greeting = "Good Afternoon!";
    } else {
        greeting = "Good Evening!";
    }

    // Display the greeting
    document.getElementById("greeting").textContent = greeting;
}

// Call updateTimeAndGreeting function every second to update the clock and greeting
setInterval(updateTimeAndGreeting, 1000);

// Initial call to display time and greeting immediately
updateTimeAndGreeting();

// Toggling

// function toggleUploadOptions() {
//     var uploadOptions = document.getElementById("upload-options");
//     var radios = document.getElementsByName("choice");
//     for (var i = 0; i < radios.length; i++) {
//         if (radios[i].checked) {
//             uploadOptions.style.display = "block";
//             return;
//         }
//     }
//     uploadOptions.style.display = "none";
// }
// Function to toggle upload options based on the selected choice
function toggleUploadOptions(choice) {
    var sourceFileLabel = document.getElementById("sourceFileLabel");
    var fileInput = document.getElementById("fileInput");
    var uploadButton = document.getElementById("upload");
    var result = document.getElementById("result");

    if (choice === "pre-recorded") {
        sourceFileLabel.style.display = "block";
        fileInput.style.display = "block";
        uploadButton.style.display = "block";
        result.style.display = "block";
    } else {
        sourceFileLabel.style.display = "none";
        fileInput.style.display = "none";
        uploadButton.style.display = "none";
        result.style.display = "none";
    }
}

// Call toggleUploadOptions function when the page is loaded to show/hide upload options based on the checked radio button
window.onload = function() {
    toggleUploadOptions('pre-recorded'); // Ensure pre-recorded media is selected by default
};  


