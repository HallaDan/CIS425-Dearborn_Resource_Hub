<?php
$host = "141.215.80.154";
$dbname = "f24_group8_db";
$username = "f24_group8";
$password = "L8us2A@HdRr94";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the database successfully";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>