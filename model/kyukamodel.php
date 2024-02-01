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
$sqlFindKyukaInfo = 'SELECT * FROM `tbl_kyukainfo`
WHERE `tbl_kyukainfo`.`companyid` = "' . $_SESSION['auth_companyid'] . '"';
$resultKyukaInfo = mysqli_query($conn, $sqlFindKyukaInfo);
$noDataKyukaInfo = false;
$kiukaInfoList = mysqli_fetch_all($resultKyukaInfo, MYSQLI_ASSOC);
$kiukaInfoListDatasShow = array();
$kyukainfo_titletop = $kiukaInfoList[0]["titletop"];
$kyukainfo_titlebottom = $kiukaInfoList[0]["titlebottom"];

if (mysqli_num_rows($resultKyukaInfo) > 0) {
    $kiukaInfoListDatas = $kiukaInfoList[0];
    for ($i = $MIN_KYUKA_INFO_COUNT; $i <= $MAX_KYUKA_INFO_COUNT; $i++) {
        $key = "ttop" . $i;
        $keybottom = "tbottom" . $i;
        if (!isset($kiukaInfoListDatas[$key]) || trim($kiukaInfoListDatas[$key]) == '') {
            continue;
        }
        if (!isset($kiukaInfoListDatas[$keybottom]) || trim($kiukaInfoListDatas[$keybottom]) == '') {
            continue;
        }
        $value = intval($kiukaInfoListDatas[$key]);
        if ($value < 12) {
            $kyukaInfoListtop[] = $kiukaInfoListDatasShow[$key] = $value . 'ヵ月';
        } else {
            $years = floor($value / 12);
            $months = $value % 12;
            if ($months == 0) {
                $kyukaInfoListtop[] = $kiukaInfoListDatasShow[$key] = $years . '年';
            } else {
                $kyukaInfoListtop[] = $kiukaInfoListDatasShow[$key] = $years . '年' . $months . 'ヵ月';
            }
        }
        // add new min 
        if ($i == $MIN_KYUKA_INFO_COUNT) {
            $kyukaInfoListtop[0] = $kiukaInfoListDatasShow['ttop0'] = $kiukaInfoListDatasShow[$key] . '以内';
        }
        if ($i == $MAX_KYUKA_INFO_COUNT) {
            end($kyukaInfoListtop);
            $key = key($kyukaInfoListtop);
            $kyukaInfoListtop[$key] .= '以上';
            $kiukaInfoListDatasShow[$key] .= '以上';
        }
        $kyukaInfoListbottom[] = $kiukaInfoListDatasShow[$keybottom] = $kiukaInfoListDatas[$keybottom] . '日';
    }
}
$kyukaInfoListtopString = implode(',', $kyukaInfoListtop);
$kyukaInfoListbottomString = implode(',', $kyukaInfoListbottom);
function filterNull($value)
{
    return $value !== null;
}
$kiukaInfoList = array_map('array_filter', $kiukaInfoList);
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

    if (isset($kiukaInfoList[0][$ttopKey]) && $kiukaInfoList[0][$ttopKey] <= $numberOfMonths) {
        // Update the maximum values if a larger value is encountered
        if ($maxTtop === null || $kiukaInfoList[0][$ttopKey] > $kiukaInfoList[0][$maxTtop]) {
            $maxTtop = $ttopKey;
        }

        if ($maxTbottom === null || $kiukaInfoList[0][$ttopKey] > $kiukaInfoList[0][$maxTbottom]) {
            $maxTbottom = $tbottomKey;
        }
    }

    if (isset($kiukaInfoList[0][$ttopKey]) && $kiukaInfoList[0][$ttopKey] >= $numberOfMonths) {
        // Update the maximum values if a larger value is encountered
        if ($minTtop === null || $kiukaInfoList[0][$ttopKey] < $kiukaInfoList[0][$minTtop]) {
            $minTtop = $ttopKey;
        }

        if ($minTbottom === null || $kiukaInfoList[0][$ttopKey] < $kiukaInfoList[0][$minTbottom]) {
            $minTbottom = $tbottomKey;
        }
    }
}

