<?php
session_start();
require 'db.php'; // Include the database connection script

if (!isset($_SESSION['user_id'])) {
    // Redirect to SignIn page if the user is not logged in
    header("Location: SignIn.php");
    exit();
}

$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Fetch form data
    $businessName = $_POST['businessName'];
    $businessCategory = $_POST['businessCategory'];
    $address = $_POST['address'];
    $businessPhone = $_POST['businessPhone'];
    $website = $_POST['website'];
    $language = $_POST['language'];
    
    // Fetch the currently signed-in user's ID
    $businessID = $_SESSION['user_id'];
    
    try {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO admin_approval (businessID, businessName, businessCategory, address, businessPhone, website, language) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Execute the statement with the data
        $stmt->execute([$businessID, $businessName, $businessCategory, $address, $businessPhone, $website, $language]);

        $successMessage = "New record created successfully";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Submit Business</title>
    <style>
        form {
            width: 300px;
            margin: 0 auto;
        }
        label, input, select {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Business</h2>
        <?php if ($successMessage): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="SubmissionPage.php">
            <label for="businessName">Business Name:</label>
            <input type="text" id="businessName" name="businessName" required>
            
            <label for="businessCategory">Business Category:</label>
            <input type="text" id="businessCategory" name="businessCategory" required>
            
            <label for="address">Location Address:</label>
            <input type="text" id="address" name="address" required>
            
            <label for="businessPhone">Business Phone Number:</label>
            <input type="text" id="businessPhone" name="businessPhone" required>
            
            <label for="website">Business Website:</label>
            <input type="text" id="website" name="website" required>

            <label for="language">Language:</label>
            <select id="language" name="language" required>
                <option value="">Select Language</option>
                <option value="English">English</option>
                <option value="Spanish">Spanish</option>
                <option value="Arabic">Arabic</option>
            </select>
            
            <input type="submit" name="submit" value="Submit" class="btn btn-primary">
        </form>
    </div>
</body>
</html>