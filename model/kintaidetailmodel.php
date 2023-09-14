<?php
// now year-month-date
$reg_dt = date('Y-m-d H:i:s');
$upt_dt = date('Y-m-d H:i:s');

// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`companyid`="' . constant('GANASYS_COMPANY_ID') . '"';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

if (isset($_POST['uid_g']) && isset($_POST['name_g']) && isset($_POST['genid_g'])) {
        $file_path = '../kintaidetail/temporary' . $_SESSION['auth_uid'] . '.php';
        $file_content = '<?php ';
        $file_content .= '$uid_e = "' . $_POST['uid_g'] . '"';
        $file_content .= ';';
        $file_content .= '$name_e = "' . $_POST['name_g'] . '"';
        $file_content .= ';';
        $file_content .= '$genid_e = "' . $_POST['genid_g'] . '"';
        $file_content .= ';';
        $file_content .= '$dept_e = "' . $_POST['dept_g'] . '"';
        $file_content .= ';';
        $file_content .= '?>';
        if (file_put_contents($file_path, $file_content) !== false) {
        }
}

if ($_POST['selmm'] == NULL && $_POST['selyy'] == NULL && $_POST['template_table'] == NULL) {
        $_POST['selmm'] = $_SESSION['selmm'];
        $_POST['selyy'] = $_SESSION['selyy'];
        $_POST['template_table'] = $_SESSION['template_table'];
}

// Include the file
include('temporary' . $_SESSION['auth_uid'] . '.php');

// Now you can access the variables from temporary.php
$employee_uid = $uid_e;
$employee_name = $name_e;
$employee_genid = $genid_e;
$employee_dept = $dept_e;

$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');

// Get Data From tbl_workmonth
$sql_workmonth = 'SELECT
    *
FROM
    `tbl_workmonth`
WHERE
    `tbl_workmonth`.`uid` IN("' . $employee_uid . '")  
    AND LEFT(`tbl_workmonth`.`workym`, 6) IN("' .  $year . $month . '")';
$result_workmonth = mysqli_query($conn, $sql_workmonth);
$workmonth_list = mysqli_fetch_all($result_workmonth, MYSQLI_ASSOC);
foreach ($workmonth_list as $key) {
        $template_ = $key['template'];
}
$decide_template_ = isset($_POST["template_table"]) ? $_POST["template_table"] : (isset($template_) ? $template_ : '1');

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

// Display the dates, months, weekdays, and weekends in Japanese
for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = date("Y-m-d", strtotime($dateString . "-" . $day));
        $Year_ = date("Y", strtotime($dateString . "-" . $day));
        $date_ = date("d", strtotime($dateString . "-" . $day));
        $month_ = date("m", strtotime($dateString . "-" . $day));
        $date_show = $Year_ . "/" . $month_ . "/";
        $weekday = $weekdays[date("N", strtotime($date))];
        $datas[] = [
                'date' => $month_ . "/" . $date_ . "(" . $weekday . ")",
                'decide_color' => $weekday,
                'workymd' =>  $Year_ . "/" . $month_ . "/" . $date_,
                'template' =>  $decide_template_
        ];
}

// Get Data From tbl_worktime
$sql_worktime = 'SELECT
    *
FROM
    `tbl_worktime`
WHERE
    `tbl_worktime`.`uid` IN("' . $employee_uid . '")  
    AND LEFT(`tbl_worktime`.`workymd`, 8) IN("' .  $date_show . '")';
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
foreach ($datas as &$row) {       // write directly to $array1 while iterating
        if (isset($keyed[$row['workymd']])) { // check if shared key exists
                $row += $keyed[$row['workymd']]; // append associative elements
        }
}

