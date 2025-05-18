<?php
session_start();
    // include 'chatbot.php'; 
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    // echo "Not logged in";
    exit();
}

// Include the database connection file
include_once '../config/db.php';
include_once '../config/notifications.php';
$email = $_SESSION['sudo_email'];
$sql = "SELECT * FROM admins WHERE email='$email'";
$result = $conn->query($sql);
$admin = $result->fetch_assoc();

?>
<html>
<head>
    <title>SUDO Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>SUDO Control Panel</h1>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">SUDO</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_admins.php">Manage Admins</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

<div class="card mt-4" id="chatbotCard">
    
    <div class="card mt-4">
    <div class="card-header">AI Assistant</div>
    <div class="card-body">
        <div id="chat-box" style="height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;"></div>
        <input type="text" id="user-input" class="form-control mt-2" placeholder="Ask a question...">
        <button onclick="sendMessage()" class="btn btn-primary mt-2">Send</button>
    </div>
</div>
</div>
<script>
function sendMessage() {
    const input = document.getElementById('user-input');
    const message = input.value.trim();
    if (message === '') return;

    const chatBox = document.getElementById('chat-box');
    chatBox.innerHTML += `<div><strong>You:</strong> ${message}</div>`;

    fetch('chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(message)
    })
    .then(res => res.text())
    .then(data => {
        chatBox.innerHTML += `<div><strong>AI:</strong> ${data}</div>`;
        chatBox.scrollTop = chatBox.scrollHeight;
        input.value = '';
    });
}
</script>
</body>
</html>