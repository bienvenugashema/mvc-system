<?php
session_start();
if(!isset($_SESSION['sudo_email'])) {
    header('Location: ../public/login.php');
    exit();
}

include_once '../config/db.php';
include_once '../config/notifications.php';

if (isset($_POST['reply_complaint'])) {
    $complaint_id = $_POST['complaint_id'];
    $reply_message = $_POST['reply_message'];

    $stmt = $conn->prepare("INSERT INTO replies (complaint_id, admin_email, reply_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $complaint_id, $_SESSION['sudo_email'], $reply_message);

    if ($stmt->execute()) {
        $update_status = $conn->prepare("UPDATE complaints SET status = 'resolved' WHERE ticket_id = ?");
        $update_status->bind_param("i", $complaint_id);
        $update_status->execute();
        echo "<script>alert('Reply sent successfully!');</script>";
    } else {
        echo "<script>alert('Error sending reply: " . $stmt->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reply</title>
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
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        </style>
</head>
<body>

    <div class="container">          
    <div class="card mt-4">
        <div class="card-header">
            <h5>Reply to Complaint</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <?php 
                $seletced_complaint = $conn->prepare("SELECT * FROM complaints WHERE ticket_id = ?");
                $seletced_complaint->bind_param("i", $_GET['ticket_id']);
                $seletced_complaint->execute();
                $result = $seletced_complaint->get_result();
                if ($result->num_rows > 0) {
                    $complaint = $result->fetch_assoc();
                } else {
                    echo "<script>alert('No complaint found with this ID.');</script>";
                    exit();
                }
                ?>
                <input type="hidden" name="complaint_id" value="<?php echo $_GET['ticket_id']; ?>">
                <div class="mb-3">
                    <label for="complaint_title" class="form-label">Complaint Title</label>
                    <input type="text" class="form-control" id="complaint_title" name="complaint_title" value="<?php echo $complaint['title']; ?>" readonly>
                </div>
                <div>
                    <label for="complaint_description" class="form-label">Complaint Description</label>
                    <textarea class="form-control" id="complaint_description" name="complaint_description" rows="4" readonly><?php echo $complaint['message']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="reply_message" class="form-label">Reply Message</label>
                    <textarea class="form-control" id="reply_message" name="reply_message" rows="4" required></textarea>
                </div>
                <button type="submit" name="reply_complaint" class="btn btn-primary">Send Reply</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Go Home</button>
            </form>
        </div>