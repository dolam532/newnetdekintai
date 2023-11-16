<?php
// now year-month-date
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');

// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`companyid`="' . $_SESSION['auth_companyid'] . '"';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);




$uid_g = $_SESSION['auth_uid'];
$name_g = $_SESSION['auth_name'];
$dept_g = $_SESSION['auth_dept'];

if (isset($_POST['uid_g'])) {
        $_SESSION['uid_g'] = $_POST['uid_g'];
}
if (isset($_POST['name_g'])) {
        $_SESSION['name_g'] = $_POST['name_g'];
}
if (isset($_POST['dept_g'])) {
        $_SESSION['dept_g'] = $_POST['dept_g'];
}
$uid_g = isset($_SESSION['uid_g']) ? $_SESSION['uid_g'] : $_SESSION['auth_uid'];
$name_g = isset($_SESSION['name_g']) ? $_SESSION['name_g'] : $_SESSION['auth_name'];
$dept_g = isset($_SESSION['dept_g']) ? $_SESSION['dept_g'] : $_SESSION['auth_dept'];


if ($uid_g === '' || $name_g === '' || $dept_g === '') {
        echo 'error' . '選択した会員のデータが異常が発生したました。サイト管理者へ連絡してください';
}


// Now you can access the variables from temporary.php
$employee_uid = $uid_g;
$employee_name = $name_g;
$employee_genid = 0;
$employee_dept = $dept_g;
$current_CompanyId_ = $_SESSION['auth_companyid'];
$employee_signstamp = '';

// get current genId of uid 
$sql_employee_genid = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`uid`="' . $employee_uid . '" 
                         AND `tbl_user`.`companyid` = ' . $current_CompanyId_ . '  ';
$result = $conn->query($sql_employee_genid);
if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
                $employee_genid = $row['genid'];
                $employee_signstamp = $row['signstamp'];
        }
}

// $employee_signstamp = $signstamp_e; ///**** NOT VALUE NEED work*/
$sql_user_admin = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`type`="' . constant('ADMIN') . '"';
$result_user_admin = mysqli_query($conn, $sql_user_admin);
$signstamp_admin = mysqli_fetch_all($result_user_admin, MYSQLI_ASSOC);

$sql_user_kanri = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`type`="' . constant('ADMINISTRATOR') . '"';
$result_user_kanri = mysqli_query($conn, $sql_user_kanri);
$signstamp_kanri = mysqli_fetch_all($result_user_kanri, MYSQLI_ASSOC);


if ($_POST['selmm'] == NULL && $_POST['selyy'] == NULL && $_POST['template_table'] == NULL) {
        $_POST['selmm'] = $_SESSION['selmm'];
        $_POST['selyy'] = $_SESSION['selyy'];
        $_POST['template_table'] = $currentTemplate;
}

$_SESSION['selmm'] = $_POST['selmm'];
$_SESSION['selyy'] = $_POST['selyy'];


$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');

// Get Data From tbl_workmonth
$sql_workmonth = 'SELECT
    *
FROM
    `tbl_workmonth`
WHERE
    `tbl_workmonth`.`uid` IN("' . $employee_uid . '")  
    AND LEFT(`tbl_workmonth`.`workym`, 6) IN("' . $year . $month . '")';
$result_workmonth = mysqli_query($conn, $sql_workmonth);
$workmonth_list = mysqli_fetch_all($result_workmonth, MYSQLI_ASSOC);
foreach ($workmonth_list as $key) {
        $template_ = $key['template'];
}

// get template from genid 
$currentGenid = $employee_genid;
$currentTemplate_ = 1;
$sqlTemplate = "SELECT `template` from tbl_genba WHERE `genid` = '$currentGenid';";
$result = $conn->query($sqlTemplate);
if ($result) {
        if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentTemplate_ = $row['template'];
        } else {
        }
} else {
        echo 'error' . mysqli_error($conn);
}
$decide_template_ = $currentTemplate_;

// get company Name 
$getCompanysql = "SELECT `companyname` FROM tbl_company WHERE `companyid` = '$current_CompanyId_' LIMIT 1";
$companyName_ = "";
$result = $conn->query($getCompanysql);
if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
                $companyName_ = $row['companyname'];
        }
}

// get list holyday to show red date 
$sqlGetCurrentMonthHolydays = "SELECT `holiday` FROM tbl_holiday 
WHERE `companyid` = '$current_CompanyId_' 
AND `holiyear` = '$year' 
AND `holiday` LIKE '$year/$month/%' ";
$holidayDates_ = array();
$result = $conn->query($sqlGetCurrentMonthHolydays);
if ($result) {
        while ($row = $result->fetch_assoc()) {
                $holidayDates_[] = $row['holiday'];
        }
}

