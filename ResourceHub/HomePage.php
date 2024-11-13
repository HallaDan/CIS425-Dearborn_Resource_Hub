<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>
    <h1>HomePage</h1>
    <!-- <a href="update_profile.php">Update Profile</a> -->
    <a href="SignOut.php">Sign Out</a>
</body>
</html>
