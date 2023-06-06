<?php
// Select data from tbl_user
// (kyakaReg.php)
$sql_user = 'SELECT `uid`, `name` FROM `tbl_user`';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_manageinfo
$sql_manageinfo = 'SELECT * FROM `tbl_manageinfo`';
$result_manageinfo = mysqli_query($conn, $sql_manageinfo);
$manageinfo_list = mysqli_fetch_all($result_manageinfo, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = 02 GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// Select data from tbl_userkyuka
$sql_userkyuka_select_db = 'SELECT DISTINCT
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
`tbl_codebase`.`typecode` = 02';

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
}
$result_userkyuka = mysqli_query($conn, $sql_userkyuka);
$userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);

// Save data to tbl_userkyuka table of database
if (isset($_POST['SaveKyuka'])) {
    if ($_POST['kyukaid'] = "") {
        $_POST['kyukaid'] = "0";
    }

    $_POST['uid'] = "0";
    $_POST['vacationid'] = "0";
    $_POST['oldcnt'] = "0";
    $_POST['newcnt'] = "0";
    $_POST['vacationstr'] = "0";
    $_POST['vacationend'] = "0";
    $_POST['usecnt'] = "0";
    $_POST['restcnt'] = "0";

    $_POST['kyukaid'] = intval($_POST['kyukaid']);
    $_POST['usetime'] = intval($_POST['usetime']);
    $_POST['strtime'] = intval($_POST['strtime']);
    $_POST['endtime'] = intval($_POST['endtime']);
    $reg_dt = date('Y-m-d H:i:s');

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $vacationid = mysqli_real_escape_string($conn, $_POST['vacationid']);
    $oldcnt = mysqli_real_escape_string($conn, $_POST['oldcnt']);
    $newcnt = mysqli_real_escape_string($conn, $_POST['newcnt']);
    $vacationstr = mysqli_real_escape_string($conn, $_POST['vacationstr']);
    $vacationend = mysqli_real_escape_string($conn, $_POST['vacationend']);
    $usecnt = mysqli_real_escape_string($conn, $_POST['usecnt']);
    $usetime = mysqli_real_escape_string($conn, $_POST['usetime']);
    $restcnt = mysqli_real_escape_string($conn, $_POST['restcnt']);

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

    mysqli_query($conn, "SET AUTOCOMMIT=0");
    mysqli_query($conn, "START TRANSACTION");
    $sql_userkyuka_save = mysqli_query($conn, "INSERT INTO `tbl_userkyuka` (`uid`, `vacationid`, `kyukaymd`, `kyukaid`, `kyukacode`, `kyukatype`, `strymd`, `endymd`, `strtime`, `endtime`, `ymdcnt`, `timecnt`, `allowok`, `destcode`, `destplace`, `desttel`, `reg_dt`) 
    VALUES ('$uid', '$vacationid', '$kyukaymd', '$kyukaid', '$kyukacode' ,'$kyukatype' ,'$strymd', '$endymd', '$strtime', '$endtime', '$ymdcnt', '$timecnt', '$allowok', '$destcode', '$destplace', '$desttel', '$reg_dt')");

    $sql_vacationinfo_save = mysqli_query($conn, "INSERT INTO `tbl_vacationinfo` (`vacationid`, `uid`, `vacationstr`, `vacationend`, `oldcnt`, `newcnt`, `usecnt`, `usetime`, `restcnt`, `reg_dt`) 
    VALUES('$vacationid', '$uid', '$vacationstr', '$vacationend', '$oldcnt' , '$newcnt' ,'$usecnt' ,'$usetime' , '$restcnt', '$reg_dt')");

    if ($sql_userkyuka_save && $sql_vacationinfo_save) {
        $_SESSION['save_success'] =  $save_success;
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
    mysqli_query($conn, "SET AUTOCOMMIT=1");
}