// get count workdays 
$companyid = $current_CompanyId_;
$uid_ = $employee_uid;
$jobdays2;
$sql_getjd_currentMonth = "SELECT `workdays` FROM tbl_workday WHERE `companyid` = '$companyid' AND `workyear` = '$year' AND `workmonth` = '$month' LIMIT 1;";
$result = mysqli_query($conn, $sql_getjd_currentMonth);
if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
                $jobdays2 = $row['workdays'];
        } else {
                $jobdays2 = 0;
        }
} else {
        echo 'Query error: ' . mysqli_error($conn);
}



// Create a date string in "YYYY-MM" format
$dateString = $year . "-" . $month;

// Get the number of days in the selected month and year
$daysInMonth = date("t", strtotime($dateString));
$weekdays = array(
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
        7 => '日'
);


//----- 2023/11/13---- submisstion add start//
// get current submisstion -> when != 0 no change

$sql_get_currentSubmission_status = 'SELECT `submission_status` FROM tbl_workmonth WHERE `tbl_workmonth`.`uid` 
IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '")  AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';
$currentSubmission_status;

$result = $conn->query($sql_get_currentSubmission_status);
if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
                $currentSubmission_status = $row['submission_status'];
        }
}
if ($currentSubmission_status == null) {
        $currentSubmission_status = 0;
}
$submissionStatusText = "";
if ($submissionStatusText == null) {
        $submissionStatusText = $SUBMISSTION_STATUS[0];
}

if ($submissionStatus == null) {
        $submissionStatus = 0;
}
$submissionStatusText = isset($SUBMISSTION_STATUS[$currentSubmission_status]) ? $SUBMISSTION_STATUS[$currentSubmission_status] : $SUBMISSTION_STATUS[0];
// error_log("CURRENT SUBMIS STATUS: " . $currentSubmission_status);
//----- 2023/11/13---- submisstion add end//



// Display the dates, months, weekdays, and weekends in Japanese
for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = date("Y-m-d", strtotime($dateString . "-" . $day));
        $Year_ = date("Y", strtotime($dateString . "-" . $day));
        $date_ = date("d", strtotime($dateString . "-" . $day));
        $month_ = date("m", strtotime($dateString . "-" . $day));
        $date_show = $Year_ . "/" . $month_ . "/";
        $weekday = $weekdays[date("N", strtotime($date))];
        $isHoliday = in_array($Year_ . "/" . $month_ . "/" . $date_, $holidayDates_);
        $datas[] = [
                'date' => $month_ . "/" . $date_ . "(" . $weekday . ")",
                'decide_color' => $weekday,
                'workymd' => $Year_ . "/" . $month_ . "/" . $date_,
                'template' => $decide_template_,
                'isHoliday' => $isHoliday
        ];
}

// Get Data From tbl_worktime
$sql_worktime = 'SELECT
    *
FROM
    `tbl_worktime`
WHERE
    `tbl_worktime`.`uid` IN("' . $employee_uid . '")  
    AND LEFT(`tbl_worktime`.`workymd`, 8) IN("' . $date_show . '")';
$result_worktime = mysqli_query($conn, $sql_worktime);
$worktime_list = mysqli_fetch_all($result_worktime, MYSQLI_ASSOC);

$totalWorkHours = 0;
$totalWorkMinutes = 0;
$totalJanHours = 0;
$totalJanMinutes = 0;
$totalDayHours = 0;
$totalDayMinutes = 0;
$countJobStartHH = 0;
$countDayStartHH = 0;
$countLate = 0;
$countEarly = 0;

//---2023-10-18 add start ------//
$countHoliday = 0;
$countKuyka = 0;
$countDaikyu = 0;
$countKekkin = 0;
//---2023-10-18 add end ------//
foreach ($worktime_list as $work) {
        if (isset($work['jobstarthh']) && !empty($work['jobstarthh'])) {
                $countJobStartHH++;
        }

        if (isset($work['daystarthh']) && !empty($work['daystarthh'])) {
                $countDayStartHH++;

                $dayHours = isset($work['workhh']) ? intval($work['workhh']) : 0;
                $dayMinutes = isset($work['workmm']) ? intval($work['workmm']) : 0;
                $totalDayHours += $dayHours;
                $totalDayMinutes += $dayMinutes;
        }

        if (!empty($work['daystarthh']) && !empty($work['daystartmm']) && !empty($work['dayendhh']) && !empty($work['dayendhh'])) {
                $jobstarthh = intval($work['jobstarthh']);
                $daystarthh = intval($work['daystarthh']);
                $jobstartmm = intval($work['jobstartmm']);
                $daystartmm = intval($work['daystartmm']);
                $jobstart_time = new DateTime();
                $jobstart_time->setTime($jobstarthh, $jobstartmm);
                $daystart_time = new DateTime();
                $daystart_time->setTime($daystarthh, $daystartmm);
                if ($jobstart_time < $daystart_time) {
                        $countLate++;
                }

                $dayendhh = intval($work['dayendhh']);
                $dayendmm = intval($work['dayendmm']);
                $jobendhh = intval($work['jobendhh']);
                $jobendmm = intval($work['jobendmm']);
                $dayend_time = new DateTime();
                $dayend_time->setTime($dayendhh, $dayendmm);
                $jobend_time = new DateTime();
                $jobend_time->setTime($jobendhh, $jobendmm);
                if ($dayend_time < $jobend_time) {
                        $countEarly++;
                }
        }

        $workHours = isset($work['workhh']) ? intval($work['workhh']) : 0;
        $workMinutes = isset($work['workmm']) ? intval($work['workmm']) : 0;
        $totalWorkHours += $workHours;
        $totalWorkMinutes += $workMinutes;

        $janHours = isset($work['janhh']) ? intval($work['janhh']) : 0;
        $janMinutes = isset($work['janmm']) ? intval($work['janmm']) : 0;
        $totalJanHours += $janHours;
        $totalJanMinutes += $janMinutes;
        //---2023-10-18 add start ------//
        if ($work['holy_decide'] != array_keys($HOLY_DECIDE)[0]) {
                if ($work['holy_decide'] == array_keys($HOLY_DECIDE)[1]) { // select by array index 
                        $countHoliday++;
                }
                if ($work['holy_decide'] == array_keys($HOLY_DECIDE)[2]) {
                        $countKuyka++;
                }
                if ($work['holy_decide'] == array_keys($HOLY_DECIDE)[3]) {
                        $countDaikyu++;
                }
                if ($work['holy_decide'] == array_keys($HOLY_DECIDE)[4]) {
                        $countKekkin++;
                }
        }
        //---2023-10-18 add end ------//
}
$countJobAct = $countJobStartHH - $countDayStartHH;

