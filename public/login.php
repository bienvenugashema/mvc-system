<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer classes
require '../vendor/autoload.php';

$mail = new PHPMailer(true);

if(isset($_POST['submit'])) {
    include_once '../config/db.php';
    include_once '../config/notifications.php'; // this is the notification control file
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    $admin_sql = "SELECT * FROM admins WHERE email='$email'";
    $admin_result = $conn->query($admin_sql);
    if ($admin_result && $admin_result->num_rows > 0) {
        $row = $admin_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $otp = rand(100000, 999999);
            $otp_hash = password_hash($otp, PASSWORD_DEFAULT);
            $insert_otp = "update admins set otp_code='$otp_hash' where email='$email'";
            $result = $conn->query($insert_otp);

            if ($result) {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'bienvenugashema@gmail.com';
                $mail->Password = 'ckgp iujo nveh yuex';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('bienvenugashema@gmail.com', 'Mwimule Bienvenu');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code';
                $mail->Body = "Your OTP code is: $otp";
                $mail->AltBody = "Your OTP code is: $otp";
                $mail->send();
                $_SESSION['id'] = $row['id'];
                header("Location: verify_otp.php");
                exit();

            }
          }
        }
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_email'] = $email;
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            addError('Invalid email or password');
        }
    }
    

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
</head>
<body>
    <section class="vh-100" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100 ">
      <div class="col-lg-12 col-xl-11 ">
        <div class="card text-black " style="border-radius: 20px;">
          <div class="card-body p-md-3">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign in</p>
                

                <?php include_once '../config/notifications.php'; ?>
                <?php displayMessages(); ?>
                <form class="mx-1 mx-md-4" method="post" action="login.php">

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="email" name="email" id="form3Example3c" class="form-control" />
                      <label class="form-label" for="form3Example3c">Your Email</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="password" name="password" id="form3Example4c" class="form-control" />
                      <label class="form-label" for="form3Example4c">Password</label>
                    </div>
                  </div>

                  <div class="form-check d-flex justify-content-center mb-5">
                    <label class="form-check-label" for="form2Example3">
                      If you don't have an account click here to <a href="register.php">Register</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button name="submit" type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">Login</button>
                  </div>

                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                <img src="https://static.vecteezy.com/system/resources/thumbnails/022/985/554/small_2x/silhouette-teamwork-male-hikers-climbing-up-mountain-cliff-and-one-of-them-giving-helping-hand-people-helping-and-team-work-concept-photo.jpg" class="img-fluid" alt="Image">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>