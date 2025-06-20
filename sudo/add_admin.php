<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if(isset($_POST['add_admin'])) {
    include_once '../config/db.php';
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $sql = "INSERT INTO admins (full_name, email, password, role) VALUES ('$full_name', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        addInfo("Admin added successfully.");
        header("Location: manage_admins.php");
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

?>


<html>
    <head>
        <title>Add Users</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">

</head>

<body>
        <div class="container">
            <h1>Add Users</h1>
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
            <div class="row">
                <div class="col-md-6">
                    <h2>Add New Admin</h2>
                    <form action="add_admin.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Names</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="sudo">Super Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="add_admin" class="btn btn-primary">Add Admin</button>
                    </form>
                </div>

</body>
</html>