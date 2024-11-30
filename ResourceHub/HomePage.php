<?php
session_start();
require 'db.php';

//redirect to SignIn.php if the user is not logged in
//we dont need to keep this obviously, but just to test login/logout
if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

// get user details
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; //default language (for now?)
}

//generic translations
$translations = [
    'en' => [
        'welcome' => 'Welcome!',
        'description' => 'Bridging Language Barriers in Your Community',
        'business_listings' => 'Find Local Experts',
        'contribute' => 'Contribute',
        'sign_out' => 'Sign Out',
    ],
    'ar' => [
        'welcome' => 'مرحبًا!',
        'description' => 'سد الفجوة اللغوية في مجتمعك',
        'business_listings' => 'ابحث عن خبراء محليين',
        'contribute' => 'أضف القوائم',
        'sign_out' => 'تسجيل الخروج',
    ],
    'es' => [
        'welcome' => '¡Bienvenido!',
        'description' => 'Superando las barreras lingüísticas en tu comunidad',
        'business_listings' => 'Encuentra expertos locales',
        'contribute' => 'Contribuir listados',
        'sign_out' => 'Cerrar sesión',
    ],
];
$lang = $translations[$_SESSION['lang']];
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage - Resource Hub</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<body>
<header>
        <div class="nav-container">
            <h1>Multilingual Resource Hub</h1>
        </div>
        <div class="dropdown">
            <button class="dropdown-toggle">☰</button>
            <ul class="hamburger-menu">
                <li><a href="BusinessListingPage.php"><?= $lang['business_listings'] ?></a></li>
                <li><a href="SubmissionPage.php"><?= $lang['contribute'] ?></a></li>
                <li><a href="SignOut.php"><?= $lang['sign_out'] ?></a></li>
            </ul>
        </div>
    </header>

    <main>
        <section class="language-selector">
            <form method="GET" action="">
                <label>Select Your Language:</label><br>
                <input type="radio" name="lang" value="en" <?= $_SESSION['lang'] === 'en' ? 'checked' : '' ?>> English<br>
                <input type="radio" name="lang" value="ar" <?= $_SESSION['lang'] === 'ar' ? 'checked' : '' ?>> Arabic<br>
                <input type="radio" name="lang" value="es" <?= $_SESSION['lang'] === 'es' ? 'checked' : '' ?>> Spanish<br>
                <button type="submit">Apply</button>
            </form>
        </section>
        <section class="content">
            <h2><?= $lang['welcome'] ?></h2>
            <p><?= $lang['description'] ?></p>
        </section>
    </main>

    <script src="assets/js/script.js"></script>
</body>
</html>
