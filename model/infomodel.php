<?php
// (workdayList.php)
// Select database from tbl_workday table
$reg_dt = date('Y-m-d H:i:s');
$companyId_ = $_SESSION['auth_companyid'];
$sql_workday = "SELECT workyear,
    MAX(CASE WHEN workmonth = '01' THEN workmonth END) AS one_month,
    MAX(CASE WHEN workmonth = '01' THEN workdays END) AS one_monthwd,
    MAX(CASE WHEN workmonth = '02' THEN workmonth END) AS two_month,
    MAX(CASE WHEN workmonth = '02' THEN workdays END) AS two_monthwd,
    MAX(CASE WHEN workmonth = '03' THEN workmonth END) AS three_month,
    MAX(CASE WHEN workmonth = '03' THEN workdays END) AS three_monthwd,
    MAX(CASE WHEN workmonth = '04' THEN workmonth END) AS four_month,
    MAX(CASE WHEN workmonth = '04' THEN workdays END) AS four_monthwd,
    MAX(CASE WHEN workmonth = '05' THEN workmonth END) AS five_month,
    MAX(CASE WHEN workmonth = '05' THEN workdays END) AS five_monthwd,
    MAX(CASE WHEN workmonth = '06' THEN workmonth END) AS six_month,
    MAX(CASE WHEN workmonth = '06' THEN workdays END) AS six_monthwd,
    MAX(CASE WHEN workmonth = '07' THEN workmonth END) AS seven_month,
    MAX(CASE WHEN workmonth = '07' THEN workdays END) AS seven_monthwd,
    MAX(CASE WHEN workmonth = '08' THEN workmonth END) AS eight_month,
    MAX(CASE WHEN workmonth = '08' THEN workdays END) AS eight_monthwd,
    MAX(CASE WHEN workmonth = '09' THEN workmonth END) AS nine_month,
    MAX(CASE WHEN workmonth = '09' THEN workdays END) AS nine_monthwd,
    MAX(CASE WHEN workmonth = '10' THEN workmonth END) AS ten_month,
    MAX(CASE WHEN workmonth = '10' THEN workdays END) AS ten_monthwd,
    MAX(CASE WHEN workmonth = '11' THEN workmonth END) AS eleven_month,
    MAX(CASE WHEN workmonth = '11' THEN workdays END) AS eleven_monthwd,
    MAX(CASE WHEN workmonth = '12' THEN workmonth END) AS twelve_month,
    MAX(CASE WHEN workmonth = '12' THEN workdays END) AS twelve_monthwd
    FROM `tbl_workday`
    WHERE `companyid` = $companyId_
    GROUP BY workyear
    ORDER BY workyear DESC, one_month ASC";
$result_workday = mysqli_query($conn, $sql_workday);
$workday_list = mysqli_fetch_all($result_workday, MYSQLI_ASSOC);

