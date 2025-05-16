<?php
include_once '../config/db.php'; 
session_start();

if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [
        'errors' => [],
        'success' => []
    ];
}

function addErrorMessage($message) {
    $_SESSION['messages']['errors'][] = $message;
}

function addSuccessMessage($message) {
    $_SESSION['messages']['success'][] = $message;
}

function displayMessages() {
    if (!empty($_SESSION['messages']['errors'])) {
        echo '<div class="alert alert-danger alert-dismissible fade show">';
        foreach ($_SESSION['messages']['errors'] as $error) {
            echo '<p>'.$error.'</p>';
        }
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
    
    if (!empty($_SESSION['messages']['success'])) {
        echo '<div class="alert alert-success alert-dismissible fade show">';
        foreach ($_SESSION['messages']['success'] as $success) {
            echo '<p>'.$success.'</p>';
        }
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
    
    $_SESSION['messages'] = [
        'errors' => [],
        'success' => []
    ];
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        addErrorMessage('Please fill in all fields.');
        header("Location: register.php");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        addErrorMessage('Invalid email format.');
        header("Location: register.php");
        exit();
    }
    
    if (strlen($password) < 8) {
        addErrorMessage('Password must be at least 8 characters long.');
        header("Location: register.php");
        exit();
    }
    
    if (!preg_match("/[A-Z]/", $password)) {
        addErrorMessage('Password must contain at least one uppercase letter.');
        header("Location: register.php");
        exit();
    }
    
    if (!preg_match("/[a-z]/", $password)) {
        addErrorMessage('Password must contain at least one lowercase letter.');
        header("Location: register.php");
        exit();
    }
    
    if (!preg_match("/[0-9]/", $password)) {
        addErrorMessage('Password must contain at least one number.');
        header("Location: register.php");
        exit();
    }
    
    if (!preg_match("/[\W]/", $password)) {
        addErrorMessage('Password must contain at least one special character.');
        header("Location: register.php");
        exit();
    }
    
    if ($password !== $confirm_password) {
        addErrorMessage('Passwords do not match.');
        header("Location: register.php");
        exit();
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        addErrorMessage('Email already exists.');
        header("Location: register.php");
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        addSuccessMessage('Registration successful!');
        header("Location: login.php");
        exit();
    } else {
        addErrorMessage('Error: ' . $stmt->error);
        header("Location: register.php");
        exit();
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
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 20px;">
                        <div class="card-body p-md-3">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                                    
                                    <?php displayMessages(); ?>
                                    
                                    <form class="mx-1 mx-md-4" method="post" action="register.php">
                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="text" name="name" id="form3Example1c" class="form-control" required />
                                                <label class="form-label" for="form3Example1c">Your Name</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="email" name="email" id="form3Example3c" class="form-control" required />
                                                <label class="form-label" for="form3Example3c">Your Email</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="password" name="password" id="form3Example4c" class="form-control" required />
                                                <label class="form-label" for="form3Example4c">Password</label>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center mb-4">
                                            <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                            <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                <input type="password" name="confirm_password" id="form3Example4cd" class="form-control" required />
                                                <label class="form-label" for="form3Example4cd">Repeat your password</label>
                                            </div>
                                        </div>

                                        <div class="form-check d-flex justify-content-center mb-5">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" required />
                                            <label class="form-check-label" for="form2Example3">
                                                I agree all statements in <a href="#!">Terms of service</a><br>
                                                If you have an account click here to <a href="login.php">Login</a>
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                            <button name="submit" type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg">Register</button>
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
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>