<?php
session_start();
if(!isset($_SESSION['sudo_email'])){
    // header("Location: ../public/login.php");
    echo "Not logged in";
    exit();
}
include_once '../config/db.php'; // adjust if needed

if (isset($_POST['message'])) {
    $msg = strtolower(trim($_POST['message']));
    $reply = "Sorry, I didn’t understand that.";

    // Pattern: "number of admins"
    if (strpos($msg, "number of admins") !== false || strpos($msg, "how many admins") !== false) {
        $result = $conn->query("SELECT COUNT(*) as total FROM admins");
        $data = $result->fetch_assoc();
        $reply = "There are " . $data['total'] . " admins in the system.";
    }

    // Pattern: "list all admins"
    elseif (strpos($msg, "list all admins") !== false || strpos($msg, "show me admins") !== false) {
        $result = $conn->query("SELECT * FROM admins");
        if ($result->num_rows > 0) {
            $reply = "Admin List:<br>";
            while ($row = $result->fetch_assoc()) {
                $reply .= "- " . $row['full_name'] . " (" . $row['email'] . ")<br>";
            }
        } else {
            $reply = "No admins found.";
        }
    } elseif (strpos($msg, "add admin") !== false) {
        if (preg_match('/name=(.+?)\s+email=([^\s]+)\s+password=(\S+)\s+role=(\S+)/', $msg, $matches)) {
            $full_name = trim($matches[1]);
            $email = trim($matches[2]);
            $password = password_hash(trim($matches[3]), PASSWORD_BCRYPT); // Securely hash password
            $role = trim($matches[4]);

            // Insert into DB
            $stmt = $conn->prepare("INSERT INTO admins (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $password, $role);
            if ($stmt->execute()) {
                $reply = " Admin '$full_name' added successfully with role '$role'.";
            } else {
                $reply = "Error adding admin: " . $stmt->error;
            }
        } else {
            $reply = "Please provide details like this:\nadd admin name=John Doe email=john@example.com password=1234 role=Executive";
        }
    }
    // Pattern: "delete admin"
    elseif (strpos($msg, "delete admin") !== false) {
        // Accept either name or email
        if (preg_match('/delete admin (name|email)=(.+)/', $msg, $matches)) {
            $field = $matches[1] == 'email' ? 'email' : 'full_name';
            $value = trim($matches[2]);

            $stmt = $conn->prepare("DELETE FROM admins WHERE $field = ?");
            $stmt->bind_param("s", $value);
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $reply = "Admin with $field '$value' deleted successfully.";
            } else {
                $reply = " No admin found with $field '$value'.";
            }
        } else {
            $reply = " To delete, use:\ndelete admin email=example@site.com\nor\ndelete admin name=John Doe";
        }
    } elseif (strpos($msg, "list all users") !== false || strpos($msg, "show me users") !== false) {
        $result = $conn->query("SELECT * FROM users");
        if ($result->num_rows > 0) {
            $reply = "User List:<br>";
            while ($row = $result->fetch_assoc()) {
                $reply .= "- " . $row['full_name'] . " (" . $row['email'] . ")<br>";
            }
        } else {
            $reply = "No users found.";
        }
    }
    echo json_encode(['reply' => $reply]);
} else {
    echo json_encode(['reply' => 'No message received.']);
}
?>