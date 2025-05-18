<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: ../public/login.php");
    exit();
}
$user_email = $_SESSION['user_email'];
include_once '../config/db.php';
$sql = "SELECT * FROM users WHERE email='$user_email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
if (isset($_GET['id'])) {
    $complaint_id = $_GET['id'];
    $complaint_sql = "SELECT * FROM complaints WHERE id='$complaint_id' AND user_id=" . $user['id'];
    $complaint_result = $conn->query($complaint_sql);
    if ($complaint_result->num_rows > 0) {
        $complaint = $complaint_result->fetch_assoc();
    } else {
        echo "Complaint not found.";
        exit();
    }
} else {
    echo "No complaint ID specified.";
    exit();
}
if (isset($_POST['update_complaint'])) {
    $updated_title = $_POST['title'];
    $updated_description = $_POST['description'];
    $update_sql = "UPDATE complaints SET title='$updated_title', message='$updated_description' WHERE id='$complaint_id' AND user_id=" . $user['id'];
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Complaint updated successfully!');</script>";
        header("Location: view_status.php");
        exit();
    } else {
        echo "Error updating complaint: " . $conn->error;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Complaint</title>
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
                        <span class="nav-link"><?php echo $user['full_name']; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Edit Complaint</h1>
        <form method="post" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $complaint['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $complaint['message']; ?></textarea>
            </div>
            <button type="submit" name="update_complaint" class="btn btn-primary">Update Complaint</button>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>
