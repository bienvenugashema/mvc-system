<?php
session_start();
if(isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
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
        <title>User Status</title>
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
                        <img src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp" height="15" alt="MDB Logo" loading="lazy"/>
                    </a>
                    <ul class="navbar-nav
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Complaint</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link"><?php echo $email; ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1>User Status</h1>
    </body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</html>
<?php
// Include the database connection file
include_once '../config/db.php';
if (!isset($_SESSION['user_email'])) {
    header("Location: ../public/login.php");
    exit();
}
$user_email = $_SESSION['user_email'];
$sql = "SELECT * FROM users WHERE email='$user_email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
$complaints_sql = "SELECT * FROM complaints WHERE user_id=" . $user['id'];
$complaints_result = $conn->query($complaints_sql);
if ($complaints_result->num_rows > 0) {
    echo "<h2>Your Complaints</h2>";
    echo "<table class='table'>";
    echo "<tr><th>Ticket ID</th><th>Title</th><th>Status</th><th>Actions</th></tr>";
    while ($complaint = $complaints_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $complaint['ticket_id'] . "</td>";
        echo "<td>" . $complaint['title'] . "</td>";
        echo "<td>" . $complaint['status'] . "</td>";
        echo "<td><a href='edit_complaint.php?id=" . $complaint['id'] . "'>Edit</a> <a href='delete_complaint.php?id=" . $complaint['id'] . "' >Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No complaints found.";
}
// Close the database connection
$conn->close();
?>

