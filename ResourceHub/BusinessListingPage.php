<?php 
session_start();
require 'db.php'; // Include the database connection script

$user = null; // Default value for user details
$is_admin = false; // Default value for admin status

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // get user details
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $is_admin = ($user['role'] === 'admin');
    }
}

//language switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // default language (for now?)
}           


//generic translations
$translations = [
    'en' => [
        'home_page' => 'Home Page',
        'business_listings' => 'Find Local Experts',
        'contribute' => 'Contribute',
        'sign_out' => 'Sign Out',
    ],
    'ar' => [
        'home_page' => 'الصفحة الرئيسية',
        'business_listings' => 'ابحث عن خبراء محليين',
        'contribute' => 'أضف القوائم',
        'sign_out' => 'تسجيل الخروج',
    ],
    'es' => [
        'home_page' => 'Página de Inicio',
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
        'businessName' => 'enBusinessName',
        'businessCategory' => 'enBusinessCategory',
        'address' => 'enAddress',
        'businessPhone' => 'enBusinessPhone',
        'website' => 'enWebsite',
    ],
    'ar' => [
        'businessName' => 'arBusinessName',
        'businessCategory' => 'arBusinessCategory',
        'address' => 'arAddress',
        'businessPhone' => 'arBusinessPhone',
        'website' => 'arWebsite',
    ],
    'es' => [
        'businessName' => 'esBusinessName',
        'businessCategory' => 'esBusinessCategory',
        'address' => 'esAddress',
        'businessPhone' => 'esBusinessPhone',
        'website' => 'esWebsite',
    ],
];

$selected_table = $table_mapping[$_SESSION['lang']];
$attributes = $attribute_mapping[$_SESSION['lang']];

// Pagination settings
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Fetch the total number of rows
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM {$selected_table}");
    $stmt->execute();
    $total_rows = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Calculate total number of pages
$total_pages = ceil($total_rows / $rows_per_page);

// Filter by category
$search_category = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch the total number of rows with optional search filter
try {
    $where_clause = '';
    $params = [];
    if ($search_category) {
        $where_clause = "WHERE {$attributes['businessCategory']} LIKE :category";
        $params = [':category' => "%$search_category%"];
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM {$selected_table} $where_clause");
    $stmt->execute($params);
    $total_rows = $stmt->fetchColumn();
    
    // Calculate total number of pages
    $total_pages = ceil($total_rows / $rows_per_page);

    // Fetch the rows for the current page with optional search filter
    $stmt = $conn->prepare("SELECT * FROM {$selected_table} $where_clause LIMIT :limit OFFSET :offset");
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $rows_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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
            border-radius: 10px; /* Apply border-radius to the table */
            overflow: hidden; /* Ensure cells respect the border radius */
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            background-color: #f1c40f; /* Dark blue background */
            color: #3a3a3a; /* White text color */
        }
        th {
            color: #ffffff; /* White text color */
            background-color: #002244;
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
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #333;
        }
        .pagination a.active {
            background-color: #333;
            color: white;
            border: 1px solid #333;
        }
        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px;
        }
        .search-form input[type="text"] {
            padding: 10px 15px;
            margin-right: 10px;
            border: 1px solid #ddd;
            width: 300px;
            font-size: 16px;
        }
        .search-form button {
            padding: 10px 15px;
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
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <!-- User is not logged in -->
                        <li><a href="HomePage.php"><?= $lang['home_page'] ?></a></li>
                        <li><a href="SubmissionPage.php"><?= $lang['contribute'] ?></a></li>
                        <li><a href="SignIn.php">Sign In</a></li>
                    <?php else: ?>
                        <!-- User is logged in -->
                        <li><a href="HomePage.php"><?= $lang['home_page'] ?></a></li>
                        <li><a href="SubmissionPage.php"><?= $lang['contribute'] ?></a></li>
                        <?php if ($is_admin): ?>
                            <li><a href="AdminPanel.php">Admin Panel</a></li>
                        <?php endif; ?>
                        <li><a href="SignOut.php"><?= $lang['sign_out'] ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div> 
        

        

        <div class="main-container">
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
            <div class="search-form">
                <form method="GET" action="">
                    <input type="text" name="category" placeholder="Search by Category" value="<?= htmlspecialchars($search_category) ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <?php if (count($businesses) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Business Name</th>
                            <th>Category</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Website</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($businesses as $business): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($business[$attributes['businessName']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['businessCategory']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['address']]); ?></td>
                                <td><?php echo htmlspecialchars($business[$attributes['businessPhone']]); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars((strpos($business[$attributes['website']], 'http') === 0 ? '' : 'http://') . $business[$attributes['website']]); ?>" target="_blank">
                                        <?php echo htmlspecialchars($business[$attributes['website']]); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($business['create_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No businesses found.</p>
            <?php endif; ?>
        </div>

        <div class="pagination">
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <a href="?page=<?= $page ?>" class="<?= $page == $current_page ? 'active' : '' ?>"><?= $page ?></a>
            <?php endfor; ?>
        </div>

        <script src="assets/js/script.js"></script>
    </body>
</html>