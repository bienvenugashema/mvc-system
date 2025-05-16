<?php
session_start();
if(isset($_SESSION['user_email'])) {
    include_once '../config/db.php'; // Include database connection
    $user_email = $_SESSION['user_email'];
    // You can fetch user details from the database if needed
    // For example:
    $sql = "SELECT * FROM users WHERE email='$user_email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {  
        $user = $result->fetch_assoc();
    }
    if (isset($_POST['submit_complaint'])) {
        $complaintTitle = $_POST['complaintTitle'];
        $complaintType = $_POST['complaintType'];
        $complaintDescription = $_POST['complaintDescription'];
        $complaintId = random_int(100000, 999999);
        $stmt = $conn->prepare("INSERT INTO complaints (user_id, ticket_id, title, message) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("iiss", $user['id'], $complaintId, $complaintTitle, $complaintDescription);
        if ($stmt->execute()) {
            echo "<script>alert('Complaint submitted successfully!');</script>";
        } else {
            echo "Error:" . $stmt->error;
        }
    }
} else {
    // User is not logged in, redirect to login page
    header("Location: ../public/login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- MDB CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <!-- Toggle button -->
            <button
                class="navbar-toggler"
                type="button"
                data-mdb-toggle="collapse"
                data-mdb-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img
                        src="https://mdbcdn.b-cdn.net/img/logo/mdb-transaprent-noshadows.webp"
                        height="15"
                        alt="MDB Logo"
                        loading="lazy"
                    />
                </a>
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Complaint</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    
                    <li class="nav-item">
                        <span class="nav-link"><?php echo $user['full_name']; ?></span>
                    </li>
                </ul>
                <!-- Right elements -->
                <div class="d-flex align-items-center">
                    <a class="nav-link" href="view_status.php" style="margin: 0 10px;">View Feedback</a>
                    <a href="../public/logout.php">Logout</a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Main content -->
     <div class="container mt-4">
        <h1 class="text-center">Welcome to the Complaint Dashboard</h1>
        <p class="text-center">Here you can submit your complaints and feedback.</p>
            
                    <h2>Submit a Complaint</h2>
                    <form action="dashboard.php" method="POST">
                        <div class="mb-3">
                            <label for="complaintTitle" class="form-label">Complaint Title</label>
                            <input type="text" class="form-control" id="complaintTitle" name="complaintTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="complaintType" class="form-label">Comment Type</label>
                            <select class="form-select" id="complaintType" name="complaintType" required>
                                <option value="">Select Comment Type</option>
                                <option value="service">Complaint</option>
                                <option value="product">Feedback</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="complaintDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="complaintDescription" name="complaintDescription" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit_complaint" class="btn btn-primary">Click to Submit</button>
                    </form>
                </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
</body>
</html>