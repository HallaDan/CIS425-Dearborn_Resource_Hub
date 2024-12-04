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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Admin Panel</h1>
    <!-- <a href="update_profile.php">Update Profile</a> -->
    <a href="SignOut.php">Sign Out</a>
</body>
</html>