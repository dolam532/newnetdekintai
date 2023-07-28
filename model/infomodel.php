<?php
// Select database from tbl_workday table
// (workdayList.php)
$ganasys_company_id = constant('GANASYS_COMPANY_ID');
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
    WHERE `companyid` = $ganasys_company_id
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
    // Execute the SQL query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $_SESSION['save_success'] = $save_success;
        header("Refresh:3");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
