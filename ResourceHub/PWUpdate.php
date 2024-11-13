<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$message = '';

// Fetch user details
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Update password only if filled and matches validation
    if ($newPassword) {
        if ($newPassword !== $confirmPassword) {
            $message = "Passwords do not match.";
        } elseif (strlen($newPassword) < 10 || !preg_match("/[A-Z]/", $newPassword) || !preg_match("/[a-z]/", $newPassword) || !preg_match("/[0-9]/", $newPassword)) {
            $message = "Password must be at least 10 characters long and include uppercase, lowercase letters, and a number.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->execute([':password' => $hashedPassword, ':id' => $_SESSION['user_id']]);
            $message = "Password updated successfully.";
        }
    }
    
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Password</title>
</head>
<body>
    <h1>Update Password</h1>
    <p><?php echo $message; ?></p>
    <form method="POST" enctype="multipart/form-data">
        <label>New Password:</label>
        <input type="password" name="new_password">
        <label>Confirm New Password:</label>
        <input type="password" name="confirm_password">
        <button type="submit">Update</button>
    </form>
    <a href="HomePage.php">Back to Home</a>
</body>
</html>
