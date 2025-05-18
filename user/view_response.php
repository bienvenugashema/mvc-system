<?php
session_start();
if(isset($_SESSION['user_email'])) {
    include_once '../config/db.php';
    include_once '../config/notifications.php';
    $user_email = $_SESSION['user_email'];
    $sql = "SELECT * FROM users WHERE email='$user_email'";
    $result = $conn->query($sql);
    if(isset($_GET['id'])) {
        $complaint_id = $_GET['id'];
        $complaint_sql = "SELECT * FROM complaints WHERE id='$complaint_id'";
        $complaint_result = $conn->query($complaint_sql);
        if ($complaint_result->num_rows > 0) {
            $complaint = $complaint_result->fetch_assoc();
        } else {
            echo "Complaint not found.";
        }
    } else {
        echo "No complaint ID provided.";
    }
} else {
    header("Location: ../public/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Response</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                </a>
                <ul class="navbar-nav
                    
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link"><?php echo $user_email; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <h1>View Response</h1>
    <div class="container">
        <h2>Complaint Details</h2>
        <p>Ticket ID: <?php echo $complaint['ticket_id']; ?></p>
        <p>Title: <?php echo $complaint['title']; ?></p>
        <p>Status: <?php echo $complaint['status']; ?></p>
        <p>Complaint: <?php echo $complaint['message']; ?></p>
        <p>Created At: <?php echo $complaint['created_at']; ?></p>
        <h2>Response</h2>
        <?php 
        $get_response_sql = "SELECT * FROM replies WHERE complaint_id='" . $complaint['ticket_id'] . "'";
        $response_result = $conn->query($get_response_sql);
        if ($response_result->num_rows > 0) {
            while ($response = $response_result->fetch_assoc()) {
                ?>
                <div>
                    <p><?php 
                    $select_admin_sql = "SELECT * FROM admins WHERE email='" . $response['admin_email'] . "'";
                    $admin_result = $conn->query($select_admin_sql);
                    if ($admin_result->num_rows > 0) {
                        $admin = $admin_result->fetch_assoc();
                        echo "Admin: " . $admin['full_name'];
                    } else {
                        echo "Admin not found.";
                    }
                    ?></p>
                    <p>Response: <?php echo $response['reply_text']; ?></p>
                    <p>Created At: <?php echo $response['created_at']; ?></p>
                </div>

                <?php
            }
        } else {
            echo "<p>No response found.</p>";
        }
        ?>
</body>
</html>