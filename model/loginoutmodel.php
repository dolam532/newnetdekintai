<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const.php');

// Login
if (isset($_POST['btnLogin'])) {
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
            $user_genid = $data['genid'];
            $user_dept = $data['dept'];
        }
        $_SESSION['auth'] = true;
        $_SESSION['auth_type'] = "$user_type"; //9=admin, 3=管理者, 1=user
        $_SESSION['auth_uid'] = "$user_uid";
        $_SESSION['auth_pwd'] = "$user_pwd";
        $_SESSION['auth_name'] = "$user_name";
        $_SESSION['auth_genid'] = "$user_genid";
        $_SESSION['auth_dept'] = "$user_dept";
        $_SESSION['decide_show'] = "1";

        if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('USER') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
            header("Location: ../index.php");
            $_SESSION['login_success'] =  $login_success;
            $_SESSION['last_login_timestamp'] =  time();
            header("Location: ../index.php");
        } else {
            $_SESSION['login_fail'] =  $login_fail;
        }
    }else{
        $_SESSION['login_fail'] =  $login_fail;
    }
}

//Logout
if (isset($_POST['btnLogout'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['auth_type']);
    unset($_SESSION['auth_uid']);
    unset($_SESSION['auth_pwd']);
    unset($_SESSION['auth_name']);
    unset($_SESSION['auth_genid']);
    unset($_SESSION['auth_dept']);
    unset($_SESSION['decide_show']);

    header("Location: ../index.php");
    $_SESSION['logout_success'] =  $logout_success;
}
