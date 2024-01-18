<?php
// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user`';
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_user .= 'ORDER BY `tbl_user`.`companyid`';
} elseif ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_user .= 'WHERE
            `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
        ORDER BY `tbl_user`.`reg_dt`';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_user .= 'WHERE
            `tbl_user`.`type` = "' . $_SESSION['auth_type'] . '"
        AND
            `tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"';
}
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = "' . constant('VACATION_TYPE') . '"
AND `tbl_codebase`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);


// kyukaReg.php
// Select data from tbl_userkyuka & tbl_vacationinfo
$sql_userkyuka = 'SELECT DISTINCT
    `tbl_userkyuka`.*,
    `tbl_user`.`uid`,
    `tbl_user`.`companyid`,
    `tbl_user`.`name`,
    `tbl_user`.`dept`,
    `tbl_user`.`email`,
    `tbl_user`.`inymd`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`tothday`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usefinishcnt`,
    `tbl_vacationinfo`.`usebeforecnt`,
    `tbl_vacationinfo`.`usenowcnt`,
    `tbl_vacationinfo`.`usefinishaftercnt`,
    `tbl_vacationinfo`.`useafterremaincnt`,
    `tbl_manageinfo`.`kyukatimelimit`
FROM
    `tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`email` = `tbl_user`.`email`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`';
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
    $sql_userkyuka .= 'ORDER BY `tbl_userkyuka`.`kyukaid`';
} elseif ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_userkyuka .= 'CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`
    WHERE
        `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
    AND 
        `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")
        ORDER BY `tbl_userkyuka`.`kyukaid`';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_userkyuka .= 'WHERE
        `tbl_user`.`type` = "' . $_SESSION['auth_type'] . '"
    AND
        `tbl_user`.`uid` = "' . $_SESSION['auth_uid'] . '"';
}
$result_userkyuka = mysqli_query($conn, $sql_userkyuka);
$userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);
$user_inymd_ = $userkyuka_list[0]['inymd'];
$user_name_ = $userkyuka_list[0]['name'];

// calculate year between 6month and 1 year
$givenDate = strtotime($user_inymd_);
$currentTimestamp = time();

$sixMonthsFromgivenDate = strtotime("+6 months", $givenDate);
$oneYear6monthFromgivenDate = strtotime(" +1 year +6 months", $givenDate);
$twoYear6monthFromgivenDate = strtotime(" +2 year +6 months", $givenDate);
$threeYear6monthFromgivenDate = strtotime(" +3 year +6 months", $givenDate);
$fourYear6monthFromgivenDate = strtotime(" +4 year +6 months", $givenDate);
$fiveYear6monthFromgivenDate = strtotime(" +5 year +6 months", $givenDate);
$sixYear6monthFromgivenDate = strtotime(" +6 year +6 months", $givenDate);

if ($currentTimestamp >= $sixMonthsFromgivenDate && $currentTimestamp <= $oneYear6monthFromgivenDate) {
    $oneYear6monthLastdateFromgivenDate = strtotime("-1 day", $oneYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $sixMonthsFromgivenDate);
    $enddate_ = date('Y/m/d', $oneYear6monthLastdateFromgivenDate);
    $oldcnt_ = 0;
    $newcnt_ = 10;
    $tothday_ = $oldcnt_ + $newcnt_;
} elseif ($currentTimestamp >= $oneYear6monthFromgivenDate && $currentTimestamp <= $twoYear6monthFromgivenDate) {
    $twoYear6monthLastdateFromgivenDate = strtotime("-1 day", $twoYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $oneYear6monthFromgivenDate);
    $enddate_ = date('Y/m/d', $twoYear6monthLastdateFromgivenDate);
} elseif ($currentTimestamp >= $twoYear6monthFromgivenDate && $currentTimestamp <= $threeYear6monthFromgivenDate) {
    $threeYear6monthLastdateFromgivenDate = strtotime("-1 day", $threeYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $twoYear6monthFromgivenDate);
    $enddate_ = date('Y/m/d', $threeYear6monthLastdateFromgivenDate);
} elseif ($currentTimestamp >= $threeYear6monthFromgivenDate && $currentTimestamp <= $fourYear6monthFromgivenDate) {
    $fourYear6monthLastdateFromgivenDate = strtotime("-1 day", $fourYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $threeYear6monthFromgivenDate);
    $enddate_ = date('Y/m/d', $fourYear6monthLastdateFromgivenDate);
} elseif ($currentTimestamp >= $fourYear6monthFromgivenDate && $currentTimestamp <= $fiveYear6monthFromgivenDate) {
    $fiveYear6monthLastdateFromgivenDate = strtotime("-1 day", $fiveYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $fourYear6monthFromgivenDate);
    $enddate_ = date('Y/m/d', $fiveYear6monthLastdateFromgivenDate);
} elseif ($currentTimestamp >= $fiveYear6monthFromgivenDate) {
    $sixYear6monthLastdateFromgivenDate = strtotime("-1 day", $sixYear6monthFromgivenDate);
    $startdate_ = date('Y/m/d', $fiveYear6monthFromgivenDate);
    $enddate_ = date('Y/m/d', $sixYear6monthLastdateFromgivenDate);
}

