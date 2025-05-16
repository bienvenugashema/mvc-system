<?php
session_start();
if(!isset($_SESSION['user_email'])) {
    header("Location: ../public/login.php");
    exit();
}
include_once '../config/db.php';
if (isset($_GET['id'])) {
    $complaint_id = $_GET['id'];
    $sql = "DELETE FROM complaints WHERE id='$complaint_id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Complaint deleted successfully!');</script>";
        header("Location: view_status.php");
        exit();
    } else {
        echo "Error deleting complaint: " . $conn->error;
    }
} else {
    echo "No complaint ID specified.";
}