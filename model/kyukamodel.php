<?php
// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user`';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = 02 GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// kyukaReg.php
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

if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
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
    `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '", "' . constant('ADMIN') . '")';
    $result_vacationinfo = mysqli_query($conn, $sql_vacationinfo);
    $vacationinfo_list = mysqli_fetch_all($result_vacationinfo, MYSQLI_ASSOC);
} elseif ($_SESSION['auth_type'] == constant('USER')) {
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
    `tbl_user`.`type` IN("' . constant('USER') . '")';
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


// Select data from tbl_user, tbl_vacationinfo and tbl_manageinfo of table
$sql_select_table3_db = 'SELECT DISTINCT
`tbl_user`.*,
`tbl_vacationinfo`.`vacationid`,
`tbl_vacationinfo`.`vacationstr`,
`tbl_vacationinfo`.`vacationend`,
`tbl_vacationinfo`.`oldcnt`,
`tbl_vacationinfo`.`newcnt`,
`tbl_vacationinfo`.`usecnt`,
`tbl_vacationinfo`.`usetime`,
`tbl_vacationinfo`.`restcnt`,
`tbl_manageinfo`.`kyukatimelimit`
FROM
`tbl_vacationinfo`
CROSS JOIN `tbl_user` ON `tbl_vacationinfo`.`uid` = `tbl_user`.`uid`
CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`
WHERE
`tbl_user`.`type` = "' . $_SESSION['auth_type'] . '"
AND
`tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"';
$sql_table3_select = mysqli_query($conn, $sql_select_table3_db);
$result_uservacationmanage_select = mysqli_fetch_all($sql_table3_select, MYSQLI_ASSOC);


// Save data to tbl_userkyuka table of database
if (isset($_POST['SaveKyuka'])) {
    $_POST['vacationid'] = intval($_POST['vacationid']);
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
    $allowok = "0";
    $allowid = "0";
    $allowdecide = "0";
    $allowdt = $reg_dt = date('Y-m-d H:i:s');

    $sql_userkyuka_insert = mysqli_query($conn, "INSERT INTO `tbl_userkyuka` (`uid`, `vacationid`, `kyukaymd`, `kyukatype`, `strymd`, `endymd`, `ymdcnt`, `strtime`, `endtime`, `timecnt`, `kyukacode`, `destcode`, `destplace`, `desttel`, `allowok`, `allowid`, `allowdecide`, `allowdt`, `reg_dt`) 
    VALUES ('$uid', '$vacationid', '$kyukaymd', '$kyukatype', '$strymd', '$endymd', '$ymdcnt', '$strtime', '$endtime', '$timecnt', '$kyukacode', '$destcode', '$destplace', '$desttel', '$allowok', '$allowid', '$allowdecide', '$allowdt', '$reg_dt')");

    if ($sql_userkyuka_insert) {
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

// Update data to tbl_userkyuka table of database
if (isset($_POST['DecideUpdateKyuka'])) {
    $allowdt = date('Y-m-d H:i:s');
    $uid = mysqli_real_escape_string($conn, $_POST['duid']);
    $allowid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
    $allowok = mysqli_real_escape_string($conn, $_POST['allowok']);
    $allowdecide = mysqli_real_escape_string($conn, $_POST['decide_allowok']);

    if ($_POST['decide_allowok'] == "0") {
        $sql = "UPDATE tbl_userkyuka SET 
        allowid='$allowid',
        allowok='$allowok',
        allowdecide='$allowdecide',
        allowdt='$allowdt'
    WHERE uid ='$uid'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    } elseif ($_POST['decide_allowok'] == "1") {
        $usecnt = $_POST['oldusecnt'] + $_POST['newymdcnt'];
        $usetime = $_POST['oldusetime'] + $_POST['newtimecnt'];
        $restcnt = $_POST['oldrestcnt'] + 1;

        $queries[] = "UPDATE tbl_userkyuka SET 
        allowid='$allowid',
        allowok='$allowok',
        allowdecide='$allowdecide',
        allowdt='$allowdt'
    WHERE uid ='$uid'";

        $queries[] = "UPDATE tbl_vacationinfo SET 
        usecnt='$usecnt',
        usetime='$usetime',
        restcnt='$restcnt'
    WHERE uid ='$uid'";

        $sql=implode( ';', $queries );
        if ($conn->multi_query( $sql ) === TRUE) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}


// vacactionReg.php
// Save data to tbl_vacationinfo table of database
if (isset($_POST['SaveUpdateKyuka'])) {
    $reg_dt = date('Y-m-d H:i:s');
    $uid = mysqli_real_escape_string($conn, $_POST['usuid']);
    $vacationstr = mysqli_real_escape_string($conn, $_POST['usvacationstr']);
    $vacationend = mysqli_real_escape_string($conn, $_POST['usvacationend']);
    $oldcnt = mysqli_real_escape_string($conn, $_POST['usoldcnt']);
    $newcnt = mysqli_real_escape_string($conn, $_POST['usnewcnt']);
    $usecnt = mysqli_real_escape_string($conn, $_POST['ususecnt']);
    $usetime = mysqli_real_escape_string($conn, $_POST['ususetime']);
    $restcnt = mysqli_real_escape_string($conn, $_POST['usrestcnt']);
    if ($_POST['usvacationid'] != "0") {
        $vacationid = mysqli_real_escape_string($conn, $_POST['usvacationid']);
        $sql = "UPDATE tbl_vacationinfo SET 
            vacationstr='$vacationstr',
            vacationend='$vacationend',
            oldcnt='$oldcnt',
            newcnt='$newcnt',
            usecnt='$usecnt',
            usetime='$usetime',
            restcnt='$restcnt',
            reg_dt='$reg_dt'
        WHERE uid ='$uid' AND vacationid ='$vacationid'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    } elseif ($_POST['usvacationid'] == "0") {
        $sql_vacationinfo_insert = "INSERT INTO `tbl_vacationinfo` (`uid`, `vacationstr`, `vacationend`, `oldcnt`, `newcnt`, `usecnt`, `usetime`, `restcnt`, `reg_dt`) 
	        VALUES('$uid', '$vacationstr' ,'$vacationend' ,'$oldcnt', '$newcnt', '$usecnt', '$usetime', '$restcnt', '$reg_dt')";
        if (mysqli_query($conn, $sql_vacationinfo_insert)) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}
