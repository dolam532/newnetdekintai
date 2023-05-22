<?php
// connect to database
$conn = mysqli_connect('localhost', 'root', 'root', 'ndknew');
// $conn = mysqli_connect('localhost', 'newndk', 'ganasysnewndk2023', 'newndk');

// check connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