// Save data to tbl_worktime table of database
if (isset($_POST['SaveUpdateKintaiUserDetail'])) {
        $_SESSION['selmm'] = substr($_POST['date_show'], 5, 2);
        $_SESSION['selyy'] = substr($_POST['date_show'], 0, 4);
        $_SESSION['template_table'] = $_POST["template_table_"];
        $genid_ = intval($_POST['genid']);

        $offtime = DateTime::createFromFormat('H:i', $_POST['offtimehh'] . ':' . $_POST['offtimemm']);
        $jobstarttime = DateTime::createFromFormat('H:i', $_POST['jobstarthh'] . ':' . $_POST['jobstartmm']);
        $jobendtime = DateTime::createFromFormat('H:i', $_POST['jobendhh'] . ':' . $_POST['jobendmm']);

        $interval = $jobendtime->diff($jobstarttime);
        $totalMinutes = ($interval->h * 60) + $interval->i;
        $totalMinutes -= ($offtime->format('H') * 60) + $offtime->format('i');
        $calculatedHours = floor($totalMinutes / 60);
        $calculatedMinutes = $totalMinutes % 60;

        if ($_POST["template_table_"] == "1") {
                $janhh = '0';
                $janmm = '0';
        } elseif ($_POST["template_table_"] == "2") {
                $janhh = $_POST['workhh'] - $calculatedHours;
                $janmm = $_POST['workmm'] - $calculatedMinutes;
        }

        $uid = mysqli_real_escape_string($conn, $_POST['uid']);
        $genid = mysqli_real_escape_string($conn, $genid_);
        $workymd = mysqli_real_escape_string($conn, $_POST['date_show']);
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
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $bigo = mysqli_real_escape_string($conn, $_POST['bigo']);

        $sql = "INSERT INTO `tbl_worktime` (`uid`, `genid`, `workymd`, `daystarthh`, `daystartmm`, `dayendhh`, `dayendmm`, `jobstarthh`, `jobstartmm`,
                    `jobendhh`, `jobendmm`, `offtimehh`, `offtimemm`, `workhh`, `workmm`, `janhh`, `janmm`, `comment`, `bigo`, `reg_dt`, `upt_dt`)
                    VALUES ('$uid', '$genid', '$workymd', '$daystarthh', '$daystartmm', '$dayendhh', '$dayendmm', '$jobstarthh', '$jobstartmm',
                    '$jobendhh', '$jobendmm', '$offtimehh', '$offtimemm', '$workhh', '$workmm', '$janhh', '$janmm', '$comment', '$bigo', '$reg_dt', '$upt_dt')
                    ON DUPLICATE KEY UPDATE
                    genid='$genid', daystarthh='$daystarthh', daystartmm='$daystartmm', dayendhh='$dayendhh', dayendmm='$dayendmm', jobstarthh='$jobstarthh', jobstartmm='$jobstartmm',
                    jobendhh='$jobendhh', jobendmm='$jobendmm', offtimehh='$offtimehh', offtimemm='$offtimemm', workhh='$workhh', workmm='$workmm', janhh='$janhh',
                    janmm='$janmm', comment='$comment', bigo='$bigo', upt_dt='$upt_dt'";

        if ($conn->query($sql) === TRUE) {
                $_SESSION['save_success'] =  $save_success;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}

