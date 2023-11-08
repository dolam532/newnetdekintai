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
        WHERE `email` ="' . $userid . '" AND `pwd` = "' . $password . '" LIMIT 1';
    $login_query_run = mysqli_query($conn, $login_query);
    if (mysqli_num_rows($login_query_run) > 0) {
        foreach ($login_query_run as $data) {
            $user_uid = $data['uid'];
            $user_companyid = $data['companyid'];
            $user_pwd = $data['pwd'];
            $user_name = $data['name'];
            $user_type = $data['type'];
            $user_genid = $data['genid'];
            $user_dept = $data['dept'];
            $user_signstamp = $data['signstamp'];
        }
        $_SESSION['auth'] = true;
        $_SESSION['auth_type'] = "$user_type"; //9=admin, 3=管理者, 1=user , 6=社長
        $_SESSION['auth_uid'] = "$user_uid";
        $_SESSION['auth_companyid'] = "$user_companyid";
        $_SESSION['auth_pwd'] = "$user_pwd";
        $_SESSION['auth_name'] = "$user_name";
        $_SESSION['auth_genid'] = "$user_genid";
        $_SESSION['auth_dept'] = "$user_dept";
        $_SESSION['auth_signstamp_user'] = "$user_signstamp";

        if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('USER') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
            header("Location: ../index.php");
            $_SESSION['login_success'] =  $login_success;
            $_SESSION['last_login_timestamp'] =  time();

            $uid = $_SESSION['auth_uid'];
            $workymd = date('Y/m/d');
            $logtype = $_SESSION['auth_type'];
            $logtime = date('Y-m-d H:i:s');
            // $ipaddress = gethostbyname(constant('DOMAIN_NAME'));
            $ipaddress = $_SERVER['REMOTE_ADDR'];  // browser ip -> localhost ::1 

            function getDomainFromURL($url)
            {
                $parsedURL = parse_url($url);
                if (isset($parsedURL['host'])) {
                    $domain = $parsedURL['host'];
                } else {
                    $domain = '';
                }
                return $domain;
            }
            $domain = getDomainFromURL(constant('URL_NAME'));

            $sql_userlogin_insert = "INSERT INTO `tbl_userlogin` (`uid`, `workymd`, `logtype`, `logtime`, `ipaddress`, `domain`) 
            VALUES('$uid', '$workymd' ,'$logtype' ,'$logtime', '$ipaddress', '$domain')";
            if (mysqli_query($conn, $sql_userlogin_insert)) {
            } else {
                echo 'query error: ' . mysqli_error($conn);
            }
        } else {
            $_SESSION['login_fail'] =  $login_fail;
        }
    } else {
        $_SESSION['login_fail'] =  $login_fail;
    }
}

//Logout
if (isset($_POST['btnLogout'])) {
    unset($_SESSION['auth']);
    unset($_SESSION['auth_type']);
    unset($_SESSION['auth_uid']);
    unset($_SESSION['auth_companyid']);
    unset($_SESSION['auth_pwd']);
    unset($_SESSION['auth_name']);
    unset($_SESSION['auth_genid']);
    unset($_SESSION['auth_dept']);
    unset($_SESSION['auth_signstamp_user']);

    header("Location: ../index.php");
    $_SESSION['logout_success'] =  $logout_success;
}