// Adjust minutes to hours if necessary
if ($totalWorkMinutes >= 60) {
        $additionalWHours = floor($totalWorkMinutes / 60);
        $totalWorkHours += $additionalWHours;
        $totalWorkMinutes %= 60;
}

if ($totalJanMinutes >= 60) {
        $additionalJHours = floor($totalJanMinutes / 60);
        $totalJanHours += $additionalJHours;
        $totalJanMinutes %= 60;
}

if ($totalDayMinutes >= 60) {
        $additionalDHours = floor($totalDayMinutes / 60);
        $totalDayHours += $additionalDHours;
        $totalDayMinutes %= 60;
}

$keyed = array_column($worktime_list, NULL, 'workymd'); // replace indexes with ur_user_id values
foreach ($datas as &$row) { // write directly to $array1 while iterating
        if (isset($keyed[$row['workymd']])) { // check if shared key exists
                $row += $keyed[$row['workymd']]; // append associative elements
        }
}

// 2023-10-20----- add start // 
if (isset($_POST['changeGenid'])) {
        $companyid = $current_CompanyId_;
        $selectedGenid = mysqli_real_escape_string($conn, $_POST['selectedGenid']);
        $sql = "UPDATE tbl_user SET `genid` = '$selectedGenid' , `upt_dt`= '$upt_dt'  where `uid` = '$uid_' AND `companyid` = '$companyid' ;";
        if ($conn->query($sql) === TRUE) {
                $_SESSION['save_success'] = $save_success;
                $_SESSION['auth_genid'] = $selectedGenid;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}
// 2023-10-20----- add end // 


// Save data to tbl_worktime table of database 
if (isset($_POST['SaveUpdateKintaiUserDetail'])) {
        if ($currentSubmission_status != 0) {
                $_SESSION['is_submissed_notchange'] = $is_submissed_notchange;
                return;
        }

        $genid_ = intval($_POST['genid']);
        $holy_decide = mysqli_real_escape_string($conn, $_POST['holy_decide']);
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);
        $workymd = mysqli_real_escape_string($conn, $_POST['date_show']);
        $uid = $employee_uid;
        $genid = mysqli_real_escape_string($conn, $_POST['genid']);
        $offtime = 0;
        $jobstarttime = 0;
        $jobendtime = 0;

        if (isset($_POST['jobstarthh']) && isset($_POST['jobstartmm']) && isset($_POST['jobendhh']) && isset($_POST['jobendmm'])) {
                $jobstarttime = DateTime::createFromFormat('H:i', $_POST['jobstarthh'] . ':' . $_POST['jobstartmm']);
                $jobendtime = DateTime::createFromFormat('H:i', $_POST['jobendhh'] . ':' . $_POST['jobendmm']);
                if (isset($_POST['offtimehh']) && isset($_POST['offtimemm'])) {
                        $offtime = DateTime::createFromFormat('H:i', $_POST['offtimehh'] . ':' . $_POST['offtimemm']);
                }
        }

        $calculatedHours = 0;
        $calculatedMinutes = 0;

        if ($_POST['jobstarthh'] !== null || $_POST['jobstartmm'] !== null) {
                if ($_POST['offtimehh'] == null || $_POST['offtimehh'] == null) {
                        $offtime = 0;
                }
                $interval = $jobendtime->diff($jobstarttime);
                $totalMinutes = ($interval->h * 60) + $interval->i;
                $totalMinutes -= ($offtime->format('H') * 60) + $offtime->format('i');
                $calculatedHours = floor($totalMinutes / 60);
                $calculatedMinutes = $totalMinutes % 60;
        }

        if ($_POST["template_table_"] == "1") {
                $janhh = '0';
                $janmm = '0';
                $_POST['daystarthh'] = '';
                $_POST['daystartmm'] = '';
                $_POST['dayendhh'] = '';
                $_POST['dayendmm'] = '';
        } elseif ($_POST["template_table_"] == "2") {
                $janhh = $_POST['workhh'] - $calculatedHours;
                $janmm = $_POST['workmm'] - $calculatedMinutes;
        }
        $jobstarthh = mysqli_real_escape_string($conn, $_POST['jobstarthh']);
        $jobstartmm = mysqli_real_escape_string($conn, $_POST['jobstartmm']);
        $jobendhh = mysqli_real_escape_string($conn, $_POST['jobendhh']);
        $jobendmm = mysqli_real_escape_string($conn, $_POST['jobendmm']);
        $daystarthh = mysqli_real_escape_string($conn, $_POST['daystarthh']);
        $daystartmm = mysqli_real_escape_string($conn, $_POST['daystartmm']);
        $dayendhh = mysqli_real_escape_string($conn, $_POST['dayendhh']);
        $dayendmm = mysqli_real_escape_string($conn, $_POST['dayendmm']);
        $offtimehh = mysqli_real_escape_string($conn, $_POST['offtimehh']);
        $offtimemm = mysqli_real_escape_string($conn, $_POST['offtimemm']);
        $workhh = mysqli_real_escape_string($conn, $_POST['workhh']);
        $workmm = mysqli_real_escape_string($conn, $_POST['workmm']);

        // $holy_decide = mysqli_real_escape_string($conn, $_POST['holy_decide']);
        if (!isset($holy_decide) || $holy_decide == null || $holy_decide == '') {
                $holy_decide = array_keys($HOLY_DECIDE)[0];
        }

        $sql = "INSERT INTO `tbl_worktime` (`uid`,  `companyid` ,`genid`, `workymd`, `daystarthh`, `daystartmm`, `dayendhh`, `dayendmm`, `jobstarthh`, `jobstartmm`,
                `jobendhh`, `jobendmm`, `offtimehh`, `offtimemm`, `workhh`, `workmm`, `janhh`, `janmm`, `comment`, `holy_decide`, `bigo`, `reg_dt` , `upt_dt`)
                VALUES ('$uid',  '$companyid' , '$genid', '$workymd', '$daystarthh', '$daystartmm', '$dayendhh', '$dayendmm', '$jobstarthh', '$jobstartmm',
                '$jobendhh', '$jobendmm', '$offtimehh', '$offtimemm', '$workhh', '$workmm', '$janhh', '$janmm', '$comment', '$holy_decide','$bigo', '$reg_dt' , null)
                ON DUPLICATE KEY UPDATE
                genid='$genid', daystarthh='$daystarthh', daystartmm='$daystartmm', dayendhh='$dayendhh', dayendmm='$dayendmm', jobstarthh='$jobstarthh', jobstartmm='$jobstartmm',
                jobendhh='$jobendhh', jobendmm='$jobendmm', offtimehh='$offtimehh', offtimemm='$offtimemm', workhh='$workhh', workmm='$workmm', janhh='$janhh',
                janmm='$janmm', comment='$comment', holy_decide = '$holy_decide' , bigo='$bigo',  upt_dt='$upt_dt'";


        if ($conn->query($sql) === TRUE) {
                $_SESSION['save_success'] = $save_success;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}

