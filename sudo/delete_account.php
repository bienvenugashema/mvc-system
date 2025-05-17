<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';

if(isset($_GET['email'])) {
    $email = $_GET['email'];
    $delete_query = "DELETE FROM admins WHERE email = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("s", $email);
    if($stmt->execute()) {
        // $stmt->close();
        header("Location: ../public/login.php");
        exit();
    } else {
        echo "Error deleting account: " . $conn->error;
    }
}