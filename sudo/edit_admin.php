<?php

session_start();
if(!isset($_SESSION['sudo_email'])){
    header("Location: ../public/login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if(isset($_GET['email'])){
    $email = $_GET['email'];

    // Fetch admin details
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>No admin found with this email.</div>";
    }

    if(isset($_POST['edit_admin'])) {
            $full_name = $_POST['full_name'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = $_POST['role'];

            // Update admin details
            $update_sql = "UPDATE admins SET full_name='$full_name', password='$password', role='$role' WHERE email='$email'";
            if ($conn->query($update_sql) === TRUE) {
                addInfo("Admin updated successfully.");
                header("Location: manage_admins.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error updating admin: " . $conn->error . "</div>";
            }
        }
}

?>

<html>
    <head>
        <title>
            Edit Admin <?php echo htmlspecialchars($admin['full_name']); ?>
        </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <h1>Edit Admin</h1>
            <form action="edit_admin.php?email=<?php echo urlencode($admin['email']); ?>" method="POST">

                <input type="hidden" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($admin['full_name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required readonly>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option value="<?php echo htmlspecialchars($admin['role']); ?>"><?php echo htmlspecialchars($admin['role']); ?></option>
                        <option value="admin">Admin</option>
                        <option value="sudo">Sudo</option>
                    </select>
                </div>
                <button type="submit" name="edit_admin" class="btn btn-primary">Update Admin</button>
                <a href="dashboard.php" class="btn btn-secondary text-decoration-none">Go Home</a>
            </form>
        </div>

    </body>
</html>