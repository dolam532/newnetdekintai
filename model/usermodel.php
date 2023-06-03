<?php
// Select database from tbl_user table
// (userList.php)
if (isset($_POST['searchGrade']) || isset($_POST['searchName'])) {
    $searchData = $_POST['searchGrade'];
    $searchName = $_POST['searchName'];
    $clear = $_POST['clear'];

    if ($clear !== NULL) {
        unset($_POST);
        $sql_user = 'SELECT * FROM `tbl_user`';
    } elseif (!empty($searchData) && empty($searchName)) {
        $sql_user = "SELECT * FROM `tbl_user` WHERE grade LIKE '%$searchData%'";
    } elseif (!empty($searchName) && empty($searchData)) {
        $sql_user = "SELECT * FROM `tbl_user` WHERE name LIKE '%$searchName%'";
    } else if (!empty($searchData) && !empty($searchName)) {
        $sql_user = "SELECT * FROM `tbl_user` WHERE grade LIKE '%$searchData%' AND name LIKE '%$searchName%'";
    } else {
        $sql_user = 'SELECT * FROM `tbl_user`';
    }
} else {
    $sql_userlist = 'SELECT * FROM `tbl_user`';
}
$result_userlist = mysqli_query($conn, $sql_userlist);
$userlist_list = mysqli_fetch_all($result_userlist, MYSQLI_ASSOC);

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `companyid` IN (1)';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list_db = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);

// Save data to tbl_user table of database
if (isset($_POST['SaveUserList'])) {
    if ($_POST['companyid'] = "") {
        $_POST['companyid'] = "0";
    }
    $_POST['companyid'] = intval($_POST['companyid']);
    $reg_dt = date('Y-m-d H:i:s');

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dept = mysqli_real_escape_string($conn, $_POST['dept']);
    $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
    $inymd = mysqli_real_escape_string($conn, $_POST['inymd']);
    $outymd = mysqli_real_escape_string($conn, $_POST['outymd']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['genba_list']);
    $genstrymd = mysqli_real_escape_string($conn, $_POST['genstrymd']);
    $genendymd = mysqli_real_escape_string($conn, $_POST['genendymd']);

    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

    $sql_user_insert = "INSERT INTO `tbl_user` (`uid`, `companyid`, `pwd`, `name`, `grade`, `email`, `dept`, `bigo`, `inymd`, `outymd`, `genid`, `genstrymd`, `genendymd`, `reg_dt`) 
	VALUES('$uid', '$companyid' ,'$pwd' ,'$name', '$grade', '$email', '$dept', '$bigo', '$inymd', '$outymd', '$genid', '$genstrymd', '$genendymd', '$reg_dt')";
    if (mysqli_query($conn, $sql_user_insert)) {
        $_SESSION['save_success'] =  $save_success;
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
