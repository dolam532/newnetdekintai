<?php
// Select data from tbl_notice
// (index.php)
$sql_notice = 'SELECT * FROM `tbl_notice`
JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
WHERE `tbl_notice`.`uid` = `tbl_user`.`uid`
ORDER BY `tbl_notice`.`bid`';

$result_notice = mysqli_query($conn, $sql_notice);
$notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);



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



// Select data from tbl_user
// (kyakaReg.php)
$sql_user = 'SELECT `uid`, `name` FROM `tbl_user`';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = 02 GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// Select data from tbl_userkyuka
$sql_userkyuka_select_db = 'SELECT DISTINCT
`tbl_userkyuka`.*,
`tbl_user`.`name`,
`tbl_vacationinfo`.`vacationstr`,
`tbl_vacationinfo`.`vacationend`,
`tbl_vacationinfo`.`oldcnt`,
`tbl_vacationinfo`.`newcnt`
FROM
`tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
WHERE
`tbl_codebase`.`typecode` = 02';

// Search Button Click
$sql_userkyuka_select = mysqli_query($conn, $sql_userkyuka_select_db);
$result_userkyuka_select = mysqli_fetch_all($sql_userkyuka_select, MYSQLI_ASSOC);
if (!empty($result_userkyuka_select)) {
    foreach ($result_userkyuka_select as $key) {
        $AllowOk[] = $key['allowok'];
        $UId[] = $key['uid'];
        $KyukayMD[] = substr($key['kyukaymd'], 0, 4);
    }
}
$AllowOk = array_unique($AllowOk);
$UId = array_unique($UId);
$KyukayMD = array_unique($KyukayMD);
if ($_POST['btnSearch'] == NULL) {
    $_POST['searchAllowok'] = "9";
    $sql_userkyuka = $sql_userkyuka_select_db;
} elseif ($_POST['btnSearch'] != NULL) {
    if ($_POST['searchAllowok'] == "9") {
        $searchAllowok = implode('","', $AllowOk);
    } else {
        $searchAllowok = $_POST['searchAllowok'];
    }
    if ($_POST['searchUid'] == "") {
        $searchUid = implode('","', $UId);
    } else {
        $searchUid = $_POST['searchUid'];
    }
    if ($_POST['searchYY'] == "") {
        $searchYY = implode('","', $KyukayMD);
    } else {
        $searchYY = $_POST['searchYY'];
    }
    $sql_userkyuka = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`name`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`
FROM
    `tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
    WHERE
        `tbl_userkyuka`.`allowok` IN("' . $searchAllowok . '") 
        AND `tbl_user`.`uid` IN ("' . $searchUid . '") 
        AND LEFT(`tbl_userkyuka`.`kyukaymd`, 4)  IN ("' . $searchYY . '")
        AND `tbl_codebase`.`typecode` = 02';
}

$result_userkyuka = mysqli_query($conn, $sql_userkyuka);
$userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);

// Save data to tbl_userkyuka table of database
if (isset($_POST['SaveKyuka'])) {
    if ($_POST['kyukaid'] = "") {
        $_POST['kyukaid'] = "0";
    }

    // 申込区分 時間時
    if ($_POST['kyukatype'] == "0") {
        $_POST['totcnt'] = "0";
        $_POST['ymdcnt'] = "0";
        $_POST['timecnt'] = "0";
        $_POST['uid'] = "0";
        $_POST['vacationid'] = "0";
        $_POST['kyukaymd'] = "0";
    }

    $_POST['kyukaid'] = intval($_POST['kyukaid']);
    $reg_dt = date('Y-m-d H:i:s');

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $vacationid = mysqli_real_escape_string($conn, $_POST['vacationid']);
    $kyukaymd = mysqli_real_escape_string($conn, $_POST['kyukaymd']);
    $kyukaid = mysqli_real_escape_string($conn, $_POST['kyukaid']);
    $kyukacode = mysqli_real_escape_string($conn, $_POST['kyukacode']);
    $kyukatype = mysqli_real_escape_string($conn, $_POST['kyukatype']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
    $strtime = mysqli_real_escape_string($conn, $_POST['strtime']);
    $endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
    $ymdcnt = mysqli_real_escape_string($conn, $_POST['ymdcnt']);
    $inymd = mysqli_real_escape_string($conn, $_POST['inymd']);
    $timecnt = mysqli_real_escape_string($conn, $_POST['timecnt']);
    $allowok = mysqli_real_escape_string($conn, $_POST['allowok']);
    $destcode = mysqli_real_escape_string($conn, $_POST['destcode']);
    $destplace = mysqli_real_escape_string($conn, $_POST['destplace']);
    $desttel = mysqli_real_escape_string($conn, $_POST['desttel']);


    $sql_userkyuka_i = "INSERT INTO `tbl_userkyuka` (`uid`, `vacationid`, `kyukaymd`, `kyukaid`, `kyukacode`, `kyukatype`, `strymd`, `endymd`, `strtime`, `endtime`, `ymdcnt`, `timecnt`, `allowok`, `destcode`, `destplace`, `desttel`, `reg_dt`) 
	VALUES('$uid', '$vacationid', '$kyukaymd', '$kyukaid', '$kyukacode' ,'$kyukatype' ,'$strymd', '$endymd', '$strtime', '$endtime', '$ymdcnt', '$timecnt', '$allowok', '$destcode', '$destplace', '$desttel', '$reg_dt')";
    if (mysqli_query($conn, $sql_userkyuka_i)) {
        $_SESSION['save_success'] =  $save_success;
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}