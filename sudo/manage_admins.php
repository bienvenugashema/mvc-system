<?php
session_start();

if(!isset($_SESSION['sudo_email'])) {
    header('Location: ../public/login.php');
    exit();
}
include_once '../config/db.php';
include_once '../config/notifications.php';

?>

<html>
    <head>
        <title>Manage Admins</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <link href="https://mdbcdn.b-cdn.net/css/mdb.min.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
        <style>
            .container {
                margin-top: 50px;
            }
            .card {
                border-radius: 10px;
            }
            .card-header {
                background-color:rgb(57, 62, 68);
                color: white;
                border-radius: 10px 10px 0 0;
            }
            .card-body {
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <h1 class="text-center">Manage Admins</h1>
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-12 text-end">
                    <a href="add_admin.php" class="text-primary text-decoration-none">Add New Admin</a>
                </div>
                <div class="col-md-12 text-end">
                    <a href="dashboard.php" class="text-primary text-decoration-none">Go Home</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Admin List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM admins");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['role']); ?></td>
                                    <td>
                                        <a href="edit_admin.php?email=<?php echo urlencode($row['email']); ?>" class="btn btn-primary">Edit</a>
                                        <?php if ($row['role'] == 'sudo') { ?>
                                            <button class="btn btn-secondary" disabled>Cannot Delete Super Admin</button>
                                        <?php } else { ?>
                                            <a href="delete_admin.php?email=<?php echo urlencode($row['email']); ?>" class="btn btn-danger">Delete</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </body>
</html>