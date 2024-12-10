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
    } else {
        $message = 'Invalid email or password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="signin-container">
    <header class="signin-header">
        <h1>Sign In</h1>
    </header>
    <?php if (!empty($message)) : ?>
        <p class="signin-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" class="signin-form">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn-primary">Login</button>
    </form>
    <p class="signin-alt-options">
        Don't have an account? <a href="SignUp.php">Sign Up Here</a>.
        <br>
        <a href="PWResetCode.php">Forgot Password?</a>
    </p>
</div>
</body>
</html>