// Display the largest values
if ($maxTtop !== null && $maxTbottom !== null) {
    $last_data_max = [$maxTtop => $kiukaInfoList[0][$maxTtop], $maxTbottom => $kiukaInfoList[0][$maxTbottom]];
    $lastTtopMax = $last_data_max[$maxTtop];
    $lastTbottomMax = $last_data_max[$maxTbottom];
}

// Display the largest values
if ($minTtop !== null && $minTbottom !== null) {
    $last_data_min = [$minTtop => $kiukaInfoList[0][$minTtop], $minTbottom => $kiukaInfoList[0][$minTbottom]];
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
$oldcnt_ = $tothday_ - $newcnt_;

// Select data from tbl_kyuka_notice
$sql_kyuka_notice = 'SELECT * FROM `tbl_kyuka_notice`
WHERE `tbl_kyuka_notice`.`companyid` = "' . $_SESSION['auth_companyid'] . '"';
$result_kyuka_notice = mysqli_query($conn, $sql_kyuka_notice);
$kyuka_notice_list = mysqli_fetch_all($result_kyuka_notice, MYSQLI_ASSOC);
$kyuka_notice_title = $kyuka_notice_list[0]['title'];
$kyuka_notice_message = $kyuka_notice_list[0]['message'];
$kyuka_notice_subtitle = $kyuka_notice_list[0]['subtitle'];

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
$userkyuka_list_ = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);

