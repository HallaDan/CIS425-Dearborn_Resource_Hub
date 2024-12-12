<?php
require 'db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: HomePage.php");
}

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; //default language (for now?)
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


$translations = [
    'en' => [
        'sign_in' => 'Sign In',
        'email' => 'Email',
        'password' => 'Password',
        'log_in' => 'Login',
        'no_acct'=> "Don't have an account?",
        'sign_up_here' => 'Sign Up Here.',
        'forgot_PW' => 'Forgot Password?'        
    ],
    'ar' => [
        'sign_in' => 'تسجيل الدخول',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'log_in' => 'دخول',
        'no_acct'=> "ليس لديك حساب؟",
        'sign_up_here' => 'سجل هنا.',
        'forgot_PW' => 'نسيت كلمة المرور؟'
    ],
    'es' => [
        'sign_in' => 'Iniciar Sesión',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'log_in' => 'Acceder',
        'no_acct'=> "¿No tienes una cuenta?",
        'sign_up_here' => 'Regístrate Aquí.',
        'forgot_PW' => '¿Olvidaste tu Contraseña?'
    ],
];
$lang = $translations[$_SESSION['lang']];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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
        /* Sign-in container */
        .signin-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            margin: 40px auto;
        }

        /* Header for the sign-in page */
        .signin-header {
            background-color: #00274C; /* Michigan Blue */
            color: #fdd835; /* Maize Yellow */
            padding: 20px;
            border-radius: 10px;
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Form styling */
        .signin-form .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .signin-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .signin-form input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .signin-form input:focus {
            border-color: #00274C;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 39, 76, 0.5);
        }

        /* Buttons */
        .btn-primary {
            background-color: #00274C;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #283593; /* Slightly lighter blue */
        }

        /* Message styling */
        .signin-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Alternative options */
        .signin-alt-options {
            margin-top: 20px;
            font-size: 0.9em;
        }

        .signin-alt-options a {
            color: #00274C;
            text-decoration: none;
            font-weight: bold;
        }

        .signin-alt-options a:hover {
            text-decoration: underline;
        }

        /* Sign-up container */
        .signup-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            margin: 40px auto;
        }

        /* Header for the sign-up page */
        .signup-header {
            background-color: #00274C; /* Michigan Blue */
            color: #fdd835; /* Maize Yellow */
            padding: 20px;
            border-radius: 10px;
            font-size: 1.8em;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Form styling */
        .signup-form .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .signup-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .signup-form input {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .signup-form input:focus {
            border-color: #00274C;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 39, 76, 0.5);
        }

        /* Buttons */
        .btn-primary {
            background-color: #00274C;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 1em;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #283593; /* Slightly lighter blue */
        }

        /* Message styling */
        .signup-message {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
        }

        /* Alternative options */
        .signup-alt-options {
            margin-top: 20px;
            font-size: 0.9em;
        }

        .signup-alt-options a {
            color: #00274C;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-alt-options a:hover {
            text-decoration: underline;
        }
    </style>
</head>
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
            <div class="signin-container">
            <header class="signin-header">
                <h1><?= $lang['sign_in']?></h1>
            </header>
            <?php if (!empty($message)) : ?>
                <p class="signin-message"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <form method="POST" class="signin-form">
                <div class="form-group">
                    <label for="email"><?= $lang['email'] ?></label>
                    <input type="text" name="email" id="email" required>
                </div>
                <div class="form-group"><?= $lang['password'] ?></label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn-primary"><?= $lang['log_in'] ?></button>
            </form>
            <p class="signin-alt-options">
                <?= $lang['no_acct'] ?> <a href="SignUp.php"><?= $lang['sign_up_here'] ?></a>.
                <br>
                <a href="PWResetCode.php"><?= $lang['forgot_PW'] ?></a>
            </p>
            </div>
        </div>            
    </div>
</body>
</html>
