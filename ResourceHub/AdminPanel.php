<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: SignIn.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nav = [
    'home_page' => 'Home Page',
    'business_listings' => 'Find Local Experts',
    'sign_out' => 'Sign Out',
];

// Pagination settings
$rows_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Fetch the total number of rows
try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM admin_approval");
    $stmt->execute();
    $total_rows = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Calculate total number of pages
$total_pages = ceil($total_rows / $rows_per_page);

// Fetch the rows for the current page
try {
    $stmt = $conn->prepare("SELECT * FROM admin_approval LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $rows_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
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
        </header>

        <div class="hamburger-container">
            <div class="dropdown">
                <button class="dropdown-toggle">☰</button>
                <ul class="hamburger-menu">
                    <li><a href="HomePage.php"><?= $nav['home_page'] ?></a></li>
                    <li><a href="BusinessListingPage.php"><?= $nav['business_listings'] ?></a></li>
                    <li><a href="SignOut.php"><?= $nav['sign_out'] ?></a></li>
                </ul>
            </div>
        </div>

        <div class="table-container">
            <?php if (count($businesses) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Business ID</th>
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
                                <td><?php echo htmlspecialchars($business['businessID']); ?></td>
                                <td><?php echo htmlspecialchars($business['businessName']); ?></td>
                                <td><?php echo htmlspecialchars($business['businessCategory']); ?></td>
                                <td><?php echo htmlspecialchars($business['address']); ?></td>
                                <td><?php echo htmlspecialchars($business['businessPhone']); ?></td>
                                <td><a href="<?php echo htmlspecialchars($business['website']); ?>" target="_blank"><?php echo htmlspecialchars($business['website']); ?></a></td>
                                <td><?php echo htmlspecialchars($business['language']); ?></td>
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