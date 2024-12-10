<?php
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (strlen($password) < 10 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $message = "Password must be at least 10 characters long and include uppercase, lowercase letters, and a number.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        $checkUser = $conn->prepare("SELECT * FROM users WHERE  email = :email");
        $checkUser->execute([':email' => $email]);
        
        if ($checkUser->rowCount() > 0) {
            $message = "Email is already taken.";
        } else {
            // Password hash
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            

            $sql = "INSERT INTO users ( email, password, role ) VALUES ( :email, :password, :role )";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([ ':email' => $email, ':password' => $hashedPassword, ':role' => 'user' ])) {
                $message = "Successfully registered!";
                header("Location: SignIn.php");
                exit();
            } else {
                $message = "Registration failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<div class="signup-container">
    <header class="signup-header">
        <h1>Sign Up</h1>
    </header>
    <?php if (!empty($message)) : ?>
        <p class="signup-message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" class="signup-form">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <button type="submit" class="btn-primary">Register</button>
    </form>
    <p class="signup-alt-options">
        Already registered? <a href="SignIn.php">Sign In Here</a>.
    </p>
</div>
</body>
</html>
