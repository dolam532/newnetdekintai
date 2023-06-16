<?php
// Select database from tbl_user table
// (userList.php)
$sql_user_select_db = 'SELECT * FROM `tbl_user` 
    WHERE `tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
    AND `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")';
$sql_user_select = mysqli_query($conn, $sql_user_select_db);
$result_user_select = mysqli_fetch_all($sql_user_select, MYSQLI_ASSOC);

if (!empty($result_user_select)) {
    foreach ($result_user_select as $key) {
        $Grade[] = $key['grade'];
        $Name[] = $key['name'];
    }
}
$Grade = array_unique($Grade);
$Name = array_unique($Name);
if ($_POST['SearchButton'] == NULL || isset($_POST['ClearButton'])) {
    $userlist_list = $result_user_select;
    unset($_POST);
} elseif ($_POST['SearchButton'] != NULL) {
    if ($_POST['searchName'] == "") {
        $searchName = implode('","', $Name);
    } else {
        $searchName = $_POST['searchName'];
    }
    if ($_POST['searchGrade'] == "") {
        $searchGrade = implode('","', $Grade);
    } else {
        $searchGrade = $_POST['searchGrade'];
    }
    $sql_user = 'SELECT * FROM `tbl_user` 
    WHERE `tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
    AND `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")
    AND `tbl_user`.`name` IN("' . $searchName . '") 
    AND `tbl_user`.`grade` IN("' . $searchGrade . '")';

    $sql_user_re = mysqli_query($conn, $sql_user);
    $userlist_list = mysqli_fetch_all($sql_user_re, MYSQLI_ASSOC);
}


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