// Save data to tbl_workday table of database
if (isset($_POST['btnRegWdl'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $workyear = mysqli_real_escape_string($conn, $_POST['workyear']);
    if ($_POST['workday01'] == "") {
        $_POST['workday01'] = 0;
    }
    if ($_POST['workday02'] == "") {
        $_POST['workday02'] = 0;
    }
    if ($_POST['workday03'] == "") {
        $_POST['workday03'] = 0;
    }
    if ($_POST['workday04'] == "") {
        $_POST['workday04'] = 0;
    }
    if ($_POST['workday05'] == "") {
        $_POST['workday05'] = 0;
    }
    if ($_POST['workday06'] == "") {
        $_POST['workday06'] = 0;
    }
    if ($_POST['workday07'] == "") {
        $_POST['workday07'] = 0;
    }
    if ($_POST['workday07'] == "") {
        $_POST['workday07'] = 0;
    }
    if ($_POST['workday08'] == "") {
        $_POST['workday08'] = 0;
    }
    if ($_POST['workday09'] == "") {
        $_POST['workday09'] = 0;
    }
    if ($_POST['workday10'] == "") {
        $_POST['workday10'] = 0;
    }
    if ($_POST['workday11'] == "") {
        $_POST['workday11'] = 0;
    }
    if ($_POST['workday12'] == "") {
        $_POST['workday12'] = 0;
    }

    $workmonth[1] = mysqli_real_escape_string($conn, $_POST['month01']);
    $workdays[1] = mysqli_real_escape_string($conn, $_POST['workday01']);
    $workmonth[2] = mysqli_real_escape_string($conn, $_POST['month02']);
    $workdays[2] = mysqli_real_escape_string($conn, $_POST['workday02']);
    $workmonth[3] = mysqli_real_escape_string($conn, $_POST['month03']);
    $workdays[3] = mysqli_real_escape_string($conn, $_POST['workday03']);
    $workmonth[4] = mysqli_real_escape_string($conn, $_POST['month04']);
    $workdays[4] = mysqli_real_escape_string($conn, $_POST['workday04']);
    $workmonth[5] = mysqli_real_escape_string($conn, $_POST['month05']);
    $workdays[5] = mysqli_real_escape_string($conn, $_POST['workday05']);
    $workmonth[6] = mysqli_real_escape_string($conn, $_POST['month06']);
    $workdays[6] = mysqli_real_escape_string($conn, $_POST['workday06']);
    $workmonth[7] = mysqli_real_escape_string($conn, $_POST['month07']);
    $workdays[7] = mysqli_real_escape_string($conn, $_POST['workday07']);
    $workmonth[8] = mysqli_real_escape_string($conn, $_POST['month08']);
    $workdays[8] = mysqli_real_escape_string($conn, $_POST['workday08']);
    $workmonth[9] = mysqli_real_escape_string($conn, $_POST['month09']);
    $workdays[9] = mysqli_real_escape_string($conn, $_POST['workday09']);
    $workmonth[10] = mysqli_real_escape_string($conn, $_POST['month10']);
    $workdays[10] = mysqli_real_escape_string($conn, $_POST['workday10']);
    $workmonth[11] = mysqli_real_escape_string($conn, $_POST['month11']);
    $workdays[11] = mysqli_real_escape_string($conn, $_POST['workday11']);
    $workmonth[12] = mysqli_real_escape_string($conn, $_POST['month12']);
    $workdays[12] = mysqli_real_escape_string($conn, $_POST['workday12']);
    $work_data = [];
    for ($month = 1; $month <= 12; $month++) {
        // Prepare the data for insertion
        $work_data[] = "('$companyid', '$workyear', '$workmonth[$month]', '$workdays[$month]')";
    }

    // Prepare the SQL query to insert all data in a single query
    $sql = "INSERT INTO `tbl_workday` (`companyid`, `workyear`, `workmonth`, `workdays`) VALUES " . implode(",", $work_data);
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update data to tbl_workday table of database
if (isset($_POST['btnUpdateWdl'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $workyear = mysqli_real_escape_string($conn, $_POST['udworkyear']);

    $workmonth[1] = $_POST['udmonth01'];
    $workdays[1] = $_POST['udworkday01'];
    $workmonth[2] = $_POST['udmonth02'];
    $workdays[2] = $_POST['udworkday02'];
    $workmonth[3] = $_POST['udmonth03'];
    $workdays[3] = $_POST['udworkday03'];
    $workmonth[4] = $_POST['udmonth04'];
    $workdays[4] = $_POST['udworkday04'];
    $workmonth[5] = $_POST['udmonth05'];
    $workdays[5] = $_POST['udworkday05'];
    $workmonth[6] = $_POST['udmonth06'];
    $workdays[6] = $_POST['udworkday06'];
    $workmonth[7] = $_POST['udmonth07'];
    $workdays[7] = $_POST['udworkday07'];
    $workmonth[8] = $_POST['udmonth08'];
    $workdays[8] = $_POST['udworkday08'];
    $workmonth[9] = $_POST['udmonth09'];
    $workdays[9] = $_POST['udworkday09'];
    $workmonth[10] = $_POST['udmonth10'];
    $workdays[10] = $_POST['udworkday10'];
    $workmonth[11] = $_POST['udmonth11'];
    $workdays[11] = $_POST['udworkday11'];
    $workmonth[12] = $_POST['udmonth12'];
    $workdays[12] = $_POST['udworkday12'];

    $workmonth_arr = array();
    $workdays_arr = array();
    for ($month = 1; $month <= 12; $month++) {
        $workmonth_arr[$month] = mysqli_real_escape_string($conn, $workmonth[$month]);
        $workdays_arr[$month] = mysqli_real_escape_string($conn, $workdays[$month]);
    }

    $update_queries = array();
    for ($month = 1; $month <= 12; $month++) {
        $update_queries[] = "workmonth = '$workmonth_arr[$month]', workdays = '$workdays_arr[$month]'";
    }

    $combined_sql = "INSERT INTO tbl_workday (companyid, workyear, workmonth, workdays) VALUES ";
    $insert_values = array();
    for ($month = 1; $month <= 12; $month++) {
        $insert_values[] = "('$companyid', '$workyear', '$workmonth_arr[$month]', '$workdays_arr[$month]')";
    }
    $combined_sql .= implode(", ", $insert_values) . " ON DUPLICATE KEY UPDATE workmonth = VALUES(workmonth), workdays = VALUES(workdays)";
    $result = mysqli_query($conn, $combined_sql);
    if ($result) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete data to tbl_workday table of database
if (isset($_POST['btnDelWdl'])) {
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $workyear = mysqli_real_escape_string($conn, $_POST['udworkyear']);

    $sql = "DELETE FROM `tbl_workday` 
    WHERE companyid ='$companyid' AND workyear ='$workyear'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}


// (holidayReg.php)
if ($_POST['selyy'] == NULL) {
    $_POST["selyy"] = $_SESSION['year_Hdr'];
}
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');

// Select database from tbl_holiday table
$sql_holiday = 'SELECT * FROM `tbl_holiday` 
    WHERE `tbl_holiday`.`companyid` IN("' . $companyId_ . '")
    AND `tbl_holiday`.`holiyear` IN("' . $year . '")';
$result_holiday = mysqli_query($conn, $sql_holiday);
$holiday_list = mysqli_fetch_all($result_holiday, MYSQLI_ASSOC);

// Save database to tbl_holiday table
if (isset($_POST['btnRegHdr'])) {
    $_SESSION['year_Hdr'] = $year_Hdr = substr($_POST['holiday'], 0, 4);
    $companyid = mysqli_real_escape_string($conn, $_POST['companyid']);
    $holiyear = mysqli_real_escape_string($conn, $year_Hdr);
    $holiday = mysqli_real_escape_string($conn, $_POST['holiday']);
    $holiremark = mysqli_real_escape_string($conn, $_POST['holiremark']);

    $sql_holiday_insert = "INSERT INTO `tbl_holiday` (`companyid`, `holiyear`, `holiday`, `holiremark`) 
	VALUES('$companyid', '$holiyear', '$holiday', '$holiremark')";
    if (mysqli_query($conn, $sql_holiday_insert)) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update database to tbl_holiday table
if (isset($_POST['btnUpdateHdr'])) {
    $_SESSION['year_Hdr'] = $_POST['udholiyear'];
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $holiyear = mysqli_real_escape_string($conn, $_POST['udholiyear']);
    $holiday = mysqli_real_escape_string($conn, $_POST['udholiday']);
    $holiremark = mysqli_real_escape_string($conn, $_POST['udholiremark']);

    $sql = "UPDATE tbl_holiday SET 
                holiremark='$holiremark'
            WHERE companyid ='$companyid'
            AND holiyear ='$holiyear'
            AND holiday ='$holiday'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Delete database to tbl_holiday table
if (isset($_POST['btnDelHdr'])) {
    $_SESSION['year_Hdr'] = $_POST['udholiyear'];
    $companyid = mysqli_real_escape_string($conn, $_POST['udcompanyid']);
    $holiyear = mysqli_real_escape_string($conn, $_POST['udholiyear']);
    $holiday = mysqli_real_escape_string($conn, $_POST['udholiday']);

    $sql = "DELETE FROM `tbl_holiday` 
    WHERE companyid ='$companyid' AND holiyear ='$holiyear' AND holiday ='$holiday'";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// (uservacationList.php)
// Select data from tbl_user & tbl_vacationinfo
if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) {
    $sql_uservacation_select = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_vacationinfo`.`vacationid`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt`
    FROM
    `tbl_user`
    LEFT JOIN `tbl_vacationinfo` ON `tbl_user`.`uid` = `tbl_vacationinfo`.`uid`
    WHERE `tbl_user`.`companyid` IN ("' . $companyId_ . '")';
    $result_uservacation_select = mysqli_query($conn, $sql_uservacation_select);
    $uservacation_list_select = mysqli_fetch_all($result_uservacation_select, MYSQLI_ASSOC);
} elseif ($_SESSION['auth_type'] == constant('USER')) {
    $sql_uservacation_select = 'SELECT DISTINCT
    `tbl_user`.*,
    `tbl_vacationinfo`.`vacationid`,
    `tbl_vacationinfo`.`vacationstr`,
    `tbl_vacationinfo`.`vacationend`,
    `tbl_vacationinfo`.`oldcnt`,
    `tbl_vacationinfo`.`newcnt`,
    `tbl_vacationinfo`.`usecnt`,
    `tbl_vacationinfo`.`usetime`,
    `tbl_vacationinfo`.`restcnt` 
    FROM
    `tbl_user`
    LEFT JOIN `tbl_vacationinfo` ON `tbl_user`.`uid` = `tbl_vacationinfo`.`uid`
    WHERE `tbl_user`.`companyid` IN ("' . $companyId_ . '")
    AND `tbl_user`.`uid` IN ("' . $_SESSION['auth_uid'] . '")';
    $result_uservacation_select = mysqli_query($conn, $sql_uservacation_select);
    $uservacation_list_select = mysqli_fetch_all($result_uservacation_select, MYSQLI_ASSOC);
}

if ($_POST['uservacationListSearch'] == NULL) {
    $uservacation_list = $uservacation_list_select;
} elseif (isset($_POST['uservacationListSearch'])) {
    if (!empty($uservacation_list_select)) {
        foreach ($uservacation_list_select as $key) {
            $Name[] = $key['name'];
            $Inymd[] = $key['inymd'];
        }
    }
    $Name = array_unique($Name);
    $Inymd = array_unique($Inymd);

    $searchName = isset($_POST['searchName']) ? "%" . trim($_POST['searchName']) . "%" : "";
    $searchYmd = isset($_POST['searchYmd']) ? "%" . trim($_POST['searchYmd']) . "%" : "";
    $sql_uservacation = 'SELECT DISTINCT
`tbl_user`.*,
`tbl_vacationinfo`.`vacationid`,
`tbl_vacationinfo`.`vacationstr`,
`tbl_vacationinfo`.`vacationend`,
`tbl_vacationinfo`.`oldcnt`,
`tbl_vacationinfo`.`newcnt`,
`tbl_vacationinfo`.`usecnt`,
`tbl_vacationinfo`.`usetime`,
`tbl_vacationinfo`.`restcnt`
FROM
`tbl_user`
LEFT JOIN `tbl_vacationinfo` ON `tbl_user`.`uid` = `tbl_vacationinfo`.`uid`
WHERE `tbl_user`.`companyid` IN ("' . $companyId_ . '") ' ;


if ($searchName !== "" && $searchName !=='%%') {
    $sql_uservacation .= ' AND `tbl_user`.`name` LIKE "' . $searchName . '"';
}

if ($searchYmd !== "" && $searchYmd !=='%%') {
    $sql_uservacation .= ' AND `tbl_user`.`inymd` LIKE "' . $searchYmd . '"';
}
    $result_uservacation = mysqli_query($conn, $sql_uservacation);
    $uservacation_list = mysqli_fetch_all($result_uservacation, MYSQLI_ASSOC);
}

if (isset($_POST['btnUpdateUvl'])) {
    $udvacationid = intval($_POST['udvacationid']);
    $vacationid = mysqli_real_escape_string($conn, $udvacationid);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);
    $vacationstr = mysqli_real_escape_string($conn, $_POST['udvacationstr']);
    $vacationend = mysqli_real_escape_string($conn, $_POST['udvacationend']);
    $oldcnt = mysqli_real_escape_string($conn, $_POST['udoldcnt']);
    $newcnt = mysqli_real_escape_string($conn, $_POST['udnewcnt']);
    $usecnt = mysqli_real_escape_string($conn, $_POST['udusecnt']);
    $usetime = mysqli_real_escape_string($conn, $_POST['udusetime']);
    $restcnt = mysqli_real_escape_string($conn, $_POST['udrestcnt']);

    $sql = "INSERT INTO `tbl_vacationinfo` (`vacationid`, `uid`, `vacationstr`, `vacationend`, `oldcnt`, `newcnt`, `usecnt`, `usetime`, `restcnt`, `reg_dt`)
                VALUES ('$vacationid', '$uid', '$vacationstr', '$vacationend', '$oldcnt', '$newcnt', '$usecnt', '$usetime', '$restcnt', '$reg_dt')
                ON DUPLICATE KEY UPDATE
                vacationstr='$vacationstr', vacationend='$vacationend', oldcnt='$oldcnt', newcnt='$newcnt', usecnt='$usecnt', usetime='$usetime', restcnt='$restcnt'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

if (isset($_POST['btnDelUvl'])) {
    $vacationid = mysqli_real_escape_string($conn, $_POST['udvacationid']);
    $uid = mysqli_real_escape_string($conn, $_POST['uduid']);

    $sql = "DELETE FROM `tbl_vacationinfo` 
    WHERE vacationid ='$vacationid' AND uid ='$uid'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete_success'] = $delete_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// Update Database of tbl_user inymd column
if (isset($_POST['btnUpdateUser'])) {
    $uid = mysqli_real_escape_string($conn, $_POST['useruid']);
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $inymd = mysqli_real_escape_string($conn, $_POST['userinymd']);

    $sql = "UPDATE tbl_user SET 
    inymd='$inymd'
    WHERE uid ='$uid'
    AND name ='$name'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = $update_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}