// Save data to tbl_workmonth table of database
if (isset($_POST['MonthSaveKintaiUserDetail'])) {
        $_SESSION['selmm'] = $month;
        $_SESSION['selyy'] = $year;
        $yearmonth = $year . $month;
        $_SESSION['template_table'] = $_POST["template_table_"];

        $gen_id_ = intval($_SESSION['auth_genid']);
        $template_table_s = intval($_POST["template_table_"]);

        $jobhh_top_ = intval($_POST['jobhh_top']);
        $jobmm_top_ = intval($_POST['jobmm_top']);
        $holydays_top_ = intval($_POST['holydays_top']);
        $janhh_top_ = intval($_POST['janhh_top']);
        $janmm_top_ = intval($_POST['janmm_top']);
        $jobdays_top_ = intval($_POST['jobdays_top']);
        $workdays_top_ = intval($_POST['workdays_top']);
        $offdays_top_ = intval($_POST['offdays_top']);
        $delaydays_top_ = intval($_POST['delaydays_top']);
        $earlydays_top_ = intval($_POST['earlydays_top']);

        $jobhh_bottom_ = intval($_POST['jobhh_bottom']);
        $jobmm_bottom_ = intval($_POST['jobmm_bottom']);
        $holydays_bottom_ = intval($_POST['holydays_bottom']);
        $janhh_bottom_ = intval($_POST['janhh_bottom']);
        $janmm_bottom_ = intval($_POST['janmm_bottom']);

        $uid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
        $genid = mysqli_real_escape_string($conn, $gen_id_);
        $workym = mysqli_real_escape_string($conn, $yearmonth);

        $jobhour2 = mysqli_real_escape_string($conn, $jobhh_top_);
        $jobminute2 = mysqli_real_escape_string($conn, $jobmm_top_);
        $jobhour = mysqli_real_escape_string($conn, $jobhh_bottom_);
        $jobminute = mysqli_real_escape_string($conn, $jobmm_bottom_);

        $janhour2 = mysqli_real_escape_string($conn, $janhh_top_);
        $janminute2 = mysqli_real_escape_string($conn, $janmm_top_);
        $janhour = mysqli_real_escape_string($conn, $janhh_bottom_);
        $janminute = mysqli_real_escape_string($conn, $janmm_bottom_);

        $jobdays2 = mysqli_real_escape_string($conn, $jobdays_top_);
        $jobdays = mysqli_real_escape_string($conn, $_POST['jobdays_bottom']);
        $workdays2 = mysqli_real_escape_string($conn, $workdays_top_);
        $workdays = mysqli_real_escape_string($conn, $_POST['workdays_bottom']);
        $holydays2 = mysqli_real_escape_string($conn, $holydays_top_);
        $holydays = mysqli_real_escape_string($conn, $holydays_bottom_);
        $offdays2 = mysqli_real_escape_string($conn, $offdays_top_);
        $offdays = mysqli_real_escape_string($conn, $_POST['offdays_bottom']);
        $delaydays2 = mysqli_real_escape_string($conn, $delaydays_top_);
        $delaydays = mysqli_real_escape_string($conn, $_POST['delaydays_bottom']);
        $earlydays2 = mysqli_real_escape_string($conn, $earlydays_top_);
        $earlydays = mysqli_real_escape_string($conn, $_POST['earlydays_bottom']);
        $template = mysqli_real_escape_string($conn, $template_table_s);

        $sql = "INSERT INTO `tbl_workmonth` (`uid`, `genid`, `workym`, `jobhour`, `jobminute`, `jobhour2`, `jobminute2`, `janhour`, `janminute`, `janhour2`, `janminute2`,
                    `jobdays`, `jobdays2`, `workdays`, `workdays2`, `holydays`, `holydays2`, `offdays`, `offdays2`, `delaydays`, `delaydays2`, `earlydays`, `earlydays2`, `template`, `reg_dt`)
                    VALUES ('$uid', '$genid', '$workym', '$jobhour', '$jobminute', '$jobhour2', '$jobminute2', '$janhour', '$janminute', '$janhour2', '$janminute2',
                    '$jobdays', '$jobdays2', '$workdays', '$workdays2', '$holydays', '$holydays2', '$offdays', '$offdays2', '$delaydays', '$delaydays2', '$earlydays', '$earlydays2', '$template', '$reg_dt')
                    ON DUPLICATE KEY UPDATE
                    genid='$genid', jobhour='$jobhour', jobminute='$jobminute', jobhour2='$jobhour2', jobminute2='$jobminute2',
                    janhour='$janhour', janminute='$janminute', janhour2='$janhour2', janminute2='$janminute2', jobdays='$jobdays', jobdays2='$jobdays2', workdays='$workdays', workdays2='$workdays2', holydays='$holydays',
                    holydays2='$holydays2', offdays='$offdays', offdays2='$offdays2', delaydays='$delaydays', delaydays2='$delaydays2', template='$template', reg_dt='$reg_dt'";

        if ($conn->query($sql) === TRUE) {
                $_SESSION['save_success'] =  $save_success;
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
        $_SESSION['template_table'] = $_POST["template_table_"];
        $yearmonthSlet = $_POST["year"] . '/' . $_POST["month"];
        $yearmonth = $_POST["year"] . $_POST["month"];

        $uid = mysqli_real_escape_string($conn, $_SESSION['auth_uid']);
        $workymS = mysqli_real_escape_string($conn, $yearmonthSlet);
        $workym = mysqli_real_escape_string($conn, $yearmonth);
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
                        $_SESSION['delete_all_success'] =  $delete_all_success;
                        header("Refresh:3");
                } else {
                        echo 'query error: ' . mysqli_error($conn);
                }
        }
}

// Delete data to tbl_worktime table of database
if (isset($_POST['DeleteKintaiUserDetail'])) {
        $_SESSION['selmm'] = substr($_POST['date_show'], 5, 2);
        $_SESSION['selyy'] = substr($_POST['date_show'], 0, 4);
        $_SESSION['template_table'] = $_POST["template_table_"];

        $uid = mysqli_real_escape_string($conn, $_POST['uid']);
        $genid = mysqli_real_escape_string($conn, $_POST['genid']);
        $workymd = mysqli_real_escape_string($conn, $_POST['date_show']);
        $sql = "DELETE FROM `tbl_worktime` 
                WHERE uid ='$uid' AND genid ='$genid' AND workymd ='$workymd'";

        if ($conn->query($sql) === TRUE) {
                $_SESSION['delete_success'] =  $delete_success;
                header("Refresh:3");
        } else {
                echo 'query error: ' . mysqli_error($conn);
        }
}

// Select data from tbl_genba
$sql_genba = 'SELECT * FROM `tbl_genba` WHERE `tbl_genba`.`use_yn`="' . constant('USE_YES') . '"';
$result_genba = mysqli_query($conn, $sql_genba);
$genba_list = mysqli_fetch_all($result_genba, MYSQLI_ASSOC);
