<?php
// connect to database
require_once __DIR__ . '/./const.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// check connection
if (!$conn) {
    echo $_SESSION['connect_error'] . mysqli_connect_error();
}

$dbHost = DB_HOST;
$dbUser = DB_USER;
$dbPass = DB_PASSWORD;
$dbName = DB_NAME;

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($_SESSION['connect_error'] . $e->getMessage());
}
