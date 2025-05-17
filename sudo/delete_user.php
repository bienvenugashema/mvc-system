<?php
session_start();
if (!isset($_SESSION['sudo_email'])) {
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM users WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        addInfo("User deleted successfully.");
        header("Location: manage_users.php");
    } else {
        addError("Error deleting user: " . $conn->error);
    }
}
?>