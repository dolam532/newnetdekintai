<?php
// Select data from tbl_user
$sql_user = 'SELECT DISTINCT
`tbl_user`.*,
`tbl_company`.`kyukatemplate`
FROM 
    `tbl_user`
CROSS JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`';
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
$user_inymd_ = $user_list[0]['inymd'];
$user_name_ = $user_list[0]['name'];
$user_kyukatemplate_ = $user_list[0]['kyukatemplate'];
$givenDate = strtotime($user_inymd_);
$currentDate = time();
$givenDateTime = new DateTime("@$givenDate");
$currentDateTime = new DateTime("@$currentDate");
$interval = $givenDateTime->diff($currentDateTime);
$numberOfMonths = $interval->format('%m');

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

$sql_codebase_kyuka = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = "' . constant('VACATION_TYPE') . '"
AND `tbl_codebase`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
GROUP BY `code`, `name`';
$result_codebase_kyuka = mysqli_query($conn, $sql_codebase_kyuka);
$codebase_list_kyuka = mysqli_fetch_all($result_codebase_kyuka, MYSQLI_ASSOC);

// Select data from tbl_kyukainfo
$sql_kyukainfo = 'SELECT * FROM `tbl_kyukainfo`
WHERE `tbl_kyukainfo`.`companyid` = "' . $_SESSION['auth_companyid'] . '"';
$result_kyukainfo = mysqli_query($conn, $sql_kyukainfo);
$kyukainfo_list = mysqli_fetch_all($result_kyukainfo, MYSQLI_ASSOC);
function filterNull($value)
{
    return $value !== null;
}
$kyukainfo_list = array_map('array_filter', $kyukainfo_list);
// Define variables to store the maximum values
$maxTtop = null;
$maxTbottom = null;
$minTtop = null;
$minTbottom = null;

// Define the upper limit for n
$upperLimit = 21;

// Iterate from 0 to the upper limit
for ($n = 0; $n < $upperLimit; $n++) {
    $ttopKey = "ttop" . $n;
    $tbottomKey = "tbottom" . $n;

    if (isset($kyukainfo_list[0][$ttopKey]) && $kyukainfo_list[0][$ttopKey] <= $numberOfMonths) {
        // Update the maximum values if a larger value is encountered
        if ($maxTtop === null || $kyukainfo_list[0][$ttopKey] > $kyukainfo_list[0][$maxTtop]) {
            $maxTtop = $ttopKey;
        }

        if ($maxTbottom === null || $kyukainfo_list[0][$ttopKey] > $kyukainfo_list[0][$maxTbottom]) {
            $maxTbottom = $tbottomKey;
        }
    }

    if (isset($kyukainfo_list[0][$ttopKey]) && $kyukainfo_list[0][$ttopKey] >= $numberOfMonths) {
        // Update the maximum values if a larger value is encountered
        if ($minTtop === null || $kyukainfo_list[0][$ttopKey] < $kyukainfo_list[0][$minTtop]) {
            $minTtop = $ttopKey;
        }

        if ($minTbottom === null || $kyukainfo_list[0][$ttopKey] < $kyukainfo_list[0][$minTbottom]) {
            $minTbottom = $tbottomKey;
        }
    }
}

// Display the largest values
if ($maxTtop !== null && $maxTbottom !== null) {
    $last_data_max = [$maxTtop => $kyukainfo_list[0][$maxTtop], $maxTbottom => $kyukainfo_list[0][$maxTbottom]];
    $lastTtopMax = $last_data_max[$maxTtop];
    $lastTbottomMax = $last_data_max[$maxTbottom];
}

// Display the largest values
if ($minTtop !== null && $minTbottom !== null) {
    $last_data_min = [$minTtop => $kyukainfo_list[0][$minTtop], $minTbottom => $kyukainfo_list[0][$minTbottom]];
    $lastTtopMin = $last_data_min[$minTtop];
    $lastTbottomMin = $last_data_min[$minTbottom];
}

$startmonth = strtotime("+" . $lastTtopMax . " months", $givenDate);
$endmonth = strtotime("+" . $lastTtopMin . " months", $givenDate);
$enddate = strtotime("-1 day", $endmonth);
$startdate_ = date('Y/m/d', $startmonth);
$enddate_ = date('Y/m/d', $enddate);
$newcnt_ = $lastTbottomMax;
$tothday_ = $lastTbottomMax;
$oldcnt_= $tothday_ - $newcnt_;

// Select data from tbl_kyuka_notice
$sql_kyuka_notice = 'SELECT * FROM `tbl_kyuka_notice`
WHERE `tbl_kyuka_notice`.`companyid` = "' . $_SESSION['auth_companyid'] . '"';
$result_kyuka_notice = mysqli_query($conn, $sql_kyuka_notice);
$kyuka_notice_list = mysqli_fetch_all($result_kyuka_notice, MYSQLI_ASSOC);

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
    `tbl_user`.`signstamp`,
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
    `tbl_company`.`kyukatemplate`
FROM
    `tbl_userkyuka`
CROSS JOIN `tbl_user` ON `tbl_userkyuka`.`email` = `tbl_user`.`email`
CROSS JOIN `tbl_vacationinfo` ON `tbl_userkyuka`.`vacationid` = `tbl_vacationinfo`.`vacationid`
CROSS JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`';
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
    foreach ($codebase_list_kyuka as $key) {
        if ($key['code'] == $_POST['kyukacode'] && $_POST['kyukanamedetail'] == "") {
            $kyukaname_l = $key['name'];
            $kyukaname_l_d = "";
        } elseif ($key['code'] == $_POST['kyukacode'] && $_POST['kyukanamedetail'] != "") {
            $kyukaname_l = $key['name'];
            $kyukaname_l_d = $_POST['kyukanamedetail'];
        }
    }

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
    $kyukaname = mysqli_real_escape_string($conn, $kyukaname_l);
    $kyukanamedetail = mysqli_real_escape_string($conn, $kyukaname_l_d);
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

    $sql_userkyuka_insert = "INSERT INTO `tbl_userkyuka` (`companyid`, `uid`, `email`, `vacationid`, `kyukaymd`, `kyukatype`, `strymd`, `endymd`, `ymdcnt`, `strtime`, `endtime`, `timecnt`, `kyukacode`, `kyukaname`, `kyukanamedetail`, `destcode`, `destplace`, `desttel`, `allowok`, `allowid`, `allowdecide`, `allowdt`, `reason`, `reg_dt`, `upt_dt`) 
    VALUES ('$companyid', '$uid', '$email', '$vacationid', '$kyukaymd', '$kyukatype', '$strymd', '$endymd', '$ymdcnt', '$strtime', '$endtime', '$timecnt', '$kyukacode', '$kyukaname', '$kyukanamedetail', '$destcode', '$destplace', '$desttel', '$allowok', '$allowid', '$allowdecide', null, '$reason', '$reg_dt', null)";
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
    foreach ($codebase_list_kyuka as $key) {
        if ($key['code'] == $_POST['udkyukacode'] && $_POST['udkyukanamedetail'] == "") {
            $kyukaname_l = $key['name'];
            $kyukanamed_l_d = "";
        } elseif ($key['code'] == $_POST['udkyukacode'] && $_POST['udkyukanamedetail'] != "") {
            $kyukaname_l = $key['name'];
            $kyukaname_l_d = $_POST['udkyukanamedetail'];
        }
    }

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
    $kyukaname = mysqli_real_escape_string($conn, $kyukaname_l); // 4
    $kyukanamedetail = mysqli_real_escape_string($conn, $kyukaname_l_d); // 4
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
    kyukaname='$kyukaname',
    kyukanamedetail='$kyukanamedetail',
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
