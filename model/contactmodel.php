<?php
$year = isset($_POST["selyy"]) ? $_POST["selyy"] : date('Y');
$month = isset($_POST["selmm"]) ? $_POST["selmm"] : date('m');
$day = isset($_POST["seldd"]) ? $_POST["seldd"] : date('d');

// Select database from tbl_userlogin table
$sql_userlogin= 'SELECT * FROM `tbl_userlogin` 
    WHERE YEAR(`tbl_userlogin`.`workymd`) IN("' . $year . '")
    AND MONTH(`tbl_userlogin`.`workymd`) IN("' . $month . '")
    AND DAY(`tbl_userlogin`.`workymd`) IN("' . $day . '")';
$result_userlogin = mysqli_query($conn, $sql_userlogin);
$userlogin_list = mysqli_fetch_all($result_userlogin, MYSQLI_ASSOC);
