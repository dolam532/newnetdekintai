<?php
// Select data from tbl_user
$login_query = 'SELECT * FROM `tbl_user` 
        WHERE `uid` ="' . $userid . '" AND `pwd` = "' . $password . '" LIMIT 1';


$sql_user = 'SELECT * FROM `tbl_user` WHERE `tbl_user`.`companyid`="' . constant('GANASYS_COMPANY_ID') . '"';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);
