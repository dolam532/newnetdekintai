<?php
// connect to database
require_once __DIR__ . '/./const.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// check connection
if (!$conn) {
    echo $_SESSION['connect_error'] . mysqli_connect_error();
}
