<?php
session_start();
if(!isset($_SESSION['user_email'])) {
    header("Location: ../public/login.php");
    exit();
}
include_once '../config/db.php';
include_once '../config/notifications.php';
$email = $_SESSION['user_email'];
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
if (isset($_POST['update_settings'])) {

    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $new_password = $_POST['new_password'];
    $result = $conn ->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $new_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET full_name=?, password=? WHERE email=?");
            $stmt->bind_param("sss", $full_name, $new_password, $email);
            if ($stmt->execute()) {
                echo "<script>alert('Settings updated successfully!');</script>";
            } else {
                echo "<script>alert('Error updating settings: " . $stmt->error . "');</script>";
            }
        } else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    }
    
    
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Settings Page</title>
</head>
<body>
    <div class="container">
        <h1>Update Settings</h1>
        <form action="update_settings.php" method="POST">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Intial Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" name="update_settings" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Go to Dashboard</button>
        </form>
    </div>
</body>
</html>