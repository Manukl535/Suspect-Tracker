<?php

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON data from the request body
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->userInput)) {
        // Handle user input scenario
        // Sanitize user input (optional)
        $userInput = htmlspecialchars(strip_tags($data->userInput));

        // Execute ama.py script with user input
        $escapedInput = escapeshellarg($userInput);
        $output = shell_exec('python3 ama.py ' . $escapedInput);

        // Check if output is valid JSON
        $response = json_decode($output);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(array("answer" => "Sorry, I don't understand that question."));
            exit;
        }

        // Prepare JSON response for user input
        $response_data = array(
            "answer" => $response->answer
        );

        // Send JSON response
        echo json_encode($response_data);
        exit;
    } else {
        // Handle invalid request
        echo json_encode(array("error" => "Sorry, I don't understand that question."));
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ask Me Anything</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <style>
    /* CSS styles for the virtual assistant widget */
    .widget-container {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 350px; 
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        border-radius: 8px;
        overflow: hidden;
        z-index: 1000;
    }

    .widget-header {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
    }

    .widget-body {
        padding: 10px;
        width: 95%; 
        max-width: 100%; 
        margin: 0 auto;
    }

    .chat-messages {
        list-style-type: none;
        padding: 0;
        margin: 0;
        max-height: 300px;
        overflow-y: scroll;
        color: black;
    }

    .user-message {
        text-align: right;
        margin-bottom: 10px;
    }

    .assistant-message {
        text-align: left;
        margin-bottom: 10px;
    }

    .message {
        background-color: #e5e5ea;
        padding: 8px;
        border-radius: 5px;
        display: inline-block;
        max-width: 100%;
    }

    .user-message .message {
        background-color: #4CAF50;
        color: white;
        align-self: flex-end;
        max-width: 100%; 
    }

    .assistant-message .message {
        background-color: #f0f0f0;
        color: #333;
        align-self: flex-start;
        max-width: 100%; 
    }

    .input-container {
        display: flex;
        margin-top: 10px;
    }

    .input-container input[type="text"] {
        flex: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
        font-size: 14px;
        color: black;
        max-width: calc(100% - 70px); /* Adjust the input width to leave space for the button */
    }

    .input-container button {
        padding: 8px 12px;
        background-color: #4CAF50;
        border: none;
        color: white;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        font-size: 14px;
        max-width: 70px; /* Adjust the button width */
    }

    .widget-icon {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #4CAF50;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        cursor: pointer;
        z-index: 1000;
    }

    .close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: #666;
    }

    /* Typing animation */
    @keyframes typing {
        from { width: 0 }
    }

    .typing-indicator {
        overflow: hidden;
        white-space: nowrap;
        animation: typing 2s steps(20, end);
    }

    .typing-indicator::after {
        content: '|';
        overflow: hidden;
        display: inline-block;
        vertical-align: bottom;
        animation: caret 1s steps(1, end) infinite;
    }

    @keyframes caret {
        50% { border-color: transparent }
    }

    /* Blue spinner */
    .blue-spinner {
        border: 2px solid #f3f3f3;
        border-radius: 50%;
        border-top: 2px solid #3498db;
        width: 20px;
        height: 20px;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


</head>
<body>

<div class="widget-icon" onclick="toggleWidget()">💬</div>

<div class="widget-container" id="widget-container" aria-hidden="true" aria-labelledby="widget-header">
    <div class="widget-header" id="widget-header">
        AMA
        <span class="close-button" onclick="toggleWidget()" aria-label="Close">&times;</span>
    </div>
    <div class="widget-body">
        <ul class="chat-messages" id="chat-messages" role="log" aria-live="polite">
            
        <li class="assistant-message">
            <div class="message" id="greetingMessage"></div>
        </li>

        </ul>
        <div class="input-container">
            <input type="text" id="user-input" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()" aria-label="Send">Send</button>
        </div>
    </div>
</div>

<script>
    function toggleWidget() {
        const widgetContainer = document.getElementById('widget-container');
        const isVisible = widgetContainer.style.display !== 'none';
    
        widgetContainer.style.display = isVisible ? 'none' : 'block';
        document.getElementById('user-input').focus(); // Focus on input field when widget opens
    }
    
    function sendMessage() {
        const userInput = document.getElementById('user-input').value.trim();
        if (userInput === '') return; // If input is empty, do nothing
    
        const chatMessages = document.getElementById('chat-messages');
        const userMessage = document.createElement('li');
        userMessage.className = 'user-message';
        userMessage.innerHTML = `<div class="message">${userInput}</div>`;
        chatMessages.appendChild(userMessage);
    
        // Clear input after sending message
        document.getElementById('user-input').value = '';
    
        // Simulate typing effect
        const typingIndicator = document.createElement('li');
        typingIndicator.className = 'assistant-message';
        typingIndicator.innerHTML = `<div class="blue-spinner"></div>`;
        chatMessages.appendChild(typingIndicator);
    
        // Scroll to the bottom of the chat messages
        chatMessages.scrollTop = chatMessages.scrollHeight;
    
        // Send user input to server via AJAX
        fetch('ama.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userInput: userInput }),
        })
        .then(response => response.json())
        .then(data => {
            // Simulate typing response
            const responseText = data.answer;
            typingIndicator.innerHTML = ''; // Clear typing indicator
            
            for (let i = 0; i < responseText.length; i++) {
                setTimeout(() => {
                    typingIndicator.innerHTML += responseText.charAt(i);
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom after each character
                }, 50 * i); // Adjust typing speed (50 milliseconds per character here)
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    }

     // Get the current hour of the day (0-23)
     var currentHour = new Date().getHours();

// Define greetings based on the time of day
var greeting;
if (currentHour >= 0 && currentHour < 12) {
    greeting = "Good morning Officer!";
} else if (currentHour >= 12 && currentHour < 16) {
    greeting = "Good afternoon Officer!";
} else {
    greeting = "Good evening Officer!";
}

// Update the greeting message in the HTML
var greetingMessage = document.getElementById('greetingMessage');
greetingMessage.textContent = greeting + " I am AMA your virtual assistant. How can I help you today?";

</script>

</body>
</html>
