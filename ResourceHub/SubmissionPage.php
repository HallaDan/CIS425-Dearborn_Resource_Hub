<?php
session_start();
require 'db.php'; // Include the database connection script

if (!isset($_SESSION['user_id'])) {
    // Redirect to SignIn page if the user is not logged in
    header("Location: SignIn.php");
    exit();
}

$successMessage = '';

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // default language (for now?)
}


$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$is_admin = ($user['role'] === 'admin');


//generic translations
$translations = [
    'en' => [
        'home_page' => 'Home Page',
        'business_listings' => 'Find Local Experts',
        'contribute' => 'Contribute',
        'sign_out' => 'Sign Out',
        'business_name' => 'Business Name',
        'business_category' => 'Business Category',
        'address' => 'Location Address',
        'business_phone' => 'Business Phone Number',
        'website' => 'Business Website',
        'language' => 'Language',
        'submit' => 'Submit',
        'submit_business' => 'Submit your Business'
    ],
    'ar' => [
        'home_page' => 'الصفحة الرئيسية',
        'business_listings' => 'ابحث عن خبراء محليين',
        'contribute' => 'أضف القوائم',
        'sign_out' => 'تسجيل الخروج',
        'business_name' => 'اسم العمل',
        'business_category' => 'فئة الأعمال',
        'address' => 'عنوان الموقع',
        'business_phone' => 'رقم هاتف العمل',
        'website' => 'موقع العمل',
        'language' => 'لغة',
        'submit' => 'إرسال',
        'submit_business' => 'قدّم عملك'
    ],
    'es' => [
        'home_page' => 'Página de Inicio',
        'business_listings' => 'Encuentra expertos locales',
        'contribute' => 'Contribuir listados',
        'sign_out' => 'Cerrar sesión',
        'business_name' => 'Nombre del Negocio',
        'business_category' => 'Categoría del Negocio',
        'address' => 'Dirección',
        'business_phone' => 'Número de Teléfono',
        'website' => 'Sitio Web',
        'language' => 'Idioma',
        'submit' => 'Enviar',
        'submit_business' => 'Envía tu Negocio'
    ],
];

$lang = $translations[$_SESSION['lang']];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Fetch form data
    $businessName = $_POST['businessName'];
    $businessCategory = $_POST['businessCategory'];
    $address = $_POST['address'];
    $businessPhone = $_POST['businessPhone'];
    $website = $_POST['website'];
    $language = $_POST['language'];

    //Validate Form Data
    if(empty($businessName) || empty($businessCategory) || empty($address) || empty($businessPhone) || empty($website) || empty($language)){
        die('Error: All fields are required.');
    }

    // Fetch the currently signed-in user's ID
    $businessID = $_SESSION['user_id'];

    //Verify the user exists in the database to prevent foreign key errors
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$businessID]);
    if($stmt->rowCount() == 0){
        die('Please Sign in to submit business form');
    }
    
    try {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO admin_approval (businessID, businessName, businessCategory, address, businessPhone, website, language) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Execute the statement with the data
        $stmt->execute([$businessID, $businessName, $businessCategory, $address, $businessPhone, $website, $language]);

        $successMessage = "Your business has been submitted for admin approval";
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Submit Business</title>
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
        .center-content form {
            width: 400px;
            margin: 0;
            padding: 20px; /* Add padding inside the form */
            color: #ffffff; /* White text color */
            background-color: #002244;
            border-radius: 10px; /* Round the edges of the form */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow for better depth */
        }
        .center-content label, .center-content input, .center-content select {
            display: block;
            margin-bottom: 15px; /* Increase space between form elements */
            font-size: 16px; /* Increase font size for better readability */
        }
        .center-content input {
            padding: 10px;
            width: 377px; /* Make input and select elements take up full width */
            font-size: 16px; /* Increase the font size */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .center-content select {
            padding: 10px;
            width: 100%; /* Make input and select elements take up full width */
            font-size: 16px; /* Increase the font size */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .center-content input[type="submit"] {
            width: 100%; /* Make input and select elements take up full width */
            background-color: #004488;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
        .center-content input[type="submit"]:hover {
            background-color: #003366; /* Darker shade on hover */
        }
        .language-selector {
            margin-right: 20px; /* Space between language selector and form */
        }
    </style>
</head>
    <body>
        <header>
            <div class="nav-container">
                <h1>Multilingual Resource Hub</h1>
            </div>
        </header>

    <div class="hamburger-container">
        <div class="dropdown">
            <button class="dropdown-toggle">☰</button>
            <ul class="hamburger-menu">
                <li><a href="HomePage.php"><?= $lang['home_page'] ?></a></li>
                <li><a href="BusinessListingPage.php"><?= $lang['business_listings'] ?></a></li>
                <?php if ($is_admin): ?>
                    <li><a href="AdminPanel.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="SignOut.php"><?= $lang['sign_out'] ?></a></li>
            </ul>
        </div>

        <!-- Language Selector Form -->
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

            <!-- Submission Form -->
            <div class="center-content">
            <h2 style="text-align:center;"><?= $lang['submit_business'] ?></h2>
                <?php if ($successMessage): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="SubmissionPage.php">
                    <label for="businessName"><?= $lang['business_name'] ?>:</label>
                    <input type="text" id="businessName" name="businessName" required>
                    
                    <label for="businessCategory"><?= $lang['business_category'] ?>:</label>
                    <input type="text" id="businessCategory" name="businessCategory" required>
                    
                    <label for="address"><?= $lang['address'] ?>:</label>
                    <input type="text" id="address" name="address" required>
                    
                    <label for="businessPhone"><?= $lang['business_phone'] ?>:</label>
                    <input type="text" id="businessPhone" name="businessPhone" required>
                    
                    <label for="website"><?= $lang['website'] ?>:</label>
                    <input type="text" id="website" name="website" required>

                    <label for="language"><?= $lang['language'] ?>:</label>
                    <select id="language" name="language" required>
                        <option value=""><?= $lang['language'] ?></option>
                        <option value="English">English</option>
                        <option value="Spanish">Spanish</option>
                        <option value="Arabic">Arabic</option>
                    </select>
                    
                    <input type="submit" name="submit" value="<?= $lang['submit'] ?>">
                </form>
            </div>
        </div>
        <script src="assets/js/script.js"></script>
    </body>
</html>