// Delete data to tbl_worktime table of database
if (isset($_POST['DeleteKintaiUserDetail'])) {

        if ($currentSubmission_status != 0) {
                $_SESSION['is_submissed_notchange'] = $is_submissed_notchange;
                return;
        }


        $_SESSION['selmm'] = substr($_POST['date_show'], 5, 2);
        $_SESSION['selyy'] = substr($_POST['date_show'], 0, 4);
        $uid = $employee_uid;
        $workymd = mysqli_real_escape_string($conn, $_POST['date_show']);
        $sql = "DELETE FROM `tbl_worktime` 
                  WHERE uid ='$uid' AND companyid ='$companyid' AND workymd ='$workymd'";
        if ($conn->query($sql) === TRUE) {
                $_SESSION['delete_success'] = $delete_success;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}

// Save data to tbl_workmonth table of database
if (isset($_POST['MonthSaveKintaiUserDetail'])) {
        error_log("Current Status: " . $currentSubmission_status);
        if ($currentSubmission_status != 0) {
                $_SESSION['is_submissed_notchange'] = $is_submissed_notchange;
                return;
        }

        $_SESSION['selmm'] = $month;
        $_SESSION['selyy'] = $year;
        $yearmonth = $year . $month;

        $jobhh_top_ = intval($_POST['jobhh_top']);
        $jobmm_top_ = intval($_POST['jobmm_top']);
        $jobhh_bottom_ = intval($_POST['jobhh_bottom']);
        $jobmm_bottom_ = intval($_POST['jobmm_bottom']);
        $holydays_top_ = intval($_POST['holydays_top']);
        $holydays_bottom_ = intval($_POST['holydays_bottom']);

        $uid = $employee_uid;
        $genid = $currentGenid;
        $workym = $yearmonth;

        $jobhour2 = mysqli_real_escape_string($conn, $jobhh_top_);
        $jobminute2 = mysqli_real_escape_string($conn, $jobmm_top_);
        $jobhour = mysqli_real_escape_string($conn, $jobhh_bottom_);
        $jobminute = mysqli_real_escape_string($conn, $jobmm_bottom_);


        $janhour2 = mysqli_real_escape_string($conn, $_POST['janhh_top']);
        $janminute2 = mysqli_real_escape_string($conn, $_POST['janmm_top']);
        $janhour = mysqli_real_escape_string($conn, $_POST['janhh_bottom']);
        $janminute = mysqli_real_escape_string($conn, $_POST['janmm_bottom']);

        $jobdays2 = mysqli_real_escape_string($conn, $_POST['jobdays_top']);
        $jobdays = mysqli_real_escape_string($conn, $_POST['jobdays_bottom']);
        $workdays2 = mysqli_real_escape_string($conn, $_POST['workdays_top']);
        $workdays = mysqli_real_escape_string($conn, $_POST['workdays_bottom']);
        $holydays2 = mysqli_real_escape_string($conn, $holydays_top_);
        $holydays = mysqli_real_escape_string($conn, $holydays_bottom_);
        $offdays2 = mysqli_real_escape_string($conn, $_POST['offdays_top']);
        $offdays = mysqli_real_escape_string($conn, $_POST['offdays_bottom']);
        $delaydays2 = mysqli_real_escape_string($conn, $_POST['delaydays_top']);
        $delaydays = mysqli_real_escape_string($conn, $_POST['delaydays_bottom']);
        $earlydays2 = mysqli_real_escape_string($conn, $_POST['earlydays_top']);
        $earlydays = mysqli_real_escape_string($conn, $_POST['earlydays_bottom']);

        $template = $currentTemplate_;

        $sql = "INSERT INTO `tbl_workmonth` (`uid`,  `companyid` , `genid`, `workym`, `jobhour`, `jobminute`, `jobhour2`, `jobminute2`, `janhour`, `janminute`, `janhour2`, `janminute2`,
        `jobdays`, `jobdays2`, `workdays`, `workdays2`, `holydays`, `holydays2`, `offdays`, `offdays2`, `delaydays`, `delaydays2`, `earlydays`, `earlydays2`, `template`, `submission_status` , `reg_dt` , `upt_dt`)
        VALUES ('$uid', '$companyid' , '$genid', '$workym', '$jobhour', '$jobminute', '$jobhour2', '$jobminute2', '$janhour', '$janminute', '$janhour2', '$janminute2',
        '$jobdays', '$jobdays2', '$workdays', '$workdays2', '$holydays', '$holydays2', '$offdays', '$offdays2', '$delaydays', '$delaydays2', '$earlydays', '$earlydays2', '$template',  0  ,  '$reg_dt' , null)
        ON DUPLICATE KEY UPDATE
        companyid='$companyid', genid='$genid', jobhour='$jobhour', jobminute='$jobminute', jobhour2='$jobhour2', jobminute2='$jobminute2',
        janhour='$janhour', janminute='$janminute', janhour2='$janhour2', janminute2='$janminute2', jobdays='$jobdays', jobdays2='$jobdays2', workdays='$workdays', workdays2='$workdays2', holydays='$holydays',
        holydays2='$holydays2', offdays='$offdays', offdays2='$offdays2', delaydays='$delaydays', delaydays2='$delaydays2',earlydays='$earlydays' , earlydays2='$earlydays2', template='$template', upt_dt='$upt_dt'";

        if ($conn->query($sql) === TRUE) {
                $_SESSION['save_success'] = $save_success;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}

// Delete data to tbl_worktime table and tbl_workmonth of database
if (isset($_POST['DeleteAllKintaiUserDetail'])) {
        $deleteAllData = false;
        $_SESSION['selmm'] = $_POST['month'];
        $_SESSION['selyy'] = $_POST['year'];
        // $_SESSION['template_table'] = $_POST["template_table_"];
        $yearmonthSlet = $_POST["year"] . '/' . $_POST["month"];
        $yearmonth = $_POST["year"] . $_POST["month"];

        $genid = $currentGenid;
        $workym = $yearmonth;

        $uid = $employee_uid;
        $workymS = mysqli_real_escape_string($conn, $yearmonthSlet);
        $workym = $yearmonth;
        $sql = "DELETE FROM `tbl_worktime` 
                WHERE uid ='$uid' 
                AND LEFT(`tbl_worktime`.`workymd`, 7) IN('$workymS')";

        if ($conn->query($sql) === TRUE) {
                $deleteAllData = true;
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
        if ($deleteAllData = true) {
                $sql2 = "DELETE FROM `tbl_workmonth` 
                WHERE uid ='$uid' 
                AND LEFT(`tbl_workmonth`.`workym`, 6) IN('$workym')";

                if ($conn->query($sql2) === TRUE) {
                        $_SESSION['delete_all_success'] = $delete_all_success;
                        header("Refresh:3");
                } else {
                        echo 'query error: ' . mysqli_error($conn);
                }
        }
}

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE  (`tbl_genba`.`use_yn`="' . constant('USE_YES') . '"
AND
    `tbl_genba`.`companyid`="' . $_SESSION['auth_companyid'] . '")
OR
    `tbl_genba`.`companyid` = 0';

$result_genba = mysqli_query($conn, $sql_genba);
$genba_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);

// 自動入力
if (isset($_POST['AutoUpdateKintaiUserDetail'])) {
        if ($currentSubmission_status != 0) {
                $_SESSION['is_submissed_notchange'] = $is_submissed_notchange;
                return;
        }
        $_SESSION['selmm'] = $_POST['month'];
        $_SESSION['selyy'] = $_POST["year"];
        $genba_selection_rmodal = $_POST['genba_selection_rmodal'];
        $uid_ = $employee_uid;
        $dataArray = explode(",", $genba_selection_rmodal);
        $genid_ = $dataArray[0];
        $starttime = $dataArray[1];
        $starttimeArray = explode(":", $starttime);
        $starthh = $starttimeArray[0];
        $startmm = $starttimeArray[1];
        $endtime = $dataArray[2];
        $endtimeArray = explode(":", $endtime);
        $endhh = $endtimeArray[0];
        $endmm = $endtimeArray[1];
        $dayoff = $dataArray[3];
        $dayoffArray = explode(":", $dayoff);
        $offtimehh_ = $dayoffArray[0];
        $offtimemm_ = $dayoffArray[1];
        $nightoff = $dataArray[4];
        $workcontent_rmodal = $_POST['workcontent_rmodal'];
        $bigo_rmodal = $_POST['bigo_rmodal'];
        $weekdayCheckbox = $_POST['weekdayCheckbox'];
        $weekendCheckbox = $_POST['weekendCheckbox'];
        $offtime = DateTime::createFromFormat('H:i', $offtimehh_ . ':' . $offtimemm_);
        $jobstarttime = DateTime::createFromFormat('H:i', $starthh . ':' . $startmm);
        $jobendtime = DateTime::createFromFormat('H:i', $endhh . ':' . $endmm);
        $interval = $jobendtime->diff($jobstarttime);
        $totalMinutes = ($interval->h * 60) + $interval->i;
        $totalMinutes -= ($offtime->format('H') * 60) + $offtime->format('i');
        $calculatedHours = floor($totalMinutes / 60);
        $calculatedMinutes = $totalMinutes % 60;
        $year_ = $_POST["year"];
        $month_ = $_POST['month'];
        // Create a date string in "YYYY-MM" format
        $dateString = $year_ . "-" . $month_;
        $weekdaysArray = [];
        $weekendsArray = [];
        $daysInMonth = date("t", strtotime($dateString));
        $weekdays = array(
                1 => '月',
                2 => '火',
                3 => '水',
                4 => '木',
                5 => '金',
                6 => '土',
                7 => '日'
        );
        for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = date("Y-m-d", strtotime($dateString . "-" . $day));
                $Year_ = date("Y", strtotime($dateString . "-" . $day));
                $date_ = date("d", strtotime($dateString . "-" . $day));
                $month_ = date("m", strtotime($dateString . "-" . $day));
                $weekday = $weekdays[date("N", strtotime($date))];
                $data_Array[] = [
                        'uid' => $uid_,
                        'genid' => $genid_,
                        'workymd' => $Year_ . "/" . $month_ . "/" . $date_,
                        'jobstarthh' => $starthh,
                        'jobstartmm' => $startmm,
                        'jobendhh' => $endhh,
                        'jobendmm' => $endmm,
                        'offtimehh' => $offtimehh_,
                        'offtimemm' => $offtimemm_,
                        'workhh' => $calculatedHours,
                        'workmm' => $calculatedMinutes,
                        'comment' => $workcontent_rmodal,
                        'bigo' => $bigo_rmodal
                ];
                // Categorize weekdays and weekends
                if ($weekday === '土' || $weekday === '日') {
                        $weekendsArray[] = $data_Array[$day - 1];
                } else {
                        $weekdaysArray[] = $data_Array[$day - 1];
                }
        }

        if ($weekdayCheckbox == '1' && $weekendCheckbox == '2') {
                $Array_Result = $data_Array;
        } elseif ($weekdayCheckbox == '1') {
                $Array_Result = $weekdaysArray;
        } elseif ($weekendCheckbox == '2') {
                $Array_Result = $weekendsArray;
        }

        $lastValue = null;
        foreach ($Array_Result as $element) {
                if (isset($element['workymd'])) {
                        $lastValue = $element['workymd'];
                }
        }
        $keyed = array_column($worktime_list, NULL, 'workymd'); // replace indexes with ur_user_id values
        foreach ($Array_Result as &$row) { // write directly to $array1 while iterating
                if (isset($keyed[$row['workymd']])) { // check if shared key exists
                        $row += $keyed[$row['workymd']]; // append associative elements
                }
        }
        $ArraySave = false;
        $c = 0;
        $offTimeAutohh = '';
        $offTimeAutomm = '';
        foreach ($Array_Result as $row) {
                $c++;
                $uid = $row['uid'];
                $genid = $row['genid'];
                $workymd = $row['workymd'];
                $jobstarthh = $row['jobstarthh'];
                $jobstartmm = $row['jobstartmm'];
                $jobendhh = $row['jobendhh'];
                $jobendmm = $row['jobendmm'];
                $offtimehh = $row['offtimehh'];
                $offtimemm = $row['offtimemm'];
                $workhh = $row['workhh'];
                $workmm = $row['workmm'];
                $comment = $row['comment'];
                $bigo = $row['bigo'];

                $sql = "INSERT INTO `tbl_worktime` (`uid`, `companyid` ,`genid`, `workymd`, `jobstarthh`, `jobstartmm`, `jobendhh`,
            `jobendmm`, `offtimehh`, `offtimemm`, `workhh`, `workmm`, `comment`, `holy_decide`, `bigo`, `reg_dt` , `upt_dt`)
            VALUES (:uid, '$companyid' , :genid, :workymd, :jobstarthh, :jobstartmm, :jobendhh, :jobendmm,
            :offtimehh, :offtimemm, :workhh, :workmm, :comment, 0 , :bigo, :reg_dt , null)
            ON DUPLICATE KEY UPDATE
            companyid = '$companyid',  genid = :genid, jobstarthh = :jobstarthh, jobstartmm = :jobstartmm, jobendhh = :jobendhh, jobendmm = :jobendmm,
            offtimehh = :offtimehh, offtimemm = :offtimemm, workhh = :workhh, workmm = :workmm, comment = :comment, holy_decide = 0  , bigo = :bigo, upt_dt = :upt_dt";
                // Prepare the statement
                $stmt = $pdo->prepare($sql);
                // Bind the parameters
                $stmt->bindParam(':uid', $uid);
                $stmt->bindParam(':genid', $genid);
                $stmt->bindParam(':workymd', $workymd);
                $stmt->bindParam(':jobstarthh', $jobstarthh);
                $stmt->bindParam(':jobstartmm', $jobstartmm);
                $stmt->bindParam(':jobendhh', $jobendhh);
                $stmt->bindParam(':jobendmm', $jobendmm);
                $stmt->bindParam(':offtimehh', $offtimehh);
                $stmt->bindParam(':offtimemm', $offtimemm);
                $stmt->bindParam(':workhh', $workhh);
                $stmt->bindParam(':workmm', $workmm);
                $stmt->bindParam(':comment', $comment);
                $stmt->bindParam(':bigo', $bigo);
                $stmt->bindParam(':reg_dt', $reg_dt);
                $stmt->bindParam(':upt_dt', $upt_dt);

                // Execute the statement
                if ($stmt->execute()) {
                        $ArraySave = true;
                } else {
                        echo 'query error: ' . $stmt->errorInfo()[2];
                }


                if ($c > 1 && $c < 3) {
                        $offTimeAutohh = $offtimehh;
                        $offTimeAutomm = $offtimemm;
                        error_log(' autoOFF:' . $offTimeAutohh . " mm: " . $offTimeAutomm . "\n");
                }
        }

        if ($ArraySave = true) {
                $uid = mysqli_real_escape_string($conn, $uid_);
                $genid = mysqli_real_escape_string($conn, $genid_);
                $workymd = mysqli_real_escape_string($conn, $lastValue);
                $jobstarthh = mysqli_real_escape_string($conn, $starthh);
                $jobstartmm = mysqli_real_escape_string($conn, $startmm);
                $jobendhh = mysqli_real_escape_string($conn, $endhh);
                $jobendmm = mysqli_real_escape_string($conn, $endmm);

                $workhh = mysqli_real_escape_string($conn, $calculatedHours);
                $workmm = mysqli_real_escape_string($conn, $calculatedMinutes);
                $comment = mysqli_real_escape_string($conn, $workcontent_rmodal);
                $bigo = mysqli_real_escape_string($conn, $bigo_rmodal);

                $sql = "INSERT INTO `tbl_worktime` (`uid`, `companyid` ,`genid`, `workymd`, `jobstarthh`, `jobstartmm`, `jobendhh`, `jobendmm`, 
                `offtimehh`, `offtimemm`, `workhh`, `workmm`, `comment`,  `holy_decide`, `bigo`, `reg_dt` , `upt_dt`)
                VALUES ('$uid', '$companyid' ,'$genid', '$workymd', '$jobstarthh', '$jobstartmm', '$jobendhh', '$jobendmm',
                '$offTimeAutohh', '$offTimeAutomm', '$workhh', '$workmm', '$comment', 0 ,'$bigo', '$reg_dt' , null)
                ON DUPLICATE KEY UPDATE
                companyid = '$companyid' ,genid='$genid', jobstarthh='$jobstarthh', jobstartmm='$jobstartmm', jobendhh='$jobendhh', jobendmm='$jobendmm',
                offtimehh='$offTimeAutohh', offtimemm='$offTimeAutomm', workhh='$workhh', workmm='$workmm', comment='$comment',  holy_decide = 0 , bigo='$bigo', upt_dt='$upt_dt'";

                if ($conn->query($sql) === TRUE) {
                        $_SESSION['autosave_success'] = $autosave_success;
                        header("Refresh:3");
                } else {
                        echo 'query error: ' . mysqli_error($conn);
                }
        }
}


