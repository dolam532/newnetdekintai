<?php
// connect to database
$conn = mysqli_connect('153.127.255.167', 'ndk', 'ganandkadm2019', 'newndk');

// check connection
if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
