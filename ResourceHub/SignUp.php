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

            

            $sql = "INSERT INTO users ( email, password ) VALUES ( :email, :password )";
            $stmt = $conn->prepare($sql);
            
            if ($stmt->execute([ ':email' => $email, ':password' => $hashedPassword ])) {
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
<html>
<head>
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <p><?php echo $message; ?></p>
    <form method="POST" enctype="multipart/form-data">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="SignIn.php">Sign In Here</a>.</p>
</body>
</html>
