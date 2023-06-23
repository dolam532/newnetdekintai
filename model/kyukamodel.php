<?php
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
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_userkyuka_select_db = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`companyid`,
    `tbl_user`.`name`,
    `tbl_user`.`dept`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_codebase`.`remark`,
    `tbl_manageinfo`.`kyukatimelimit`
FROM
`tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
WHERE
`tbl_codebase`.`typecode` = 02
AND
`tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
AND 
    `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_userkyuka_select_db = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`companyid`,
    `tbl_user`.`name`,
    `tbl_user`.`dept`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_codebase`.`remark`,
    `tbl_manageinfo`.`kyukatimelimit`
FROM
`tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
WHERE
`tbl_codebase`.`typecode` = 02
AND
`tbl_user`.`type` = "' . $_SESSION['auth_type'] . '"
AND
`tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"';
}

// Search Button Click kyukaReg.php
$sql_userkyuka_select = mysqli_query($conn, $sql_userkyuka_select_db);
$result_userkyuka_select = mysqli_fetch_all($sql_userkyuka_select, MYSQLI_ASSOC);
if (!empty($result_userkyuka_select)) {
    foreach ($result_userkyuka_select as $key) {
        $AllowOk[] = $key['allowok'];
        $UId[] = $key['uid'];
        $KyukaY[] = substr($key['kyukaymd'], 0, 4);
        $Name[] = $key['name'];
        $VacationY[] = substr($key['vacationstr'], 0, 4);
    }
}
$AllowOk = array_unique($AllowOk);
$UId = array_unique($UId);
$KyukaY = array_unique($KyukaY);
$Name = array_unique($Name);
$VacationY = array_unique($VacationY);

if ($_SESSION['auth_type'] == constant('ADMIN')) {
    $sql_vacationinfo = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_vacationinfo`.`vacationid`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_vacationinfo`.`reg_dt`
FROM
    `tbl_user`
LEFT JOIN 
`tbl_vacationinfo` ON `tbl_user`.`uid` = `tbl_vacationinfo`.`uid`
WHERE
    `tbl_user`.`companyid` = "' . constant('GANASYS_COMPANY_ID') . '"
AND 
    `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")';
    $result_vacationinfo = mysqli_query($conn, $sql_vacationinfo);
    $vacationinfo_list = mysqli_fetch_all($result_vacationinfo, MYSQLI_ASSOC);
}

if ($_POST['btnSearchReg'] == NULL) {
    $_POST['searchAllowok'] = "9";
    $sql_userkyuka = $sql_userkyuka_select_db;
} elseif ($_POST['btnSearchReg'] != NULL) {
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
        $searchYY = implode('","', $KyukaY);
    } else {
        $searchYY = $_POST['searchYY'];
    }
    $sql_userkyuka = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`name`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_codebase`.`remark`
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

// Save data to tbl_userkyuka table of database
if (isset($_POST['SaveKyuka'])) {
    if ($_POST['kyukatype'] == "0") {
        if ($_POST['timecnt'] >= 8) {
            $usecnt = $_POST['usecnt'] + 1;
            $usetime = $_POST['usetime'];
        } elseif ($_POST['timecnt'] < 8) {
            $usecnt = $_POST['usecnt'];
            $usetime = $_POST['usetime'] + $_POST['timecnt'];
        }
        $restcnt = $_POST['restcnt'] + 1;
    } elseif ($_POST['kyukatype'] == "1") {
        $usecnt = $_POST['usecnt'] + $_POST['ymdcnt'];
        $restcnt = $_POST['restcnt'] + 1;
        $usetime = $_POST['usetime'];
    }

    $_POST['strtime'] = intval($_POST['strtime']);
    $_POST['endtime'] = intval($_POST['endtime']);

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $vacationid = mysqli_real_escape_string($conn, $_POST['vacationid']);
    $kyukaymd = mysqli_real_escape_string($conn, $_POST['kyukaymd']);
    $kyukatype = mysqli_real_escape_string($conn, $_POST['kyukatype']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
    $ymdcnt = mysqli_real_escape_string($conn, $_POST['ymdcnt']);
    $strtime = mysqli_real_escape_string($conn, $_POST['strtime']);
    $endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
    $timecnt = mysqli_real_escape_string($conn, $_POST['timecnt']);
    $kyukacode = mysqli_real_escape_string($conn, $_POST['kyukacode']);
    $destcode = mysqli_real_escape_string($conn, $_POST['destcode']);
    $destplace = mysqli_real_escape_string($conn, $_POST['destplace']);
    $desttel = mysqli_real_escape_string($conn, $_POST['desttel']);
    $allowok = mysqli_real_escape_string($conn, $_POST['allowok']);
    $allowid = mysqli_real_escape_string($conn, $_POST['allowid']);
    $allowdt = mysqli_real_escape_string($conn, $_POST['allowdt']);
    $reg_dt = date('Y-m-d H:i:s');

    $sql_userkyuka_insert = mysqli_query($conn, "INSERT INTO `tbl_userkyuka` (`uid`, `vacationid`, `kyukaymd`, `kyukatype`, `strymd`, `endymd`, `ymdcnt`, `strtime`, `endtime`, `timecnt`, `kyukacode`, `destcode`, `destplace`, `desttel`, `allowok`, `allowid`, `allowdt`, `reg_dt`) 
    VALUES ('$uid', '$vacationid', '$kyukaymd', '$kyukatype', '$strymd', '$endymd', '$ymdcnt', '$strtime', '$endtime', '$timecnt', '$kyukacode', '$destcode', '$destplace', '$desttel', '$allowok', '$allowid', '$allowdt', '$reg_dt')");

    $sql_vacationinfo_update = mysqli_query($conn, "UPDATE tbl_vacationinfo SET 
            usecnt=$usecnt,
            usetime=$usetime,
            restcnt=$restcnt
        WHERE vacationid ='$vacationid'");

    if ($sql_userkyuka_insert && $sql_vacationinfo_update) {
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}


// Search Button Click kyukaMonthly.php
if ($_POST['btnSearchMon'] != NULL) {
    if ($_POST['searchUid'] == "") {
        $searchUid = implode('","', $UId);
    } else {
        $searchUid = $_POST['searchUid'];
    }
    if ($_POST['searchYY'] == "") {
        $searchYY = implode('","', $VacationY);
    } else {
        $searchYY = $_POST['searchYY'];
    }
    if ($_SESSION['auth_type'] == constant('ADMIN')) {
        $sql_userkyuka = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`name`,
    `tbl_user`.`dept`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_codebase`.`remark`
FROM
    `tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
WHERE
    `tbl_user`.`uid` IN("' . $searchUid . '") 
    AND LEFT(`tbl_vacationinfo`.`vacationstr`, 4) IN("' . $searchYY . '") 
    AND `tbl_codebase`.`typecode` = 02';
    } elseif ($_SESSION['auth_type'] == constant('USER')) {
        $sql_userkyuka = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`name`,
    `tbl_user`.`dept`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`,
    `tbl_codebase`.`remark`
FROM
    `tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_codebase` ON `tbl_userkyuka`.`kyukacode` = `tbl_codebase`.`code`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
WHERE
    `tbl_user`.`uid` IN("' . $_SESSION['auth_uid'] . '") 
    AND LEFT(`tbl_vacationinfo`.`vacationstr`, 4) IN("' . $searchYY . '") 
    AND `tbl_codebase`.`typecode` = 02';
    }
}
$result_userkyuka = mysqli_query($conn, $sql_userkyuka);
$userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);
