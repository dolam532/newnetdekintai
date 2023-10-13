<?php
// Auto logout inactive page
if (isset($_SESSION['last_login_timestamp']) && (time() - $_SESSION['last_login_timestamp']) > 600) {
    session_destroy();
    header("Location: ../index.php");
} else {
    if ($_SESSION['auth'] == true) {
        $_SESSION['last_login_timestamp'] = time();
    }
}
