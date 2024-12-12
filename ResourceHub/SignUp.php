<?php
require 'db.php';

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // default language (for now?)
}

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

$translations = [
    'en' => [
        'sign_in' => 'Sign In',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'register' => 'Register',
        'already_registered' => 'Alrady Registered? ',
        'sign_in_here' => 'Sign In Here',
    ],
    'ar' => [
        'sign_in' => 'تسجيل الدخول',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'confirm_password' => 'تأكيد كلمة المرور',
        'register' => 'تسجيل',
        'already_registered' => 'مسجل بالفعل؟ ',
        'sign_in_here' => 'سجّل الدخول هنا',
    
    ],
    'es' => [
        'sign_in' => 'Iniciar Sesión',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'confirm_password' => 'Confirmar contraseña',
        'register' => 'Registrarse',
        'already_registered' => '¿Ya estás registrado? ',
        'sign_in_here' => 'Inicia sesión aquí',
    ],
];

$lang = $translations[$_SESSION['lang']];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<style>
        .center-content-container {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            padding: 20px;
        }
        .center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: left; /* Center-align text for child elements */
        }
    </style>
<body>
    <div class="center-content-container"> 
        <div class="language-selector">
            <form method="GET" action="">
                <label>Select Your Language:</label><br>
                <input type="radio" name="lang" value="en" <?= $_SESSION['lang'] === 'en' ? 'checked' : '' ?>> English<br>
                <input type="radio" name="lang" value="ar" <?= $_SESSION['lang'] === 'ar' ? 'checked' : '' ?>> Arabic<br>
                <input type="radio" name="lang" value="es" <?= $_SESSION['lang'] === 'es' ? 'checked' : '' ?>> Spanish<br>
                <button type="submit">Apply</button>
            </form>
        </div>

        <div class="center-content">
            <div class="signup-container">
                <header class="signup-header">
                    <h1><?= $lang['sign_in'] ?></h1>
                </header>
                <?php if (!empty($message)) : ?>
                    <p class="signup-message"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <form method="POST" class="signup-form">
                    <div class="form-group">
                        <label for="email"><?= $lang['email'] ?></label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password"><?= $lang['password'] ?></label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password"><?= $lang['confirm_password'] ?></label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-primary"><?= $lang['register'] ?></button>
                </form>
                <p class="signup-alt-options">
                <?= $lang['already_registered'] ?> <a href="SignIn.php"><?= $lang['sign_in_here'] ?></a>.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