// kyukaMonthly.php
// Search Button Click
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
    if ($_SESSION['auth_type'] == constant('ADMIN') ||  $_SESSION['auth_type'] == constant('ADMINISTRATOR')  || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
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

// Save data to tbl_userkyuka & tbl_vacation table of database
if (isset($_POST['SaveKyuka'])) {
    $_POST['strtime'] = intval($_POST['strtime']);
    $_POST['endtime'] = intval($_POST['endtime']);
    $_POST['timecnt'] = intval($_POST['timecnt']);
    $_POST['ymdcnt'] = intval($_POST['ymdcnt']);

    $companyid = mysqli_real_escape_string($conn, $_SESSION['auth_companyid']);
    $uid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
    $email = mysqli_real_escape_string($conn, $_SESSION['auth_email']);

    $vacationstr = mysqli_real_escape_string($conn, $_POST['vacationstr']);
    $vacationend = mysqli_real_escape_string($conn, $_POST['vacationend']);
    $tothday = mysqli_real_escape_string($conn, $_POST['tothday']);
    $oldcnt = mysqli_real_escape_string($conn, $_POST['oldcnt']);
    $newcnt = mysqli_real_escape_string($conn, $_POST['newcnt']);
    $usefinishcnt = mysqli_real_escape_string($conn, $_POST['usefinishcnt']);
    $usebeforecnt = mysqli_real_escape_string($conn, $_POST['usebeforecnt']);
    $usenowcnt = mysqli_real_escape_string($conn, $_POST['usenowcnt']);
    $usefinishaftercnt = mysqli_real_escape_string($conn, $_POST['usefinishaftercnt']);
    $useafterremaincnt = mysqli_real_escape_string($conn, $_POST['useafterremaincnt']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    $kyukaymd = mysqli_real_escape_string($conn, $_POST['kyukaymd']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $kyukatype = mysqli_real_escape_string($conn, $_POST['kyukatype']);
    $kyukacode = mysqli_real_escape_string($conn, $_POST['kyukacode']);
    $strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
    $endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
    $strtime = mysqli_real_escape_string($conn, $_POST['strtime']);
    $endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
    $ymdcnt = mysqli_real_escape_string($conn, $_POST['ymdcnt']);
    $timecnt = mysqli_real_escape_string($conn, $_POST['timecnt']);
    $destcode = mysqli_real_escape_string($conn, $_POST['destcode']);
    $destplace = mysqli_real_escape_string($conn, $_POST['destplace']);
    $desttel = mysqli_real_escape_string($conn, $_POST['desttel']);
    $allowok = "0";
    $allowid = "0";
    $allowdecide = "0";
    $reg_dt = date('Y-m-d H:i:s');

    $sql_vacationinfo_insert = "INSERT INTO `tbl_vacationinfo` (`companyid`, `uid`, `email`, `vacationstr`, `vacationend`, `tothday`, `oldcnt`, `newcnt`, `usefinishcnt`, `usebeforecnt`, `usenowcnt`, `usefinishaftercnt`, `useafterremaincnt`, `reg_dt`, `upt_dt`) 
    VALUES('$companyid', '$uid', '$email', '$vacationstr' ,'$vacationend', '$tothday', '$oldcnt', '$newcnt', '$usefinishcnt', '$usebeforecnt', '$usenowcnt', '$usefinishaftercnt', '$useafterremaincnt', '$reg_dt' , null)";

    // Start a transaction
    mysqli_begin_transaction($conn);
    $sql_vacationinfo_result = mysqli_query($conn, $sql_vacationinfo_insert);
    $vacationid = mysqli_insert_id($conn);

    $sql_userkyuka_insert = "INSERT INTO `tbl_userkyuka` (`companyid`, `uid`, `email`, `vacationid`, `kyukaymd`, `kyukatype`, `strymd`, `endymd`, `ymdcnt`, `strtime`, `endtime`, `timecnt`, `kyukacode`, `destcode`, `destplace`, `desttel`, `allowok`, `allowid`, `allowdecide`, `allowdt`, `reason`, `reg_dt`, `upt_dt`) 
    VALUES ('$companyid', '$uid', '$email', '$vacationid', '$kyukaymd', '$kyukatype', '$strymd', '$endymd', '$ymdcnt', '$strtime', '$endtime', '$timecnt', '$kyukacode', '$destcode', '$destplace', '$desttel', '$allowok', '$allowid', '$allowdecide', null, '$reason', '$reg_dt', null)";
    $sql_userkyuka_result = mysqli_query($conn, $sql_userkyuka_insert);

    if ($sql_userkyuka_result && $sql_vacationinfo_result) {
        mysqli_commit($conn);
        $_SESSION['save_success'] =  $save_success;
        header("Refresh:3");
    } else {
        mysqli_rollback($conn);
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update tbl_userkyuka & tbl_vacation table of database
if (isset($_POST['UpdateKyuka'])) {
    $_POST['udstrtime'] = intval($_POST['udstrtime']);
    $_POST['udendtime'] = intval($_POST['udendtime']);
    $_POST['udtimecnt'] = intval($_POST['udtimecnt']);
    $_POST['udymdcnt'] = intval($_POST['udymdcnt']);
    $companyid = mysqli_real_escape_string($conn, $_SESSION['auth_companyid']);
    $uid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
    $email = mysqli_real_escape_string($conn, $_SESSION['auth_email']);
    $kyukaid = mysqli_real_escape_string($conn, $_POST['udkyukaid']);
    $vacationid = mysqli_real_escape_string($conn, $_POST['udvacationid']);

    $vacationstr = mysqli_real_escape_string($conn, $_POST['udvacationstr']); // 5
    $vacationend = mysqli_real_escape_string($conn, $_POST['udvacationend']); // 6
    $tothday = mysqli_real_escape_string($conn, $_POST['udtothday']); // 11
    $oldcnt = mysqli_real_escape_string($conn, $_POST['udoldcnt']); // 12
    $newcnt = mysqli_real_escape_string($conn, $_POST['udnewcnt']); // 13
    $usefinishcnt = mysqli_real_escape_string($conn, $_POST['udusefinishcnt']); // 14
    $usebeforecnt = mysqli_real_escape_string($conn, $_POST['udusebeforecnt']); // 15
    $usenowcnt = mysqli_real_escape_string($conn, $_POST['udusenowcnt']); // 16
    $usefinishaftercnt = mysqli_real_escape_string($conn, $_POST['udusefinishaftercnt']); // 17
    $useafterremaincnt = mysqli_real_escape_string($conn, $_POST['uduseafterremaincnt']); // 18
    $reason = mysqli_real_escape_string($conn, $_POST['udreason']); // 19

    $kyukaymd = mysqli_real_escape_string($conn, $_POST['udkyukaymd']); // 1
    $kyukatype = mysqli_real_escape_string($conn, $_POST['udkyukatype']); // 3
    $kyukacode = mysqli_real_escape_string($conn, $_POST['udkyukacode']); // 4
    $strymd = mysqli_real_escape_string($conn, $_POST['udstrymd']); // 7
    $endymd = mysqli_real_escape_string($conn, $_POST['udendymd']); // 8
    $ymdcnt = mysqli_real_escape_string($conn, $_POST['udymdcnt']); // 20
    $strtime = mysqli_real_escape_string($conn, $_POST['udstrtime']); // 9
    $endtime = mysqli_real_escape_string($conn, $_POST['udendtime']); // 10
    $timecnt = mysqli_real_escape_string($conn, $_POST['udtimecnt']); // 21
    $kyukacode = mysqli_real_escape_string($conn, $_POST['udkyukacode']); // 21
    $destcode = mysqli_real_escape_string($conn, $_POST['uddestcode']); // 22
    $destplace = mysqli_real_escape_string($conn, $_POST['uddestplace']); // 23
    $desttel = mysqli_real_escape_string($conn, $_POST['uddesttel']); // 24
    $allowok = mysqli_real_escape_string($conn, $_POST['udallowok']);
    $allowid = mysqli_real_escape_string($conn, $_POST['udallowid']);
    $allowdecide = mysqli_real_escape_string($conn, $_POST['udallowdecide']);
    $upt_dt = date('Y-m-d H:i:s');

    $queries1 = "UPDATE tbl_vacationinfo SET 
    vacationstr='$vacationstr',
    vacationend='$vacationend',
    tothday='$tothday',
    oldcnt='$oldcnt',
    newcnt='$newcnt',
    usefinishcnt='$usefinishcnt',
    usebeforecnt='$usebeforecnt',
    usenowcnt='$usenowcnt',
    usefinishaftercnt='$usefinishaftercnt',
    useafterremaincnt='$useafterremaincnt',
    upt_dt='$upt_dt'
    WHERE vacationid ='$vacationid'
    AND companyid ='$companyid'
    AND uid ='$uid'
    AND email ='$email'";

    $queries2 = "UPDATE tbl_userkyuka SET 
    kyukaymd='$kyukaymd',
    kyukatype='$kyukatype',
    strymd='$strymd',
    endymd='$endymd',
    ymdcnt='$ymdcnt',
    strtime='$strtime',
    endtime='$endtime',
    timecnt='$timecnt',
    kyukacode='$kyukacode',
    destcode='$destcode',
    destplace='$destplace',
    desttel='$desttel',
    allowok='$allowok',
    allowid='$allowid',
    allowdecide='$allowdecide',
    allowdt=null,
    reason='$reason',
    upt_dt='$upt_dt'
    WHERE kyukaid ='$kyukaid'
    AND companyid ='$companyid'
    AND uid ='$uid'
    AND vacationid ='$vacationid'";

    $result1 = mysqli_query($conn, $queries1);
    $result2 = mysqli_query($conn, $queries2);

    if ($result1 && $result2) {
        $_SESSION['update_success'] =  $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update tbl_userkyuka & tbl_vacation table of database
if (isset($_POST['DelKyuka'])) {
    $companyid = mysqli_real_escape_string($conn, $_SESSION['auth_companyid']);
    $uid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
    $email = mysqli_real_escape_string($conn, $_SESSION['auth_email']);
    $kyukaid = mysqli_real_escape_string($conn, $_POST['udkyukaid']);
    $vacationid = mysqli_real_escape_string($conn, $_POST['udvacationid']);

    $queries1 = "DELETE FROM tbl_vacationinfo
    WHERE vacationid ='$vacationid'
    AND companyid ='$companyid'
    AND uid ='$uid'
    AND email ='$email'";

    $queries2 = "DELETE FROM tbl_userkyuka
    WHERE kyukaid ='$kyukaid'
    AND companyid ='$companyid'
    AND uid ='$uid'
    AND vacationid ='$vacationid'";

    $result1 = mysqli_query($conn, $queries1);
    $result2 = mysqli_query($conn, $queries2);

    if ($result1 && $result2) {
        $_SESSION['delete_success'] =  $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

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
        allowdt='$allowdt', 
        upt_dt = '$upt_dt'
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
        allowdt='$allowdt', 
        upt_dt = '$upt_dt'
    WHERE uid ='$uid'";

        $queries[] = "UPDATE tbl_vacationinfo SET 
        usecnt='$usecnt',
        usetime='$usetime',
        restcnt='$restcnt'
    WHERE uid ='$uid'";

        $sql = implode(';', $queries);
        if ($conn->multi_query($sql) === TRUE) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}


// vacactionReg.php
// Select data from tbl_vacationinfo
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
`tbl_vacationinfo` ON `tbl_user`.`email` = `tbl_vacationinfo`.`email`
WHERE
    `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
AND 
    `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '", "' . constant('ADMIN') . '")';
if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
} elseif ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_vacationinfo .= 'WHERE `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
        AND `tbl_user`.`type` IN("' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '", "' . constant('ADMIN') . '")';
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_vacationinfo .= 'WHERE `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
        AND `tbl_user`.`type` IN("' . constant('USER') . '")';
}
$result_vacationinfo = mysqli_query($conn, $sql_vacationinfo);
$vacationinfo_list = mysqli_fetch_all($result_vacationinfo, MYSQLI_ASSOC);
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
        $sql_vacationinfo_insert = "INSERT INTO `tbl_vacationinfo` (`uid`, `vacationstr`, `vacationend`, `oldcnt`, `newcnt`, `usecnt`, `usetime`, `restcnt`, `reg_dt` , `upt_dt`) 
	        VALUES('$uid', '$vacationstr' ,'$vacationend' ,'$oldcnt', '$newcnt', '$usecnt', '$usetime', '$restcnt', '$reg_dt' , null)";
        if (mysqli_query($conn, $sql_vacationinfo_insert)) {
            $_SESSION['save_success'] =  $save_success;
            header("Refresh:3");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}
