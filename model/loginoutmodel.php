<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const.php');

// Login
if (isset($_POST['btnLogin'])) {
    if (($_POST['uid'] == "" || $_POST['pwd'] == "")) {
        $_SESSION['login_empty'] =  $login_empty;
    } else {
        $userid = mysqli_real_escape_string($conn, $_POST['uid']);
        $password = mysqli_real_escape_string($conn, $_POST['pwd']);

        $login_query = 'SELECT * FROM `tbl_user` 
        WHERE `uid` ="' . $userid . '" AND `pwd` = "' . $password . '" LIMIT 1';
        $login_query_run = mysqli_query($conn, $login_query);
        if (mysqli_num_rows($login_query_run) > 0) {
            foreach ($login_query_run as $data) {
                $user_uid = $data['uid'];
                $user_pwd = $data['pwd'];
                $user_name = $data['name'];
                $user_type = $data['type'];
            }
            $_SESSION['auth'] = true;
            $_SESSION['auth_type'] = "$user_type"; //9=admin, 1=user
            $_SESSION['auth_uid'] = "$user_uid";
            $_SESSION['auth_pwd'] = "$user_pwd";
            $_SESSION['auth_name'] = "$user_name";

            if ($_SESSION['auth_type'] == constant('ADMIN')) {
                header("Location: ../index.php");
                $_SESSION['login_success'] =  $login_success;
                $_SESSION['last_login_timestamp'] =  time();
                header("Location: ../index.php");
            } elseif ($_SESSION['auth_type'] == constant('USER')) {
                header("Location: ../index.php");
                $_SESSION['login_success'] =  $login_success;
                $_SESSION['last_login_timestamp'] =  time();
                header("Location: ../index.php");
            } else {
                $_SESSION['login_fail'] =  $login_fail;
            }
        } else {
            $_SESSION['login_fail'] =  $login_fail;
        }
    }
}

//Logout
if (isset($_POST['btnLogout'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['auth_type']);
    unset($_SESSION['auth_uid']);
    unset($_SESSION['auth_pwd']);
    unset($_SESSION['auth_name']);
    session_destroy();

    header("Location: ../index.php");
    $_SESSION['logout_success'] =  $logout_success;
}