if (!empty($userkyuka_list_)) {
    foreach ($userkyuka_list_ as $key) {
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

if (($_POST['btnSearchReg'] == NULL && $_POST['ClearButton'] == NULL) || isset($_POST['ClearButton'])) {
    $_POST['searchAllowok'] = constant('SEARCH_ALLOW');
    $_POST['searchYY'] = "";
    $_POST['searchName'] = "";
    $userkyuka_list = $userkyuka_list_;
} elseif (isset($_POST['btnSearchReg'])) {
    if ($_POST['searchAllowok'] == constant('SEARCH_ALLOW')) {
        $searchAllowok = implode('","', $AllowOk);
    } else {
        $searchAllowok = $_POST['searchAllowok'];
    }
    if ($_POST['searchName'] == "") {
        $searchName = implode('","', $Name);
    } else {
        $searchName = $_POST['searchName'];
    }
    if ($_POST['searchYY'] == "") {
        $searchYY = implode('","', $KyukaY);
    } else {
        $searchYY = $_POST['searchYY'];
    }

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
CROSS JOIN `tbl_company` ON `tbl_user`.`companyid` = `tbl_company`.`companyid`
CROSS JOIN `tbl_manageinfo` ON `tbl_user`.`companyid` = `tbl_manageinfo`.`companyid`
WHERE `tbl_user`.`companyid` = "' . $_SESSION['auth_companyid'] . '"
AND `tbl_user`.`type` IN("' . constant('ADMIN') . '", "' . constant('USER') . '", "' . constant('ADMINISTRATOR') . '")
AND `tbl_userkyuka`.`allowok` IN("' . $searchAllowok . '") 
AND `tbl_user`.`name` IN ("' . $searchName . '") 
AND LEFT(`tbl_userkyuka`.`kyukaymd`, 4)  IN ("' . $searchYY . '")
ORDER BY `tbl_userkyuka`.`kyukaid`';
    $result_userkyuka = mysqli_query($conn, $sql_userkyuka);
    $userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);
}

foreach ($userkyuka_list as $key => $value) {
    $teishutsu_uid = $value['teishutsu_uid'];
    $tantosha_uid = $value['tantosha_uid'];
    $sekininsha_uid = $value['sekininsha_uid'];
    $userkyuka_uid = $value['email'];

    if ($teishutsu_uid !== $userkyuka_uid ) {
        $userkyuka_list[$key]['teishutsu_stamp'] = "other_user";
    } else {
        $sql_teishutsu = "SELECT signstamp FROM tbl_user WHERE `email` = '$teishutsu_uid'";
        $result_teishutsu = mysqli_query($conn, $sql_teishutsu);
        $teishutsu_stamp = mysqli_fetch_assoc($result_teishutsu)['signstamp'];
        $userkyuka_list[$key]['teishutsu_stamp'] = $teishutsu_stamp;
    }

    $sql_tantosha = "SELECT `signstamp` FROM `tbl_user` WHERE `email` = '$tantosha_uid'";
    $result_tantosha = mysqli_query($conn, $sql_tantosha);
    $tantosha_stamp = mysqli_fetch_assoc($result_tantosha)['signstamp'];

    $sql_sekininsha = "SELECT `signstamp` FROM `tbl_user` WHERE `email` = '$sekininsha_uid'";
    $result_sekininsha = mysqli_query($conn, $sql_sekininsha);
    $sekininsha_stamp = mysqli_fetch_assoc($result_sekininsha)['signstamp'];

    $userkyuka_list[$key]['tantosha_stamp'] = $tantosha_stamp;
    $userkyuka_list[$key]['sekininsha_stamp'] = $sekininsha_stamp;
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

// kyuka 提出
$currentUseUid = $_SESSION['auth_uid'];
$currentUseCompanyId = $_SESSION['auth_companyid'];
$currentUseEmail = $_SESSION['auth_email'];
$user_stamp = '';



if (isset($_POST['Kyukateishutsu'])) {
    $selectedUserKyukaId = mysqli_real_escape_string($conn, $_POST['selectedUserKyukaId']);
    $selectedUserKyukaEmail = mysqli_real_escape_string($conn, $_POST['selectedUserKyukaEmail']);

    
    // check is registed userKyuka ?
    $sql_get_selectedUserKyukaStatus = 'SELECT * FROM tbl_userkyuka WHERE  
    `tbl_userkyuka`.`companyid` IN("' . $currentUseCompanyId . '") AND `tbl_userkyuka`.`kyukaid` IN("' . $selectedUserKyukaId . '")';
    $result = $conn->query($sql_get_selectedUserKyukaStatus);
    if ($result === false) {
        $_SESSION['$user_kyuka_data_not_found'] = $user_kyuka_data_not_found;
        return;
    }
    $resultUserKyuka = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $kyuka_submission_code =  $resultUserKyuka[0]['submission_status'];

    if($kyuka_submission_code == 0) {       
        $query_teishutsu_kyuka = "UPDATE tbl_userkyuka SET `submission_status` = 1 , `teishutsu_uid` = '$currentUseEmail' , `upt_dt`='$upt_dt'  WHERE  
        `tbl_userkyuka`.`companyid` IN('$currentUseCompanyId') AND `tbl_userkyuka`.`kyukaid` IN('$selectedUserKyukaId')";
        // set User SignStamp
        if($selectedUserKyukaEmail ==$currentUseEmail ) {
            $query_get_stamp = "SELECT `tbl_user`.`signstamp` FROM tbl_user where `tbl_user`.`email` = '$currentUseEmail' ";
            $result_user_stamp = $conn->query($query_get_stamp);
            $user_stamp = mysqli_fetch_all($result_user_stamp, MYSQLI_ASSOC);
        }

        if ($conn->query($query_teishutsu_kyuka) === TRUE) {
            $_SESSION['user_kyuka_kakutei_success'] = $user_kyuka_kakutei_success;
            header("Refresh: 3");
        } else {
            $_SESSION['user_kyuka_kakutei_fail'] = $user_kyuka_kakutei_fail;
        }
    }
}


//  Admin Only Action 
if ($_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {

// User Kyuka 編集戻し
if (isset($_POST['KyukaHenshuModoshi'])) {

    $selectedId = mysqli_real_escape_string($conn, $_POST['selectedUserKyukaModoshiId']);
    $sql_get_selectedUserKyukaStatus = 'SELECT * FROM tbl_userkyuka WHERE  
    `tbl_userkyuka`.`companyid` IN("' . $currentUseCompanyId . '") AND `tbl_userkyuka`.`kyukaid` IN("' . $selectedId . '")';
    $result = $conn->query($sql_get_selectedUserKyukaStatus);
    // check is registed userKyuka ?
    if ($result === false) {
        return;
    } 
    $resultUserKyuka = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $current_submission_code =  $resultUserKyuka[0]['submission_status'];
        $query_modoshi_kyuka = "UPDATE tbl_userkyuka SET `submission_status` = 0 ,  allowok = '0', `teishutsu_uid` = null ,  `tantosha_uid` = null ,
        `sekininsha_uid` = null , `upt_dt`='$upt_dt'  WHERE  
        `tbl_userkyuka`.`companyid` IN('$currentUseCompanyId') AND `tbl_userkyuka`.`kyukaid` IN('$selectedId')";
        if ($conn->query($query_modoshi_kyuka) === TRUE) {
            $_SESSION['user_kyuka_modoshi_success'] = $user_kyuka_modoshi_success;
            header("Refresh: 3");
        } else {
            $_SESSION['user_kyuka_modoshi_fail'] = $user_kyuka_modoshi_fail;
        }
}

// User Kyuka TantoshaShonin    selectedUserKyukaTantoShoninId
if (isset($_POST['KyukaTantoshaShonin'])) {
    $selectedId = mysqli_real_escape_string($conn, $_POST['selectedUserKyukaTantoShoninId']);
    $sql_get_selectedUserKyukaStatus = 'SELECT * FROM tbl_userkyuka WHERE  
    `tbl_userkyuka`.`companyid` IN("' . $currentUseCompanyId . '") AND `tbl_userkyuka`.`kyukaid` IN("' . $selectedId . '")';
    $result = $conn->query($sql_get_selectedUserKyukaStatus);
    // check is registed userKyuka ?
    if ($result === false) {
        return;
    } 
    $resultUserKyuka = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $current_submission_code =  $resultUserKyuka[0]['submission_status'];

    if($current_submission_code != 1 && $current_submission_code != 2 && $current_submission_code != 3) {
        return;
    }
    $savingCode = 2;
    // when 責任者確認済み
     if($current_submission_code == 3) { 
         $savingCode  = 3;
     }
     $query_shonin = "UPDATE tbl_userkyuka SET `submission_status` = '$savingCode' , `tantosha_uid` = '$currentUseEmail' , `upt_dt`='$upt_dt'  WHERE  
     `tbl_userkyuka`.`companyid` IN('$currentUseCompanyId') AND `tbl_userkyuka`.`kyukaid` IN('$selectedId')";
        if ($conn->query($query_shonin) === TRUE) {
            $_SESSION['tanto_shonin_success'] = $tanto_shonin_success;
            header("Refresh: 3");
      } else {
            $_SESSION['tanto_shonin_error'] = $tanto_shonin_error;
     }
  
}

// User Kyuka SekininshaShonin     selectedUserKyukaSekininShoninId
if (isset($_POST['KyukaSekininshaShonin'])) {  
    $selectedId = mysqli_real_escape_string($conn, $_POST['selectedUserKyukaSekininShoninId']);
    $sql_get_selectedUserKyukaStatus = 'SELECT * FROM tbl_userkyuka WHERE  
    `tbl_userkyuka`.`companyid` IN("' . $currentUseCompanyId . '") AND `tbl_userkyuka`.`kyukaid` IN("' . $selectedId . '")';
    $result = $conn->query($sql_get_selectedUserKyukaStatus);
    // check is registed userKyuka ?
    if ($result === false) {
        return;
    } 
    $resultUserKyuka = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $current_submission_code =  $resultUserKyuka[0]['submission_status'];

    if($current_submission_code != 1 && $current_submission_code != 2 && $current_submission_code != 3) {
        return;
    }
    $savingCode = 3;
     $query_shonin = "UPDATE tbl_userkyuka SET `submission_status` = '$savingCode' , `sekininsha_uid` = '$currentUseEmail' , `upt_dt`='$upt_dt'  WHERE  
     `tbl_userkyuka`.`companyid` IN('$currentUseCompanyId') AND `tbl_userkyuka`.`kyukaid` IN('$selectedId')";
        if ($conn->query($query_shonin) === TRUE) {
            $_SESSION['sekinin_shonin_success'] = $sekinin_shonin_success;
            header("Refresh: 3");
      } else {
            $_SESSION['sekinin_shonin_error'] = $sekinin_shonin_error;
     }

}

}
