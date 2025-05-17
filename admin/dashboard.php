<?php
session_start();
if(!isset($_SESSION['admin_email'])) {
    header("Location: ../public/login.php");
    exit();
} 

include_once '../config/db.php';
include_once '../config/notifications.php';

$email = $_SESSION['admin_email'];
$sql = "SELECT * FROM admins WHERE email='$email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}


?>


<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
   </style>
</head>
<body>

    <div class="container">
        <h1 class="text-center">Admin Dashboard</h1>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="reply.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Manage Complaints</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1>Welcome <?php echo isset($user['email']) ? $user['full_name'] : 'Admin'; ?></h1>
            </div>
        </div>
        <div>
            <h2>Available Complaints</h2>
            <ul>
                <?php
                $sql = "SELECT * FROM complaints";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table class='table'>";
                    echo "<thead><tr><th>Title</th><th>Status</th><th>Action</th></tr></thead>";
                    echo "<tbody>";
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <?php if ($row['status'] == 'resolved'): ?>
                                <td><button href="#" class="btn btn-success" disabled>Reply</button></td>
                            <?php else: ?>
                                <td><a href="reply.php?ticket_id=<?php echo $row['ticket_id']; ?>" class="btn btn-primary">Reply</a></td>
                            <?php endif; ?>
                        </tr>
                    <?php
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<li>No complaints found.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
