<?php
// Select database from tbl_workday table
// (workdayList.php)
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
    WHERE `companyid` = '1'
    GROUP BY workyear
    ORDER BY workyear DESC, one_month ASC";
$result_workday = mysqli_query($conn, $sql_workday);
$workday_list = mysqli_fetch_all($result_workday, MYSQLI_ASSOC);