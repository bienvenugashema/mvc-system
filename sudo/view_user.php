<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id='$id'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

?>

<html>
    <head>
        <title>View user <?php echo $user['full_name']; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
    </head>
    <body>
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
                        <a class="nav-link" href="manage_notifications.php">Manage Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../public/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container">
            <h1>User Details</h1>
            <table class="table table-bordered">
                <tr>
                    <th>Full Name</th>
                    <td><?php echo $user['full_name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $user['email']; ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
            </table>

            <div>
                <h2>Complaints</h2>
                <ul>
                    <?php
                    $get_user_complaint_by_id = "SELECT * FROM complaints WHERE user_id = ?";
                    $stmt = $conn->prepare($get_user_complaint_by_id);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    echo '<table class="table table-bordered">';
                    echo '<thead><tr><th>Complaint</th><th>Status</th><th>Created At</th><th>Actions</th></tr></thead>';
                    echo '<tbody>';
                    while ($complaint = $result->fetch_assoc()) {
                        ?>
                        <tr>
                        <td><?php echo $complaint['message']; ?></td>
                        <td><?php echo $complaint['status']; ?></td>
                        <td><?php echo $complaint['created_at']; ?></td>
                        <td>
                            <div class="modal fade" id="viewComplaintModal<?php echo $complaint['ticket_id']; ?>" tabindex="-1" aria-labelledby="viewComplaintModalLabel<?php echo $complaint['ticket_id']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel<?php echo $complaint['ticket_id']; ?>">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Message:</strong> <?php echo $complaint['message']; ?></p>
                <p><strong>Status:</strong> <?php echo $complaint['status']; ?></p>
                <p><strong>Created At:</strong> <?php echo $complaint['created_at']; ?></p>
                <p><strong>Solved by:</strong> <?php 
                    $select_admin = "SELECT * FROM replies WHERE complaint_id = ?";
                    $stmt = $conn->prepare($select_admin);
                    $stmt->bind_param("i", $complaint['ticket_id']);
                    $stmt->execute();
                    $admin_result = $stmt->get_result();
                    if ($admin_result->num_rows > 0) {
                        $admin = $admin_result->fetch_assoc();
                        echo $admin['admin_email'];
                    } else {
                        echo "Not solved yet";
                    }
                ?></p>
                <p><strong>Solved At:</strong> <?php echo $admin['created_at']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<a href="#" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewComplaintModal<?php echo $complaint['ticket_id']; ?>">View</a>

                            <a href="delete_complaint.php?id=<?php echo $complaint['ticket_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            <?php if ($complaint['status'] == 'pending'): ?>
                                <a href="reply_complaint.php?ticket_id=<?php echo $complaint['ticket_id']; ?>" class="btn btn-success btn-sm">Reply</a>
                            <?php endif; ?>
                        </td>
                        </tr>
                        <?php
                    }
                    echo '</tbody>';
                    echo '</table>';
                    ?>
                </ul>
            </div>

            <a href="manage_users.php" class="btn btn-primary">Back to Users</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
</html>