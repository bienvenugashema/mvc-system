<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}
$email = $_SESSION['sudo_email'];
// Include the database connection file
include_once '../config/db.php';
include_once '../config/notifications.php';

?>

<html>
<head>
    <title>SUDO Control Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://mdbcdn.b-cdn.net/js/mdb.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table th {
            background-color:rgb(59, 65, 71);
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #ffffff;
        }
        .table-striped tbody tr:hover {
            background-color: #e9ecef;
        }
        .form-control {
            border-radius: 0.25rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            margin-bottom: 0.5rem;
        }
        .form-check {
            margin-bottom: 1rem;
        }
        .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        .form-check-inline {
            margin-right: 1rem;
        }
        .form-check-inline .form-check-label {
            margin-left: 0.3rem;
        }
        .form-check-inline .form-check-input {
            margin-top: 0.3rem;
        }
        </style>
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
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_admins.php">Manage Admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_notifications.php">Manage Notifications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content">
        <div>
            <h2>Manage Users</h2>
            <a href="add_user.php" class="text-primary text-decoration-none">Add User</a>
            <?php
            $select_users = "SELECT * FROM users";
            $result = $conn->query($select_users);
            if ($result->num_rows > 0) {
                echo '<table class="table table-striped">';
                echo '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Actions</th></tr></thead>';
                echo '<tbody>';
                while($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['id'] . '</td>';
                    echo '<td>' . $row['full_name'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>
                            <a href="edit_user.php?id=' . $row['id'] . '" class="btn btn-primary">Edit</a>
                            <a href="delete_user.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a>
                            <a href="view_user.php?id=' . $row['id'] . '" class="btn btn-info">View more</a>
                          </td>';
                    echo '</tr>';
                }
                echo '</tbody></table>';
            } else {
                echo 'No users found.';
            }
            ?>
        </div>
    </div>
