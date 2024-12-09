<?php
require 'db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: HomePage.php");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    
        // Redirect based on user role
        if ($_SESSION['role'] === 'admin') {
            header("Location: AdminPanel.php");
        } else {
            header("Location: HomePage.php");
        }
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
</head>
<body>
    <h1>Sign In</h1>
    <p><?php echo $message; ?></p>
    <form method="POST">
        <label for="username">Email:</label>
        <input type="text" name="email" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="SignUp.php">Sign Up Here</a>.</p>
    <p><a href="PWResetCode.php">Forgot Password?</a></p>
</body>
</html>