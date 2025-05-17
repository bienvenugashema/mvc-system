<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if(isset($_GET['email'])){
    $email = $_GET['email'];

    // Check if the email exists in the admins table
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email exists, proceed to delete
        $delete_sql = "DELETE FROM admins WHERE email='$email'";
        if ($conn->query($delete_sql) === TRUE) {
            addInfo("Admin deleted successfully.");
            header("Location: manage_admins.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error deleting admin: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>No admin found with this email.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>No email provided.</div>";
}