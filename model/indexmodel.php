<?php
// Select data from tbl_notice
// (index.php)
$sql_notice = 'SELECT * FROM `tbl_notice`
JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
WHERE `tbl_notice`.`uid` = `tbl_user`.`uid`
ORDER BY `tbl_notice`.`bid`';

$result_notice = mysqli_query($conn, $sql_notice);
$notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);
