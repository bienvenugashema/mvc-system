<?php

session_start();

if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';

include_once '../config/notifications.php';

$email = $_SESSION['sudo_email'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = escapeshellarg($_POST['message']);
    $output = shell_exec("python3 ai/mode.py $message");
    echo nl2br(htmlspecialchars($output));
}
?>
