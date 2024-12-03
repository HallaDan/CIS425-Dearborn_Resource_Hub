<?php 
session_start();
require 'db.php'; // Include the database connection script

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // default language (for now?)
}

//generic translations
$translations = [
    'en' => [
        
        'business_listings' => 'Find Local Experts',
        'contribute' => 'Contribute',
        'sign_out' => 'Sign Out',
    ],
    'ar' => [
        'business_listings' => 'ابحث عن خبراء محليين',
        'contribute' => 'أضف القوائم',
        'sign_out' => 'تسجيل الخروج',
    ],
    'es' => [
        'business_listings' => 'Encuentra expertos locales',
        'contribute' => 'Contribuir listados',
        'sign_out' => 'Cerrar sesión',
    ],
];

$lang = $translations[$_SESSION['lang']];

// Determine which table to use based on the selected language
$table_mapping = [
    'en' => 'business_en',
    'ar' => 'business_ar',
    'es' => 'business_es',
];

$attribute_mapping = [
    'en' => [
        'businessID' => 'enBusinessID',
        'businessName' => 'enBusinessName',
        'businessCategory' => 'enBusinessCategory',
        'address' => 'enAddress',
        'businessPhone' => 'enBusinessPhone',
        'website' => 'enWebsite',
    ],
    'ar' => [
        'businessID' => 'arBusinessID',
        'businessName' => 'arBusinessName',
        'businessCategory' => 'arBusinessCategory',
        'address' => 'arAddress',
        'businessPhone' => 'arBusinessPhone',
        'website' => 'arWebsite',
    ],
    'es' => [
        'businessID' => 'esBusinessID',
        'businessName' => 'esBusinessName',
        'businessCategory' => 'esBusinessCategory',
        'address' => 'esAddress',
        'businessPhone' => 'esBusinessPhone',
        'website' => 'esWebsite',
    ],
];

$selected_table = $table_mapping[$_SESSION['lang']];
$attributes = $attribute_mapping[$_SESSION['lang']];

// Fetch data from the selected table
try {
    $stmt = $conn->prepare("SELECT * FROM {$selected_table}");
    $stmt->execute();
    $businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ListingPage - Resource Hub</title>
        <link rel="stylesheet" href="assets/css/styles.css">
        <style>
        .table-container {
            padding: 10px;
        }
        table {
            margin: auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 600px) {
            .table-container {
                overflow-x: scroll;
            }
        }
        .language-selector {
            margin: auto;
            border-collapse: collapse;
        }
      
    </style>
    </head>
    <body>
        <header>
            <div class="nav-container">
                <h1>Multilingual Resource Hub</h1>
            </div>
            <div class="dropdown">
                <button class="dropdown-toggle">☰</button>
                <ul class="hamburger-menu">
                    <li><a href="BussinessListingPage.php"><?= $lang['business_listings'] ?></a></li>
                    <li><a href="SubmissionPage.php"><?= $lang['contribute'] ?></a></li>
                    <li><a href="SignOut.php"><?= $lang['sign_out'] ?></a></li>
                </ul>
            </div>
        </header>

        <div class="container">
            <h2 style="text-align:center;"><?= $lang['business_listings'] ?></h2>
            <div class="language-selector">
                <form method="GET" action="">
                    <label>Select Your Language:</label><br>
                    <input type="radio" name="lang" value="en" <?= $_SESSION['lang'] === 'en' ? 'checked' : '' ?>> English<br>
                    <input type="radio" name="lang" value="ar" <?= $_SESSION['lang'] === 'ar' ? 'checked' : '' ?>> Arabic<br>
                    <input type="radio" name="lang" value="es" <?= $_SESSION['lang'] === 'es' ? 'checked' : '' ?>> Spanish<br>
                    <button type="submit">Apply</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <?php if (count($businesses) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Business Name</th>
                            <th>Category</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Website</th>
                            <th>Language</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($businesses as $business): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($business[$attributes['businessID']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['businessName']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['businessCategory']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['address']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['businessPhone']]); ?></td>
                                <td><a href="<?php echo htmlspecialchars($business[$attributes['website']]); ?>" target="_blank"><?php echo htmlspecialchars($business[$attributes['website']]); ?></a></td>
                                <td><?php echo htmlspecialchars($_SESSION['lang']); ?></td>
                                <td><?php echo htmlspecialchars($business['create_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No businesses found.</p>
            <?php endif; ?>
        </div>
        
        <script src="assets/js/script.js"></script>
    </body>
</html>