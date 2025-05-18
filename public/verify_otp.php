<?php
session_start();

if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

$id = $_SESSION['id'];

if(isset($_POST['submit_otp'])) {
    $otp = $_POST['otp'];
    $sql = "SELECT * FROM admins WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($otp, $row['otp_code'])) {
            // to delete the otp from database
            $delete_otp = "UPDATE admins SET otp_code=NULL WHERE id='$id'";
            $conn->query($delete_otp);
            if($row['role'] == 'admin') {
                $_SESSION['admin_email'] = $row['email'];
                header("Location: ../admin/dashboard.php");
            } else {
                $_SESSION['sudo_email'] = $row['email'];
                header("Location: ../sudo/dashboard.php");
            }
            exit();
        } else {
            addError('Invalid OTP');
        }
    } else {
        addError('User not found');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 1rem;
            color: #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
</style>

    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Verify OTP</h5>
                        <p class="card-text text-center">Please enter the OTP sent to your email.</p>
                        <?php include_once '../config/notifications.php'; ?>
                        <form method="POST" action="verify_otp.php">
                            <?php displayMessages(); ?>
                            <div class="mb-3">
                                <label for="otp" class="form-label">Enter OTP</label>
                                <input type="text" name="otp" id="otp" class="form-control" required>
                            </div>
                            <button type="submit" name="submit_otp" class="btn btn-primary w-100">Verify</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
