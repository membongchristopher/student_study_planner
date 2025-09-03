<?php
$host = 'localhost';
$dbname = 'study_planner';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Connection successful, no need to run SQL here
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
