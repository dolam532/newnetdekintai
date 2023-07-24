<?php
// Select database from tbl_user table
// (userList.php)
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_user_select_db = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`
FROM
    `tbl_user`
CROSS JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
WHERE
    `tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
    AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_user_select_db = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`
FROM
    `tbl_user`
CROSS JOIN 
    `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
WHERE
    `tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"
    AND `tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
    AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")';
}

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
    $sql_user = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_genba`.`genbaname`,
    `tbl_genba`.`workstrtime`,
    `tbl_genba`.`workendtime`
 FROM
    `tbl_user`
 CROSS JOIN 
 `tbl_genba` ON `tbl_user`.`genid` = `tbl_genba`.`genid` 
WHERE 
`tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
    AND `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")
    AND `tbl_user`.`name` IN("' . $searchName . '") 
    AND `tbl_user`.`grade` IN("' . $searchGrade . '")';

    $sql_user_re = mysqli_query($conn, $sql_user);
    $userlist_list = mysqli_fetch_all($sql_user_re, MYSQLI_ASSOC);
}

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `companyid` IN ("' . constant('GANASYS_COMPANY_ID') . '")';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list_db = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);

// Save data to tbl_user table of database
if (isset($_POST['SaveUserList'])) {
    $_POST['companyid'] = intval($_POST['companyid']);
    $reg_dt = date('Y-m-d H:i:s');
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
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

    $sql_user_insert = "INSERT INTO `tbl_user` (`uid`, `companyid`, `pwd`, `name`, `grade`, `type`, `email`, `dept`, `bigo`, `inymd`, `outymd`, `genid`, `genstrymd`, `genendymd`, `reg_dt`) 
	VALUES('$uid', '$companyid' ,'$pwd' ,'$name', '$grade', '$type', '$email', '$dept', '$bigo', '$inymd', '$outymd', '$genid', '$genstrymd', '$genendymd', '$reg_dt')";
    if (mysqli_query($conn, $sql_user_insert)) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_user table of database(ADMIN & ADMINISTRATOR)
if (isset($_POST['UpdateUserList'])) {
    $_POST['ulcompanyid'] = intval($_POST['ulcompanyid']);
    $upt_dt = date('Y-m-d H:i:s');
    $uid = mysqli_real_escape_string($conn, $_POST['uluid']);
    $companyid = mysqli_real_escape_string($conn, $_POST['ulcompanyid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['ulpwd']);
    $name = mysqli_real_escape_string($conn, $_POST['ulname']);
    $grade = mysqli_real_escape_string($conn, $_POST['ulgrade']);
    $type = mysqli_real_escape_string($conn, $_POST['ultype']);
    $email = mysqli_real_escape_string($conn, $_POST['ulemail']);
    $dept = mysqli_real_escape_string($conn, $_POST['uldept']);
    $bigo = mysqli_real_escape_string($conn, $_POST['ulbigo']);
    $inymd = mysqli_real_escape_string($conn, $_POST['ulinymd']);
    $outymd = mysqli_real_escape_string($conn, $_POST['uloutymd']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['ulgenba_list']);
    $genstrymd = mysqli_real_escape_string($conn, $_POST['ulgenstrymd']);
    $genendymd = mysqli_real_escape_string($conn, $_POST['ulgenendymd']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

    $sql = "UPDATE tbl_user SET 
            companyid='$companyid',
            pwd='$pwd',
            name='$name',
            grade='$grade',
            type='$type',
            email='$email',
            dept='$dept',
            bigo='$bigo',
            genid='$genid',
            inymd='$inymd',
            outymd='$outymd',
            genstrymd='$genstrymd',
            genendymd='$genendymd',
            upt_dt='$upt_dt'
        WHERE uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_user table of database(USER)
if (isset($_POST['UpdateUser'])) {
    $upt_dt = date('Y-m-d H:i:s');
    $uid = mysqli_real_escape_string($conn, $_POST['useruid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['userpwd']);
    $genba_list = mysqli_real_escape_string($conn, $_POST['usergenba_list']);
    $gen_id_dev = explode(",", $genba_list);
    $genid = $gen_id_dev[0];

    $sql = "UPDATE tbl_user SET 
            pwd='$pwd',
            genid='$genid',
            upt_dt='$upt_dt'
        WHERE uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}