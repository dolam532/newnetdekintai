<?php
// Select data from tbl_user
$sql_user = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`companyid`="' . constant('GANASYS_COMPANY_ID') . '"';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

if ($_POST['selmm'] == NULL && $_POST['selyy'] == NULL && $_POST['template_table'] == NULL) {
        $_SESSION['employee_uid'] = $_POST['uid'];
        $_SESSION['employee_name'] = $_POST['name'];
        $_SESSION['employee_dept'] = $_POST['dept'];
        $_POST['selmm'] = $_SESSION['selmm'];
        $_POST['selyy'] = $_SESSION['selyy'];
        $_POST['template_table'] = $_SESSION['template_table'];
}

$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');

// Get Data From tbl_workmonth
$sql_workmonth = 'SELECT
    *
FROM
    `tbl_workmonth`
WHERE
    `tbl_workmonth`.`uid` IN("' . $_SESSION['employee_uid'] . '")  
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
    `tbl_worktime`.`uid` IN("' . $_SESSION['employee_uid'] . '")  
    AND LEFT(`tbl_worktime`.`workymd`, 8) IN("' .  $date_show . '")';
$result_worktime = mysqli_query($conn, $sql_worktime);
$worktime_list = mysqli_fetch_all($result_worktime, MYSQLI_ASSOC);

$keyed = array_column($worktime_list, NULL, 'workymd'); // replace indexes with ur_user_id values
foreach ($datas as &$row) {       // write directly to $array1 while iterating
        if (isset($keyed[$row['workymd']])) { // check if shared key exists
                $row += $keyed[$row['workymd']]; // append associative elements
        }
}