// 2023/11/10 submission-status  add start 



// WorkmonthKakutei 
if (isset($_POST['WorkmonthKakutei'])) {
        // check is registed workmonth ?
        $sql_check_workmonth = 'SELECT * FROM tbl_workmonth WHERE `tbl_workmonth`.`uid` 
    IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '") AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';
        $result = $conn->query($sql_check_workmonth);
        if ($result === false) {
                echo 'Query error: ' . mysqli_error($conn);
        } else {
                $rowCount = mysqli_num_rows($result);
                if ($rowCount <= 0) {
                        $_SESSION['kakutei_fail'] = $kakutei_fail;
                        // header("Refresh: 3");
                        return;
                }
        }
        // check submisstion status
        if ($currentSubmission_status == 0) {
                $query_kakutei = 'UPDATE tbl_workmonth SET `submission_status` = 1 , `upt_dt`="' . $upt_dt . ' "  WHERE `tbl_workmonth`.`uid` 
        IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '") AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';

                if ($conn->query($query_kakutei) === TRUE) {
                        $_SESSION['kakutei_success'] = $kakutei_success;
                        header("Refresh: 3");
                } else {
                        $_SESSION['kakutei_fail'] = $kakutei_fail;
                }
        }
}


if ($_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
        // WorkmonthModoshi
        if (isset($_POST['WorkmonthModoshi'])) {
                $query_modoshi = 'UPDATE tbl_workmonth SET `submission_status` = 0 , `upt_dt`="' . $upt_dt . ' "  WHERE `tbl_workmonth`.`uid` 
            IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '") AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';
                error_log($query_modoshi);
                if ($conn->query($query_modoshi) === TRUE) {
                        $_SESSION['modoshi_success'] = $modoshi_success;
                        header("Refresh: 2");
                } else {
                        echo 'query error: ' . mysqli_error($conn);
                }
        }
        // WorkmonthShonin
        if (isset($_POST['WorkmonthShonin'])) {
                if ($currentSubmission_status == 1 || $currentSubmission_status == 2 || $currentSubmission_status == 3) {
                        $query_shonin = 'UPDATE tbl_workmonth SET `submission_status` = 2 , `upt_dt`="' . $upt_dt . ' "  WHERE `tbl_workmonth`.`uid` 
            IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '") AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';
                        error_log($query_shonin);
                        if ($conn->query($query_shonin) === TRUE) {
                                $_SESSION['shonin_success'] = $shonin_success;
                                header("Refresh: 2");
                        } else {
                                echo 'query error: ' . mysqli_error($conn);
                        }
                } else {
                        $_SESSION['shonin_notkakutei_fail'] = $shonin_notkakutei_fail;
                        header("Refresh: 5");
                }
        }
        // WorkmonthSekininShonin
        if (isset($_POST['WorkmonthSekininShonin'])) {
                if ($currentSubmission_status == 1 || $currentSubmission_status == 2 || $currentSubmission_status == 3) {
                        $query_sekinin_shonin = 'UPDATE tbl_workmonth SET `submission_status` = 3 , `upt_dt`="' . $upt_dt . ' " WHERE `tbl_workmonth`.`uid` 
            IN("' . $employee_uid . '")  AND `tbl_workmonth`.`companyid` IN("' . $current_CompanyId_ . '") AND (`tbl_workmonth`.`workym`) IN("' . $year . $month . '")';
                        error_log($query_sekinin_shonin);
                        if ($conn->query($query_sekinin_shonin) === TRUE) {
                                $_SESSION['sekininshonin_success'] = $sekininshonin_success;
                                header("Refresh: 2");
                        } else {
                                echo 'query error: ' . mysqli_error($conn);
                        }
                } else {
                        $_SESSION['sekininshonin_notkakutei_fail'] = $sekininshonin_notkakutei_fail;
                        header("Refresh: 5");
                }
        }

}

// 2023/11/10 submission-status  add end 