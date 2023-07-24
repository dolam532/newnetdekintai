<?php
// Select data from tbl_notice
// (index.php)
$sql_notice = 'SELECT * FROM `tbl_notice`
JOIN `tbl_user` ON `tbl_notice`.`uid` = `tbl_user`.`uid`
WHERE `tbl_notice`.`uid` = `tbl_user`.`uid`
ORDER BY `tbl_notice`.`bid`';

$result_notice = mysqli_query($conn, $sql_notice);
$notice_list = mysqli_fetch_all($result_notice, MYSQLI_ASSOC);

if (isset($_POST['bid_']) && isset($_POST['viewcnt_'])) {
    $bid = intval($_POST["bid_"]);
    $viewcnt = intval($_POST["viewcnt_"]) + 1;
    $bid_ = mysqli_real_escape_string($conn, $bid);
    $viewcnt_ = mysqli_real_escape_string($conn, $viewcnt);
    $sql = "UPDATE tbl_notice SET 
            viewcnt='$viewcnt_'
        WHERE bid ='$bid_'";

    if ($conn->query($sql) === TRUE) {
        header("Refresh:1");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}
