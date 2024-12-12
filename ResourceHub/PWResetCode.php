<?php 
session_start();
require 'db.php';
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use \Mailjet\Resources;

// Load environment variables (API keys)
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['API_GENERAL_KEY'];
$apiSecret = $_ENV['API_SECRET_KEY'];

// Initialize variables
$message = "";
$success = false; // Track success status

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; //default language (for now?)
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // Check if email exists in users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a random 6-digit code
        $code = random_int(100000, 999999);

        // Insert the code into the reset_code table
        $insertStmt = $conn->prepare("INSERT INTO reset_code (user_id, code) VALUES (:user_id, :code)");
        $insertStmt->execute(['user_id' => $user['id'], 'code' => $code]);

        // Send the code via Mailjet
        $mj = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);
        $emailData = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "dbresourcehub@gmail.com",
                        'Name' => "Dearborn Resource Hub"
                    ],
                    'To' => [
                        [
                            'Email' => $email,
                            'Name' => "User"
                        ]
                    ],
                    'Subject' => "Your Password Reset Code",
                    'TextPart' => "Your reset code is: $code",
                    'HTMLPart' => "<h3>Your reset code is: <strong>$code</strong></h3>"
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $emailData]);

        if ($response->success()) {
            $message = "A reset code has been sent to your email.";
            $success = true; // Mark success
        } else {
            $message = "Failed to send email. Please try again.";
        }
    } else {
        $message = "Email not found. Please check and try again.";
    }
}

$translations = [
    'en' => [
        'update_pw' => 'Update Password',
        'email' => 'Email',
        'send_code' => 'Send Code',
        'go_back' => 'Back to Sign In'                
    ],
    'ar' => [
        'update_pw' => 'تحديث كلمة المرور',
        'email' => 'البريد الإلكتروني',
        'send_code' => 'إرسال الرمز',
        'go_back' => 'العودة إلى تسجيل الدخول'
    ],
    'es' => [
        'update_pw' => 'Actualizar contraseña',
        'email' => 'Correo electrónico',
        'send_code' => 'Enviar código',
        'go_back' => 'Volver a iniciar sesión'   
    ],
];
$lang = $translations[$_SESSION['lang']];
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Code</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* body */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #3D648A;
        }

        /* Container for form */
        .login-container {
            background-color: #00274C;
            border-radius: 10px;
            padding: 40px;
            width: 400px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            text-align: center;
            color: white;
        }

        .login-container h1 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .login-container label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            text-align: left;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        /* Button styling */
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #FFCB05;
            border: none;
            color: #00274C;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .login-container button:hover {
            background-color: #FFB600;
        }

        /* Error message */
        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        /* Footer link */
        .footer a {
            margin-top: 20px;
            color: #FFCB05;
            text-decoration: none;
        }

        .footer a:hover {
            margin-top: 20px;
            text-decoration: underline;
        }
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
    <script>
        // Redirect if success
        <?php if ($success): ?>
        setTimeout(function() {
            alert("A reset code has been sent to your email.");
            window.location.href = "PWUpdate.php";
        }, 500); // Redirect after 0.5 seconds
        <?php endif; ?>
    </script>
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
            <div class="login-container">
                <h1><?= $lang['update_pw'] ?></h1>
                <?php if (!empty($message)): ?>
                    <p class="error"><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
                <form method="POST">
                    <label for="email"><?= $lang['email'] ?></label>
                    <input name="email" id="email" required>
                    <button type="submit"><?= $lang['send_code'] ?></button>
                </form>
                <div class="footer">
                    <a href="SignIn.php"><?= $lang['go_back'] ?></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

